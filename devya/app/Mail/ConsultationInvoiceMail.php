<?php

namespace App\Mail;

use App\Models\Consultation;
use App\Models\ConsultationBill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ConsultationBill $bill,
        public string $printUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            subject: 'Invoice '.$this->bill->bill_number.' - ISD Tech Hub (Pvt) Ltd',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consultation-invoice',
            with: [
                'bill' => $this->bill,
                'printUrl' => $this->printUrl,
            ],
        );
    }

    public function attachments(): array
    {
        $consultation = $this->bill->consultation;

        $treatmentCount = $consultation instanceof Consultation
            ? $consultation->treatments->count()
            : 0;

        $paperHeight = max(
            500,
            480 + ($treatmentCount * 52)
        );

        $pdf = Pdf::loadView('pdf.consultation-invoice', [
            'bill' => $this->bill,
        ])->setPaper([
            0,
            0,
            226.77,
            $paperHeight,
        ]);

        return [
            Attachment::fromData(
                fn (): string => $pdf->output(),
                "invoice-{$this->bill->bill_number}.pdf",
            )->withMime('application/pdf'),
        ];
    }
}
