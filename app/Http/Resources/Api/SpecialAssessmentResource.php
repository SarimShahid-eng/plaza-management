<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialAssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plaza_id' => $this->plaza_id,
            'title' => $this->title,
            'reason' => $this->reason,
            'required_amount' => $this->required_amount,
            'shortfall' => $this->shortfall,
            'occupied_units' => $this->occupied_units,
            'per_unit_amount' => $this->per_unit_amount,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'approved_by' => $this->approved_by,
        ];
    }
}
