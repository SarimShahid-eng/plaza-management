<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TicketStoreRequest extends FormRequest
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
            'subject' => ['required', 'string'],
            'category' => ['required', 'in:Plumbing,Electrical,Cleaning,Noise,Security,Safety,Other'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:Low,Medium,High,Urgent'],
        ];
    }
}
