<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MaintenancePostUpdateRequest extends FormRequest
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
            'category' => ['required', 'in:Plumbing,Electrical,Cleaning,Generator,Repair,Security,Painting,HVAC,Other'],
            'cost' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'status' => ['required', 'in:IMPLEMENTED,PENDING_APPROVAL,APPROVED,REJECTED'],
            'vendor_name' => ['nullable', 'string'],
            'vendor_phone' => ['nullable', 'string'],
            'created_by' => ['required', 'integer', 'exists:users,id'],
            'approved_by' => ['nullable', 'integer', 'exists:users,id'],
            'linked_ticket_id' => ['nullable', 'integer', 'exists:tickets,id'],
            'linked_assessment_id' => ['nullable', 'integer', 'exists:special_assessments,id'],
            'approval_notes' => ['nullable', 'string'],
        ];
    }
}
