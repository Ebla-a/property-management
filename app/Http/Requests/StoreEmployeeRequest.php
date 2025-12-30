<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
{
    $user = Auth::user();

   return Auth::user()?->hasRole('admin');
      
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    //Get the validation rules that apply to the request.
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];
    
    }
    //Custom validation messages
            public function messages(): array
        {
            return [
                'name.required'     => 'Name is required.',
                'email.unique'      => 'This email is already registered.',
                'password.min'      => 'Password must contain at least 6 characters.',
            ];
        }

            
    
}
