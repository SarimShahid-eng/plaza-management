<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            // 'status' => $this->status,
            'notes' => $this->notes ?? 'No additional notes provided.',
            'changed_by' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at->format('d M, Y'),
        ];
    }
}
