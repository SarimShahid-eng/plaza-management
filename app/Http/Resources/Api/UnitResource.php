<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plaza_id' => $this->plaza_id,
            'unit_number' => $this->unit_number,
            'floor' => $this->floor,
            'status' => $this->status,
            'unit_type' => $this->unit_type,
            'resident_name' => $this->resident_name,
            'resident_phone' => $this->resident_phone,
            'due' => $this->due,
            'monthly_dues_amount' => $this->monthly_dues_amount,
            'last_payment_date' => $this->last_payment_date,
        ];
    }
}
