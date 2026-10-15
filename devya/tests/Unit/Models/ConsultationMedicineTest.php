<?php

namespace Tests\Unit\Models;

use App\Models\Consultation;
use App\Models\ConsultationMedicine;
use App\Models\Doctor;
use App\Models\Patient;
use Tests\TestCase;

class ConsultationMedicineTest extends TestCase
{
    public function test_quantity_multiplies_the_meal_doses_by_duration_days(): void
    {
        $quantity = ConsultationMedicine::calculateQuantity(
            breakfastDose: 1,
            lunchDose: 2,
            dinnerDose: 0,
            durationDays: 5,
        );

        $this->assertSame(15, $quantity);
    }

    public function test_prescription_instructions_include_meal_timing_and_duration(): void
    {
        $medicine = new ConsultationMedicine([
            'breakfast_dose' => 1,
            'breakfast_timing' => 'before',
            'lunch_dose' => 2,
            'lunch_timing' => 'after',
            'dinner_dose' => 0,
            'duration_days' => 5,
        ]);

        $this->assertSame(
            'Before Breakfast: 1; After Lunch: 2; For 5 days',
            $medicine->prescription_instructions
        );
    }

    public function test_existing_dosage_text_remains_available_without_a_meal_schedule(): void
    {
        $medicine = new ConsultationMedicine([
            'dosage' => 'Take as prescribed',
        ]);

        $this->assertSame('Take as prescribed', $medicine->prescription_instructions);
    }

    public function test_quantity_and_meal_instruction_are_safe_with_missing_optional_timing(): void
    {
        $this->assertSame(0, ConsultationMedicine::calculateQuantity(0, 0, 0, 1));
        $medicine = new ConsultationMedicine([
            'breakfast_dose' => 2,
            'duration_days' => 3,
        ]);
        $this->assertSame('Breakfast: 2; For 3 days', $medicine->prescription_instructions);
    }

    public function test_prescription_pdf_displays_the_meal_dosage_schedule(): void
    {
        $medicine = new ConsultationMedicine([
            'breakfast_dose' => 1,
            'breakfast_timing' => 'before',
            'lunch_dose' => 2,
            'lunch_timing' => 'after',
            'duration_days' => 5,
            'quantity' => 15,
        ]);

        $consultation = new Consultation([
            'consultation_number' => 'CONS-000001',
        ]);
        $consultation->setRelation('patient', new Patient([
            'full_name' => 'Test Patient',
        ]));
        $consultation->setRelation('doctor', new Doctor([
            'name' => 'Test Doctor',
        ]));
        $consultation->setRelation('medicines', collect([$medicine]));

        $html = view('pdf.consultation-prescription', [
            'consultation' => $consultation,
        ])->render();

        $this->assertStringContainsString('Before Breakfast: 1; After Lunch: 2; For 5 days', $html);
        $this->assertStringContainsString('<td>15</td>', $html);
    }
}
