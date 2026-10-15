<?php

namespace Tests\Unit;

use App\Models\Doctor;
use App\Models\OnlineAppointment;
use App\Models\Patient;
use App\Support\AppointmentWhatsApp;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AppointmentWhatsAppTest extends TestCase
{
    public static function phoneNumbers(): array
    {
        return [
            'local' => ['071 342 3173'],
            'international' => ['+94 71 342 3173'],
            'international prefix' => ['0094713423173'],
        ];
    }

    #[DataProvider('phoneNumbers')]
    public function test_confirmation_targets_the_booking_number_and_contains_the_confirmed_details(string $phoneNumber): void
    {
        $appointment = $this->confirmedAppointment();
        $appointment->phone_number = $phoneNumber;
        $appointment->setRelation('patient', new Patient(['whatsapp_number' => '0770000000']));

        $url = AppointmentWhatsApp::confirmationUrl($appointment);

        $this->assertSame('wa.me', parse_url($url, PHP_URL_HOST));
        $this->assertSame('/94713423173', parse_url($url, PHP_URL_PATH));
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $this->assertStringContainsString('Your appointment is confirmed.', $query['text']);
        $this->assertStringContainsString('Hello Test Patient,', $query['text']);
        $this->assertStringContainsString('Booking number: WEB-TEST-001', $query['text']);
        $this->assertStringContainsString('Doctor: Dr. Test', $query['text']);
        $this->assertStringContainsString('Date: 20 Oct 2026', $query['text']);
        $this->assertStringContainsString('Time: 01:00 PM', $query['text']);
    }

    public function test_confirmation_is_unavailable_until_the_appointment_is_confirmed_and_a_doctor_is_assigned(): void
    {
        $appointment = $this->confirmedAppointment();
        $appointment->status = 'pending';
        $this->assertNull(AppointmentWhatsApp::confirmationUrl($appointment));

        $appointment->status = 'cancelled';
        $this->assertNull(AppointmentWhatsApp::confirmationUrl($appointment));

        $appointment->status = 'confirmed';
        $appointment->doctor_id = null;
        $this->assertNull(AppointmentWhatsApp::confirmationUrl($appointment));
    }

    public function test_confirmation_is_unavailable_without_a_valid_booking_phone_number(): void
    {
        $appointment = $this->confirmedAppointment();
        $appointment->phone_number = 'invalid';

        $this->assertNull(AppointmentWhatsApp::confirmationUrl($appointment));
    }

    private function confirmedAppointment(): OnlineAppointment
    {
        $appointment = new OnlineAppointment([
            'full_name' => 'Test Patient',
            'booking_number' => 'WEB-TEST-001',
            'phone_number' => '0713423173',
            'doctor_id' => 1,
            'appointment_date' => '2026-10-20 13:00:00',
            'status' => 'confirmed',
        ]);
        $appointment->setRelation('doctor', new Doctor(['name' => 'Dr. Test']));

        return $appointment;
    }
}
