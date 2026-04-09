<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitAssesmentPayment extends Model
{
    use HasFactory;

    protected $table = 'unit_assesment_payments';

    protected $fillable = [
        'unit_id',
        'user_id',
        'plaza_id',
        'assessment_id',
        'assessed_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'assessed_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the unit that owns this payment history
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the plaza that owns this payment history
     */
    public function plaza()
    {
        return $this->belongsTo(Plaza::class);
    }

    /**
     * Get the special assessment this payment is for
     */
    public function assessment()
    {
        return $this->belongsTo(SpecialAssessment::class, 'assessment_id');
    }

    /**
     * Scopes
     */

    /**
     * Get all unpaid assessments
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'UNPAID');
    }

    /**
     * Get all paid assessments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'PAID');
    }

    /**
     * Get all partial payments
     */
    public function scopePartial($query)
    {
        return $query->where('status', 'PARTIAL');
    }

    /**
     * Get all overdue assessments
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'OVERDUE')
                    ->where('due_date', '<', now()->toDateString());
    }

    /**
     * Get assessments by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get assessments for a specific plaza
     */
    public function scopeForPlaza($query, $plazaId)
    {
        return $query->where('plaza_id', $plazaId);
    }

    /**
     * Get assessments for a specific unit
     */
    public function scopeForUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    /**
     * Get assessments for a specific assessment
     */
    public function scopeForAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }

    /**
     * Get overdue assessments
     */
    public function scopeIsOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
                    ->where('status', '!=', 'PAID');
    }

    /**
     * Methods
     */

    /**
     * Check if this assessment is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'PAID';
    }

    /**
     * Check if this assessment is unpaid
     */
    public function isUnpaid(): bool
    {
        return $this->status === 'UNPAID';
    }

    /**
     * Check if this assessment is partial
     */
    public function isPartial(): bool
    {
        return $this->status === 'PARTIAL';
    }

    /**
     * Check if this assessment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'OVERDUE';
    }

    /**
     * Check if this assessment is due soon (within 3 days)
     */
    public function isDueSoon(): bool
    {
        $now = now();
        $dueDate = $this->due_date;

        return $dueDate->isBetween($now, $now->copy()->addDays(3))
               && $this->status !== 'PAID';
    }

    /**
     * Check if this assessment is late
     */
    public function isLate(): bool
    {
        return $this->due_date < now()->toDateString()
               && $this->status !== 'PAID';
    }

    /**
     * Get days remaining to pay
     */
    public function daysRemaining(): int
    {
        $daysLeft = $this->due_date->diffInDays(now(), false);
        return $daysLeft;
    }

    /**
     * Get days overdue
     */
    public function daysOverdue(): int
    {
        if (!$this->isLate()) {
            return 0;
        }
        return now()->diffInDays($this->due_date);
    }

    /**
     * Process a payment against this assessment
     */
    public function processPayment($amount): void
    {
        // Update paid amount
        $this->paid_amount += $amount;

        // Calculate remaining
        $this->remaining_amount = $this->assessed_amount - $this->paid_amount;

        // Determine status
        if ($this->remaining_amount <= 0) {
            $this->status = 'PAID';
            $this->payment_date = now();
        } elseif ($this->paid_amount > 0) {
            $this->status = 'PARTIAL';
        }

        $this->save();
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(): void
    {
        $this->paid_amount = $this->assessed_amount;
        $this->remaining_amount = 0;
        $this->status = 'PAID';
        $this->payment_date = now();
        $this->save();
    }

    /**
     * Mark as unpaid
     */
    public function markAsUnpaid(): void
    {
        $this->paid_amount = 0;
        $this->remaining_amount = $this->assessed_amount;
        $this->status = 'UNPAID';
        $this->payment_date = null;
        $this->save();
    }

    /**
     * Mark as overdue
     */
    public function markAsOverdue(): void
    {
        if ($this->status !== 'PAID') {
            $this->status = 'OVERDUE';
            $this->save();
        }
    }

    /**
     * Get remaining amount formatted
     */
    public function getRemainingFormatted(): string
    {
        return 'Rs. ' . number_format($this->remaining_amount, 2);
    }

    /**
     * Get paid amount formatted
     */
    public function getPaidFormatted(): string
    {
        return 'Rs. ' . number_format($this->paid_amount, 2);
    }

    /**
     * Get assessed amount formatted
     */
    public function getAssessedFormatted(): string
    {
        return 'Rs. ' . number_format($this->assessed_amount, 2);
    }

    /**
     * Get collection percentage
     */
    public function getCollectionPercentage(): float
    {
        if ($this->assessed_amount == 0) {
            return 0;
        }
        return ($this->paid_amount / $this->assessed_amount) * 100;
    }

    /**
     * Get late fee if applicable
     */
    public function getLateFee($latePercentage = 5): float
    {
        if (!$this->isLate()) {
            return 0;
        }

        return ($this->remaining_amount * $latePercentage) / 100;
    }

    /**
     * Get total amount due (including late fee if applicable)
     */
    public function getTotalDue($latePercentage = 5): float
    {
        $lateFee = $this->getLateFee($latePercentage);
        return $this->remaining_amount + $lateFee;
    }
}
