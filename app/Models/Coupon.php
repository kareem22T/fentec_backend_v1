<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'gift',
        'start_in',
        'end_in'
    ];
    public function hasExpired()
    {
        return now()->gt($this->end_in);
    }
    public function hasNotStarted()
    {
        return now()->lt($this->start_in);
    }
    public $timestamps = false;
}
