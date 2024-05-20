<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveStartingLocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $trip, $lng, $lat;
    public function __construct($trip, $lng, $lat)
    {
        $this->trip = $trip;
        $this->lng = $lng;
        $this->lat = $lat;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $trip = Trip::find($this->trip->id);
        
    }
}
