<?php

namespace App\Http\Controllers;

use App\Models\ConsultationBill;
use Illuminate\Http\Response;

class ConsultationReceiptController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PRINT BILL
    |--------------------------------------------------------------------------
    */

    public function bill(
        ConsultationBill $consultationBill
    ): Response {
        abort_unless(
            auth()->user()?->canAccessModule('billing') ?? false,
            403
        );

        return $this->renderBill($consultationBill);
    }

    /*
    |--------------------------------------------------------------------------
    | TEMPORARY SHARED BILL
    |--------------------------------------------------------------------------
    */

    public function shared(
        ConsultationBill $consultationBill
    ): Response {
        return $this->renderBill($consultationBill);
    }

    protected function renderBill(
        ConsultationBill $consultationBill
    ): Response {
        $consultationBill->load([
            'consultation.patient',
            'consultation.doctor',
            'consultation.treatments.treatment',
            'consultation.medicines.medicine',
        ]);

        return response()->view(
            'receipts.consultation-bill-80mm',
            [
                'bill' => $consultationBill,
                'autoPrint' => true,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT RECEIPT
    |--------------------------------------------------------------------------
    */

    public function print(
        ConsultationBill $consultationBill
    ): Response {

        abort_unless(
            auth()->user()?->canAccessModule('billing') ?? false,
            403
        );

        abort_unless(
            $consultationBill->payment_status === 'paid',
            404
        );

        $consultationBill->load([
            'consultation.patient',
            'consultation.doctor',
            'consultation.treatments.treatment',
            'consultation.medicines.medicine',
            'paidBy',
        ]);

        return response()->view(
            'receipts.consultation-80mm',
            [
                'bill' => $consultationBill,
                'autoPrint' => true,
            ]
        );
    }
}
