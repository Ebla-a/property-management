<?php

namespace App\Services;

use App\Models\Property;

class PropertyService
{
    /**
     * Get all properties with their relations.
     *
     * Loads propertyType, mainImage, and amenities for each property.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Property::with([
            'propertyType',
            'mainImage',
            'amenities'
        ])->latest()->get();
    }

    /**
     * Create a new property.
     *
     * Separates 'amenity_ids' from the main data to avoid mass assignment issues,
     * then syncs them in the pivot table.
     *
     * @param array $data
     * @return Property
     */
    public function create(array $data): Property
    {
        // Extract amenity IDs if provided
        $amenities = $data['amenity_ids'] ?? [];
        unset($data['amenity_ids']); // Remove from main data to prevent mass assignment error

        // Create the property
        $property = Property::create($data);

        // Sync amenities in the pivot table
        if (!empty($amenities)) {
            $property->amenities()->sync($amenities);
        }

        return $property;
    }

    /**
     * Update an existing property.
     *
     * Separates 'amenity_ids' before updating the property,
     * then syncs them if provided.
     *
     * @param Property $property
     * @param array $data
     * @return Property
     */
    public function update(Property $property, array $data): Property
    {
        // Extract amenity IDs if provided
        $amenities = $data['amenity_ids'] ?? null;
        unset($data['amenity_ids']); // Remove from main data to prevent mass assignment error

        // Update property attributes
        $property->update($data);

        // Sync amenities in the pivot table if provided
        if ($amenities !== null) {
            $property->amenities()->sync($amenities);
        }

        return $property;
    }

    /**
     * Delete a property.
     *
     * @param Property $property
     * @return void
     */
    public function delete(Property $property): void
    {
        $property->delete();
    }
}
