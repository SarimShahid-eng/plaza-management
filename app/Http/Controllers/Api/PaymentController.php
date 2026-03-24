<?php

namespace App\Http\Controllers\Api;

use App\Events\PaymentProcessed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentStoreRequest;
use App\Http\Requests\Api\PaymentUpdateRequest;
use App\Http\Resources\Api\PaymentCollection;
use App\Http\Resources\Api\PaymentResource;
use App\Models\Payment;
use App\Notification\PaymentPendingNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    public function index(Request $request): Response
    {
        $payments = Payment::all();

        return new PaymentCollection($payments);
    }

    public function store(PaymentStoreRequest $request): Response
    {
        $payment = Payment::create($request->validated());

        Notification::send($chairman, new PaymentPendingNotification());

        return new PaymentResource($payment);
    }

    public function show(Request $request, Payment $payment): Response
    {
        return new PaymentResource($payment);
    }

    public function update(PaymentUpdateRequest $request, Payment $payment): Response
    {
        $payment->update($request->validated());

        PaymentProcessed::dispatch();

        return new PaymentResource($payment);
    }

    public function destroy(Request $request, Payment $payment): Response
    {
        $payment->delete();

        return response()->noContent();
    }
}
