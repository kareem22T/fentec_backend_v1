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
        "ended_at"
    ];

    public $timestamps = false;
}
