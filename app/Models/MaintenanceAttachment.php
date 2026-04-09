<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'maintenance_post_id',
        'file_url',
        'uploaded_by',
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
            'maintenance_post_id' => 'integer',
            'uploaded_by' => 'integer',
        ];
    }

    public function maintenancePost(): BelongsTo
    {
        return $this->belongsTo(MaintenancePost::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
