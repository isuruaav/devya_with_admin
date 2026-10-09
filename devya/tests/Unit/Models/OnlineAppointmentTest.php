<?php

namespace Tests\Unit\Models;

use App\Models\OnlineAppointment;
use Tests\TestCase;

class OnlineAppointmentTest extends TestCase
{
    public function test_invoice_total_includes_the_doctor_fee_and_facility_service_fee(): void
    {
        $appointment = new OnlineAppointment([
            'appointment_fee' => 3000,
            'facility_service_fee' => 250,
        ]);

        $this->assertSame(3250.0, $appointment->invoice_total);
    }
}
