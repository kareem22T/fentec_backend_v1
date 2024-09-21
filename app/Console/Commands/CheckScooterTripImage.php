<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\User;
use App\Traits\PushNotificationTrait;
use App\Traits\SendEmailTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;

abstract class CheckScooterTripImage extends Command
{
    use PushNotificationTrait, SendEmailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scooter:check:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check if scooter trip image is submitted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scooter = $this->getScooter();
        $trip = $scooter->trips()->orderBy("started_at", "desc")->first();

        if ($trip && $trip->ended_at && $trip->lock_photo == null) {
            $now = Carbon::now();
            $timeInterval = $now->diffInMinutes($trip->ended_at);

            if ($timeInterval < 5) {
                $admins = Admin::where("role", "Master")->get();
                $user = User::find($trip->user_id);

                if ($user) {
                    $content = "This user did not end their trip correctly:<br />";
                    $content .= "Name: " . $user->name . "<br />";
                    $content .= "Phone: " . $user->phone . "<br />";
                    $content .= "Email: " . $user->email . "<br />";

                    foreach ($admins as $admin) {
                        $this->sendEmail($admin->email, "Unsubmitted Trip!", $content);
                    }

                    if ($user->notification_token) {
                        $this->pushNotification(
                            "Warning",
                            "You violated the trip termination policy by not taking a parking photo. Call 0660980645 to avoid a ban.",
                            $user->notification_token,
                            $user->id
                        );
                    }

                    $content2 = "عزيزي المستخدم <br>";
                    $content2 .= "لقد قمت بعدم إحترام تعليمات إنهاء الرحلة بعدم أخذ صورة التروتينات بعد الركن.<br>";
                    $content2 .= "الرجاء الإتصال بالرقم 0540842707.<br><br>";
                    $content2 .= "الرجاء العلم أنه في حال تكرار هذا التصرف سيتم إيقاف حسابك.<br><br>";
                    $content2 .= "فانتك موبيليتي تقدم خدمة فعالة و مفيده للمجتمع الرجاء المحافظة عليها.<br><br>";
                    $content2 .= "Dear User <br>";
                    $content2 .= "You violated the trip termination policy by not taking a parking photo of the e-scooter. Please Call 0540842707.<br>";
                    $content2 .= "Please note that if this behavior is repeated, your account will be suspended.<br>";
                    $content2 .= "FenTec Mobility provides an effective and useful service to the community, please maintain it.";


                    $this->sendEmail(
                        $user->email,
                        "Warning",
                        $content2
                    );
                }
            }
        }
    }

    /**
     * Retrieve the scooter instance.
     * You need to implement this method based on your application logic.
     */
    abstract protected function getScooter();
}
