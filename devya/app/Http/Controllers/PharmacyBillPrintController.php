<?php

namespace App\Http\Controllers;

use App\Models\PharmacyBill;
use Illuminate\Http\Response;

class PharmacyBillPrintController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PRINT PHARMACY BILL
    |--------------------------------------------------------------------------
    */

    public function print(
        PharmacyBill $bill
    ): Response {

        abort_unless(
            auth()->user()?->canAccessModule('pharmacy_bills') ?? false,
            403
        );

        abort_if($bill->payment_status === 'cancelled', 404);

        $bill->load([
            'patient',
            'items.medicine',
            'paidBy',
        ]);

        return response()->view(
            'pharmacy.bills.print',
            [
                'bill' => $bill,
                'autoPrint' => true,
            ]
        );
    }
}
