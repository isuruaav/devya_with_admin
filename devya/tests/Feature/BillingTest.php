<?php

namespace Tests\Feature;

use App\Filament\Pages\Billing;
use App\Filament\Resources\PharmacyBills\Tables\PharmacyBillsTable;
use App\Models\Consultation;
use App\Models\ConsultationBill;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use App\Models\Patient;
use App\Models\PharmacyBill;
use Tests\TestCase;

class BillingTest extends TestCase
{
    public function test_it_generates_a_signed_whatsapp_link_for_a_sri_lankan_number(): void
    {
        $patient = new Patient([
            'full_name' => 'Test Patient',
            'whatsapp_number' => '0771234567',
        ]);

        $consultation = new Consultation;
        $consultation->setAttribute('id', 15);
        $consultation->setRelation('patient', $patient);

        $bill = new ConsultationBill([
            'bill_number' => 'CONS-BILL-001',
            'currency' => 'LKR',
            'grand_total' => 2500,
        ]);
        $bill->setAttribute('id', 25);
        $bill->setRelation('consultation', $consultation);

        $url = (new Billing)->getWhatsAppUrl($bill);

        $this->assertSame('wa.me', parse_url($url, PHP_URL_HOST));
        $this->assertSame('/94771234567', parse_url($url, PHP_URL_PATH));

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        $this->assertTrue(str_contains($query['text'], 'Bill No: CONS-BILL-001'));
        $this->assertTrue(str_contains($query['text'], '/shared/consultation-bills/25?'));
        $this->assertTrue(str_contains($query['text'], 'signature='));
    }

    public function test_it_does_not_offer_whatsapp_sharing_without_a_valid_phone_number(): void
    {
        $patient = new Patient([
            'full_name' => 'Test Patient',
            'whatsapp_number' => '',
            'phone_number' => '',
        ]);

        $consultation = new Consultation;
        $consultation->setRelation('patient', $patient);

        $bill = new ConsultationBill([
            'bill_number' => 'CONS-BILL-002',
            'currency' => 'LKR',
            'grand_total' => 2500,
        ]);
        $bill->setRelation('consultation', $consultation);

        $this->assertSame(null, (new Billing)->getWhatsAppUrl($bill));
    }

    public function test_it_generates_a_signed_whatsapp_link_for_a_pharmacy_bill(): void
    {
        $patient = new Patient([
            'full_name' => 'Test Patient',
            'whatsapp_number' => '0771234567',
        ]);

        $bill = new PharmacyBill([
            'bill_number' => 'PHARM-BILL-001',
            'currency' => 'LKR',
            'grand_total' => 1250,
        ]);
        $bill->setAttribute('id', 35);
        $bill->setRelation('patient', $patient);

        $url = PharmacyBillsTable::getWhatsAppUrl($bill);

        $this->assertSame('wa.me', parse_url($url, PHP_URL_HOST));
        $this->assertSame('/94771234567', parse_url($url, PHP_URL_PATH));

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        $this->assertTrue(str_contains($query['text'], 'Bill No: PHARM-BILL-001'));
        $this->assertTrue(str_contains($query['text'], '/shared/pharmacy-bills/35?'));
        $this->assertTrue(str_contains($query['text'], 'signature='));
    }

    public function test_it_does_not_offer_pharmacy_bill_whatsapp_sharing_without_a_valid_phone_number(): void
    {
        $patient = new Patient([
            'full_name' => 'Test Patient',
            'whatsapp_number' => '',
            'phone_number' => '',
        ]);

        $bill = new PharmacyBill([
            'bill_number' => 'PHARM-BILL-002',
            'payment_status' => 'unpaid',
        ]);
        $bill->setRelation('patient', $patient);

        $this->assertSame(null, PharmacyBillsTable::getWhatsAppUrl($bill));
    }

    public function test_it_does_not_offer_whatsapp_sharing_for_cancelled_pharmacy_bills(): void
    {
        $patient = new Patient([
            'full_name' => 'Test Patient',
            'whatsapp_number' => '0771234567',
        ]);

        $bill = new PharmacyBill([
            'bill_number' => 'PHARM-BILL-003',
            'payment_status' => 'cancelled',
        ]);
        $bill->setAttribute('id', 36);
        $bill->setRelation('patient', $patient);

        $this->assertSame(null, PharmacyBillsTable::getWhatsAppUrl($bill));
    }

    public function test_appointment_documents_show_the_facility_fee_and_full_invoice_total(): void
    {
        $appointment = new OnlineAppointment([
            'invoice_number' => 'INV-APPT-000001',
            'receipt_number' => 'RCP-APPT-000001',
            'booking_number' => 'REC-000001',
            'full_name' => 'Test Patient',
            'invoice_currency' => 'LKR',
            'appointment_fee' => 3000,
            'facility_service_fee' => 250,
            'payment_status' => 'paid',
            'paid_at' => now(),
            'appointment_date' => now(),
        ]);
        $appointment->setRelation('doctor', new Doctor([
            'name' => 'Test Doctor',
        ]));

        $invoice = view('pdf.appointment-invoice', [
            'appointment' => $appointment,
        ])->render();
        $thermalBill = view('print.appointment-bill', [
            'appointment' => $appointment,
        ])->render();
        $receipt = view('pdf.appointment-receipt', [
            'appointment' => $appointment,
        ])->render();
        $thermalReceipt = view('print.appointment-receipt', [
            'appointment' => $appointment,
        ])->render();

        foreach ([$invoice, $thermalBill, $receipt, $thermalReceipt] as $document) {
            $this->assertStringContainsString('Facilities / Service Fee', $document);
            $this->assertStringContainsString('3,250.00', $document);
        }
    }
}
