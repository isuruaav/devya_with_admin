<?php

namespace Tests\Feature;

use App\Models\BookingAdvancePayment;
use App\Models\OnlineAppointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PayHerePaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.payhere.merchant_id' => '1234567',
            'services.payhere.merchant_secret' => 'test-merchant-secret',
            'services.payhere.mode' => 'sandbox',
            'services.payhere.notify_url' => 'https://devyaceylon.lk/devya/public/payments/payhere/notify',
            'branding.website_url' => 'https://devyaceylon.lk',
        ]);
    }

    public function test_checkout_is_signed_and_contains_the_advance_amount_not_the_consultation_fee(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);

        $response = $this->get($this->checkoutUrl($appointment, $payment));

        $expectedHash = strtoupper(md5(
            '1234567'
            .$payment->order_id
            .'1500.00'
            .'LKR'
            .strtoupper(md5('test-merchant-secret'))
        ));

        $response->assertOk()
            ->assertSee('https://sandbox.payhere.lk/pay/checkout')
            ->assertSee('name="amount" value="1500.00"', false)
            ->assertSee('name="hash" value="'.$expectedHash.'"', false)
            ->assertSee('LKR 3,000.00 consultation fee is separate');
    }

    public function test_tampered_checkout_link_is_rejected(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $url = $this->checkoutUrl($appointment, $payment);

        $this->get($url.'&tampered=1')->assertForbidden();
    }

    public function test_expired_payment_attempt_cannot_be_checked_out(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $payment->update(['expires_at' => now()->subSecond()]);
        $url = URL::temporarySignedRoute(
            'payhere.checkout',
            now()->addDay(),
            [
                'onlineAppointment' => $appointment->getKey(),
                'bookingAdvancePayment' => $payment->uuid,
            ]
        );

        $this->get($url)->assertGone();
    }

    public function test_signed_payhere_notification_marks_only_the_booking_advance_paid(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);

        $this->post('/payments/payhere/notify', $this->notification($payment))
            ->assertOk()
            ->assertSeeText('OK');

        $payment->refresh();
        $appointment->refresh();

        $this->assertSame('paid', $payment->status);
        $this->assertSame('PH-TEST-001', $payment->payhere_payment_id);
        $this->assertSame('VISA', $payment->payment_method);
        $this->assertNotNull($payment->paid_at);
        $this->assertSame('unpaid', $appointment->payment_status);
    }

    public function test_invalid_notification_signature_does_not_change_payment_status(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $notification = $this->notification($payment);
        $notification['md5sig'] = str_repeat('0', 32);

        $this->post('/payments/payhere/notify', $notification)->assertBadRequest();

        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_notification_with_a_different_amount_is_rejected(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $notification = $this->notification($payment);
        $notification['payhere_amount'] = '1501.00';
        $notification['md5sig'] = $this->notificationSignature($notification);

        $this->post('/payments/payhere/notify', $notification)->assertBadRequest();

        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_notification_with_a_different_currency_is_rejected(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $notification = $this->notification($payment);
        $notification['payhere_currency'] = 'USD';
        $notification['md5sig'] = $this->notificationSignature($notification);

        $this->post('/payments/payhere/notify', $notification)->assertBadRequest();

        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_notification_with_an_unexpected_merchant_is_rejected(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $notification = $this->notification($payment);
        $notification['merchant_id'] = '7654321';
        $notification['md5sig'] = $this->notificationSignature($notification);

        $this->post('/payments/payhere/notify', $notification)->assertBadRequest();

        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_notification_for_an_unknown_order_is_rejected(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $notification = $this->notification($payment);
        $notification['order_id'] = 'UNKNOWN-ORDER';
        $notification['md5sig'] = $this->notificationSignature($notification);

        $this->post('/payments/payhere/notify', $notification)->assertNotFound();

        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_repeated_notification_is_idempotent_and_second_success_is_flagged(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $notification = $this->notification($payment);

        $this->post('/payments/payhere/notify', $notification)->assertOk();
        $paidAt = $payment->fresh()->paid_at;
        $this->post('/payments/payhere/notify', $notification)->assertOk();
        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertEquals($paidAt, $payment->fresh()->paid_at);

        $notification['payment_id'] = 'PH-TEST-002';
        $notification['md5sig'] = $this->notificationSignature($notification);

        $this->post('/payments/payhere/notify', $notification)->assertOk();
        $duplicate = $payment->fresh();
        $this->assertSame('duplicate', $duplicate->status);
        $this->assertSame('PH-TEST-001', $duplicate->payhere_payment_id);
        $this->assertSame(['PH-TEST-002'], $duplicate->additional_payhere_payment_ids);

        $this->post('/payments/payhere/notify', $notification)->assertOk();
        $this->assertSame('duplicate', $payment->fresh()->status);
        $this->assertSame(['PH-TEST-002'], $payment->fresh()->additional_payhere_payment_ids);
    }

    public function test_returning_from_payhere_does_not_mark_an_unverified_payment_paid(): void
    {
        $appointment = $this->confirmedAppointment();
        $payment = $this->bookingAdvance($appointment);
        $returnUrl = URL::temporarySignedRoute(
            'payhere.return',
            now()->addDay(),
            ['paymentTransaction' => $payment->uuid]
        );

        $this->get($returnUrl)
            ->assertOk()
            ->assertSeeText('PayHere is processing your payment notification');

        $this->assertSame('pending', $payment->fresh()->status);
    }

    private function confirmedAppointment(): OnlineAppointment
    {
        return OnlineAppointment::create([
            'booking_number' => 'WEB-PAY-001',
            'full_name' => 'Payment Test Patient',
            'nic_or_passport' => 'WEBSITE-PAY-001',
            'phone_number' => '0770000000',
            'email' => 'payment-test@example.test',
            'address' => '12 Test Road',
            'city' => 'Kandy',
            'patient_type' => 'Local',
            'doctor_id' => null,
            'appointment_date' => now()->addDays(3),
            'source' => 'Website',
            'status' => 'confirmed',
            'appointment_fee' => 3000,
        ]);
    }

    private function bookingAdvance(OnlineAppointment $appointment): BookingAdvancePayment
    {
        return $appointment->bookingAdvancePayments()->create([
            'uuid' => (string) str()->uuid(),
            'order_id' => 'DEVYA-BA-'.$appointment->id.'-'.str()->random(12),
            'amount' => 1500,
            'currency' => 'LKR',
            'status' => 'pending',
            'expires_at' => now()->addDay(),
        ]);
    }

    private function checkoutUrl(OnlineAppointment $appointment, BookingAdvancePayment $payment): string
    {
        return URL::temporarySignedRoute(
            'payhere.checkout',
            $payment->expires_at,
            [
                'onlineAppointment' => $appointment->getKey(),
                'bookingAdvancePayment' => $payment->uuid,
            ]
        );
    }

    private function notification(BookingAdvancePayment $payment): array
    {
        $notification = [
            'merchant_id' => '1234567',
            'order_id' => $payment->order_id,
            'payment_id' => 'PH-TEST-001',
            'payhere_amount' => '1500.00',
            'payhere_currency' => 'LKR',
            'status_code' => '2',
            'method' => 'VISA',
            'status_message' => 'Payment successful',
        ];
        $notification['md5sig'] = $this->notificationSignature($notification);

        return $notification;
    }

    private function notificationSignature(array $notification): string
    {
        return strtoupper(md5(
            $notification['merchant_id']
            .$notification['order_id']
            .$notification['payhere_amount']
            .$notification['payhere_currency']
            .$notification['status_code']
            .strtoupper(md5('test-merchant-secret'))
        ));
    }
}
