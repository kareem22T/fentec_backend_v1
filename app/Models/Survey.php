<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "reaction",
        "comment",
    ];
    public function user()
    {
        return $this->hasOne("App\Models\User", 'id', 'user_id');
    }

}
