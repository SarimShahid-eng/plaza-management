<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenancePost extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plaza_id',
        'title',
        'category',
        'cost',
        'status',
        'vendor_name',
        'vendor_phone',
        'created_by',
        'approved_by',
        'linked_ticket_id',
        'linked_assessment_id',
        'approval_notes',
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
            'cost' => 'decimal:2',
            'created_by' => 'integer',
            'approved_by' => 'integer',
            'linked_ticket_id' => 'integer',
            'linked_assessment_id' => 'integer',
        ];
    }

    public function plaza(): BelongsTo
    {
        return $this->belongsTo(Plaza::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function linkedTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function linkedAssessment(): BelongsTo
    {
        return $this->belongsTo(SpecialAssessment::class);
    }
}
