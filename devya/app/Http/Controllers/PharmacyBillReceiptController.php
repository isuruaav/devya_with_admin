<?php

namespace App\Http\Controllers;

use App\Models\PharmacyBill;
use Illuminate\View\View;

class PharmacyBillReceiptController extends Controller
{
    public function show(PharmacyBill $pharmacyBill): View
    {
        abort_unless(
            auth()->user()?->canAccessModule('pharmacy_bills') ?? false,
            403
        );

        abort_unless(
            $pharmacyBill->payment_status === 'paid',
            404
        );

        $pharmacyBill->load([
            'patient',
            'items.medicine',
            'paidBy',
            'createdBy',
        ]);

        return view(
            'pharmacy-bills.receipt',
            [
                'bill' => $pharmacyBill,
            ]
        );
    }

    public function shared(PharmacyBill $pharmacyBill): View
    {
        abort_if($pharmacyBill->payment_status === 'cancelled', 404);

        $pharmacyBill->load([
            'patient',
            'items.medicine',
            'paidBy',
        ]);

        return view('pharmacy.bills.print', [
            'bill' => $pharmacyBill,
            'autoPrint' => false,
        ]);
    }
}
