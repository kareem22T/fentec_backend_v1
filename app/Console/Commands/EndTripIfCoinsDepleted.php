<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class EndTripIfCoinsDepleted extends Command
{
    protected $signature = 'trip:end-if-coins-depleted';
    protected $description = 'End user trips if their coins are depleted';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Fetch all ongoing trips (trips that have not been ended yet)
        $ongoingTrips = Trip::whereNull('ended_at')->get();

        foreach ($ongoingTrips as $trip) {
            $user = $trip->user;
            $iot = $trip->scooter;

            // Calculate trip duration
            $startedAt = Carbon::parse($trip->started_at);
            $currentDuration = Carbon::now()->diffInMinutes($startedAt);

            // Calculate how many coins are required for the trip so far
            $requiredCoins = ($currentDuration == 0 ? 1 : $currentDuration) * 15;

            if ($user->coins < $requiredCoins) {
                // User has run out of coins, end the trip
                $this->endTrip($trip, $iot);
            }
        }

        return 0;
    }

    private function endTrip($trip, $iot)
    {
        $client = new Client();

        // Lock the scooter
        $lockScooter = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            'form_params' => [
                'machineNO' => $iot->machine_no,
                'token' => $iot->token,
                'paramName' => 12,
                'controlType' => 'control'
            ]
        ]);

        $endedAt = Carbon::now();  // Replace with your actual ended_at
        $trip->ended_at = $endedAt;
        $trip->duration = $trip->ended_at->diffInMinutes($trip->started_at);
        $startedAt = Carbon::parse($trip->started_at);
        $timeInterval = $endedAt->diffInMinutes($startedAt);
        $trip->duration = $timeInterval;

        // Get trip end location
        $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);
        if ($response->successful()) {
            $end_lat = $response['data'][0]['latitude'];
            $end_lng = $response['data'][0]['longitude'];
            $address = Http::post('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $end_lat . ',' . $end_lng . '&key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&language=ar');
            $trip->ending_location = $address["results"][0]['formatted_address'];
        }

        $trip->save();

        // Update user coins
        $user = $trip->user;
        $user->coins = (float) $user->coins - (($timeInterval == 0 ? 1 : $timeInterval) * 15);
        $user->save();

        $this->info("Ended trip for user {$user->id} due to insufficient coins.");
    }
}
