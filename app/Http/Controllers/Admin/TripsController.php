<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;

class TripsController extends Controller
{
    use DataFormController;

    public function index() {
        return view("admin.dashboard.trips");
    }

    public function getTripsBySearch(Request $request) {
        $trips = Trip::latest("ended_at")->with(["user", "scooter"])
        ->where("starting_location", 'like', '%' . $request->search_word . '%')
        ->orWhere("ending_location", 'like', '%' . $request->search_word . '%')
        ->orWhere("lock_photo", 'like', '%' . $request->search_word . '%')
        ->paginate(15);

        if ($trips->count() === 0)
            $trips = Trip::latest("ended_at")->with(["user", "scooter"])
            ->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search_word.'%')
                ->orWhere('email', 'like', '%'.$request->search_word.'%')
                ->orWhere('phone', 'like', '%'.$request->search_word.'%');
            })
            ->paginate(15);

        if ($trips->count() === 0)
            $trips = Trip::latest("ended_at")->with(["user", "scooter"])
            ->whereHas('scooter', function ($query) use ($request) {
                $query->where('machine_no', 'like', '%'.$request->search_word.'%')
                ->orWhere('token', 'like', '%'.$request->search_word.'%');
            })
            ->paginate(15);

        return $this->jsonData(true, true, '', [], $trips->isEmpty() ? [] : $trips);
    }

    public function fillterTripsByDate(Request $request) {
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
        ], [
            "from.required" => "من فضلك اختر الفرة الزمنية",
            "to.required" => "من فضلك اختر الفرة الزمنية"
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'fillter failed', [$validator->errors()->first()], []);
        }

        $trips = Trip::latest("ended_at")->where("ended_at", ">", $request->from)->where("ended_at", "<", $request->to)->with(["user", "scooter"])->paginate(15);
        return  $this->jsondata(true, null, 'Successful Operation', [], $trips);

    }

    public function getTrips() {
        $trips = Trip::latest("ended_at")->with(["user", "scooter"])->paginate(15);
        return  $this->jsondata(true, null, 'Successful Operation', [], $trips);
    }
}
