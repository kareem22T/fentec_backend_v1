<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

use App\Jobs\TestJob;

use App\Models\Zone;
use App\Models\Scooter;
use App\Models\User;
use Carbon\Carbon;

class ManageScooters extends Controller
{
    use DataFormController;

    public function index () {
        $Activated_scooters = Scooter::whereHas('trips', function ($q) {
            $q->whereDate('started_at', '>=', Carbon::now()->setTimezone('Africa/Algiers')->subHours(4))
                ->whereNull('ended_at');
        })->get();
        $locked_scooters = Scooter::all()->count() - $Activated_scooters->count();
        $this->updateScotersData();
        return view("admin.dashboard.scooters")->with(compact(['Activated_scooters', 'locked_scooters']));
    }
    public function zonesIndex () {
        $zones = Zone::all();
        return view("admin.dashboard.zones")->with(compact('zones'));
    }
    public function addZone (Request $request) {
        $validator = Validator::make($request->all(), [
            'path' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Add failed', [$validator->errors()->first()], []);
        }

        if (count(json_decode($request->path)) < 3)
            return $this->jsondata(false, null, 'Add failed', ["please select at least 3 points"], []);

        $zone = Zone::create([
            'path' => $request->path,
            "type" => $request->type
        ]);

        if ($zone)
            return $this->jsondata(true, null, 'Zone Has added successfuly', [], []);

    }

    public function deleteZone (Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Add failed', [$validator->errors()->first()], []);
        }

        $zone = Zone::find($request->id);
        $zone->delete();

        if ($zone)
            return $this->jsondata(true, null, 'Zone Has deleted successfuly', [], []);

    }

    public function getZones () {
        $zones = Zone::latest()->get();
        return $zones;
    }

    public function getAllScooters () {
        $Scooters = Scooter::with(['trips' => function($q) {
            $q->with("user")->latest("started_at")->get();
        }])->latest()->get();
        try {
            $this->updateScotersData();
        } catch (\Throwable $th) {
        }
        return $Scooters;
    }

    public function updateScotersData() {
        $scooters = Scooter::all();

        if ($scooters->count() > 0) {
            foreach ($scooters as $iot) {
                $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);
                if ($response->successful()) {
                    $iot->latitude = $response['data'][0]['latitude'] ?? null;
                    $iot->longitude = $response['data'][0]['longitude'] ?? null;
                    $iot->battary_charge = $response['data'][0]['batteryPower'] ?? null;
                    $iot->save();
                }
            }
        }
    }

    public function getScooters(Request $request) {
        $scooters = Scooter::
        where(function ($query) use ($request) {
            $query->where('machine_no', 'like', '%' . $request->search_word . '%')
                ->orWhere('token', 'like', '%' . $request->search_word . '%');
        })->
        paginate(15);
        return  $this->jsondata(true, null, 'Successful Operation', [], $scooters);
    }

    public function updateScooter(Request $request) {
        $validator = Validator::make($request->all(), [
            'iot_id' => 'required',
            'token' => 'required',
            'machine_no' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Update failed', [$validator->errors()->first()], []);
        }

        $iot = Scooter::find($request->iot_id);
        $iot->token = $request->token;
        $iot->machine_no = $request->machine_no;
        $iot->save();

        if ($iot)
            return $this->jsondata(true, null, 'Scooter updated successfuly', [], []);
    }
    public function addScooter(Request $request) {
        $validator = Validator::make($request->all(), [
            'machine_no' => 'required',
            'token' => 'required',
        ], [
            "machine_no.required" => "Please Enter Machine Number",
            "token.required" => "Please Enter Machine token"
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Add failed', [$validator->errors()->first()], []);
        }

        $iot = Scooter::create([
            "token" => $request->token,
            "machine_no" => $request->machine_no,
        ]);
        $iot->save();

        if ($iot)
            return $this->jsondata(true, null, 'Scooter has Added successfuly', [], []);
    }
    public function deleteScooter(Request $request) {
        $validator = Validator::make($request->all(), [
            'iot_id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Delete failed', [$validator->errors()->first()], []);
        }

        $iot = Scooter::find($request->iot_id);
        $iot->delete();

        if ($iot)
            return $this->jsondata(true, null, 'Scooter has deleted successfuly', [], []);
    }
    public function unlockBattary(Request $request) {
        $validator = Validator::make($request->all(), [
            'iot_id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Delete failed', [$validator->errors()->first()], []);
        }

        $iot = Scooter::find($request->iot_id);

        $client = new Client();
        // First HTTP POST request
        $unlock_lock = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            'form_params' => [
                'machineNO' => $iot->machine_no,
                'token' => $iot->token,
                'paramName' => 15,
                'controlType' => 'control'
            ]
        ]);

        if ($iot)
            return $this->jsondata(true, null, 'Scooter battary has unlocked successfuly', [], []);
    }
    public function lockbattary(Request $request) {
        $validator = Validator::make($request->all(), [
            'iot_id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Lock failed', [$validator->errors()->first()], []);
        }

        $iot = Scooter::find($request->iot_id);

        $client = new Client();
        // First HTTP POST request
        $unlock_lock = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            'form_params' => [
                'machineNO' => $iot->machine_no,
                'token' => $iot->token,
                'paramName' => 16,
                'controlType' => 'control'
            ]
        ]);

        if ($iot)
            return $this->jsondata(true, null, 'Scooter battary has Locked successfuly', [], []);
    }
    public function lockWheel(Request $request) {
        $validator = Validator::make($request->all(), [
            'iot_id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'lock failed', [$validator->errors()->first()], []);
        }

        $iot = Scooter::find($request->iot_id);

        $client = new Client();
        // First HTTP POST request
        $unlock_lock = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            'form_params' => [
                'machineNO' => $iot->machine_no,
                'token' => $iot->token,
                'paramName' => 12,
                'controlType' => 'control'
            ]
        ]);

        if ($iot)
            return $this->jsondata(true, null, 'Scooter wheel has locked successfuly', [], []);
    }
    public function unlockWheelAndLock(Request $request) {
        $validator = Validator::make($request->all(), [
            'iot_id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'lock failed', [$validator->errors()->first()], []);
        }

        $iot = Scooter::find($request->iot_id);

        $client = new Client();
            // // First HTTP POST request
            // $unlock_lock = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            //     'form_params' => [
            //         'machineNO' => $iot->machine_no,
            //         'token' => $iot->token,
            //         'paramName' => 22,
            //         'controlType' => 'control'
            //     ]
            // ]);

            // // Second HTTP POST request
            // $unlock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            //     'form_params' => [
            //         'machineNO' => $iot->machine_no,
            //         'token' => $iot->token,
            //         'paramName' => 11,
            //         'controlType' => 'control'
            //     ]
            // ]);

        if ($iot)
            return $this->jsondata(true, null, 'Scooter wheel has unlocked successfuly', [], []);
    }
}
