<?php

namespace Tests\Unit\Models;

use App\Models\Consultation;
use App\Models\ConsultationBill;
use App\Models\ConsultationMedicine;
use App\Models\ConsultationTreatment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Treatment;
use Tests\TestCase;

class ConsultationTest extends TestCase
{
    public function test_it_excludes_prescribed_medicines_from_consultation_bill_totals(): void
    {
        $consultation = new Consultation([
            'doctor_fee' => 3000,
        ]);
        $consultation->setRelation('treatments', collect([
            new ConsultationTreatment([
                'quantity' => 1,
                'price' => 280,
            ]),
        ]));
        $consultation->setRelation('medicines', collect([
            new ConsultationMedicine([
                'quantity' => 2,
                'unit_price' => 25,
            ]),
        ]));

        $totals = $consultation->calculateBillTotals();

        $this->assertSame([
            'doctor_fee' => 3000.0,
            'treatment_total' => 280.0,
            'medicine_total' => 50.0,
            'grand_total' => 3280.0,
        ], $totals);
    }

    public function test_it_uses_a_quantity_of_one_for_missing_or_nonpositive_treatment_quantity(): void
    {
        $consultation = new Consultation([
            'doctor_fee' => 3000,
        ]);
        $consultation->setRelation('treatments', collect([
            new ConsultationTreatment([
                'quantity' => 0,
                'price' => 280,
            ]),
        ]));
        $consultation->setRelation('medicines', collect());

        $totals = $consultation->calculateBillTotals();

        $this->assertSame(3280.0, $totals['grand_total']);
    }

    public function test_consultation_bill_print_view_starts_the_browser_print_dialog(): void
    {
        $this->withoutVite();

        $patient = new Patient([
            'full_name' => 'Test Patient',
        ]);

        $doctor = new Doctor([
            'name' => 'Test Doctor',
        ]);

        $treatment = new Treatment([
            'name' => 'Herbal Therapy',
        ]);

        $treatmentItem = new ConsultationTreatment([
            'quantity' => 1,
            'price' => 280,
        ]);
        $treatmentItem->setRelation('treatment', $treatment);

        $consultation = new Consultation;
        $consultation->setRelation('patient', $patient);
        $consultation->setRelation('doctor', $doctor);
        $consultation->setRelation('treatments', collect([$treatmentItem]));

        $bill = new ConsultationBill([
            'bill_number' => 'CONS-BILL-000007',
            'currency' => 'LKR',
            'doctor_fee' => 3000,
            'treatment_total' => 280,
            'grand_total' => 3280,
            'appointment_paid' => 3000,
            'amount_received' => 500,
            'change_amount' => 220,
            'balance_due' => 0,
            'payment_status' => 'paid',
        ]);
        $bill->setRelation('consultation', $consultation);

        $html = view('receipts.consultation-bill-80mm', [
            'bill' => $bill,
            'autoPrint' => true,
        ])->render();

        $this->assertStringContainsString('CONS-BILL-000007', $html);
        $this->assertStringContainsString('Herbal Therapy', $html);
        $this->assertStringContainsString('Amount Received', $html);
        $this->assertStringContainsString('window.print();', $html);
    }

    public function test_shared_consultation_bill_view_does_not_automatically_open_print_dialog(): void
    {
        $this->withoutVite();

        $bill = new ConsultationBill([
            'bill_number' => 'CONS-BILL-000007',
            'currency' => 'LKR',
            'grand_total' => 3280,
            'balance_due' => 280,
            'payment_status' => 'unpaid',
        ]);
        $bill->setRelation('consultation', new Consultation);

        $html = view('receipts.consultation-bill-80mm', [
            'bill' => $bill,
            'autoPrint' => false,
        ])->render();

        $this->assertStringNotContainsString('window.print();', $html);
    }
}
