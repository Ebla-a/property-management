<?php

namespace App\Services;

use App\Models\Property;

class PropertyService
{
    /**
     * Get all properties with filtering, sorting and pagination.
     *
     * Supported filters (from query params):
     * - type -> property_type_id
     * - city
     * - min_price
     * - max_price
     * - sort (allowed: price, created_at)
     * - order (asc|desc)
     * - limit (pagination size)
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(array $filters = [])
    {
        $query = Property::with([
            'propertyType',
            'mainImage',
            'amenities'
        ]);

        // Filtering
        if (!empty($filters['type'])) {
            $query->where('property_type_id', $filters['type']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Sorting
        $allowedSorts = ['price', 'created_at'];
        $sortBy = $filters['sort'] ?? 'created_at';
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        $order = strtolower($filters['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $order);

        // Pagination
        $perPage = isset($filters['limit']) && is_numeric($filters['limit'])
            ? (int) $filters['limit']
            : 10;

        return $query->paginate($perPage);
    }

    /**
     * Create a new property.
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
