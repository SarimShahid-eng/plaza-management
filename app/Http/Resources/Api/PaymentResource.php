<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plaza_id' => $this->plaza_id,
            'unit_id' => $this->unit_id,
            'amount' => $this->amount,
            'payment_type' => $this->payment_type,
            'payment_month' => $this->payment_month,
            'is_late' => $this->is_late,
            'reference_number' => $this->reference_number,
            'recorded_by' => $this->recorded_by,
            'approved_by' => $this->approved_by,
        ];
    }
}
