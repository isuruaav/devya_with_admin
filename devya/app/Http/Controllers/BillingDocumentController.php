<?php

namespace App\Http\Controllers;

use App\Mail\ConsultationInvoiceMail;
use App\Models\Consultation;
use App\Models\OnlineAppointment;
use App\Support\OutboundMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BillingDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Consultation Invoice
    |--------------------------------------------------------------------------
    */
    public function invoice(int $consultationId): Response
    {
        $this->authorizeBillingAccess();

        $consultation = $this->findConsultation($consultationId);
        $bill = $consultation->bill;

        abort_if(
            ! $bill,
            404,
            'A consultation bill has not been generated.'
        );

        $bill->setRelation('consultation', $consultation);

        return response()->view(
            'receipts.consultation-bill-80mm',
            ['bill' => $bill]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Consultation Prescription
    |--------------------------------------------------------------------------
    */
    public function prescription(int $consultationId): Response
    {
        $this->authorizeBillingAccess();

        $consultation = $this->findConsultation($consultationId);

        return Pdf::loadView(
            'pdf.consultation-prescription',
            compact('consultation')
        )
            ->setPaper('a4')
            ->download(
                "prescription-{$consultation->consultation_number}.pdf"
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Email Consultation Invoice
    |--------------------------------------------------------------------------
    */
    public function emailInvoice(
        int $consultationId
    ): RedirectResponse {
        $this->authorizeBillingAccess();
        OutboundMail::ensureEnabled();

        $consultation = $this->findConsultation($consultationId);

        $email = $consultation->patient?->email;

        abort_if(
            blank($email),
            422,
            'This patient does not have an email address.'
        );

        $bill = $consultation->bill;

        abort_if(
            ! $bill,
            404,
            'A consultation bill has not been generated.'
        );

        $bill->setRelation('consultation', $consultation);

        $printUrl = URL::temporarySignedRoute(
            'consultation-bills.shared',
            now()->addDays(30),
            ['consultationBill' => $bill->getKey()]
        );

        Mail::to($email)->send(
            new ConsultationInvoiceMail($bill, $printUrl)
        );

        return back()->with(
            'status',
            "Bill {$bill->bill_number} sent to {$email}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Appointment Invoice PDF
    |--------------------------------------------------------------------------
    */
    public function appointmentInvoice(
        int $appointmentId
    ): Response {
        $appointment = $this->findAppointment(
            $appointmentId
        );

        abort_if(
            blank($appointment->invoice_number),
            422,
            'Appointment invoice has not been generated yet.'
        );

        return Pdf::loadView(
            'pdf.appointment-invoice',
            compact('appointment')
        )
            ->setPaper('a4')
            ->download(
                "appointment-invoice-{$appointment->invoice_number}.pdf"
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Appointment Bill - Thermal Printer
    |--------------------------------------------------------------------------
    */
    public function appointmentBillPrint(
        int $appointmentId
    ): View {
        $appointment = $this->findAppointment(
            $appointmentId
        );

        abort_if(
            blank($appointment->invoice_number),
            422,
            'Appointment invoice has not been generated yet.'
        );

        return view(
            'print.appointment-bill',
            compact('appointment')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Appointment Receipt PDF
    |--------------------------------------------------------------------------
    */
    public function appointmentReceipt(
        int $appointmentId
    ): Response {
        $appointment = $this->findAppointment(
            $appointmentId
        );

        abort_if(
            $appointment->payment_status !== 'paid',
            422,
            'This appointment has not been paid.'
        );

        return Pdf::loadView(
            'pdf.appointment-receipt',
            compact('appointment')
        )
            ->setPaper('a4')
            ->download(
                "receipt-{$appointment->receipt_number}.pdf"
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Appointment Receipt - Thermal Printer
    |--------------------------------------------------------------------------
    */
    public function appointmentReceiptPrint(
        int $appointmentId
    ): View {
        $appointment = $this->findAppointment(
            $appointmentId
        );

        abort_if(
            $appointment->payment_status !== 'paid',
            422,
            'This appointment has not been paid.'
        );

        return view(
            'print.appointment-receipt',
            compact('appointment')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Consultation Finder
    |--------------------------------------------------------------------------
    */
    protected function findConsultation(
        int $consultationId
    ): Consultation {
        return Consultation::query()
            ->with([
                'patient',
                'doctor',
                'treatments.treatment',
                'medicines.medicine',
            ])
            ->findOrFail($consultationId);
    }

    protected function authorizeBillingAccess(): void
    {
        abort_unless(
            auth()->user()?->canAccessModule('billing') ?? false,
            403
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Appointment Finder
    |--------------------------------------------------------------------------
    */
    protected function findAppointment(
        int $appointmentId
    ): OnlineAppointment {
        $this->authorizeBillingAccess();

        return OnlineAppointment::query()
            ->with([
                'patient',
                'doctor',
            ])
            ->findOrFail($appointmentId);
    }
}
