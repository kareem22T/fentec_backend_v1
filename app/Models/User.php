<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'dob',
        'join_type',
        'identity_path',
        'photo_path',
        'last_code',
        'last_code_created_at',
        'where_know',
        'verify',
        'approved',
        'rejected',
        'rejection_reason',
        'isBanned',
        'ban_reason',
        'has_unseened_notifications',
        'approving_msg_seen',
        'password',
        'notification_token',
        'coins',
        'current_trip_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // relationships

    public function invetation_code()
    {
        return $this->hasOne('App\Models\Invetation_code', 'user_id');
    }
    public function chargeProcess()
    {
        return $this->hasMany('App\Models\Seller_history', 'user_id');
    }

    public function trips()
    {
        return $this->hasMany('App\Models\Trip', 'user_id');
    }
    public function coupons()
    {
        return $this->belongsToMany('App\Models\Coupon', 'users_coupons', 'user_id', 'coupon_id', 'id', 'id');
    }

}
