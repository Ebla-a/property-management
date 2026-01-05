<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // أو طبق سياسة صلاحياتك
    }

    public function rules(): array
    {
        return [
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'alt' => 'nullable|string|max:255',
        ];
    }
}
