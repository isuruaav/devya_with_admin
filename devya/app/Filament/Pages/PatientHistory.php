<?php

namespace App\Filament\Pages;

use App\Models\Consultation;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class PatientHistory extends Page
{
    use WithPagination;

    protected static ?string $navigationLabel = 'Patient History';

    protected static ?string $navigationParentItem = 'History';

    protected static ?string $title = 'Patient History';

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-user-circle';

    protected string $view = 'filament.pages.patient-history';

    public string $search = '';

    public string $searchDate = '';

    public int $perPage = 10;

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('history') ?? false;
    }

    public function mount(): void
    {
        $this->search = '';
        $this->searchDate = '';
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSearchDate(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->searchDate = '';

        $this->resetPage();
    }

    public function getPatientHistory(): LengthAwarePaginator
    {
        $user = auth()->user();

        $query = Consultation::query()
            ->with([
                'patient',
                'doctor',
                'medicines.medicine',
                'treatments.treatment',
                'bill',
            ])
            ->where('status', '!=', 'cancelled')
            ->when(
                filled($this->searchDate),
                function (Builder $query): void {
                    $query->whereDate(
                        'consultation_date',
                        $this->searchDate
                    );
                }
            )
            ->when(
                filled(trim($this->search)),
                function (Builder $query): void {
                    $search = trim($this->search);

                    $query->whereHas(
                        'patient',
                        function (Builder $patientQuery) use ($search): void {
                            $patientQuery
                                ->where(
                                    'full_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'nic_or_passport',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->when(
                $user?->hasRole('doctor') ?? false,
                function (Builder $query) use ($user): void {
                    $query->where(
                        'doctor_id',
                        $user->getActiveDoctor()->getKey()
                    );
                }
            )
            ->latest('consultation_date')
            ->latest('id');

        return $query->paginate($this->perPage);
    }

    public function getDoctorName(Consultation $consultation): string
    {
        $doctor = $consultation->doctor;

        if (! $doctor) {
            return 'Not Assigned';
        }

        return (string) (
            $doctor->getAttribute('name')
            ?? $doctor->getAttribute('full_name')
            ?? $doctor->getAttribute('doctor_name')
            ?? 'Doctor #'.$doctor->getKey()
        );
    }

    public function getSymptomsAndNotes(
        Consultation $consultation
    ): ?string {
        $fields = [
            'symptoms_and_notes',
            'symptoms_notes',
            'symptoms',
            'notes',
            'clinical_notes',
            'consultation_notes',
            'remarks',
        ];

        foreach ($fields as $field) {
            $value = $consultation->getAttribute($field);

            if (filled($value)) {
                return (string) $value;
            }
        }

        return null;
    }

    public function getBillNumber($bill): string
    {
        if (! $bill) {
            return 'Not Generated';
        }

        $fields = [
            'bill_number',
            'invoice_number',
            'invoice_no',
            'bill_no',
            'number',
            'reference_number',
        ];

        foreach ($fields as $field) {
            $value = $bill->getAttribute($field);

            if (filled($value)) {
                return (string) $value;
            }
        }

        return 'BILL #'.$bill->getKey();
    }

    public function getCurrency(Consultation $consultation, $bill = null): string
    {
        return strtoupper(
            (string) (
                $bill?->getAttribute('currency')
                ?? $consultation->getAttribute('currency')
                ?? 'LKR'
            )
        );
    }

    public function money($value): string
    {
        return number_format(
            (float) ($value ?? 0),
            2
        );
    }

    public function getDoctorFee(Consultation $consultation, $bill = null): float
    {
        return (float) (
            $bill?->getAttribute('doctor_fee')
            ?? $consultation->getAttribute('doctor_fee')
            ?? 0
        );
    }

    public function getMedicineTotal(Consultation $consultation, $bill = null): float
    {
        $billTotal = $bill?->getAttribute('medicine_total')
            ?? $bill?->getAttribute('medicines_total');

        if ($billTotal !== null) {
            return (float) $billTotal;
        }

        return (float) $consultation->medicines->sum(
            'total_price'
        );
    }

    public function getTreatmentTotal(Consultation $consultation, $bill = null): float
    {
        $billTotal = $bill?->getAttribute('treatment_total')
            ?? $bill?->getAttribute('treatments_total');

        if ($billTotal !== null) {
            return (float) $billTotal;
        }

        return (float) $consultation->treatments->sum(
            'price'
        );
    }

    public function getGrandTotal(Consultation $consultation, $bill = null): float
    {
        if ($bill) {
            $total = $bill->getAttribute('total_amount')
                ?? $bill->getAttribute('grand_total')
                ?? $bill->getAttribute('total');

            if ($total !== null && (float) $total > 0) {
                return (float) $total;
            }
        }

        return
            $this->getDoctorFee($consultation, $bill)
            + $this->getTreatmentTotal($consultation, $bill)
            + $this->getMedicineTotal($consultation, $bill);
    }

    public function getAppointmentPaid($consultation, $bill = null): float
    {
        if ($bill) {
            $value = $bill->getAttribute('appointment_paid')
                ?? $bill->getAttribute('paid_at_appointment')
                ?? $bill->getAttribute('appointment_payment');

            if ($value !== null) {
                return (float) $value;
            }
        }

        return (float) (
            $consultation->getAttribute('appointment_paid')
            ?? 0
        );
    }

    public function getBalanceDue(Consultation $consultation, $bill = null): float
    {
        if ($bill) {
            $value = $bill->getAttribute('balance_due');

            if ($value !== null) {
                return max(0, (float) $value);
            }

            $value = $bill->getAttribute('balance');

            if ($value !== null) {
                return max(0, (float) $value);
            }
        }

        return max(
            0,
            $this->getGrandTotal($consultation, $bill)
            - $this->getAppointmentPaid($consultation, $bill)
        );
    }

    public function getPaymentStatus($bill, float $balanceDue): string
    {
        if (! $bill) {
            return 'Not Billed';
        }

        $status = strtolower(
            (string) (
                $bill->getAttribute('payment_status')
                ?? $bill->getAttribute('status')
                ?? ''
            )
        );

        if ($balanceDue <= 0) {
            return 'Paid';
        }

        if (filled($status)) {
            return ucfirst(str_replace('_', ' ', $status));
        }

        return 'Balance Pending';
    }
}
