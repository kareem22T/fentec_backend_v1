<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Trip;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\SendEmailTrait;
use App\Traits\PushNotificationTrait;

class CheckTripPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendEmailTrait, PushNotificationTrait;

    /**
     * Create a new job instance.
     */
    public $trip_id;
    public function __construct($trip_id)
    {
        $this->trip_id = $trip_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $trip = Trip::find($this->trip_id);
        if ($trip) {
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
                } else {
                    sleep(60);
                    $trip2 = Trip::find($this->trip_id);
                    if ($trip->lock_photo == null) {
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
}
