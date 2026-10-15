<?php

namespace App\Mail;

use App\Models\OnlineAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebsiteAppointmentRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public OnlineAppointment $appointment) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New appointment request '.$this->appointment->booking_number.' - '.config('branding.company_name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.website-appointment-request',
            with: [
                'appointment' => $this->appointment,
                'adminUrl' => route('filament.admin.resources.online-appointments.index', ['tab' => 'website_requests']),
            ],
        );
    }
}
