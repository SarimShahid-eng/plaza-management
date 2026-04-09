<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\TransactionLog;
use App\Models\Plaza;

class PaymentService
{
    public function recordTransaction(
        int $plazaId,
        string $type,
        float $amount,
        string $description,
        int $userId,
        ?string $resourceType = null,
        ?int $resourceId = null
    ) {
        $plaza = Plaza::find($plazaId);
        $balanceBefore = $plaza->master_pool_balance;

        if ($type === 'credit') {
            $plaza->incrementBalance($amount);
        } else {
            $plaza->decrementBalance($amount);
        }

        $balanceAfter = $plaza->refresh()->master_pool_balance;

        AuditLog::create([
            // // WHO did WHAT and WHEN
            'plaza_id' => $plazaId,
            'user_id' => $userId,
            'action' => $type === 'credit' ? 'log_payment' : 'log_expense',
            'resource_type' => $resourceType ?? $type,
            'resource_id' => $resourceId,
        ]);
// HOW MUCH money moved and WHERE
        TransactionLog::create([
            'plaza_id' => $plazaId,
            'transaction_type' => $type,
            'amount' => $amount,
            'related_resource_id'=>$resourceId,
            'description' => $description,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'recorded_by' => $userId,
        ]);
    }
}
