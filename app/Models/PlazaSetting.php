<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlazaSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plaza_id',
        'maintenance_approval_threshold',
        'monthly_dues_amount',
        'late_fee_percentage',
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
            'maintenance_approval_threshold' => 'decimal:2',
            'monthly_dues_amount' => 'decimal:2',
            'late_fee_percentage' => 'decimal:2',
        ];
    }

    public function plaza(): BelongsTo
    {
        return $this->belongsTo(Plaza::class);
    }
}
