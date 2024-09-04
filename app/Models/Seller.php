<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Seller  extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'password',
        "unbilled_points"
    ];

    /**
     * Get all of the comments for the Seller
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany('App\Models\Seller_history', 'seller_id');
    }

    public function transactions()
    {
        return $this->hasMany(FenPayHistory::class, 'seller_id');
    }

}
