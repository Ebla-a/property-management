<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PropertyService
{
    /**
     * Get all properties with relations (no pagination).
     */
    public function getAll(): Collection
    {
        return Property::with([
            'propertyType',
            'mainImage',
            'amenities',
        ])->get();
    }

    /**
     * Get all properties with filtering, sorting and pagination.
     *
     * Supported filters:
     * - property_types (array) -> property_type_id
     * - city
     * - min_price
     * - max_price
     * - amenity_ids (array) -> AND logic
     * - sort (price, created_at)
     * - order (asc|desc)
     * - limit
     */
    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = Property::with([
            'propertyType',
            'mainImage',
            'amenities',
            'images',
        ]);

        /*
        |----------------------------------------------------------------------
        | Filter: Property Types (MULTI)
        |----------------------------------------------------------------------
        */
        $query->when(
            ! empty($filters['property_types']),
            fn ($q) => $q->whereIn(
                'property_type_id',
                (array) $filters['property_types']
            )
        );

        /*
        |----------------------------------------------------------------------
        | Filter: City
        |----------------------------------------------------------------------
        */
        $query->when(
            ! empty($filters['city']),
            fn ($q) => $q->where(
                'city',
                'like',
                '%'.$filters['city'].'%'
            )
        );

        /*
        |----------------------------------------------------------------------
        | Filter: Price Range
        |----------------------------------------------------------------------
        */
        $query->when(
            isset($filters['min_price']) && $filters['min_price'] !== '',
            fn ($q) => $q->where('price', '>=', $filters['min_price'])
        );

        $query->when(
            isset($filters['max_price']) && $filters['max_price'] !== '',
            fn ($q) => $q->where('price', '<=', $filters['max_price'])
        );

        /*
        |----------------------------------------------------------------------
        | Filter: Amenities (AND logic)
        |----------------------------------------------------------------------
        */
        $query->when(
            ! empty($filters['amenity_ids']),
            function ($q) use ($filters) {
                foreach ((array) $filters['amenity_ids'] as $amenityId) {
                    $q->whereHas('amenities', function ($sub) use ($amenityId) {
                        $sub->where('amenities.id', $amenityId);
                    });
                }
            }
        );

        /*
        |----------------------------------------------------------------------
        | Sorting
        |----------------------------------------------------------------------
        */
        $allowedSorts = ['price', 'created_at'];

        $sortBy = in_array($filters['sort'] ?? null, $allowedSorts)
            ? $filters['sort']
            : 'created_at';

        $order = strtolower($filters['order'] ?? 'desc') === 'asc'
            ? 'asc'
            : 'desc';

        $query->orderBy($sortBy, $order);

        /*
        |----------------------------------------------------------------------
        | Pagination
        |----------------------------------------------------------------------
        */
        $perPage = isset($filters['limit']) && is_numeric($filters['limit'])
            ? (int) $filters['limit']
            : 6;

        return $query->paginate($perPage);
    }

    /**
     * Get properties filtered by amenities (AND logic).
     */
    public function getAllWithFilters(array $filters = []): Collection
    {
        $query = Property::with([
            'propertyType',
            'mainImage',
            'amenities',
        ])->latest();

        $query->when(
            ! empty($filters['amenity_ids']),
            function ($q) use ($filters) {
                foreach ((array) $filters['amenity_ids'] as $id) {
                    $q->whereHas('amenities', function ($sub) use ($id) {
                        $sub->where('amenities.id', $id);
                    });
                }
            }
        );

        return $query->get();
    }

    public function create(array $data): Property
    {
        $amenities = $data['amenity_ids'] ?? [];
        unset($data['amenity_ids']);

        $property = Property::create($data);

        if (! empty($amenities)) {
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
