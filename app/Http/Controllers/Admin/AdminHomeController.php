<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Notification;
use App\Traits\DataFormController;
use App\Traits\PushNotificationTrait;

class AdminHomeController extends Controller
{
    use DataFormController, PushNotificationTrait;
    
    public function getIndex() {
        return view('admin.dashboard.home');
    }

    public function addCoupon (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'code' => 'required',
            'gift' => 'required',
            'start_in' => 'required',
            'end_in' => 'required',
        ], [
            'title.required' => 'Please Enter Coupon Title',
            'code.required' => 'Please Enter Coupon Code',
            'start_in.required' => 'Please Enter Coupon start date',
            'end_in.required' => 'Please Enter Coupon end date',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $coupon = Coupon::create([
            'title' => $request->title,
            'code' => $request->code,
            'gift' => $request->gift,
            'start_in' => $request->start_in,
            'end_in' => $request->end_in,
        ]);

        $response = $this->pushNotification("New Coupon Code", "Get " . $request->gift . " Coins by using the copoun " . $request->code, null, null, ["id" => 'djsdf']);

        return $this->jsondata(true, null, 'Coupon pushed successfuly', [], []);
    }

    public function pushNotificationmain(Request $request) {
        $validator = Validator::make($request->all(), [
            'msg_title' => 'required',
            'msg' => 'required',
        ], [
            'msg.required' => 'Please Enter Notfication msg',
            'msg_title.required' => 'Please Enter Notification title',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'push failed', [$validator->errors()->first()], []);
        }

            $response = $this->pushNotification($request->msg_title, $request->msg, null, null, ["id" => 'djsdf']);
            return $this->jsondata(true, null, 'Notification has pushed successfuly', [], [$response]);
    }
    public function resendNotification(Request $request) {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
        ]);

        
        if ($validator->fails()) {
            return $this->jsondata(false, null, 'resend failed', [$validator->errors()->first()], []);
        }
        $notification = Notification::find($request->notification_id);
        
        if ($notification)
            $response = $this->pushNotification($notification->title, $notification->body, null, $notification->user_id);
            return $this->jsondata(true, null, 'Notification has resent successfuly', [], [$response]);
    }

    public function getNotifications(Request $request) {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'get Notifications failed', [$validator->errors()->first()], []);
        }

        $notifications;

        if ($request->type === "Main")
            $notifications = Notification::latest()
            ->where("user_id", null)
            ->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search_words . '%')
                    ->orWhere('body', 'like', '%' . $request->search_words . '%');
            })
            ->paginate(10);
        else
            $notifications = Notification::latest()
            ->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search_words . '%')
                    ->orWhere('body', 'like', '%' . $request->search_words . '%');
            })
            ->paginate(10);

        return  $this->jsondata(true, null, 'Successful Operation', [], $notifications);
    }

    public function DeleteNotifications(Request $request) {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Delete Notifications failed', [$validator->errors()->first()], []);
        }
        
        $notification = Notification::find($request->notification_id);
        
        $notification->delete();

        return $this->jsondata(true, null, 'Notification deleted successfuly', [], []);
    }

    
}
