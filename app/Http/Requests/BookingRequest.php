<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_id' =>'required|exists:properties,id',
            'scheduled_at' =>'required|date|after:now',
            'notes'=>'nullable|string|max:500'
        ];
    }
   public function messages()
{
    return [
        'scheduled_at.after' => 'The booking date must be in the future',
        'scheduled_at.date'  => 'Invalid date format',
    ];
}
}
