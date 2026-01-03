<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The properties that belong to the amenity.
     */
    public function properties()
    {
        return $this->belongsToMany(
            Property::class,
            'property_amenities',
            'amenity_id',
            'property_id'
        );
    }
}
