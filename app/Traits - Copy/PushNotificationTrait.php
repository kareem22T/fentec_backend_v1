<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification; 
use App\Models\User; 
use PHPMailer\PHPMailer\Exception;
use ExpoSDK\ExpoMessage;
use ExpoSDK\Expo;
use Illuminate\Support\Facades\Http;

trait PushNotificationTrait
{

    public function pushNotification($title, $body, $token = null, $user_id = null, $data = null)
    {
        $CreateNotification = Notification::create([
            "user_id" => $user_id,
            "title" => $title,
            "body" => $body,
        ]);

        if ($user_id) :
            $user = User::find($user_id);
            if ($user) :
                $user->has_unseened_notifications = true;
                $user->save();
            endif;
        else :
            User::where('id', '>', 0)->update(['has_unseened_notifications' => true]);
        endif;

        $serverKey = 'AAAABSRf2YE:APA91bHHsnnNLnjhh6NI6pxCXWv8vH5C1ZQ4wO8qcN3K1Ql-keyWnbP77uTPz21hLgoThi3ni707rt-cufgDY8ismiLCuwbsMjD1C-FSZPgf64nuSTGFE8wP6DecOckgQHrnXauiAIWC';
        $deviceToken = $token ? $token : "/topics/all_users";
        
        $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])
            ->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'data' => $data,
                    'icon' => "https://fentecmobility.com/imgs/icon.jpg"
                ],
            ]);
        
        // You can then check the response as needed
        if ($response->successful()) {
            // Request was successful
            return $responseData = $response->json();
            // Handle the response data
        } else {
            // Request failed
            return $errorData = $response->json();
            // Handle the error data
        }
    }
}