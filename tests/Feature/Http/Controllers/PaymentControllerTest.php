<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Payment;
use App\Models\Unit;
use App\Models\User;
use App\Notification\PaymentSubmittedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PaymentController
 */
final class PaymentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PaymentController::class,
            'store',
            \App\Http\Requests\PaymentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $unit = Unit::factory()->create();
        $amount = fake()->randomFloat(/** decimal_attributes **/);
        $payment_month = fake()->word();

        Notification::fake();

        $response = $this->post(route('payments.store'), [
            'unit_id' => $unit->id,
            'amount' => $amount,
            'payment_month' => $payment_month,
        ]);

        $payments = Payment::query()
            ->where('unit_id', $unit->id)
            ->where('amount', $amount)
            ->where('payment_month', $payment_month)
            ->get();
        $this->assertCount(1, $payments);
        $payment = $payments->first();

        $response->assertRedirect(route('dashboard'));

        Notification::assertSentTo($chairman, PaymentSubmittedNotification::class);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PaymentController::class,
            'update',
            \App\Http\Requests\PaymentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $payment = Payment::factory()->create();
        $status = fake()->randomElement(/** enum_attributes **/);
        $approved_by = User::factory()->create();

        $response = $this->put(route('payments.update', $payment), [
            'status' => $status,
            'approved_by' => $approved_by->id,
        ]);

        $payment->refresh();

        $response->assertRedirect(route('chairman.dashboard'));
        $response->assertSessionHas('payment.updated', $payment->updated);

        $this->assertEquals($status, $payment->status);
        $this->assertEquals($approved_by->id, $payment->approved_by);
    }
}
