<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Property Model
 *
 * Represents a real estate property in the system.
 */
class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled using create() or update().
     */
    protected $fillable = [
        'user_id',           // Owner of the property
        'title',             // Property title
        'property_type_id',  // Type of property (apartment, house, etc.)
        'city',              // City where the property is located
        'neighborhood',      // Neighborhood name
        'address',           // Full address
        'rooms',             // Number of rooms
        'area',              // Property area (sqm)
        'price',             // Property price
        'status',            // Availability status (available, rented, etc.)
        'description',       // Detailed description
        'is_furnished',      // Whether the property is furnished
    ];

    /**
     * Attribute casting.
     * Ensures correct data types when retrieving from database.
     */
    protected $casts = [
        'rooms' => 'integer',
        'area' => 'decimal:2',
        'price' => 'decimal:2',
        'is_furnished' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the owner (user) of the property.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the property type.
     */
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    /**
     * Get all images associated with the property.
     */
    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id');
    }

    /**
     * Get the main image of the property.
     */
    public function mainImage()
    {
        return $this->hasOne(PropertyImage::class, 'property_id')
                    ->where('is_main', true);
    }

    /**
     * Get all amenities linked to the property.
     */
    public function amenities()
    {
        return $this->belongsToMany(
            Amenity::class,
            'property_amenities',
            'property_id',
            'amenity_id'
        );
    }

    /**
     * Get all bookings related to the property.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'property_id');
    }

    /**
     * Get all reviews for the property.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'property_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include available properties.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
