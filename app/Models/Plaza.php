<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaza extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'city',
        'country',
        'total_units',
        'master_pool_balance',
        'currency_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function members()
    {
        // This will only return users where the 'role' column is 'resident'
        return $this->hasMany(User::class)->where('role', 'member');
    }

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'master_pool_balance' => 'decimal:2',
        ];
    }

    public function incrementBalance($amount)
    {
        return $this->increment('master_pool_balance', $amount);
    }

    public function decrementBalance($amount)
    {
        return $this->decrement('master_pool_balance', $amount);
    }
}
