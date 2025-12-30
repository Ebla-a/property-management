<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating an existing property.
     *
     * Using "sometimes" so omitted fields are not overridden.
     * Nullable rules match the DB where appropriate.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',

            'property_type_id' => 'sometimes|nullable|exists:property_types,id',

            'city' => 'sometimes|nullable|string|max:255',
            'neighborhood' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',

            'rooms' => 'sometimes|nullable|integer|min:0',
            'area' => 'sometimes|nullable|numeric|min:0',
            'price' => 'sometimes|nullable|numeric|min:0',

            'status' => 'sometimes|in:available,booked,rented,hidden',

            'description' => 'sometimes|nullable|string',
            'is_furnished' => 'sometimes|boolean',

            'amenity_ids' => 'sometimes|nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',
        ];
    }
}
