<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plaza_id',
        'unit_number',
        'floor',
        'status',
        // due is monthlyamount+assesment amount if exist and keep it updated as how much paid
        // its tracking realtime balance how much he must pay
        'due',
        'monthly_dues_amount',
        'last_payment_date'
    ];

    protected $attributes = [
        'status' => 'Vacant',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'plaza_id' => 'integer',
            'due' => 'decimal:2',
            'monthly_dues_amount' => 'decimal:2',
            'last_payment_date' => 'timestamp',
        ];
    }

    public function resident(): HasOne
    {
        return $this->hasOne(User::class)->where('role', 'member');
    }
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function plaza(): BelongsTo
    {
        return $this->belongsTo(Plaza::class);
    }

    public function history()
    {
        return $this->hasOne(UnitHistory::class);
    }

    // Helper to get the very latest status record
    public function latestHistory()
    {
        return $this->hasOne(UnitHistory::class)->latestOfMany();
    }

    public function getTotalDue(): float
    {
        // Monthly dues remaining
        $monthlyRemaining = MonthlyDues::where('unit_id', $this->id)
            ->whereIn('status', ['UNPAID', 'PARTIAL', 'OVERDUE'])
            ->sum('remaining_amount');

        // Assessment remaining
        $assessmentRemaining = UnitPaymentHistory::where('unit_id', $this->id)
            ->whereIn('status', ['UNPAID', 'PARTIAL', 'OVERDUE'])
            ->sum('remaining_amount');

        return $monthlyRemaining + $assessmentRemaining;
    }

    public function getBalanceFormatted(): string
    {
        return 'Rs. '.number_format($this->getTotalDue(), 2);
    }

    public function getBalanceStatus(): string
    {
        $due = $this->getTotalDue();

        if ($due <= 0) {
            return 'paid'; // ✓ No dues
        } elseif ($due <= $this->monthly_dues_amount) {
            return 'warning'; // ⚠️ One month or less
        } else {
            return 'danger'; // ❌ Multiple months overdue
        }
    }
}
