<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAmenityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * You may adapt this to check user permissions or roles.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Leave true if authorization handled via middleware (e.g. can:admin)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Use "sometimes" to allow partial updates through PATCH-like flows.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
        ];
    }

    /**
     * Custom messages for validation errors (optional).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The amenity name is required.',
            'name.string' => 'The amenity name must be a string.',
            'name.max' => 'The amenity name must not exceed 255 characters.',
        ];
    }
}
