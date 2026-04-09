<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'password', 'confirmed'],
            'full_name' => ['required', 'string'],
            'phone_number' => ['nullable', 'string'],

        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Please enter the user\'s full name.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please enter a valid email address.',
            // 'email.unique' => 'This email is already registered.',
            'password.required' => 'A password is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
        ];
    }
}
