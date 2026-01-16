<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool { return true; }

public function rules(): array
{
    return [
        'old_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ];
}

public function attributes(): array
{
    return [
        'old_password' => __('user.password'),
        'new_password' => __('user.password'),
    ];
}
}
