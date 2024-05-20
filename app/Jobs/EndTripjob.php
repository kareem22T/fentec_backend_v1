<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use App\Models\Trip;

class EndTripjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $iot, $trip_id, $duration, $starting_lat, $starting_lng;

    public function __construct($iot, $trip_id, $duration, $starting_lat, $starting_lng)
    {
        $this->$iot = $iot;
        $this->$trip_id = $trip_id;
        $this->$duration = $duration;
        $this->$starting_lat = $starting_lat;
        $this->$starting_lng = $starting_lng;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = new Client();

        // Second HTTP POST request
        $lock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            'form_params' => [
                'machineNO' => $this->iot->machine_no,
                'token' => $this->iot->token,
                'paramName' => 12,
                'controlType' => 'control'
                ]
            ]);

        $trip = Trip::find($this->trip_id);

        if ($trip) {
            $trip->duration = $this->duration;
            $trip->save();
        }

    }
}
