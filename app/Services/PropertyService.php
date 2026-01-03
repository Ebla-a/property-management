<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

class PropertyService
{
    /**
     * Get all properties with relations.
     *
     * @return Collection
     */
    public function getAll(): Collection
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
     * Get properties with filters.
     *
     * Supports filtering by amenity_ids (array of ids).
     * When amenity_ids provided, returned properties must have ALL selected amenities (AND logic).
     *
     * @param array $filters
     * @return Collection
     */
    public function getAllWithFilters(array $filters = []): Collection
    {
        $query = Property::with(['propertyType', 'mainImage', 'amenities'])->latest();

        // Filter by amenity ids (AND logic: property must have all selected amenities)
        if (!empty($filters['amenity_ids'])) {
            $amenityIds = array_filter((array) $filters['amenity_ids']);
            foreach ($amenityIds as $id) {
                $query->whereHas('amenities', function ($q) use ($id) {
                    $q->where('amenities.id', $id);
                });
            }
        }

        // Additional filters can be added here (city, price range, rooms, etc.)

        return $query->get();
    }

    /**
     * Create a new property and sync amenities if provided.
     *
     * @param array $data
     * @return Property
     */
    public function create(array $data): Property
    {
        $amenities = $data['amenity_ids'] ?? [];
        unset($data['amenity_ids']);

        $property = Property::create($data);

        if (!empty($amenities)) {
            $property->amenities()->sync($amenities);
        }

        return $property;
    }

    /**
     * Update an existing property and sync amenities if provided.
     * Update an existing property.
     *
     * @param Property $property
     * @param array $data
     * @return Property
     */
    public function update(Property $property, array $data): Property
    {
        $amenities = $data['amenity_ids'] ?? null;
        unset($data['amenity_ids']);

        $property->update($data);

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
