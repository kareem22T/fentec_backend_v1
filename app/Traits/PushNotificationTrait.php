<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use ExpoSDK\ExpoMessage;
use ExpoSDK\Expo;

trait PushNotificationTrait
{

    public function pushNotification($title, $body, $tokens = [])
    {

        $expo = Expo::driver('file');
        $message = (new ExpoMessage([
            'title' => $title,
            'body' => $body,
        ]))
        ->setTitle($request->msg_title)
        ->setBody($request->msg)
        ->setData(['id' => 1])
        ->setChannelId('default')
        ->setBadge(0)
        ->playSound();

        $recipients = $tokens;

        return $response = $expo->send($message)->to($recipients)->push();
    }
}