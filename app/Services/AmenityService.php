<?php

namespace App\Services;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Collection;

class AmenityService
{
    /**
     * Get all amenities ordered by name.
     */
    public function getAll(): Collection
    {
        return Amenity::orderBy('name')->get();
    }

    /**
     * Find an amenity by id.
     */
    public function find(int $id): ?Amenity
    {
        return Amenity::find($id);
    }

    /**
     * Create a new amenity.
     */
    public function create(array $data): Amenity
    {
        return Amenity::create($data);
    }

    /**
     * Update an existing amenity.
     */
    public function update(Amenity $amenity, array $data): Amenity
    {
        $amenity->update($data);

        return $amenity;
    }

    /**
     * Delete an amenity and detach relations.
     */
    public function delete(Amenity $amenity): void
    {
        // Detach from properties to keep pivot clean
        $amenity->properties()->detach();
        $amenity->delete();
    }
}
