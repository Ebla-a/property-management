<?php

namespace App\Services;

use App\Models\Property;

class PropertyService
{
    public function getAll()
    {
        return Property::with([
            'propertyType',
            'mainImage',
            'amenities'
        ])->latest()->get();
    }

    public function create(array $data): Property
    {
        $property = Property::create($data);

        if (isset($data['amenity_ids'])) {
            $property->amenities()->sync($data['amenity_ids']);
        }

        return $property;
    }

    public function update(Property $property, array $data): Property
    {
        $property->update($data);

        if (isset($data['amenity_ids'])) {
            $property->amenities()->sync($data['amenity_ids']);
        }

        return $property;
    }

    public function delete(Property $property): void
    {
        $property->delete();
    }
}
