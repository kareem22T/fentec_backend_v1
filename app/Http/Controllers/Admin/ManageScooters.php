<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;

use App\Models\Zone;

class ManageScooters extends Controller
{
    use DataFormController;

    public function index () {
        return view("admin.dashboard.scooters");
    }
    public function zonesIndex () {
        $zones = Zone::all();
        return view("admin.dashboard.zones")->with(compact('zones'));
    }
    public function add (Request $request) {
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
    public function delete (Request $request) {
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
}
