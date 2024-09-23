<?php

namespace App\Http\Controllers\Admin;

use App\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Traits\DataFormController;

class StatisticsController extends Controller
{
    use DataFormController;

    public function index() {
        return view("admin.dashboard.accountant");
    }
    public function getSellersBySearch(Request $request) {
        $sellers = Seller::latest()->with("history")
        ->where("name", 'like', '%' . $request->search_word . '%')
        ->orWhere("email", 'like', '%' . $request->search_word . '%')
        ->orWhere("phone", 'like', '%' . $request->search_word . '%')
        ->orWhere("address", 'like', '%' . $request->search_word . '%')
        ->paginate(15);
        foreach ($sellers as $seller) {
            $seller_soldPoints = 0;
            foreach ($seller->history as $result) {
                $seller_soldPoints += (float) $result->amount;
            }
            $seller->sold_points = $seller_soldPoints;
        }
        return $this->jsonData(true, true, '', [], $sellers->isEmpty() ? [] : $sellers);
    }
    public function getSellers() {
        $sellers = Seller::latest()->with("history")->paginate(15);
        foreach ($sellers as $seller) {
            $seller_soldPoints = 0;
            foreach ($seller->history as $result) {
                $seller_soldPoints += (float) $result->amount;
            }
            $seller->sold_points = $seller_soldPoints;
        }
        return  $this->jsondata(true, null, 'Successful Operation', [], $sellers);
    }

    public function createSeller(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone' => 'required',
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

        $seller = Seller::create([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        if($seller)
            return  $this->jsondata(true, null, 'تم اضافة البائع بنجاح', [], []);

    }

    public function updateSeller(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'email' => 'required',
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

        $seller = Seller::find($request->seller_id);
        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->address = $request->address;
        $seller->phone = $request->phone;
        if ($request->password)
            $seller->password = Hash::make($request->password);
        $seller->save();
        if ($seller)
            return  $this->jsondata(true, null, 'تم تعديل بيانات البائع بنجاح', [], []);

    }

    public function deleteSeller(Request $request) {
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $seller = Seller::find($request->seller_id);
        $seller->delete();
        if ($seller)
            return  $this->jsondata(true, null, 'تم حذف البائع بنجاح', [], []);

    }
    public function fillterSellersByDate(Request $request) {
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

        $sellers = Seller::latest()->where("created_at", ">", $request->from)->where("created_at", "<", $request->to)->with("history")->paginate(15);
        foreach ($sellers as $seller) {
            $seller_soldPoints = 0;
            foreach ($seller->history as $result) {
                $seller_soldPoints += (float) $result->amount;
            }
            $seller->sold_points = $seller_soldPoints;
        }
        return  $this->jsondata(true, null, 'Successful Operation', [], $sellers);

    }

}
