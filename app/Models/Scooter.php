<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scooter extends Model
{
    use HasFactory;
    protected $fillable = [
        'machine_no',
        'token',
        'longitude',
        'latitude',
        'battary_charge'
    ];
}
