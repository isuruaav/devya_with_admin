<?php

namespace App\Filament\Pages;

use App\Models\Consultation;
use App\Models\ConsultationBill;
use App\Models\Patient;
use App\Support\WhatsAppNumber;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Livewire\WithPagination;

class Billing extends Page
{
    use WithPagination;

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('billing') ?? false;
    }

    protected string $view = 'filament.pages.billing';

    protected static ?string $navigationLabel = 'Billing';

    protected static ?string $title = 'Billing & Documents';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public string $billNumber = '';

    public string $patientName = '';

    public string $billDate = '';

    public function updatedBillNumber(): void
    {
        $this->resetPage();
    }

    public function updatedPatientName(): void
    {
        $this->resetPage();
    }

    public function updatedBillDate(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->billNumber = '';
        $this->patientName = '';
        $this->billDate = '';

        $this->resetPage();
    }

    public function getBills(): LengthAwarePaginator
    {
        return ConsultationBill::query()
            ->with([
                'consultation.patient',
                'consultation.doctor',
                'consultation.treatments.treatment',
            ])
            ->when(
                filled(trim($this->billNumber)),
                fn ($query) => $query->where(
                    'bill_number',
                    'like',
                    '%'.trim($this->billNumber).'%'
                )
            )
            ->when(
                filled(trim($this->patientName)),
                fn ($query) => $query->whereHas(
                    'consultation.patient',
                    fn ($patientQuery) => $patientQuery->where(
                        'full_name',
                        'like',
                        '%'.trim($this->patientName).'%'
                    )
                )
            )
            ->when(
                filled($this->billDate),
                fn ($query) => $query->whereDate(
                    'created_at',
                    $this->billDate
                )
            )
            ->latest('created_at')
            ->paginate(10);
    }

    public function getWhatsAppUrl(ConsultationBill $bill): ?string
    {
        $patient = null;
        $consultation = $bill->consultation;

        if ($consultation instanceof Consultation) {
            $relatedPatient = $consultation->patient;

            if ($relatedPatient instanceof Patient) {
                $patient = $relatedPatient;
            }
        }

        $phoneNumber = WhatsAppNumber::normalize(
            $patient instanceof Patient
                ? ($patient->whatsapp_number ?: $patient->phone_number)
                : null
        );

        if (! $phoneNumber) {
            return null;
        }

        $shareUrl = URL::temporarySignedRoute(
            'consultation-bills.shared',
            now()->addDays(30),
            ['consultationBill' => $bill->getKey()]
        );

        $patientName = $patient instanceof Patient
            ? (string) $patient->full_name
            : 'Patient';

        $message = implode("\n", [
            'ISD Tech Hub (Pvt) Ltd consultation bill',
            'Bill No: '.$bill->bill_number,
            'Patient: '.$patientName,
            'Total: '.$bill->currency.' '.number_format((float) $bill->grand_total, 2),
            'View / print the 80mm bill: '.$shareUrl,
        ]);

        return 'https://wa.me/'.$phoneNumber.'?text='.rawurlencode($message);
    }
}
