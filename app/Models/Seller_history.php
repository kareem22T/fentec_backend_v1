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
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
