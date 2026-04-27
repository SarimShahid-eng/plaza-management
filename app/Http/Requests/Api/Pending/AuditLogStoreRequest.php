<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AuditLogStoreRequest extends FormRequest
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
            'plaza_id' => ['required', 'integer', 'exists:plazas,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'action' => ['required', 'string'],
            'resource_type' => ['required', 'string'],
            'resource_id' => ['required'],
            'before_state' => ['nullable', 'json'],
            'after_state' => ['nullable', 'json'],
        ];
    }
}
