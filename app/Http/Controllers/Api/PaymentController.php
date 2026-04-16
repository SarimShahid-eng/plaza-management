<?php

namespace App\Http\Controllers\Api;

use App\Events\PaymentProcessed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentUpdateRequest;
use App\Http\Resources\Api\PaymentResource;
use App\Models\MonthlyDue;
use App\Models\Payment;
use Carbon\Month;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function monthlyDues(Request $request)
    {
        $user = $request->user();
        $query = MonthlyDue::query();
        if($user->role === 'chairman'){
            $query::where('plaza_id', $user->plaza_id);
        }else if($user->role === 'member'){
            $query::where('unit_id', $user->unit_id);
        }
        $perPage = $request->input('perPage', 10);
        $monthlyDues = $query->paginate($perPage);
        return response()->json([
            'data' => $monthlyDues->through(function ($item) {
                return [
                    'id' => $item->id,
                    'month' => $item->month,
                    'monthly_amount' => $item->monthly_amount,
                    'paid_amount' => $item->paid_amount,
                    'remaining_amount' => $item->remaining_amount,
                    'status' => $item->status,
                    'payment_date' => $item->payment_date ? $item->payment_date->toDateTimeString() : null,
                ];
            })
        ]);
    }


    public function payments(MonthlyDue $monthlyDue)
    {
        $payments = $monthlyDue->payments()->with('recordedBy')->get();
        return response()->json([
            'data' => $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'recorded_by' => $payment->recordedBy ? $payment->recordedBy->name : null,
                    'payment_month' => $payment->payment_month,
                ];
            })
        ]);

    }
    // public function paymentassesmentS(Request $request)
    // {
    //     $user = $request->user();
    //     $query = Payment::query();
    //     if($user->role === 'chairman'){
    //         $query::where('plaza_id', $user->plaza_id);
    //     }else if($user->role === 'member'){
    //         $query::where('unit_id', $user->unit_id);
    //     }
    //     $perPage = $request->input('perPage', 10);
    //     $payments = $query->paginate($perPage);
    //     return response()->json([
    //         'data' => $payments->through(function ($item) {
    //             return [
    //                 'id' => $item->id,
    //                 'amount' => $item->amount,
    //                 'status' => $item->status,
    //                 'payment_month' => $item->payment_month,
    //             ];
    //         })
    //     ]);
    // }
    // public function assementhistory(){

    // }
}
