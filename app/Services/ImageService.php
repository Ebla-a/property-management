<?php

namespace App\Services;

use App\Models\PropertyImage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * @param int $propertyId
     * @param UploadedFile[] $images
     * @param string|null $alt
     * @return PropertyImage[]
     */
    public function uploadPropertyImages(int $propertyId, array $images, ?string $alt = null): array
    {
        $created = [];

        foreach ($images as $image) {
            $path = $image->store("properties/{$propertyId}", 'public');

            $created[] = PropertyImage::create([
                'property_id' => $propertyId,
                'path' => $path,
                'is_main' => false,
                'alt' => $alt,
            ]);
        }

        return $created;
    }
}
