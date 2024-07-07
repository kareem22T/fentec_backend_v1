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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scooter = $this->getScooter();
        $trip = $scooter->trips()->orderBy("started_at", "desc")->first();

        if ($trip->ended_at) {
            if ($trip->lock_photo == null) {
                $now = Carbon::now();  // Replace with your actual ended_at
                $timeInterval = $now->diffInMinutes($trip->ended_at);
                if ($timeInterval >= 2) {
                    $admins = Admin::where("role", "Master")->get();
                    $user = User::find($trip->user_id);
                    if ($user) {
                        $content = "This user Does Not ended his Trip <br />";
                        $content .= "Name: " . $user->name . "<br />";
                        $content .= "Phone: " . $user->phone . "<br />";
                        $content .= "Email: " . $user->email . "<br />";
                        foreach ($admins as $admin) {
                            $this->sendEmail($admin->email, "Un Submitid Trip!", $content);
                        }
                    }
                    if ($user->notification_token)
                        $response = $this->pushNotification("Warning", "You violated the trip termination policy by not taking a parking photo, Call 0660980645 to avoid ban", $user->notification_token, $user->id);

                    $this->sendEmail($user->email, "Warning", "You violated the trip termination policy by not taking a parking photo, Call 0660980645 to avoid ban");
                }
            }
        }
    }
}
