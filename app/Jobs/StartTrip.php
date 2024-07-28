<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Trip;

class StartTrip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user, $scooter_id, $userAvilableRideMin;

    public function __construct($user, $scooter_id, $userAvilableRideMin)
    {
        $this->user = $user;
        $this->scooter_id = $scooter_id;
        $this->userAvilableRideMin = $userAvilableRideMin;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $create_trip = Trip::create([
            "user_id" => $this->user->id,
            "scooter_id" => $this->scooter_id,
            "started_at" => now()
        ]);

        $trip_duration = 0;


        while ($this->userAvilableRideMin) {
            $trip = Trip::find($create_trip->id);
            if (!$trip->ended_at) {
                if ($this->userAvilableRideMin == 1) {
                    $trip->ended_at = now();
                    $trip->save();
                    // push warning notification
                }
                $this->user->coins = (float) $this->user->coins - 10;
                $this->user->save();
                $trip_duration += 1;
                $this->userAvilableRideMin -= 1;
                sleep(58);
            } else {
                $this->userAvilableRideMin = 0;
                $trip->duration = $trip_duration;
                $trip->save();
            }

        }
    }
}
