<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "scooter_id",
        "started_at",
        "ending_location",
        "starting_location",
        "duration",
        "ended_at"
    ];

    public $timestamps = false;

    /**
     * Get the user associated with the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne("App\Models\User", 'id', 'user_id');
    }
    public function scooter()
    {
        return $this->hasOne("App\Models\Scooter", 'id', 'scooter_id');
    }
}
