<?php

namespace Tests\Feature;

use App\Mail\PharmacyInvoiceMail;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PharmacyBill;
use App\Models\PharmacyBillItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Tests\TestCase;

class PharmacyInvoiceMailTest extends TestCase
{
    public function test_it_renders_an_itemized_80mm_invoice_and_email_link(): void
    {
        $patient = new Patient([
            'full_name' => 'Test Patient',
        ]);

        $medicine = new Medicine([
            'code' => 'MED-001',
        ]);

        $item = new PharmacyBillItem([
            'medicine_name' => 'Herbal Oil',
            'quantity' => 2,
            'unit_price' => 500,
            'total' => 1000,
        ]);
        $item->setRelation('medicine', $medicine);

        $bill = new PharmacyBill([
            'bill_number' => 'PHARM-BILL-100',
            'currency' => 'LKR',
            'subtotal' => 1000,
            'discount' => 0,
            'grand_total' => 1000,
            'amount_received' => 0,
            'balance_due' => 1000,
        ]);
        $bill->setRelation('patient', $patient);
        $bill->setRelation('items', collect([$item]));

        $printUrl = 'https://example.test/shared/pharmacy-bills/100?signature=valid';
        $mail = new PharmacyInvoiceMail($bill, $printUrl);

        $emailHtml = $mail->render();
        $invoiceHtml = view('pdf.pharmacy-invoice', ['bill' => $bill])->render();
        $pdf = Pdf::loadView('pdf.pharmacy-invoice', ['bill' => $bill])
            ->setPaper([0, 0, 226.77, 520])
            ->output();

        $this->assertStringContainsString('PHARM-BILL-100', $emailHtml);
        $this->assertStringContainsString($printUrl, $emailHtml);
        $this->assertStringContainsString('MED-001', $invoiceHtml);
        $this->assertStringContainsString('Herbal Oil', $invoiceHtml);
        $this->assertStringContainsString('1,000.00', $invoiceHtml);
        $this->assertStringStartsWith('%PDF-', $pdf);
        $this->assertCount(1, $mail->attachments());
    }
}
