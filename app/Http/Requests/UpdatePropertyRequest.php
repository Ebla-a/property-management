<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'property_type_id' => 'sometimes|exists:property_types,id',
            'city' => 'sometimes|string|max:255',
            'neighborhood' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'rooms' => 'sometimes|integer|min:0',
            'area' => 'sometimes|numeric|min:0',
            'price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:available,rented,pending',
            'description' => 'nullable|string',
            'is_furnished' => 'boolean',
            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',
        ];
    }
}
