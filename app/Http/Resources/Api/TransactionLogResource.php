<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plaza_id' => $this->plaza_id,
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'description' => $this->description,
            'balance_before' => $this->balance_before,
            'balance_after' => $this->balance_after,
            'recorded_by' => $this->recorded_by,
            'related_resource_type' => $this->related_resource_type,
            'related_resource_id' => $this->related_resource_id,
        ];
    }
}
