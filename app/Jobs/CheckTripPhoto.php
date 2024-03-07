<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Trip; 
use Carbon\Carbon;

class CheckTripPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $trip_id;
    public function __construct($trip_id)
    {
        $this->$trip_id = $trip_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $trip = Trip::find($trip_id);
        if ($trip) {
            if (!$trip->lock_photo) {
                $now = Carbon::now();  // Replace with your actual ended_at
                $timeInterval = $now->diffInMinutes($trip->ended_at);
                if ($timeInterval >= 2) {
                    // send warning emails
                    // send warning notification
                    // send warning email for user
                } else {
                    sleep(60);
                    $trip2 = Trip::find($trip_id);
                    if (!!$trip->lock_photo) {
                        // send warning emails
                        // send warning notification
                        // send warning email for user
                    }
                }
            }
        }
    }
}
