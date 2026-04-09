<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitAssesmentPaymentHistory extends Model
{
    protected $fillable = [
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
    //
}
