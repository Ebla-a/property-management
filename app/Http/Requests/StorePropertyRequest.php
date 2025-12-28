<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'property_type_id' => 'required|exists:property_types,id',
            'city' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'rooms' => 'required|integer|min:0',
            'area' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented,pending',
            'description' => 'nullable|string',
            'is_furnished' => 'boolean',
            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',
        ];
    }
}
