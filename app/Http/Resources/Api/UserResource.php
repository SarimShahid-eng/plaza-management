<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'role' => $this->role,
            'plaza_id' => $this->plaza_id,
            'unit_id' => $this->unit_id,
        ];
    }
}
