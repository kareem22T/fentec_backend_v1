<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification; 
use App\Models\User; 
use PHPMailer\PHPMailer\Exception;
use ExpoSDK\ExpoMessage;
use ExpoSDK\Expo;

trait PushNotificationTrait
{

    public function pushNotification($title, $body, $tokens = [], $user_id = null)
    {
        $CreateNotification = Notification::create([
            "user_id" => $user_id,
            "title" => $title,
            "body" => $body,
        ]);

        if ($user_id) :
            $user = User::find($user_id);
            $user->has_unseened_notifications = true;
            $user->save();
        else :
            User::where('id', '>', 0)->update(['has_unseen_notifications' => true]);
        endif;

        $expo = Expo::driver('file');
        $message = (new ExpoMessage([
            'title' => $title,
            'body' => $body,
        ]))
        ->setTitle($title)
        ->setBody($body)
        ->setData(['id' => 1])
        ->setChannelId('default')
        ->setBadge(0)
        ->playSound();

        $recipients = $tokens;

        return $response = $expo->send($message)->to($recipients)->push();
    }
}