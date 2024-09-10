<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Zone;
use App\Models\Scooter;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Jobs\CheckTripPhoto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\SavePhotoTrait;

class ScooterController extends Controller
{
    use SavePhotoTrait;
    use DataFormController;

    public function unlockScooter(Request $request) {
        $lang = $request->lang ? $request->lang : 'en';
        $minCost = 5; //coins
        $user = $request->user();

        $error_msgs = [
            "invalid_serial" => [
                "en" => "Invalid Serial Number",
                "fr" => "Numéro de série invalide",
                "ar" => "رقم تسلسلي غير صالح",
            ],
            "account_banned" => [
                "en" => "Your account is banned, call customer service for more details",
                "fr" => "Votre compte est banni, appelez le service client pour plus de détails",
                "ar" => "تم حظر حسابك، اتصل بخدمة العملاء للحصول على مزيد من التفاصيل",
            ],
            "account_rejected" => [
                "en" => "Your account is rejected, please update your information to get approved",
                "fr" => "Votre compte est rejeté, veuillez mettre à jour vos informations pour être approuvé",
                "ar" => "تم رفض حسابك، يرجى تحديث معلوماتك للحصول على الموافقة",
            ],
            "account_under_review" => [
                "en" => "Your account is under review and not approved yet",
                "fr" => "Votre compte est en cours de révision et n'est pas encore approuvé",
                "ar" => "حسابك قيد المراجعة ولم يتم الموافقة عليه بعد",
            ],
            "not_enough_points" => [
                "en" => "You do not have enough points, at least " . $minCost * 10 . " coins are required for 10 minutes",
                "fr" => "Vous n'avez pas assez de points, au moins " . $minCost * 10 . " pièces sont nécessaires pour 10 minutes",
                "ar" => "ليس لديك نقاط كافية، مطلوب على الأقل " . $minCost * 10 . " قطعة لـ 10 دقائق",
            ],
            "trip_started" => [
                "en" => "Your trip has started successfully. Enjoy",
                "fr" => "Votre parcours a commencé avec succès. Profitez",
                "ar" => "لقد بدأت رحلتك بنجاح. إستمتع",
            ],
            "unlock_failed" => [
                "en" => "Unlock failed",
                "fr" => "Échec du déverrouillage",
                "ar" => "فشل في الفتح",
            ],
        ];

        $iot = Scooter::where("machine_no", $request->scooter_serial)->orWhere("iot_id", $request->scooter_serial)->first();

        if (!$iot)
            return $this->jsondata(false, null, $error_msgs["unlock_failed"][$lang], [$error_msgs["invalid_serial"][$lang]], []);

        if ($user->isBanned)
            return $this->jsondata(false, null, $error_msgs["unlock_failed"][$lang], [$error_msgs["account_banned"][$lang]], []);

        if ($user->rejected)
            return $this->jsondata(false, null, $error_msgs["unlock_failed"][$lang], [$error_msgs["account_rejected"][$lang]], []);

        if (!$user->approved)
            return $this->jsondata(false, null, $error_msgs["unlock_failed"][$lang], [$error_msgs["account_under_review"][$lang]], []);

        $userAvailableRideMin = (float) $user->coins / $minCost;

        if ($userAvailableRideMin < 10)
            return $this->jsondata(false, null, $error_msgs["unlock_failed"][$lang], [$error_msgs["not_enough_points"][$lang]], []);

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

        // Second HTTP POST request
        $unlock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
            'form_params' => [
                'machineNO' => $iot->machine_no,
                'token' => $iot->token,
                'paramName' => 11,
                'controlType' => 'control'
            ]
        ]);

        $create_trip = Trip::create([
            "user_id" => $user->id,
            "scooter_id" => $iot->id,
            "started_at" => now()
        ]);

        $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);
        if ($response->successful()) {
            $start_lat = $response['data'][0]['latitude'];
            $start_lng = $response['data'][0]['longitude'];
            $address = Http::post('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $start_lat . ',' . $start_lng . '&key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&language=ar');
            $create_trip->starting_location = $address["results"][0]['formatted_address'];
            $create_trip->save();
        }

        $user->current_trip_id = $create_trip->id;
        $user->save();

        return $this->jsondata(true, null, $error_msgs["trip_started"][$lang], [], []);
    }
    public function lockScooter(Request $request) {
        $client = new Client();
        $user = $request->user();
        $trip = Trip::find($user->current_trip_id);

        if ($trip) {
            $iot = Scooter::find($trip->scooter_id);
            if ($iot) {
                // Second HTTP POST request
                $lock_lock_wheel = $client->post('http://api.uqbike.com/terControl/sendControl.do', [
                    'form_params' => [
                        'machineNO' => $iot->machine_no,
                        'token' => $iot->token,
                        'paramName' => 12,
                        'controlType' => 'control'
                    ]
                ]);
            }

            if ($trip) {
                $startedAt = Carbon::parse($trip->started_at);

                // Assuming ended_at is available in your model or variable
                $endedAt = Carbon::now();  // Replace with your actual ended_at

                $timeInterval = $endedAt->diffInMinutes($startedAt);
                $trip->ended_at = $endedAt;
                $trip->duration = $timeInterval;
                $response = Http::post('http://api.uqbike.com/position/getpos.do?machineNO=' . $iot->machine_no . "&token=" . $iot->token);
                if ($response->successful()) {
                    $start_lat = $response['data'][0]['latitude'];
                    $start_lng = $response['data'][0]['longitude'];
                    $address = Http::post('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $start_lat . ',' . $start_lng . '&key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&language=ar');
                    $trip->ending_location = $address["results"][0]['formatted_address'];
                }
                $trip->save();
                if ($user) {
                    $user->coins = (float) $user->coins - ($timeInterval * 15);
                    $user->save();
                }

            }
            // dispatch(new CheckTripPhoto($user->current_trip_id));
        }
        return $this->jsondata(true, null, 'Scooter Has Locked please take a photo to confirm', [], []);
    }

    public function submitTrpPhoto(Request $request) {
        $validator = Validator::make($request->all(), [
            'photo' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Lock failed', [$validator->errors()->first()], []);
        }

        $user = $request->user();
        $trip = Trip::find($user->current_trip_id);

        if ($trip) {
            if ($request->photo) :
                $disk = 'public';

                // Specify the path to the image within the storage disk
                $path = 'images/uploads/' . $trip->lock_photo;
                if (Storage::disk($disk)->exists($path))
                    Storage::disk($disk)->delete($path);

                $profile_pic = $this->saveImg($request->photo, 'images/uploads', 'trip_' . $trip->id . "_" . time());
                $trip->lock_photo = $profile_pic;
                $trip->save();

                if ($trip) {
                    $user->current_trip_id = 0;
                    $user->save();
                }
            endif;
        }
        return $this->jsondata(true, null, 'Trip has submited successfuly, Thanks!', [], []);
    }

    public function getUserTrips(Request $request) {
        $user = $request->user();
        return $user->trips()->latest('started_at')->paginate(15);
    }
    public function getUserTripsNum(Request $request) {
        $user = $request->user();
        $trips = $user->trips();

        return $trips->count();
    }
    public function getZones(Request $request) {
        $zones = Zone::all();

        return $zones;
    }
}
