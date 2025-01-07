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
        'iot_id',
        'battary_charge'
    ];

    /**
     * Get the trips that owns the Scooter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trips()
    {
        return $this->belongsTo(Trip::class, 'id', 'scooter_id');
    }

    // append total_duration_cost automatically
    protected $appends = [
        'total_duration_cost',
        'total_trips_number',
    ];

    public function getTotalDurationCostAttribute()
    {
        return $this->trips()->sum('duration') * 15;
    }

    public function getTotalTripsNumberAttribute()
    {
        return $this->trips()->count();
    }
}
