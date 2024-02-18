<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Scooter;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ScooterController extends Controller
{
    use DataFormController;

    public function unlockScooter(Request $request, $scooter_id) {
        $minCost = 10; //coins
        $user = $request->user();
        
        $iot = Scooter::find($scooter_id);

        if ($user->isBanned)
            return $this->jsondata(false, null, 'Unlock failed', ["Your Account is banned call customer service for more details"], []);

        if ($user->rejected)
            return $this->jsondata(false, null, 'Unlock failed', ["Your Account is rejected please update your information to get approved"], []);

        if (!$user->approved)
            return $this->jsondata(false, null, 'Unlock failed', ["Your Account is under review and not approved yet"], []);

        $userAvilableRideMin = (int) $user->coins /$minCost;

        if ($userAvilableRideMin < 5)
            return $this->jsondata(false, null, 'Unlock failed', ["You don not have enough points at least " . $minCost * 5 . " for 5 Min" ], []);

            $client = new Client();
            // First HTTP POST request
            $unlock_lock = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
                'form_params' => [
                    'machineNO' => $iot->machine_no,
                    'token' => $iot->token,
                    'paramName' => 22,
                    'controlType' => 'control'
                ]
            ]);
            sleep(.5);
            // Second HTTP POST request
            $unlock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
                'form_params' => [
                    'machineNO' => $iot->machine_no,
                    'token' => $iot->token,
                    'paramName' => 11,
                    'controlType' => 'control'
                ]
            ]);

        // $isOneSuccess = (int) $unlock_lock->ret;
        // $isTwonSuccess = (int) $unlock_lock_wheel->ret;

        // if (!$isOneSuccess || !$isTwonSuccess) :

        //     // Second HTTP POST request
        //     $lock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
        //         'form_params' => [
        //             'machineNO' => $iot->machine_no,
        //             'token' => $iot->token,
        //             'paramName' => 12,
        //             'controlType' => 'control'
        //         ]
        //     ]);
        
        //     return $this->jsondata(false, null, 'Unlock failed', ["Faild to Unlock Scooter Try Agin or Call Customer service to solve"], []);
        // endif;
        
        $serverKey = 'AAAABSRf2YE:APA91bHHsnnNLnjhh6NI6pxCXWv8vH5C1ZQ4wO8qcN3K1Ql-keyWnbP77uTPz21hLgoThi3ni707rt-cufgDY8ismiLCuwbsMjD1C-FSZPgf64nuSTGFE8wP6DecOckgQHrnXauiAIWC';
        $deviceToken = "/topics/Journey_channel_" . $user->id;
        $title = "hello";
        $body = "hello";
        $data = "hello";
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
                "data" => ["unlock"=> true]
            ]);

        $create_trip = Trip::create([
            "user_id" => $user->id,
            "scooter_id" => $scooter_id,
            "started_at" => now()
        ]);

        while ($userAvilableRideMin) {
            $trip = Trip::find($create_trip->id);
            if (!$trip->ended_at) {
                if ($userAvilableRideMin == 1) {
                    $trip->ended_at = now();
                    $trip->save();
                    // push warning notification
                }
                $user->coins = (int) $user->coins - 10;
                $user->save();
                $userAvilableRideMin -= 1;
                sleep(58);
            } else {
                $userAvilableRideMin = 0;
            }

        }

        // Second HTTP POST request
        $lock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            'form_params' => [
                'machineNO' => $iot->machine_no,
                'token' => $iot->token,
                'paramName' => 12,
                'controlType' => 'control'
            ]
        ]);

        // send notification to unique channel to remove end pop up and tell him thanks for the journey and ask for rate

        // start timer for 5 min to check if image taked if taked send notification to remove take image pop up and end

        // check if he take the photo

        // end and send warning notification for him to call customer service for submit scooter or he would be banned

    }

    public function lockScooter(Request $request) {

        // Second HTTP POST request
        $lock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            'form_params' => [
                'machineNO' => $iot->machine_no,
                'token' => $iot->token,
                'paramName' => 12,
                'controlType' => 'control'
            ]
        ]);

        $trips = Trip::all();
        foreach ($trips as $trip) {
            $trip->ended_at = now();
            $trip->save();
        }

        $user = $request->user();
        $serverKey = 'AAAABSRf2YE:APA91bHHsnnNLnjhh6NI6pxCXWv8vH5C1ZQ4wO8qcN3K1Ql-keyWnbP77uTPz21hLgoThi3ni707rt-cufgDY8ismiLCuwbsMjD1C-FSZPgf64nuSTGFE8wP6DecOckgQHrnXauiAIWC';
        $deviceToken = "/topics/Journey_channel_" . $user->id;
        $title = "hello";
        $body = "hello";
        $data = "hello";
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
                "data" => ["lock"=> true]
            ]);


    }

    public function unlocNotification($user_id) {
        $serverKey = 'AAAABSRf2YE:APA91bHHsnnNLnjhh6NI6pxCXWv8vH5C1ZQ4wO8qcN3K1Ql-keyWnbP77uTPz21hLgoThi3ni707rt-cufgDY8ismiLCuwbsMjD1C-FSZPgf64nuSTGFE8wP6DecOckgQHrnXauiAIWC';
        $deviceToken = "/topics/Journey_channel_" . $user_id;
        $title = "hello";
        $body = "hello";
        $data = "hello";
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
                "data" => ["unlock"=> true]
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
