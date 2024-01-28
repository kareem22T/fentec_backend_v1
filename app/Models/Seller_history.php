<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller_history extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'recipient',
        'user_id',
        'amount',
        'seller_id',
        'seller_name',
        'created_at'
    ];

    protected $table = 'seller_history';

    /**
     * Get the user that owns the Seller_history
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo('App\Models\Seller', 'seller_id');
    }
    /**
     * Get the user associated with the Seller_history
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
