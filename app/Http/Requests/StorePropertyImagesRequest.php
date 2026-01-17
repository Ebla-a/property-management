<?php

namespace App\Http\Requests;

use App\Models\PropertyImage;
use Illuminate\Foundation\Http\FormRequest;

class StorePropertyImagesRequest extends FormRequest
{
    public const MAX_IMAGES_PER_PROPERTY = 10;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'alt' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $routeProperty = $this->route('property');
            $propertyId = is_object($routeProperty) ? $routeProperty->id : (int) $routeProperty;

            $existingCount = PropertyImage::where('property_id', $propertyId)->count();
            $newCount = is_array($this->file('images')) ? count($this->file('images')) : 0;

            if (($existingCount + $newCount) > self::MAX_IMAGES_PER_PROPERTY) {
                $validator->errors()->add(
                    'images',
                    'Maximum '.self::MAX_IMAGES_PER_PROPERTY.' images allowed per property.'
                );
            }
        });
    }
}
