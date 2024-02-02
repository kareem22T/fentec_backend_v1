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
    public function index() {
        return view('admin.dashboard.admins');
    }

    public function get(Request $request) {
        $masters = Admin::where('role', "Master")->where('id', '!=', Auth::guard('admin')->user()->id)
        ->where(function ($query) use ($request) {
            $query->where('full_name', 'like', '%' . $request->Master_search_words . '%')
                ->orWhere('email', 'like', '%' . $request->Master_search_words . '%')
                ->orWhere('phone', 'like', '%' . $request->Master_search_words . '%');
        })
        ->get();
        $technicians = Admin::where('role', "Technician")->where('id', '!=', Auth::guard('admin')->user()->id)
        ->where(function ($query) use ($request) {
            $query->where('full_name', 'like', '%' . $request->Technician_search_words . '%')
                ->orWhere('email', 'like', '%' . $request->Technician_search_words . '%')
                ->orWhere('phone', 'like', '%' . $request->Technician_search_words . '%');
        })
        ->get();
        $accountant = Admin::where('role', "Accountant")->where('id', '!=', Auth::guard('admin')->user()->id)
        ->where(function ($query) use ($request) {
            $query->where('full_name', 'like', '%' . $request->Accountant_search_words . '%')
                ->orWhere('email', 'like', '%' . $request->Accountant_search_words . '%')
                ->orWhere('phone', 'like', '%' . $request->Accountant_search_words . '%');
        })
        ->get();
        $moderators = Admin::where('role', "Moderator")->where('id', '!=', Auth::guard('admin')->user()->id)
        ->where(function ($query) use ($request) {
            $query->where('full_name', 'like', '%' . $request->Moderator_search_words . '%')
                ->orWhere('email', 'like', '%' . $request->Moderator_search_words . '%')
                ->orWhere('phone', 'like', '%' . $request->Moderator_search_words . '%');
        })
        ->get();

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

    public function update (Request $request) {
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required',
            'full_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'update failed', [$validator->errors()->first()], []);
        }

        $admin = Admin::find($request->admin_id);

        $isEmailTaken = Admin::where("id", "!=", $admin->id)->where("email", $request->email)->get()->count() > 0;
        if ($isEmailTaken) {
            return $this->jsondata(false, null, 'update failed', ["This Email is already taken"], []);
        }

        $admin->full_name = $request->full_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->role = $request->role;
        if($request->password)
            $admin->password = Hash::make($request->password);
        $admin->save();
        if ($admin)
            return $this->jsondata(true, null, $request->full_name . ' info updated successfuly', [], []);
    }

    public function delete (Request $request) {
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $admin = Admin::find($request->admin_id);
        $admin->delete();
        if ($admin)
            return $this->jsondata(true, null,  'admin has deleted successfuly', [], []);
    }

}
