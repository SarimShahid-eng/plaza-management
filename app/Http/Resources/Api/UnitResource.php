<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\UnitHistoryResource;
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
            'plaza' => $this->whenLoaded('plaza', fn () => $this->plaza->name),
            'resident' => $this->whenLoaded('resident', fn () => $this->resident->full_name),
            'unit_number' => $this->unit_number,
            'floor' => $this->floor,
            'history' => UnitHistoryResource::collection($this->whenLoaded('histories')),
            'status' => $this->status,
            'due' => $this->due,
            'monthly_dues_amount' => $this->monthly_dues_amount,
            'last_payment_date' => $this->last_payment_date??"No Payment made yet",
        ];
    }
}
