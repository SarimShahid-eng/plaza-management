<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BroadcastStoreRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'message' => ['required', 'string'],
            'is_urgent' => ['required'],
            'sent_to_count' => ['required', 'integer'],
            'created_by' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
