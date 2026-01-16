<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToggleStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
public function authorize(): bool { return true; }

public function rules(): array
{
    return [
        'is_active' => 'required|boolean'
    ];
}

public function attributes(): array
{
    return [
        'is_active' => __('user.account_status'),
    ];
}
}
