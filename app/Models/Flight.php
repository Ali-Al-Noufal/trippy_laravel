<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'city',
        'country',
        'points',
        'price',
        'departure_time',
        'arrival_time',
        'class_B_seats',
        'class_A_seats'
    ];
    public function bookings(){
        return $this->hasMany(Booking::class);
    }
}
