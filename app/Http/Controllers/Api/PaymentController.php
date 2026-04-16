<?php

namespace App\Http\Controllers\Api;

use App\Events\PaymentProcessed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentUpdateRequest;
use App\Http\Resources\Api\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    // public function monthlyHistory()
    // {
    //     $monthlyHistory=
    //     return

    // }
    // public function index(Request $request): Response
    // {
    //     $payments = Payment::all();

    //     return new PaymentCollection($payments);
    // }

    // public function store(PaymentStoreRequest $request): Response
    // {
    //     $payment = Payment::create($request->validated());

    //     Notification::send($chairman, new PaymentPendingNotification());

    //     return new PaymentResource($payment);
    // }

    // public function show(User $user): PaymentResource
    // {
    //     return new PaymentResource($payment);
    // }

    // public function update(PaymentUpdateRequest $request, Payment $payment): Response
    // {
    //     $payment->update($request->validated());

    //     PaymentProcessed::dispatch();

    //     return new PaymentResource($payment);
    // }

    // public function destroy(Request $request, Payment $payment): Response
    // {
    //     $payment->delete();

    //     return response()->noContent();
    // }
}
