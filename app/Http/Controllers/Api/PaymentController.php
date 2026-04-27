<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\UnitAssesmentPaymentHistory;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function monthlyDues(Request $request)
    {
        $user = $request->user();
        //  now()->format('Y-m')
        // 2016-04
        $query = Payment::when($request->payment_month,
            fn ($q) => $q->where('payment_month', $request->payment_month));
        if ($user->role === 'chairman') {
            $query->where('plaza_id', $user->plaza_id);
        } elseif ($user->role === 'member') {
            $query->where('unit_id', $user->unit_id);
        }
        $perPage = $request->input('perPage', 10);
        $payemnt = $query->paginate($perPage);

        return response()->json([
            'data' => $payemnt->through(function ($item) {
                return [
                    // 'id' => $item->id,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'unit' => $item->unit->unit_number,
                ];
            }),
        ]);
    }

    public function unitAssesmentDues(Request $request)
    {
        $user = $request->user();
        $query = UnitAssesmentPaymentHistory::when($request->payment_month,
            fn ($q) => $q->where('payment_month', $request->payment_month));
        if ($user->role === 'chairman') {
            $query->where('plaza_id', $user->plaza_id);
        } elseif ($user->role === 'member') {
            $query->where('unit_id', $user->unit_id);
        }
        $perPage = $request->input('perPage', 10);
        $payemnt = $query->paginate($perPage);

        return response()->json([
            'data' => $payemnt->through(function ($item) {
                return [
                    // 'id' => $item->id,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'unit' => $item->unit->unit_number,
                ];
            }),
        ]);
    }
}
