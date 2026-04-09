<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyDue extends Model
{
    protected $fillable = [
        'unit_id',
        'user_id',
        'plaza_id',
        'month',
        'monthly_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'due_date',
        'payment_date',
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'datetime',
    ];

    /**
     * Relations
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function plaza()
    {
        return $this->belongsTo(Plaza::class);
    }

    /**
     * Scopes
     */
    // public function scopeForMonth($query, $month)
    // {
    //     return $query->where('month', $month);
    // }

    public function scopeForUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    // public function scopeUnpaid($query)
    // {
    //     return $query->where('status', 'UNPAID');
    // }

    // public function scopePaid($query)
    // {
    //     return $query->where('status', 'PAID');
    // }

    // public function scopePartial($query)
    // {
    //     return $query->where('status', 'PARTIAL');
    // }

    // public function scopeOverdue($query)
    // {
    //     return $query->where('status', 'OVERDUE')
    //                 ->where('due_date', '<', now()->toDateString());
    // }

    // /**
    //  * Methods
    //  */
    // public function isPaid(): bool
    // {
    //     return $this->status === 'PAID';
    // }

    // public function isPartial(): bool
    // {
    //     return $this->status === 'PARTIAL';
    // }

    // public function isUnpaid(): bool
    // {
    //     return $this->status === 'UNPAID';
    // }

    // public function isOverdue(): bool
    // {
    //     return $this->status === 'OVERDUE';
    // }

    // public function isLate(): bool
    // {
    //     return $this->due_date < now()->toDateString()
    //            && $this->status !== 'PAID';
    // }

    // public function processPayment($amount): void
    // {
    //     $this->paid_amount += $amount;
    //     $this->remaining_amount = $this->monthly_amount - $this->paid_amount;

    //     if ($this->remaining_amount <= 0) {
    //         $this->status = 'PAID';
    //         $this->payment_date = now();
    //     } elseif ($this->paid_amount > 0) {
    //         $this->status = 'PARTIAL';
    //     }

    //     $this->save();
    // }

    // public function getPaymentPercentage(): float
    // {
    //     if ($this->monthly_amount == 0) {
    //         return 0;
    //     }
    //     return ($this->paid_amount / $this->monthly_amount) * 100;
    // }

    // public function getRemainingFormatted(): string
    // {
    //     return 'Rs. ' . number_format($this->remaining_amount, 2);
    // }

    // public function getPaidFormatted(): string
    // {
    //     return 'Rs. ' . number_format($this->paid_amount, 2);
    // }
}
