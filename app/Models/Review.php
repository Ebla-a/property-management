<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\Property;
class Review extends Model
{
    protected $fillable = [
    'booking_id',
    'user_id',
    'property_id',
    'rating',
    'comment',
];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bookings()
    {
        return $this->belongsTo(Booking::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

}
