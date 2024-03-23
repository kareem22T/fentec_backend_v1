<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Scooter;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\SavePhotoTrait;
use App\Jobs\CheckTripPhoto;

class ScooterController extends Controller
{
    use SavePhotoTrait;
    use DataFormController;

    public function unlockScooter(Request $request) {
        $minCost = 5; //coins
        $user = $request->user();
        
        $iot = Scooter::where("machine_no", $request->scooter_serial)->first();

        if (!$iot)
            return $this->jsondata(false, null, 'Unlock failed', ["Invalid Serial Number"], []);

        if ($user->isBanned)
            return $this->jsondata(false, null, 'Unlock failed', ["Your Account is banned call customer service for more details"], []);

        if ($user->rejected)
            return $this->jsondata(false, null, 'Unlock failed', ["Your Account is rejected please update your information to get approved"], []);

        if (!$user->approved)
            return $this->jsondata(false, null, 'Unlock failed', ["Your Account is under review and not approved yet"], []);

        $userAvilableRideMin = (int) $user->coins /$minCost;

        if ($userAvilableRideMin < 10)
            return $this->jsondata(false, null, 'Unlock failed', ["You don not have enough points at least " . $minCost * 10 . " for 10 Min" ], []);

            $client = new Client();
            // First HTTP POST request
            $unlock_lock = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
                'form_params' => [
                    'machineNO' => $iot->machine_no,
                    'token' => $iot->token,
                    'paramName' => 22,
                    'controlType' => 'control'
                ]
            ]);
            
            // Second HTTP POST request
            $unlock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
                'form_params' => [
                    'machineNO' => $iot->machine_no,
                    'token' => $iot->token,
                    'paramName' => 11,
                    'controlType' => 'control'
                ]
            ]);

        $create_trip = Trip::create([
            "user_id" => $user->id,
            "scooter_id" => $iot->id,
            "started_at" => now()
        ]);
            
        $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);                
        if ($response->successful()) {
            $start_lat = $response['data'][0]['latitude'];
            $start_lng = $response['data'][0]['longitude'];
            $address = Http::post('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $start_lat . ',' . $start_lng . '&key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&language=ar');                
            $create_trip->starting_location = $address["results"][0]['formatted_address'];
            $create_trip->save();
        }

        $user->current_trip_id = $create_trip->id;
        $user->save();

        return $this->jsondata(true, null, 'Scooter Has Unlocked Successfuly', [], []);

    }

    public function lockScooter(Request $request) {
        $client = new Client();
        $user = $request->user();
        $trip = Trip::find($user->current_trip_id);

        if ($trip) {
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
                if ($user) {
                    $user->coins = (int) $user->coins - ($timeInterval * 5) - 10;
                    $user->save();
                }
        
            }
            dispatch(new CheckTripPhoto($user->current_trip_id));
        }    
        return $this->jsondata(true, null, 'Scooter Has Locked please take a photo to confirm', [], []);
    }

    public function submitTrpPhoto(Request $request) {
        $validator = Validator::make($request->all(), [
            'photo' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Lock failed', [$validator->errors()->first()], []);
        }

        $user = $request->user();
        $trip = Trip::find($user->current_trip_id);

        if ($trip) {
            if ($request->photo) :
                $disk = 'public';
    
                // Specify the path to the image within the storage disk
                $path = 'images/uploads/' . $trip->lock_photo;
                if (Storage::disk($disk)->exists($path)) 
                    Storage::disk($disk)->delete($path);  
    
                $profile_pic = $this->saveImg($request->photo, 'images/uploads', 'trip_' . $trip->id . "_" . time());
                $trip->lock_photo = $profile_pic;
                $trip->save();

                if ($trip) {
                    $user->current_trip_id = 0;
                    $user->save();
                }
            endif;    
        }
        return $this->jsondata(true, null, 'Trip has submited successfuly, Thanks!', [], []);
    }

    public function getUserTrips(Request $request) {
        $user = $request->user();
        return $user->trips()->paginate(15);
    }
    public function getUserTripsNum(Request $request) {
        $user = $request->user();
        $trips = $user->trips();

        return $trips->count();
    }
}
