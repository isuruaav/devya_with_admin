<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationTransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_patient_can_be_transferred_to_another_doctor(): void
    {
        $doctorA = Doctor::create([
            'name' => 'Dr. A',
            'email' => 'doctor-a@example.com',
            'phone_number' => '0770000001',
            'specialization' => 'General Medicine',
            'is_active' => true,
            'availability_status' => 'available',
        ]);

        $doctorB = Doctor::create([
            'name' => 'Dr. B',
            'email' => 'doctor-b@example.com',
            'phone_number' => '0770000002',
            'specialization' => 'General Medicine',
            'is_active' => true,
            'availability_status' => 'available',
        ]);

        $patient = Patient::create([
            'full_name' => 'Kamal Perera',
            'nic_or_passport' => '123456789V',
            'patient_type' => 'Local',
            'phone_number' => '0771111111',
        ]);

        $consultation = Consultation::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctorA->id,
            'consultation_date' => now(),
            'patient_type' => 'Local',
            'currency' => 'LKR',
            'doctor_fee' => 1500,
            'treatment_total' => 0,
            'grand_total' => 1500,
            'status' => 'pending',
        ]);

        $appointment = OnlineAppointment::create([
            'booking_number' => 'TEST-TRANSFER-001',
            'full_name' => $patient->full_name,
            'nic_or_passport' => $patient->nic_or_passport,
            'phone_number' => $patient->phone_number,
            'patient_id' => $patient->id,
            'doctor_id' => $doctorA->id,
            'consultation_id' => $consultation->id,
            'appointment_date' => now(),
            'status' => 'confirmed',
            'payment_status' => 'pending',
            'patient_type' => 'Local',
            'invoice_currency' => 'LKR',
        ]);

        $consultation->transferToDoctor($doctorB);

        $this->assertSame($doctorB->id, $consultation->fresh()->doctor_id);
        $this->assertSame($doctorB->id, $appointment->fresh()->doctor_id);
    }
}
