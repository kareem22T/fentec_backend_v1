<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManageAdminControler extends Controller
{
    use DataFormController;
    function index() {
        return view('admin.dashboard.admins');
    }

    public function get() {
        $masters = Admin::where('role', "Master")->where('id', '!=', Auth::guard('admin')->user()->id)->get();
        $technicians = Admin::where('role', "Technician")->where('id', '!=', Auth::guard('admin')->user()->id)->get();
        $accountant = Admin::where('role', "Accountant")->where('id', '!=', Auth::guard('admin')->user()->id)->get();
        $moderators = Admin::where('role', "Moderator")->where('id', '!=', Auth::guard('admin')->user()->id)->get();

        return  $this->jsondata(true, null, 'Successful Operation', [], [
            "masters" => $masters,
            "technicians" => $technicians,
            "accountant" => $accountant,
            "moderators" => $moderators,
        ]);
    }

    public function add (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:admins,email',
            'phone' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Add failed', [$validator->errors()->first()], []);
        }

        $createAdmin = Admin::create(['full_name' => $request->name, 'phone' => $request->phone, 'email' => $request->email, 'password' => Hash::make($request->password), 'role' => $request->role]);

        if ($createAdmin)
            return $this->jsondata(true, null, $request->role . ' has added successfuly', [], []);
    }

}
