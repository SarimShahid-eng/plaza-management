<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateRequest extends FormRequest
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
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'subject' => ['required', 'string'],
            'category' => ['required', 'in:Plumbing,Electrical,Cleaning,Noise,Security,Safety,Other'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:Pending,InProgress,Resolved'],
            'priority' => ['required', 'in:Low,Medium,High,Urgent'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'created_by' => ['required', 'integer', 'exists:users,id'],
            'resolved_at' => ['nullable'],
            'resolution_notes' => ['nullable', 'string'],
        ];
    }
}
