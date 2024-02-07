<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use App\Traits\SendEmailTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Traits\PushNotificationTrait;
use App\Models\User;

class ManageUsersController extends Controller
{
    use DataFormController;
    use SendEmailTrait;
    use PushNotificationTrait;
    public function __construct()
    {
        $this->middleware('admin:Moderator')->only(['previewIndex', 'getUsers', "approve", "reject"]);
    }

    public function previewIndex () {
        return view('admin.dashboard.users.preview');
    }

    public function getUsers(Request $request) {

        if (!$request->getJustUsersWithType) :
            $usersRequests = User::where('approved', false)->where('rejected', false)->where('isBanned', false)->where('name', '!=', null)->paginate(15);
            $usersList = User::where('approved', true)->where('isBanned', false)->paginate(15);
            $incompleteUsers = User::where('name', null)->paginate(15);
            $rejectedUsers = User::where('rejected', true)->paginate(15);
            $bannedUsers = User::where('isBanned', true)->paginate(15);
            
            return  $this->jsondata(true, null, 'Successful Operation', [], [
                "usersRequests" => $usersRequests,
                "usersList" => $usersList,
                "incompleteUsers" => $incompleteUsers,
                "rejectedUsers" => $rejectedUsers,
                "bannedUsers" => $bannedUsers,
            ]);
        endif;

        if ($request->getJustUsersWithType && $request->getJustUsersWithType === "Active"):
            $usersList = User::where('approved', true)->where('isBanned', false)
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->Active_search_words . '%')
                    ->orWhere('email', 'like', '%' . $request->Active_search_words . '%')
                    ->orWhere('phone', 'like', '%' . $request->Active_search_words . '%');
            })
            ->paginate(15);
            return  $this->jsondata(true, null, 'Successful Operation', [], [
                "usersList" => $usersList,
            ]);
        endif;
        if ($request->getJustUsersWithType && $request->getJustUsersWithType === "Requests"):
            $usersRequests = User::where('approved', false)->where('rejected', false)->where('isBanned', false)->where('name', '!=', null)
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->Requests_search_words . '%')
                    ->orWhere('email', 'like', '%' . $request->Requests_search_words . '%')
                    ->orWhere('phone', 'like', '%' . $request->Requests_search_words . '%');
            })
            ->paginate(15);
            return  $this->jsondata(true, null, 'Successful Operation', [], [
                "usersRequests" => $usersRequests,
            ]);
        endif;
        if ($request->getJustUsersWithType && $request->getJustUsersWithType === "Incomplete"):
            $incompleteUsers = User::where('name', null)
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->Incomplete_search_words . '%')
                    ->orWhere('email', 'like', '%' . $request->Incomplete_search_words . '%')
                    ->orWhere('phone', 'like', '%' . $request->Incomplete_search_words . '%');
            })
            ->paginate(15);
            return  $this->jsondata(true, null, 'Successful Operation', [], [
                "incompleteUsers" => $incompleteUsers,
            ]);
        endif;
        if ($request->getJustUsersWithType && $request->getJustUsersWithType === "Banned"):
            $bannedUsers = User::where('isBanned', true)
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->Banned_search_words . '%')
                    ->orWhere('email', 'like', '%' . $request->Banned_search_words . '%')
                    ->orWhere('phone', 'like', '%' . $request->Banned_search_words . '%');
            })
            ->paginate(15);
            return  $this->jsondata(true, null, 'Successful Operation', [], [
                "bannedUsers" => $bannedUsers,
            ]);
        endif;
        if ($request->getJustUsersWithType && $request->getJustUsersWithType === "Rejected"):
            $rejectedUsers = User::where('rejected', true)
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->Rejected_search_words . '%')
                    ->orWhere('email', 'like', '%' . $request->Rejected_search_words . '%')
                    ->orWhere('phone', 'like', '%' . $request->Rejected_search_words . '%');
            })
            ->paginate(15);
            return  $this->jsondata(true, null, 'Successful Operation', [], [
                "rejectedUsers" => $rejectedUsers,
            ]);
        endif;
    }

    public function approve(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
            'id.required' => 'user id is required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $user = User::find($request->id);
        $user->approved  = true;
        $user->rejected  = false;
        $user->rejection_reason  = null;
        $user->isBanned = false;
        $user->ban_reason = null;
        $user->save();

        if ($user) :
            $email = $user->email;
            $msg_title = 'Fentec Account Approved';
            $msg_body = 
                "Hello " . $user->name . " <br>You Account has been approved now you can enjoy the journy with us";

            $this->sendEmail($email, $msg_title, $msg_body);
            if ($user->notification_token)
                $response = $this->pushNotification($msg_title, $msg_body, $user->notification_token, $user->id);
            return  $this->jsondata(true, null, 'User Approved Successfuly', [], []);
        endif;
    }

    public function reject(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'rejection_reason' => 'required',
        ], [
            'id.required' => 'user id is required',
            'rejection_reason.required' => 'Please enter rejection_reason',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $user = User::find($request->id);
        $user->approved = false;
        $user->rejected = true;
        $user->rejection_reason = $request->rejection_reason;
        $user->save();

        if ($user) :
            $email = $user->email;
            $msg_title = 'Fentec Account Rejected';
            $msg_body = 
                "Hello " . $user->name . " <br>You Account has been rejected because: <br>" . $user->rejection_reason;

            $this->sendEmail($email, $msg_title, $msg_body);
            if ($user->notification_token)
                $response = $this->pushNotification($msg_title, $msg_body, $user->notification_token, $user->id);
            return  $this->jsondata(true, null, 'User Rejected Successfuly', [], []);
        endif;
    }

    public function ban(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'ban_reason' => 'required',
        ], [
            'id.required' => 'user id is required',
            'ban_reason.required' => 'Please enter ban reason',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $user = User::find($request->id);
        $user->isBanned = true;
        $user->approved = false;
        $user->ban_reason = $request->ban_reason;
        $user->save();

        if ($user) :
            $email = $user->email;
            $msg_title = 'Fentec Account Banned';
            $msg_body = 
                "Hello " . $user->name . " <br>You Account has been banned because: <br>" . $user->ban_reason
                . "<br> Call Customer Service for more details: 123456789";

            $this->sendEmail($email, $msg_title, $msg_body);
            if ($user->notification_token)
                $response = $this->pushNotification($msg_title, $msg_body, $user->notification_token, $user->id);
            return  $this->jsondata(true, null, 'User Banned Successfuly', [], []);
        endif;
    }
}
