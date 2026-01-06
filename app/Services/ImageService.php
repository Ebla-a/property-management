<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    public function uploadPropertyImages(int $propertyId, array $files, ?string $alt = null)
    {
        $images = [];

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) continue;

            $path = $file->store('properties', 'public'); // folder storage/app/public/properties

            $images[] = PropertyImage::create([
                'property_id' => $propertyId,
                'path' => $path,
                'alt' => $alt,
                'is_main' => false,
            ]);
        }

        return $images;
    }
}
