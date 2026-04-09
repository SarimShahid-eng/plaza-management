<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plaza_id',
        'transaction_type',
        'amount',
        'description',
        'balance_before',
        'balance_after',
        'recorded_by',
        'related_resource_type',
        'related_resource_id',
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
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
            'recorded_by' => 'integer',
        ];
    }

    public function plaza(): BelongsTo
    {
        return $this->belongsTo(Plaza::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function relatedResource(): BelongsTo
    // {
    //     return $this->belongsTo(RelatedResource::class);
    // }
}
