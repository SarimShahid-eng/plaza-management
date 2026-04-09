<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MaintenancePostStoreRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'category' => ['required', 'in:Plumbing,Electrical,Cleaning,Generator,Repair,Security,Painting,HVAC,Other'],
            'cost' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'linked_assessment_id' => ['nullable', 'integer', 'exists:special_assessments,id'],
            'attachments' => ['required', 'array'],
            'attachments.*' => ['required', 'file'],
        ];
    }
}
