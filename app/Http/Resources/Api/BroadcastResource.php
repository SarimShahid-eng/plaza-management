<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BroadcastResource extends JsonResource
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
            'message' => $this->message,
            'is_urgent' => $this->is_urgent,
            'sent_to_count' => $this->sent_to_count,
            'created_by' => $this->created_by,
        ];
    }
}
