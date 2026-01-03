<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PropertyService
{
    /**
     * Get all properties with relations (no pagination).
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Property::with([
            'propertyType',
            'mainImage',
            'amenities'
        ])->get();
    }

    /**
     * Get all properties with filtering, sorting and pagination.
     *
     * Supported filters:
     * - type -> property_type_id
     * - city
     * - min_price
     * - max_price
     * - sort (price, created_at)
     * - order (asc|desc)
     * - limit
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getPaginated(array $filters = []): LengthAwarePaginator
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
        $sortBy = in_array($filters['sort'] ?? null, $allowedSorts)
            ? $filters['sort']
            : 'created_at';

        $order = strtolower($filters['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $order);

        // Pagination
        $perPage = isset($filters['limit']) && is_numeric($filters['limit'])
            ? (int) $filters['limit']
            : 10;

        return $query->paginate($perPage);
    }

    /**
     * Get properties filtered by amenities (AND logic).
     */
    public function getAllWithFilters(array $filters = []): Collection
    {
        $query = Property::with(['propertyType', 'mainImage', 'amenities'])->latest();

        if (!empty($filters['amenity_ids'])) {
            foreach ((array) $filters['amenity_ids'] as $id) {
                $query->whereHas('amenities', function ($q) use ($id) {
                    $q->where('amenities.id', $id);
                });
            }
        }

        return $query->get();
    }

    public function create(array $data): Property
    {
        $amenities = $data['amenity_ids'] ?? [];
        unset($data['amenity_ids']);

        $property = Property::create($data);

        if ($amenities) {
            $property->amenities()->sync($amenities);
        }

        return $property;
    }

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

    public function delete(Property $property): void
    {
        $property->delete();
    }
}
