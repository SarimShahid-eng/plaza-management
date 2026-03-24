<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'password'],
            'full_name' => ['required', 'string'],
            'phone_number' => ['nullable', 'string'],
            'role' => ['required', 'in:admin,chairman,assistant,member'],
            'plaza_id' => ['nullable', 'integer', 'exists:plazas,id'],
            'unit_id' => ['nullable', 'integer', 'exists:units,id'],
        ];
    }
}
