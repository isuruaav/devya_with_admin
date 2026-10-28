<?php

namespace App\Services;

use App\Models\OnlineAppointment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TextLkSmsService
{
    protected string $endpoint;
    protected ?string $apiToken;
    protected string $senderId;
    protected bool $enabled;

    public function __construct()
    {
        $this->endpoint = (string) config('services.textlk.endpoint', 'https://app.text.lk/api/v3/sms/send');
        $this->apiToken = config('services.textlk.api_token') ? (string) config('services.textlk.api_token') : null;
        $this->senderId = (string) config('services.textlk.sender_id', 'DEVYA');
        $this->enabled = (bool) config('services.textlk.enabled', false);
    }

    /**
     * Determine if Text.lk SMS is configured and enabled.
     */
    public function isEnabled(): bool
    {
        return $this->enabled && filled($this->apiToken);
    }

    /**
     * Send a plain SMS message to a recipient.
     *
     * @return array{success: bool, message: string, data?: mixed}
     */
    public function send(string $to, string $message): array
    {
        if (! $this->isEnabled()) {
            Log::info('Text.lk SMS sending skipped (gateway disabled or unconfigured).', [
                'recipient' => $to,
            ]);

            return [
                'success' => false,
                'message' => 'Text.lk SMS service is disabled or API token is missing in .env.',
            ];
        }

        $recipient = $this->normalizePhoneNumber($to);

        if (! $recipient) {
            Log::warning('Text.lk SMS failed: invalid recipient phone number.', [
                'raw_phone' => $to,
            ]);

            return [
                'success' => false,
                'message' => 'Invalid phone number format.',
            ];
        }

        try {
            $response = Http::withToken($this->apiToken)
                ->acceptJson()
                ->timeout(12)
                ->post($this->endpoint, [
                    'recipient' => $recipient,
                    'sender_id' => $this->senderId,
                    'type' => 'plain',
                    'message' => $message,
                ]);

            if ($response->successful()) {
                Log::info('Text.lk SMS dispatched successfully.', [
                    'recipient' => $recipient,
                    'sender_id' => $this->senderId,
                    'response' => $response->json(),
                ]);

                return [
                    'success' => true,
                    'message' => 'SMS sent successfully.',
                    'data' => $response->json(),
                ];
            }

            Log::error('Text.lk SMS API error response.', [
                'status' => $response->status(),
                'recipient' => $recipient,
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Text.lk SMS API error: '.$response->body(),
            ];
        } catch (Throwable $e) {
            Log::error('Text.lk SMS connection exception.', [
                'error' => $e->getMessage(),
                'recipient' => $recipient,
            ]);

            return [
                'success' => false,
                'message' => 'Connection error while communicating with Text.lk: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Normalize a phone number into Sri Lankan E.164 without leading plus (947XXXXXXXX).
     */
    public function normalizePhoneNumber(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (! $digits) {
            return null;
        }

        // Local format: 07XXXXXXXX (10 digits) -> 947XXXXXXXX
        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            return '94'.substr($digits, 1);
        }

        // Sri Lanka format: 947XXXXXXXX (11 digits)
        if (str_starts_with($digits, '94') && strlen($digits) === 11) {
            return $digits;
        }

        // Short format without leading zero: 7XXXXXXXX (9 digits)
        if (str_starts_with($digits, '7') && strlen($digits) === 9) {
            return '94'.$digits;
        }

        // International format (10 to 15 digits)
        if (strlen($digits) >= 10 && strlen($digits) <= 15) {
            return $digits;
        }

        return null;
    }

    /**
     * Send notification when appointment request is received via website.
     */
    public function sendAppointmentRequestReceived(OnlineAppointment $appointment): array
    {
        $name = trim((string) ($appointment->full_name ?: 'Patient'));
        $bookingNo = $appointment->booking_number;

        $message = "Dear {$name}, your appointment request (Ref: {$bookingNo}) at DEVYA CEYLON has been received. Our team will contact you shortly to confirm your consultation schedule. Hotline: 072 200 0008";

        return $this->send($appointment->phone_number, $message);
    }

    /**
     * Send notification when appointment is confirmed by clinic reception.
     */
    public function sendAppointmentConfirmed(OnlineAppointment $appointment): array
    {
        $name = trim((string) ($appointment->full_name ?: 'Patient'));
        $bookingNo = $appointment->booking_number;
        $date = $appointment->appointment_date ? $appointment->appointment_date->format('d M Y') : 'your chosen date';
        $time = $appointment->appointment_date ? $appointment->appointment_date->format('h:i A') : '';
        $doctorName = $appointment->doctor?->name ? 'Dr. '.$appointment->doctor->name : 'Ayurveda Consultant';
        $room = $appointment->doctor?->room_number ? 'Room '.$appointment->doctor->room_number : 'Reception';

        $timeStr = $time ? " at {$time}" : '';
        $message = "Dear {$name}, your appointment (Ref: {$bookingNo}) at DEVYA CEYLON is CONFIRMED for {$date}{$timeStr} with {$doctorName} ({$room}). Please arrive 15 minutes early. Hotline: 072 200 0008";

        return $this->send($appointment->phone_number, $message);
    }

    /**
     * Send notification when PayHere booking advance payment is received.
     */
    public function sendPaymentReceived(OnlineAppointment $appointment, float|string $amount, string $orderId): array
    {
        $name = trim((string) ($appointment->full_name ?: 'Patient'));
        $formattedAmount = number_format((float) $amount, 2);

        $message = "Dear {$name}, payment of LKR {$formattedAmount} for DEVYA CEYLON appointment (Ref: {$orderId}) was received successfully. Thank you. Hotline: 072 200 0008";

        return $this->send($appointment->phone_number, $message);
    }
}

