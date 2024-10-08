<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Seller_history;
use App\Traits\DataFormController;
use App\Traits\SendEmailTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\FenPayHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ManageSellersController extends Controller
{
    use DataFormController;
    use SendEmailTrait;
    public function getSellers(Request $request) {
        $sellers = Seller::all();
        $admin = $request->user();
        if ($admin->role == "Accountant") {
            return  $this->jsondata(true, null, 'Successful Operation', [], $sellers);
        } else {
            return $this->jsonData(false, null, 'Faild Operation', ['You are not have the role as Accountant'], []);
        }

    }
    public function getSellerIndex($sellerid) {
        $id = $sellerid;
        return view("admin.dashboard.seller")->with(compact('id'));
    }

    public function getSellerDetails(Request $request) {
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $seller = Seller::find($request->seller_id);

        if ($seller) :
            $history = Seller_history::with(["user" => function ($q) {
                $q->select("id", "name");
            }])->where("seller_id", $seller->id)->paginate(15);
            $seller->history = $history;
        endif;

        if($seller)
            return  $this->jsondata(true, null, 'عملية ناجحة', [], $seller);
    }

    public function fillterSellerByDate(Request $request) {
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
        $seller = Seller::find($request->seller_id);

        if ($seller) :
            $history = Seller_history::with(["user" => function ($q) {
                $q->select("id", "name");
            }])->where("seller_id", $seller->id)->where("created_at", ">", $request->from)->where("created_at", "<", $request->to)->paginate(15);
            $seller->history = $history;
        endif;

        if($seller)
            return  $this->jsondata(true, null, 'عملية ناجحة', [], $seller);

    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            "name.required" => "الاسم مطلوب",
            "address.required" => "العنوان مطلوب",
            "email.required" => "البريد الالكتروني مطلوب",
            "phone.required" => "رقم الهاتف مطلوب",
            "password.required" => "كلمة السر مطلوبة",
            "password.min" => "كلمة السر يجب ان تكون من 8 خانات ع الاقل",
            "password.confirmed" => "كلمة السر والتاكيد غير متطابقين"
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $admin = $request->user();
        if ($admin->role == "Accountant") {
            $seller = Seller::create([
                'name' => $request->name,
                'address' => $request->address,
                'email' => $request->email,
                'phone' => $request->phone,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'password' => Hash::make($request->password),
            ]);
            if ($seller)
                return  $this->jsondata(true, null, 'تم اضافة البائع بنجاح', [], []);
        } else {
            return $this->jsonData(false, null, 'Faild Operation', ['You are not have the role as Accountant'], []);
        }

    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'phone' => 'required',
        ], [
            "name.required" => "الاسم مطلوب",
            "address.required" => "العنوان مطلوب",
            "email.required" => "البريد الالكتروني مطلوب",
            "phone.required" => "رقم الهاتف مطلوب",
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        if ($request->password || $request->password_confirmation) {
            if ($request->password < 8)
                return $this->jsondata(false, null, 'Login failed', ["كلمة السر يجب ان تكون من 8 خانات ع الاقل"], []);
            if ($request->password !== $request->password_confirmation)
                return $this->jsondata(false, null, 'Login failed', ["كلمة السر والتاكيد غير متطابقين"], []);
        }

        $admin = $request->user();
        if ($admin->role == "Accountant") {
            $seller = Seller::find($request->seller_id);
            $seller->name = $request->name;
            $seller->email = $request->email;
            $seller->address = $request->address;
            $seller->phone = $request->phone;
            $seller->latitude = $request->latitude;
            $seller->longitude = $request->longitude;
            $seller->save();
            if ($request->password)
                $seller->password = $request->password;
            if ($seller)
                return  $this->jsondata(true, null, 'تم تعديل بيانات البائع بنجاح', [], []);
        } else {
            return $this->jsonData(false, null, 'Faild Operation', ['You are not have the role as Accountant'], []);
        }

    }

    public function reloadPoints(Request $request) {
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $seller = Seller::find($request->seller_id);
        $admin = $request->user();

        if ($admin->role == "Accountant") {
            DB::transaction(function () use ($seller, $admin) {
                // Record history
                FenPayHistory::create([
                    'seller_id' => $seller->id,
                    'admin_id' => $admin->id,
                    'ammount' => $seller->unbilled_points ,
                ]);

                // Reset unbilled points
                $seller->unbilled_points = 0;
                $seller->save();
            });

            return $this->jsondata(true, null, 'تم اعادة نقاط البائع بنجاح', [], []);
        } else {
            return $this->jsonData(false, null, 'Faild Operation', ['You do not have the role of Accountant'], []);
        }
    }

    public function getTransactionsHistory(Request $request) {
        $admin = $request->user();

        $transactions = $admin->transactions()->with(['admin', 'seller'])->paginate(20);

        return $this->jsonData(true, true, '', [], ['seller' => $transactions]);
    }

    public function deleteSeller(Request $request) {
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $seller = Seller::find($request->seller_id);
        $admin = $request->user();
        if ($admin->role == "Accountant") {
            $seller->delete();
            if ($seller)
                return  $this->jsondata(true, null, 'تم اعادة حذف البائع بنجاح', [], []);
        } else {
            return $this->jsonData(false, null, 'Faild Operation', ['You are not have the role as Accountant'], []);
        }

    }

    public function search(Request $request) {
        $admin = $request->user();
        if ($admin->role == "Accountant") {
            $byNames = Seller::orderBy(\DB::raw('ABS(TIMESTAMPDIFF(SECOND, created_at, NOW()))'))->where('name', 'like', '%' . $request->search . '%')->get();

            $byAddresses = Seller::orderBy(\DB::raw('ABS(TIMESTAMPDIFF(SECOND, created_at, NOW()))'))->where('address', 'like', '%'.$request->search.'%')->get();

            $phones = Seller::orderBy(\DB::raw('ABS(TIMESTAMPDIFF(SECOND, created_at, NOW()))'))->where('phone', 'like', '%'.$request->search.'%')->get();

            return $this->jsonData(true, null, '', [], !$byNames->isEmpty() ? $byNames : (!$byAddresses->isEmpty() ? $byAddresses : $phones));

        } else {
            return $this->jsonData(false, null, 'Faild Operation', ['You are not have the role as Accountant'], []);
        }

    }


}
