<?php

namespace App\Jobs;

use App\Mail\WebsiteAppointmentRequestMail;
use App\Models\OnlineAppointment;
use App\Services\TextLkSmsService;
use App\Support\OutboundMail;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendWebsiteAppointmentNotification
{
    use Dispatchable;

    public function __construct(public int $appointmentId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $appointment = OnlineAppointment::query()
            ->where('source', 'Website')
            ->find($this->appointmentId);

        if (! $appointment) {
            return;
        }

        // Send confirmation SMS to patient via Text.lk if enabled
        try {
            $smsService = app(TextLkSmsService::class);
            if ($smsService->isEnabled() && filled($appointment->phone_number)) {
                $smsService->sendAppointmentRequestReceived($appointment);
            }
        } catch (Throwable $smsException) {
            Log::warning('Website appointment request SMS notification failed.', [
                'appointment_id' => $this->appointmentId,
                'error' => $smsException->getMessage(),
            ]);
        }

        // Send notification email to clinic staff if email is enabled
        $recipient = config('appointments.notification_email');

        if (OutboundMail::isEnabled() && filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($recipient)->send(new WebsiteAppointmentRequestMail($appointment));
            } catch (Throwable $exception) {
                Log::warning('Website appointment email notification failed.', [
                    'appointment_id' => $this->appointmentId,
                    'exception' => $exception::class,
                ]);
            }
        }
    }
}
