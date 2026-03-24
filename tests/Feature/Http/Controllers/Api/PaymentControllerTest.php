<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Events\PaymentProcessed;
use App\Models\Payment;
use App\Models\Unit;
use App\Models\User;
use App\Notification\PaymentPendingNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\PaymentController
 */
final class PaymentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $payments = Payment::factory()->count(3)->create();

        $response = $this->get(route('payments.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\PaymentController::class,
            'store',
            \App\Http\Requests\Api\PaymentControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $unit = Unit::factory()->create();
        $amount = fake()->randomFloat(/** decimal_attributes **/);
        $payment_type = fake()->randomElement(/** enum_attributes **/);
        $payment_month = fake()->word();

        Notification::fake();

        $response = $this->post(route('payments.store'), [
            'unit_id' => $unit->id,
            'amount' => $amount,
            'payment_type' => $payment_type,
            'payment_month' => $payment_month,
        ]);

        $payments = Payment::query()
            ->where('unit_id', $unit->id)
            ->where('amount', $amount)
            ->where('payment_type', $payment_type)
            ->where('payment_month', $payment_month)
            ->get();
        $this->assertCount(1, $payments);
        $payment = $payments->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);

        Notification::assertSentTo($chairman, PaymentPendingNotification::class);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.show', $payment));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\PaymentController::class,
            'update',
            \App\Http\Requests\Api\PaymentControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $payment = Payment::factory()->create();
        $status = fake()->randomElement(/** enum_attributes **/);
        $approved_by = User::factory()->create();

        Event::fake();

        $response = $this->put(route('payments.update', $payment), [
            'status' => $status,
            'approved_by' => $approved_by->id,
        ]);

        $payment->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($status, $payment->status);
        $this->assertEquals($approved_by->id, $payment->approved_by);

        Event::assertDispatched(PaymentProcessed::class);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->delete(route('payments.destroy', $payment));

        $response->assertNoContent();

        $this->assertModelMissing($payment);
    }
}
