<?php

namespace App\Mail;

use App\Models\PharmacyBill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PharmacyInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PharmacyBill $bill,
        public string $printUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Pharmacy Invoice '.$this->bill->bill_number.' - ISD Tech Hub (Pvt) Ltd',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pharmacy-invoice',
            with: [
                'bill' => $this->bill,
                'printUrl' => $this->printUrl,
            ],
        );
    }

    public function attachments(): array
    {
        $itemCount = $this->bill->items->count();
        $paperHeight = max(520, 430 + ($itemCount * 44));

        $pdf = Pdf::loadView('pdf.pharmacy-invoice', [
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
                "pharmacy-invoice-{$this->bill->bill_number}.pdf",
            )->withMime('application/pdf'),
        ];
    }
}
