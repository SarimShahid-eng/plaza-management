<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitHistory extends Model
{
    protected $fillable = [
        'user_id',
        'plaza_id',
        'unit_id',
        'move_in',
        'move_out',
        'notes',
        'changed_by',
    ];
    protected $casts=[
        'move_in'=>'date'
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
