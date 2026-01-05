<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a new property.
     *
     * Rules are aligned with the properties migration:
     * - Columns that are nullable in the DB are nullable here.
     * - Enum values for `status` match the migration.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',

            // property_type_id is nullable in the migration
            'property_type_id' => 'nullable|exists:property_types,id',

            // These address fields are nullable in the migration
            'city' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',

            // rooms has a DB default (0), allow nullable on input
            'rooms' => 'nullable|integer|min:0',

            // area is nullable in the DB
            'area' => 'nullable|numeric|min:0',

            // price stored as decimal(12,2) in DB — allow nullable so DB default applies if omitted
            'price' => 'nullable|numeric|min:0',

            // Status must match enum in migration. It's nullable (DB default exists).
            'status' => 'nullable|in:available,booked,rented,hidden',

            'description' => 'nullable|string',

            // is_furnished has DB default false — accept boolean or omitted
            'is_furnished' => 'nullable|boolean',

            // Amenities handled as array of IDs (pivot sync in controller)
            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',

            //upload photo
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120', // 5MB لكل صورة
        ];
    }
}
