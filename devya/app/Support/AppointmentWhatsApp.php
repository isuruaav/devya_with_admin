<?php

namespace App\Support;

use App\Models\OnlineAppointment;

class AppointmentWhatsApp
{
    public static function requestUrl(OnlineAppointment $appointment): ?string
    {
        $phoneNumber = WhatsAppNumber::normalize(config('appointments.notification_whatsapp'));

        if (! $phoneNumber || ! $appointment->booking_number || ! $appointment->appointment_date || $appointment->source !== 'Website') {
            return null;
        }

        $message = implode("\n", [
            'Hello '.config('branding.company_name').',',
            'I have submitted an appointment request on your website.',
            '',
            'Booking number: '.$appointment->booking_number,
            'Name: '.$appointment->full_name,
            'Contact number: '.$appointment->phone_number,
            'Preferred date: '.$appointment->appointment_date->format('d M Y'),
            '',
            'Please review my request and confirm an available appointment time.',
        ]);

        return 'https://wa.me/'.$phoneNumber.'?text='.rawurlencode($message);
    }

    public static function confirmationUrl(OnlineAppointment $appointment): ?string
    {
        $phoneNumber = WhatsAppNumber::normalize($appointment->phone_number);

        if ($appointment->status !== 'confirmed' || ! $phoneNumber || ! $appointment->appointment_date || ! $appointment->doctor_id) {
            return null;
        }

        $doctor = $appointment->doctor;

        if (! $doctor) {
            return null;
        }

        $message = implode("\n", [
            config('branding.company_name'),
            config('branding.hospital_label'),
            '',
            'Hello '.$appointment->full_name.',',
            'Your appointment is confirmed.',
            '',
            'Booking number: '.$appointment->booking_number,
            'Doctor: '.$doctor->name,
            'Date: '.$appointment->appointment_date->format('d M Y'),
            'Time: '.$appointment->appointment_date->format('h:i A'),
            '',
            'Please arrive at the Centre before your appointment time.',
            'Thank you for choosing '.config('branding.company_short_name').'.',
        ]);

        return 'https://wa.me/'.$phoneNumber.'?text='.rawurlencode($message);
    }

    public static function bookingAdvancePaymentUrl(OnlineAppointment $appointment, string $paymentUrl): ?string
    {
        $phoneNumber = WhatsAppNumber::normalize($appointment->phone_number);

        if ($appointment->status !== 'confirmed' || ! $phoneNumber || $appointment->source !== 'Website') {
            return null;
        }

        $message = implode("\n", [
            config('branding.company_name'),
            '',
            'Hello '.$appointment->full_name.',',
            'Your appointment is confirmed.',
            '',
            'Booking number: '.$appointment->booking_number,
            'Booking advance to reserve your appointment: LKR '.number_format(
                (float) config('appointments.website_booking_advance_lkr'),
                2
            ),
            'Consultation fee payable at the Centre on the appointment date: LKR '.number_format(
                (float) config('appointments.website_consultation_fee_lkr'),
                2
            ),
            '',
            'Secure PayHere link for the booking advance:',
            $paymentUrl,
            '',
            'The consultation fee is not included in this online payment.',
        ]);

        return 'https://wa.me/'.$phoneNumber.'?text='.rawurlencode($message);
    }
}
