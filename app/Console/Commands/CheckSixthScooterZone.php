<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scooter;

class CheckSixthScooterZone extends CheckScooterZone
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scooter:check:zone_sixth';

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
