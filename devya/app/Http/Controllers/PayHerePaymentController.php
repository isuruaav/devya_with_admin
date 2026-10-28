<?php

namespace App\Http\Controllers;

use App\Models\BookingAdvancePayment;
use App\Models\OnlineAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PayHerePaymentController extends Controller
{
    public function checkout(
        OnlineAppointment $onlineAppointment,
        BookingAdvancePayment $bookingAdvancePayment
    ): View {
        abort_unless(
            $bookingAdvancePayment->online_appointment_id === $onlineAppointment->id,
            404
        );
        abort_unless($onlineAppointment->status === 'confirmed', 404);
        abort_unless($bookingAdvancePayment->status === 'pending', 410);
        abort_unless($bookingAdvancePayment->expires_at->isFuture(), 410);
        abort_unless($this->gatewayIsConfigured(), 503, 'Online payments are not configured.');
        abort_unless(
            filled($onlineAppointment->email)
            && filled($onlineAppointment->address)
            && filled($onlineAppointment->city),
            409,
            'Customer checkout details are incomplete.'
        );

        $merchantId = (string) config('services.payhere.merchant_id');
        $merchantSecret = (string) config('services.payhere.merchant_secret');
        $amount = number_format((float) $bookingAdvancePayment->amount, 2, '.', '');
        $currency = $bookingAdvancePayment->currency;
        $secretHash = strtoupper(md5($merchantSecret));

        $checkout = [
            'merchant_id' => $merchantId,
            'return_url' => $this->signedStatusUrl('payhere.return', $bookingAdvancePayment),
            'cancel_url' => $this->signedStatusUrl('payhere.cancel', $bookingAdvancePayment),
            'notify_url' => (string) config('services.payhere.notify_url'),
            'order_id' => $bookingAdvancePayment->order_id,
            'items' => 'DEVYA CEYLON appointment booking advance',
            'currency' => $currency,
            'amount' => $amount,
            'first_name' => $this->firstName($onlineAppointment->full_name),
            'last_name' => $this->lastName($onlineAppointment->full_name),
            'email' => $onlineAppointment->email,
            'phone' => $onlineAppointment->phone_number,
            'address' => $onlineAppointment->address,
            'city' => $onlineAppointment->city,
            'country' => 'Sri Lanka',
        ];
        $checkout['hash'] = strtoupper(md5(
            $merchantId
            .$bookingAdvancePayment->order_id
            .$amount
            .$currency
            .$secretHash
        ));

        $endpoint = config('services.payhere.mode') === 'live'
            ? 'https://www.payhere.lk/pay/checkout'
            : 'https://sandbox.payhere.lk/pay/checkout';

        return view('payments.payhere-checkout', [
            'appointment' => $onlineAppointment,
            'payment' => $bookingAdvancePayment,
            'checkout' => $checkout,
            'endpoint' => $endpoint,
            'websiteUrl' => rtrim((string) config('branding.website_url'), '/'),
        ]);
    }

    public function notify(Request $request)
    {
        abort_unless($this->gatewayIsConfigured(), 503, 'Online payments are not configured.');

        $data = $request->validate([
            'merchant_id' => ['required', 'string', 'max:100'],
            'order_id' => ['required', 'string', 'max:100'],
            'payment_id' => ['required', 'string', 'max:100'],
            'payhere_amount' => ['required', 'numeric', 'gt:0'],
            'payhere_currency' => ['required', 'string', 'size:3'],
            'status_code' => ['required', 'integer', 'in:2,0,-1,-2,-3'],
            'md5sig' => ['required', 'string', 'size:32'],
            'method' => ['nullable', 'string', 'max:50'],
            'status_message' => ['nullable', 'string', 'max:255'],
        ]);

        $merchantId = (string) config('services.payhere.merchant_id');
        if (! hash_equals($merchantId, $data['merchant_id'])) {
            Log::warning('Rejected PayHere notification with an unexpected merchant ID.');

            abort(400, 'Invalid payment notification.');
        }

        $localSignature = strtoupper(md5(
            $data['merchant_id']
            .$data['order_id']
            .$request->string('payhere_amount')->toString()
            .$data['payhere_currency']
            .$request->string('status_code')->toString()
            .strtoupper(md5((string) config('services.payhere.merchant_secret')))
        ));

        if (! hash_equals($localSignature, strtoupper($data['md5sig']))) {
            Log::warning('Rejected PayHere notification with an invalid signature.');

            abort(400, 'Invalid payment notification.');
        }

        $payment = BookingAdvancePayment::query()
            ->where('order_id', $data['order_id'])
            ->firstOrFail();

        if (
            $payment->currency !== $data['payhere_currency']
            || number_format((float) $payment->amount, 2, '.', '')
                !== number_format((float) $data['payhere_amount'], 2, '.', '')
        ) {
            Log::warning('Rejected PayHere notification with a mismatched order amount.', [
                'order_id' => $data['order_id'],
            ]);

            abort(400, 'Invalid payment notification.');
        }

        DB::transaction(function () use ($payment, $data): void {
            $lockedPayment = BookingAdvancePayment::query()
                ->lockForUpdate()
                ->findOrFail($payment->id);

            if (in_array($lockedPayment->status, ['paid', 'duplicate'], true)) {
                $knownPaymentIds = array_filter([
                    $lockedPayment->payhere_payment_id,
                    ...($lockedPayment->additional_payhere_payment_ids ?? []),
                ]);

                if (in_array($data['payment_id'], $knownPaymentIds, true)) {
                    if (
                        $lockedPayment->status === 'paid'
                        && $lockedPayment->payhere_payment_id === $data['payment_id']
                        && (int) $data['status_code'] === -3
                    ) {
                        $lockedPayment->update([
                            'status' => 'charged_back',
                            'status_message' => $data['status_message'] ?? 'PayHere reported a chargeback.',
                        ]);
                    }

                    return;
                }

                if ((int) $data['status_code'] === 2) {
                    $additionalPaymentIds = array_values(array_unique([
                        ...($lockedPayment->additional_payhere_payment_ids ?? []),
                        $data['payment_id'],
                    ]));

                    $lockedPayment->update([
                        'status' => 'duplicate',
                        'additional_payhere_payment_ids' => $additionalPaymentIds,
                        'status_message' => 'A second successful payment requires manual reconciliation.',
                    ]);

                    Log::critical('Multiple successful PayHere payments were received for one booking advance.', [
                        'order_id' => $lockedPayment->order_id,
                    ]);
                }

                return;
            }

            $newStatus = match ((int) $data['status_code']) {
                2 => BookingAdvancePayment::query()
                    ->where('online_appointment_id', $lockedPayment->online_appointment_id)
                    ->where('status', 'paid')
                    ->where('id', '!=', $lockedPayment->id)
                    ->exists()
                        ? 'duplicate'
                        : 'paid',
                0 => 'pending',
                -1 => 'cancelled',
                -2 => 'failed',
                -3 => 'charged_back',
            };

            $lockedPayment->update([
                'status' => $newStatus,
                'payhere_payment_id' => $data['payment_id'],
                'payment_method' => $data['method'] ?? null,
                'status_message' => $newStatus === 'duplicate'
                    ? 'A second successful payment requires manual reconciliation.'
                    : ($data['status_message'] ?? null),
                'paid_at' => $newStatus === 'paid' ? now() : null,
            ]);

            if ($newStatus === 'duplicate') {
                Log::critical('Multiple successful PayHere payments were received for one appointment.', [
                    'online_appointment_id' => $lockedPayment->online_appointment_id,
                    'order_id' => $lockedPayment->order_id,
                ]);
            }


            if ($newStatus === 'paid') {
                try {
                    $appointment = $lockedPayment->onlineAppointment;
                    $smsService = app(\App\Services\TextLkSmsService::class);
                    if ($appointment && $smsService->isEnabled() && filled($appointment->phone_number)) {
                        $smsService->sendPaymentReceived($appointment, $data['payhere_amount'], $lockedPayment->order_id);
                    }
                } catch (\Throwable $smsEx) {
                    \Illuminate\Support\Facades\Log::warning('PayHere advance payment SMS notification failed.', [
                        'order_id' => $lockedPayment->order_id,
                        'error' => $smsEx->getMessage(),
                    ]);
                }
            }


        });

        return response('OK', 200);
    }

    public function return(BookingAdvancePayment $paymentTransaction): View
    {
        return $this->statusPage($paymentTransaction, 'return');
    }

    public function cancel(BookingAdvancePayment $paymentTransaction): View
    {
        return $this->statusPage($paymentTransaction, 'cancel');
    }

    private function statusPage(BookingAdvancePayment $payment, string $result): View
    {
        return view('payments.payhere-status', [
            'payment' => $payment->load('appointment'),
            'result' => $result,
            'websiteUrl' => rtrim((string) config('branding.website_url'), '/'),
        ]);
    }

    private function gatewayIsConfigured(): bool
    {
        return filled(config('services.payhere.merchant_id'))
            && filled(config('services.payhere.merchant_secret'))
            && filled(config('services.payhere.notify_url'))
            && in_array(config('services.payhere.mode'), ['sandbox', 'live'], true);
    }

    private function signedStatusUrl(string $route, BookingAdvancePayment $payment): string
    {
        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            $route,
            now()->addDays(7),
            ['paymentTransaction' => $payment->uuid]
        );
    }

    private function firstName(string $fullName): string
    {
        return Str::before(trim($fullName), ' ') ?: trim($fullName);
    }

    private function lastName(string $fullName): string
    {
        $lastName = Str::after(trim($fullName), ' ');

        return $lastName === trim($fullName) ? '-' : $lastName;
    }
}
