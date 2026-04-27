<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitAssesmentPaymentHistory extends Model
{
    protected $fillable = [
        'unit_payment_id',
        'plaza_id',
        'assessment_id',
        'user_id',
        'unit_id',
        'amount',
        'status',
        'recorded_by',
        // 'approved_by',
        'payment_month',
    ];

    public function unitAssesmentPayment()
    {
        $this->belongsTo(UnitAssesmentPayment::class, 'unit_payment_id');
    }
}
