<?php

namespace App\Http\Controllers;

use App\Models\ReceptionBill;
use Illuminate\Http\Response;

class ReceptionBillPrintController extends Controller
{
    public function print(ReceptionBill $receptionBill): Response
    {
        $this->authorizeBillingAccess();

        $receptionBill->load([
            'patient',
            'items',
        ]);

        return response()->view('receipts.reception-bill-80mm', [
            'bill' => $receptionBill,
        ]);
    }

    public function printStandard(ReceptionBill $receptionBill): Response
    {
        $this->authorizeBillingAccess();

        $receptionBill->load([
            'patient',
            'items',
        ]);

        return response()->view('receipts.reception-bill-a4', [
            'bill' => $receptionBill,
        ]);
    }

    private function authorizeBillingAccess(): void
    {
        abort_unless(
            auth()->user()?->canAccessModule('billing') ?? false,
            403
        );
    }
}
