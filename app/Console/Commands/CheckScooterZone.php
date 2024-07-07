<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Jobs\CheckTripPhoto;
use App\Traits\SendEmailTrait;
use Illuminate\Support\Facades\Log;
use App\Traits\PushNotificationTrait;
use App\Models\Scooter;
use App\Models\Zone;
use Illuminate\Support\Facades\Http;

abstract class CheckScooterZone extends Command
{
    use PushNotificationTrait, SendEmailTrait;
    protected $signature = 'scooter:check:zone';

    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $scooter = $this->getScooter();

            function pointInPolygon($point, $polygon) {
                $vertices = count($polygon);
                $intersections = 0;

                for ($i = 0, $j = $vertices - 1; $i < $vertices; $j = $i++) {
                    if (($polygon[$i]['lng'] > $point[1]) != ($polygon[$j]['lng'] > $point[1]) &&
                        $point[0] < ($polygon[$j]['lat'] - $polygon[$i]['lat']) * ($point[1] - $polygon[$i]['lng']) / ($polygon[$j]['lng'] - $polygon[$i]['lng']) + $polygon[$i]['lat']) {
                        $intersections++;
                    }
                }

                return $intersections % 2 != 0;
            }

            $trip = $scooter->trips()->orderBy("started_at", "desc")->first();
            if (!$trip->ended_at) {
                $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $scooter->machine_no . "&token=" . $scooter->token);
                if ($response->successful()) {
                    $scooter->latitude = $response['data'][0]['latitude'];
                    $scooter->longitude = $response['data'][0]['longitude'];
                    $scooter->battary_charge = $response['data'][0]['batteryPower'];
                    $scooter->save();
                }

                $point = [$scooter->latitude, $scooter->longitude];

                // Assume $polygonsJson is the JSON data containing the polygons
                $polygons = Zone::all();
                $zone = null;

                foreach ($polygons as $polygon) {
                    // Parse the path of the polygon
                    $coordinates = json_decode($polygon['path'], true);

                    // Check if the point lies within the polygon
                    if (pointInPolygon($point, $coordinates)) {
                            // Return the zone of the polygon containing the point
                            $zone = $polygon['type'];
                        break;
                    }
                }

                if ($zone == 2) {
                    $email = $trip->user->email;
                    $msg_title = 'Fentec Zone Warning';
                    $msg_body =
                        "Hello " . $trip->user->name . " You have enter orange zone where you are about to get in red please return back";
                    $msg_body2 =
                        "User " . $trip->user->name . ' - ' . $trip->user->phone . " have entered orange zone and got warning";

                    $this->sendEmail($email, $msg_title, $msg_body);
                    $this->sendEmail("fentec.dev@gmail.com", $msg_title, $msg_body2);
                    if ($trip->user->notification_token)
                        $response2 = $this->pushNotification($msg_title, $msg_body, $trip->user->notification_token, $trip->user->id);

                    sleep(60);

                    $res = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $scooter->machine_no . "&token=" . $scooter->token);
                    if ($res->successful()) {
                        $scooter->latitude = $res['data'][0]['latitude'];
                        $scooter->longitude = $res['data'][0]['longitude'];
                        $scooter->battary_charge = $res['data'][0]['batteryPower'];
                        $scooter->save();
                    }

                    $point = [$scooter->latitude, $scooter->longitude];

                    foreach ($polygons as $polygon) {
                        // Parse the path of the polygon
                        $coordinates = json_decode($polygon['path'], true);

                        // Check if the point lies within the polygon
                        if (pointInPolygon($point, $coordinates)) {
                                // Return the zone of the polygon containing the point
                                $zone = $polygon['type'];
                            break;
                        }
                    }

                    if ($zone == 0 || $zone == 2) {
                        $client = new Client();
                        $iot = Scooter::find($trip->scooter_id);
                        if ($iot) {
                            // Second HTTP POST request
                            $lock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
                                'form_params' => [
                                    'machineNO' => $iot->machine_no,
                                    'token' => $iot->token,
                                    'paramName' => 12,
                                    'controlType' => 'control'
                                ]
                            ]);
                        }
                        $msg_title = 'Fentec Zone Warning';
                        $msg_body2 =
                            "User " . $trip->user->name . ' - ' . $trip->user->phone . " have entered orange zone and does not returned back";

                        $this->sendEmail("fentec.dev@gmail.com", $msg_title, $msg_body2);

                        if ($trip) {
                            $startedAt = Carbon::parse($trip->started_at);

                            // Assuming ended_at is available in your model or variable
                            $endedAt = Carbon::now();  // Replace with your actual ended_at

                            $timeInterval = $endedAt->diffInMinutes($startedAt);
                            $trip->ended_at = $endedAt;
                            $trip->duration = $timeInterval;
                            $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);
                            if ($response->successful()) {
                                $start_lat = $response['data'][0]['latitude'];
                                $start_lng = $response['data'][0]['longitude'];
                                $address = Http::post('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $start_lat . ',' . $start_lng . '&key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&language=ar');
                                $trip->ending_location = $address["results"][0]['formatted_address'];
                            }
                            $trip->save();
                            if ($trip->user) {
                                $trip->user->coins = (int) $trip->user->coins - ($timeInterval * 5) - 10;
                                $trip->user->save();
                            }

                        }
                        dispatch(new CheckTripPhoto($trip->user->current_trip_id));
                    }
                }  else if ($zone == 0) {
                        $client = new Client();
                        $iot = Scooter::find($trip->scooter_id);
                        if ($iot) {
                            // Second HTTP POST request
                            $lock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
                                'form_params' => [
                                    'machineNO' => $iot->machine_no,
                                    'token' => $iot->token,
                                    'paramName' => 12,
                                    'controlType' => 'control'
                                ]
                            ]);
                        }

                        if ($trip) {
                            $startedAt = Carbon::parse($trip->started_at);

                            // Assuming ended_at is available in your model or variable
                            $endedAt = Carbon::now();  // Replace with your actual ended_at

                            $timeInterval = $endedAt->diffInMinutes($startedAt);
                            $trip->ended_at = $endedAt;
                            $trip->duration = $timeInterval;
                            $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);
                            if ($response->successful()) {
                                $start_lat = $response['data'][0]['latitude'];
                                $start_lng = $response['data'][0]['longitude'];
                                $address = Http::post('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $start_lat . ',' . $start_lng . '&key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&language=ar');
                                $trip->ending_location = $address["results"][0]['formatted_address'];
                            }
                            $trip->save();
                            if ($trip->user) {
                                $trip->user->coins = (int) $trip->user->coins - ($timeInterval * 5) - 10;
                                $trip->user->save();
                            }

                        }
                }

            }
            // notify admin warning
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }

    abstract protected function getScooter();

}
