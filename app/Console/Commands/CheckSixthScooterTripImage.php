<?php

namespace App\Console\Commands;

use App\Models\Scooter;
use Illuminate\Console\Command;

class CheckSixthScooterTripImage extends CheckScooterTripImage
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scooter:check:image_sixth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    protected function getScooter()
    {
        return $scooter = Scooter::where("machine_no", "019592739")->first();
    }
}
