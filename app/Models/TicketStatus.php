<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketStatus extends Model
{
    protected $fillable = [
        'ticket_id',
        'status',
        'description'
    ];

    protected $touches = ['ticket'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
