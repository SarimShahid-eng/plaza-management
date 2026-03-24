<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenancePostResource extends JsonResource
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
            'category' => $this->category,
            'cost' => $this->cost,
            'status' => $this->status,
            'vendor_name' => $this->vendor_name,
            'vendor_phone' => $this->vendor_phone,
            'created_by' => $this->created_by,
            'approved_by' => $this->approved_by,
            'linked_ticket_id' => $this->linked_ticket_id,
            'linked_assessment_id' => $this->linked_assessment_id,
            'approval_notes' => $this->approval_notes,
        ];
    }
}
