<?php

namespace App\Console\Commands;

use App\Models\Scooter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateScootersCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-scooters-cmd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scooters = Scooter::all();

        if ($scooters->count() > 0) {
            foreach ($scooters as $iot) {
                $response = Http::timeout(100)->post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);
                if ($response->successful()) {
                    $iot->latitude = $response['data'][0]['latitude'];
                    $iot->longitude = $response['data'][0]['longitude'];
                    $iot->battary_charge = $response['data'][0]['batteryPower'];
                    $iot->save();
                }
            }
        }
    }
}
