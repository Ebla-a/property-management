<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    public const MAX_IMAGES_PER_PROPERTY = 10;

    /**
     * Upload images and save paths in DB
     *
     * @param Property $property
     * @param UploadedFile[] $images
     * @param string|null $alt
     * @return PropertyImage[]
     */
    public function upload(Property $property, array $images, ?string $alt = null): array
    {
        $created = [];

        // if property has no main image, first uploaded image becomes main
        $hasMain = PropertyImage::where('property_id', $property->id)
            ->where('is_main', true)
            ->exists();

        foreach ($images as $i => $image) {
            $path = $image->store("properties/{$property->id}", 'public');

            $created[] = PropertyImage::create([
                'property_id' => $property->id,
                'path' => $path,
                'is_main' => (!$hasMain && $i === 0),
                'alt' => $alt,
            ]);
        }

        return $created;
    }

    public function setMain(Property $property, PropertyImage $image): void
    {
        // NOTE: validation/authorization should be done in controller/request
        PropertyImage::where('property_id', $property->id)->update(['is_main' => false]);
        $image->update(['is_main' => true]);
    }

    // Soft delete: delete the DB row only (file stays)
    public function softDelete(Property $property, PropertyImage $image): void
    {
        $wasMain = (bool) $image->is_main;

        $image->delete(); // soft delete

        if ($wasMain) {
            $this->makeAnyImageMain($property->id);
        }
    }

    // Force delete: delete file + delete DB row permanently
    public function forceDelete(Property $property, PropertyImage $image): void
    {
        $wasMain = (bool) $image->is_main;

        // delete file only on force delete
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->forceDelete();

        if ($wasMain) {
            $this->makeAnyImageMain($property->id);
        }
    }

    // Restore a soft deleted image
    public function restore(Property $property, PropertyImage $image): void
    {
        $image->restore();

        // if property has no main image, make this restored image main
        $hasMain = PropertyImage::where('property_id', $property->id)
            ->where('is_main', true)
            ->exists();

        if (!$hasMain) {
            PropertyImage::where('property_id', $property->id)->update(['is_main' => false]);
            $image->update(['is_main' => true]);
        }
    }

    // if main image was deleted, just pick the first available image as main
    private function makeAnyImageMain(int $propertyId): void
    {
        $next = PropertyImage::where('property_id', $propertyId)->first();

        if ($next) {
            PropertyImage::where('property_id', $propertyId)->update(['is_main' => false]);
            $next->update(['is_main' => true]);
        }
    }
}
