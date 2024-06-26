<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class UpdateScooterLocations extends Command
{
    protected $signature = 'scooter:locations:update';
    protected $description = 'Update scooter locations from the API';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scooters = $this->getScooters();

        if ($scooters->count() > 0) {
            foreach ($scooters as $iot) {
                $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);
                if ($response->successful()) {
                    $iot->latitude = $response['data'][0]['latitude'];
                    $iot->longitude = $response['data'][0]['longitude'];
                    $iot->battary_charge = $response['data'][0]['batteryPower'];
                    $iot->save();
                }
            }
        }
    }

    abstract protected function getScooters();
}

