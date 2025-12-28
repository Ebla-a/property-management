<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['user_id','property_id','rating','comment'];

    public function Booking()
    {
        return $this->belongsToMany(Booking::class);
    }
}
