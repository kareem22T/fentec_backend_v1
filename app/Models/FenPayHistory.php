<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FenPayHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'admin_id',
        'ammount'
    ];

    // Relationship to the Seller model
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    // Relationship to the Admin model
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
