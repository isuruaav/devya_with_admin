<?php

namespace App\Filament\Pages;

use App\Models\Consultation;
use App\Models\ConsultationBill;
use App\Models\MedicalReport;
use App\Models\OnlineAppointment;
use App\Models\Patient;
use App\Models\PharmacyBill;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MedicalHistory extends Page
{
    use WithFileUploads;
    use WithPagination;

    protected static ?string $navigationLabel = 'Medical History';

    protected static ?string $navigationParentItem = 'History';

    protected static ?string $title = 'Medical History';

    protected static string|\BackedEnum|null $navigationIcon =

        'heroicon-o-clipboard-document-list';

    protected string $view = 'filament.pages.medical-history';

    /*

    |--------------------------------------------------------------------------

    | SEARCH

    |--------------------------------------------------------------------------

    */

    public string $search = '';

    public string $searchDate = '';

    public int $perPage = 10;

    /*

    |--------------------------------------------------------------------------

    | SELECTED PATIENT

    |--------------------------------------------------------------------------

    */

    #[Locked]
    public ?int $selectedPatientId = null;

    #[Locked]
    public ?Patient $selectedPatient = null;

    public string $activeTab = 'overview';

    /*

    |--------------------------------------------------------------------------

    | REPORT UPLOAD

    |--------------------------------------------------------------------------

    */

    public $reportFile = null;

    public string $reportType = '';

    public string $reportName = '';

    public string $reportDate = '';

    public string $hospitalLab = '';

    public string $reportDescription = '';

    public ?int $reportConsultationId = null;

    /*

    |--------------------------------------------------------------------------

    | ACCESS

    |--------------------------------------------------------------------------

    */

    public static function canAccess(): bool
    {

        return auth()

            ->user()

            ?->canAccessModule('history') ?? false;

    }

    /*

    |--------------------------------------------------------------------------

    | MOUNT

    |--------------------------------------------------------------------------

    */

    public function mount(): void
    {

        $this->search = '';

        $this->searchDate = '';

        $this->activeTab = 'overview';

    }

    /*

    |--------------------------------------------------------------------------

    | FILTERS

    |--------------------------------------------------------------------------

    */

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

    /*

    |--------------------------------------------------------------------------

    | MEDICAL RECORDS

    |--------------------------------------------------------------------------

    */

    public function getMedicalRecords(): LengthAwarePaginator
    {

        abort_unless(static::canAccess(), 403);

        $user = auth()->user();

        $search = trim($this->search);

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

                filled($search),

                function (Builder $query) use ($search): void {

                    $query->whereHas(

                        'patient',

                        function (Builder $patientQuery) use ($search): void {

                            $patientQuery

                                ->where(

                                    'full_name',

                                    'like',

                                    '%'.$search.'%'

                                )

                                ->orWhere(

                                    'nic_or_passport',

                                    'like',

                                    '%'.$search.'%'

                                );

                        }

                    );

                }

            )
            ->when(
                $user?->hasRole('doctor') ?? false,
                function (Builder $query) use ($user): void {
                    $doctor = $user->getActiveDoctor();

                    if ($doctor === null) {
                        $query->whereRaw('1 = 0');

                        return;
                    }

                    $query->where(
                        'doctor_id',
                        $doctor->getKey()
                    );
                }
            )

            ->latest('consultation_date')

            ->latest('id');

        return $query->paginate(

            $this->perPage

        );

    }

    /*

    |--------------------------------------------------------------------------

    | OPEN PATIENT

    |--------------------------------------------------------------------------

    */

    public function openPatient(int $patientId): void
    {

        abort_unless(static::canAccess(), 403);

        $user = auth()->user();

        $query = Patient::query();

        /*

        |--------------------------------------------------------------------------

        | DOCTOR ACCESS RESTRICTION

        |--------------------------------------------------------------------------

        */

        if ($user?->hasRole('doctor')) {

            $doctorId = (int) $user
                ->getActiveDoctor()
                ->getKey();

            if ($doctorId <= 0) {

                throw ValidationException::withMessages([
                    'patient' => 'Doctor account is not linked with a doctor profile.',

                ]);

            }

            $query->whereHas(

                'consultations',

                function (Builder $consultationQuery) use (

                    $doctorId

                ): void {

                    $consultationQuery->where(

                        'doctor_id',

                        $doctorId

                    );

                }

            );

        }

        $patient = $query

            ->with([

                'country',

                'consultations.doctor',

                'medicalReports.uploadedBy',

            ])

            ->find($patientId);

        if (! $patient) {

            throw ValidationException::withMessages([
                'patient' => 'You do not have permission to view this patient.',

            ]);

        }

        $this->selectedPatientId = $patient->id;

        $this->selectedPatient = $patient;

        $this->activeTab = 'overview';

        $this->resetReportForm();

    }

    /*

    |--------------------------------------------------------------------------

    | CLOSE PATIENT

    |--------------------------------------------------------------------------

    */

    public function closePatient(): void
    {

        $this->selectedPatientId = null;

        $this->selectedPatient = null;

        $this->activeTab = 'overview';

        $this->resetReportForm();

    }

    /*

    |--------------------------------------------------------------------------

    | CHANGE TAB

    |--------------------------------------------------------------------------

    */

    public function setTab(string $tab): void
    {

        $allowed = [

            'overview',

            'consultations',

            'appointments',

            'medicines',

            'treatments',

            'notes',

            'payments',

            'reports',

        ];

        if (! in_array($tab, $allowed, true)) {

            return;

        }

        $this->activeTab = $tab;

    }

    /*

    |--------------------------------------------------------------------------

    | REFRESH SELECTED PATIENT

    |--------------------------------------------------------------------------

    */

    protected function refreshSelectedPatient(): void
    {

        if (! $this->selectedPatientId) {

            return;

        }

        $this->authorizeSelectedPatient();

        $this->selectedPatient = Patient::query()

            ->with([

                'country',

                'consultations.doctor',

                'medicalReports.uploadedBy',

            ])

            ->find(

                $this->selectedPatientId

            );

    }

    /*

    |--------------------------------------------------------------------------

    | PATIENT CONSULTATIONS

    |--------------------------------------------------------------------------

    */

    public function getPatientConsultations()
    {

        if (! $this->selectedPatientId) {

            return collect();

        }

        $this->authorizeSelectedPatient();

        $query = Consultation::query()

            ->with([

                'doctor',

                'medicines.medicine',

                'treatments.treatment',

                'bill',

            ])

            ->where(

                'patient_id',

                $this->selectedPatientId

            )

            ->where(

                'status',

                '!=',

                'cancelled'

            );

        $user = auth()->user();

        if ($user?->hasRole('doctor')) {

            $doctorId = (int) $user
                ->getActiveDoctor()
                ->getKey();

            $query->where('doctor_id', $doctorId);

        }

        return $query

            ->latest('consultation_date')

            ->latest('id')

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | APPOINTMENT HISTORY

    |--------------------------------------------------------------------------

    */

    public function getPatientAppointments()
    {

        if (! $this->selectedPatientId) {

            return collect();

        }

        $this->authorizeSelectedPatient();

        $query = OnlineAppointment::query()

            ->with([

                'doctor',

            ])

            ->where(

                'patient_id',

                $this->selectedPatientId

            );

        $user = auth()->user();

        if ($user?->hasRole('doctor')) {

            $doctorId = (int) $user
                ->getActiveDoctor()
                ->getKey();

            $query->where('doctor_id', $doctorId);

        }

        return $query

            ->latest('appointment_date')

            ->latest('id')

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | MEDICINE HISTORY

    |--------------------------------------------------------------------------

    */

    public function getPatientMedicineHistory()
    {

        $consultations = $this->getPatientConsultations();

        return $consultations

            ->flatMap(

                function ($consultation) {

                    return $consultation

                        ->medicines

                        ->map(

                            function ($item) use (

                                $consultation

                            ) {

                                return (object) [

                                    'date' => $consultation

                                        ->consultation_date,

                                    'consultation' => $consultation,

                                    'medicine' => $item->medicine,

                                    'quantity' => $item->quantity ?? 0,

                                    'unit_price' => $item->unit_price ?? 0,

                                    'total_price' => $item->total_price ?? 0,

                                ];

                            }

                        );

                }

            )

            ->sortByDesc('date')

            ->values();

    }

    /*

    |--------------------------------------------------------------------------

    | TREATMENT HISTORY

    |--------------------------------------------------------------------------

    */

    public function getPatientTreatmentHistory()
    {

        $consultations = $this->getPatientConsultations();

        return $consultations

            ->flatMap(

                function ($consultation) {

                    return $consultation

                        ->treatments

                        ->map(

                            function ($item) use (

                                $consultation

                            ) {

                                return (object) [

                                    'date' => $consultation

                                        ->consultation_date,

                                    'consultation' => $consultation,

                                    'treatment' => $item->treatment,

                                    'price' => $item->price ?? 0,

                                ];

                            }

                        );

                }

            )

            ->sortByDesc('date')

            ->values();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR NOTES

    |--------------------------------------------------------------------------

    */

    public function getPatientDoctorNotes()
    {

        return $this->getPatientConsultations()

            ->map(

                function ($consultation) {

                    return (object) [

                        'date' => $consultation

                            ->consultation_date,

                        'consultation' => $consultation,

                        'doctor' => $this->getDoctorName(

                            $consultation

                        ),

                        'notes' => $this->getSymptomsAndNotes(

                            $consultation

                        ),

                    ];

                }

            )

            ->filter(

                fn ($item) => filled(

                    $item->notes

                )

            )

            ->values();

    }

    /*

    |--------------------------------------------------------------------------

    | PAYMENT HISTORY

    |--------------------------------------------------------------------------

    */

    public function getPatientPaymentHistory()
    {

        if (! $this->selectedPatientId) {

            return collect();

        }

        $this->authorizeSelectedPatient();

        $payments = collect();

        /*

        |--------------------------------------------------------------------------

        | ONLINE APPOINTMENT PAYMENTS

        |--------------------------------------------------------------------------

        */

        $appointments = OnlineAppointment::query()

            ->with('doctor')

            ->where(

                'patient_id',

                $this->selectedPatientId

            )

            ->where(

                'payment_status',

                'paid'

            )

            ->get();

        foreach ($appointments as $appointment) {

            $payments->push(

                (object) [

                    'date' => $appointment->paid_at

                        ?? $appointment->appointment_date,

                    'type' => 'Appointment',

                    'reference' => $appointment->receipt_number

                        ?? $appointment->invoice_number

                        ?? ('Appointment #'.$appointment->id),

                    'amount' => $appointment->invoice_total,

                    'currency' => strtoupper(

                        $appointment->invoice_currency

                        ?? 'LKR'

                    ),

                    'method' => $appointment->payment_method

                        ?? '-',

                    'status' => 'Paid',

                ]

            );

        }

        /*

        |--------------------------------------------------------------------------

        | CONSULTATION BILL PAYMENTS

        |--------------------------------------------------------------------------

        */

        $consultationBills = ConsultationBill::query()

            ->with('consultation')

            ->whereHas(

                'consultation',

                function (Builder $query): void {

                    $query->where(

                        'patient_id',

                        $this->selectedPatientId

                    );

                }

            )

            ->where(

                'payment_status',

                'paid'

            )

            ->get();

        foreach ($consultationBills as $bill) {

            $payments->push(

                (object) [

                    'date' => $bill->paid_at

                        ?? $bill->created_at,

                    'type' => 'Consultation',

                    'reference' => $bill->receipt_number

                        ?? $bill->bill_number

                        ?? ('Bill #'.$bill->id),

                    'amount' => (float) (

                        $bill->amount_received

                        ?? 0

                    ),

                    'currency' => strtoupper(

                        $bill->currency

                        ?? 'LKR'

                    ),

                    'method' => $bill->payment_method

                        ?? '-',

                    'status' => 'Paid',

                ]

            );

        }

        /*

        |--------------------------------------------------------------------------

        | PHARMACY PAYMENTS

        |--------------------------------------------------------------------------

        */

        $pharmacyBills = PharmacyBill::query()

            ->where(

                'patient_id',

                $this->selectedPatientId

            )

            ->where(

                'payment_status',

                'paid'

            )

            ->get();

        foreach ($pharmacyBills as $bill) {

            $payments->push(

                (object) [

                    'date' => $bill->paid_at

                        ?? $bill->created_at,

                    'type' => 'Pharmacy',

                    'reference' => $bill->receipt_number

                        ?? ('Pharmacy #'.$bill->id),

                    'amount' => (float) (

                        $bill->amount_received

                        ?? $bill->grand_total

                        ?? 0

                    ),

                    'currency' => strtoupper(

                        $bill->currency

                        ?? 'LKR'

                    ),

                    'method' => $bill->payment_method

                        ?? '-',

                    'status' => 'Paid',

                ]

            );

        }

        return $payments

            ->sortByDesc('date')

            ->values();

    }

    /*

    |--------------------------------------------------------------------------

    | REPORTS

    |--------------------------------------------------------------------------

    */

    public function getPatientReports()
    {

        if (! $this->selectedPatientId) {

            return collect();

        }

        $this->authorizeSelectedPatient();

        return MedicalReport::query()

            ->with([

                'uploadedBy',

                'consultation',

            ])

            ->where(

                'patient_id',

                $this->selectedPatientId

            )

            ->latest('report_date')

            ->latest('id')

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | REPORT UPLOAD

    |--------------------------------------------------------------------------

    */

    public function uploadReport(): void
    {

        $this->authorizeSelectedPatient();

        /*

        |--------------------------------------------------------------------------

        | CHECK PATIENT

        |--------------------------------------------------------------------------

        */

        if (! $this->selectedPatientId) {

            throw ValidationException::withMessages([
                'reportFile' => 'Please select a patient first.',

            ]);

        }

        /*

        |--------------------------------------------------------------------------

        | VALIDATE UPLOAD

        |--------------------------------------------------------------------------

        */

        $this->validate([

            'reportType' => [

                'required',

                'string',

                'max:100',

            ],

            'reportName' => [

                'required',

                'string',

                'max:255',

            ],

            'reportDate' => [

                'nullable',

                'date',

            ],

            'hospitalLab' => [

                'nullable',

                'string',

                'max:255',

            ],

            'reportConsultationId' => [

                'nullable',

                'integer',

            ],

            'reportDescription' => [

                'nullable',

                'string',

                'max:2000',

            ],

            'reportFile' => [

                'required',

                'file',

                'mimes:pdf,jpg,jpeg,png',

                'max:10240',

            ],

        ]);

        /*

        |--------------------------------------------------------------------------

        | GET PATIENT

        |--------------------------------------------------------------------------

        */

        $patient = Patient::query()

            ->find($this->selectedPatientId);

        if (! $patient) {

            throw ValidationException::withMessages([
                'reportFile' => 'Selected patient was not found.',

            ]);

        }

        /*

        |--------------------------------------------------------------------------

        | DOCTOR SECURITY

        |--------------------------------------------------------------------------

        */

        $user = auth()->user();

        if ($user?->hasRole('doctor')) {

            $doctorId = (int) $user
                ->getActiveDoctor()
                ->getKey();

            if ($doctorId <= 0) {

                throw ValidationException::withMessages([
                    'reportFile' => 'Your doctor account is not linked with a doctor profile.',

                ]);

            }

            $allowed = $patient

                ->consultations()

                ->where('doctor_id', $doctorId)

                ->exists();

            if (! $allowed) {

                throw ValidationException::withMessages([
                    'reportFile' => 'You do not have permission to upload reports for this patient.',

                ]);

            }

        }

        /*

        |--------------------------------------------------------------------------

        | VALIDATE CONSULTATION

        |--------------------------------------------------------------------------

        */

        if ($this->reportConsultationId) {

            $consultationExists = $patient

                ->consultations()

                ->whereKey($this->reportConsultationId)

                ->exists();

            if (! $consultationExists) {

                throw ValidationException::withMessages([
                    'reportConsultationId' => 'Selected consultation does not belong to this patient.',

                ]);

            }

        }

        /*

        |--------------------------------------------------------------------------

        | FILE INFORMATION

        |--------------------------------------------------------------------------

        */

        $file = $this->reportFile;

        $originalName = $file->getClientOriginalName();

        $extension = strtolower(

            $file->getClientOriginalExtension()

        );

        $fileSize = $file->getSize();

        /*

        |--------------------------------------------------------------------------

        | STORE FILE

        |--------------------------------------------------------------------------

        |

        | Actual location:

        |

        | storage/app/public/patients/reports/{patient_id}/

        |

        */

        $directory = 'patients/reports/'.$patient->id;

        $path = $file->store(

            $directory,

            'confidential'

        );

        /*

        |--------------------------------------------------------------------------

        | CHECK FILE WAS REALLY STORED

        |--------------------------------------------------------------------------

        */

        if (! $path || ! Storage::disk('confidential')->exists($path)) {

            throw ValidationException::withMessages([
                'reportFile' => 'The file could not be stored. Please try again.',

            ]);

        }

        /*

        |--------------------------------------------------------------------------

        | SAVE DATABASE RECORD

        |--------------------------------------------------------------------------

        */

        MedicalReport::create([

            'patient_id' => $patient->id,

            'consultation_id' => $this->reportConsultationId ?: null,

            'report_type' => $this->reportType,

            'report_name' => $this->reportName,

            'report_date' => filled($this->reportDate)

                    ? $this->reportDate

                    : now()->toDateString(),

            'hospital_lab' => filled($this->hospitalLab)

                    ? $this->hospitalLab

                    : null,

            'description' => filled($this->reportDescription)

                    ? $this->reportDescription

                    : null,

            'file_path' => $path,

            'original_file_name' => $originalName,

            'file_extension' => $extension,

            'file_size' => $fileSize,

            'uploaded_by_user_id' => auth()->id(),

        ]);

        /*

        |--------------------------------------------------------------------------

        | RESET FORM

        |--------------------------------------------------------------------------

        */

        $this->resetReportForm();

        /*

        |--------------------------------------------------------------------------

        | REFRESH PATIENT

        |--------------------------------------------------------------------------

        */

        $this->refreshSelectedPatient();

        /*

        |--------------------------------------------------------------------------

        | SUCCESS MESSAGE

        |--------------------------------------------------------------------------

        */

        session()->flash(

            'report_success',

            'Medical report uploaded successfully.'

        );

    }

    /*

    |--------------------------------------------------------------------------

    | DELETE REPORT

    |--------------------------------------------------------------------------

    */

    public function deleteReport(int $reportId): void
    {

        $this->authorizeSelectedPatient();

        $report = MedicalReport::query()

            ->where(

                'patient_id',

                $this->selectedPatientId

            )

            ->findOrFail($reportId);

        $user = auth()->user();

        /*

        |--------------------------------------------------------------------------

        | DOCTOR SECURITY

        |--------------------------------------------------------------------------

        */

        if ($user?->hasRole('doctor')) {

            $doctorId = (int) $user
                ->getActiveDoctor()
                ->getKey();

            $patient = $report->patient;

            $allowed = $patient instanceof Patient
                && $patient->consultations()
                    ->where(
                        'doctor_id',
                        $doctorId
                    )
                    ->exists();

            if (! $allowed) {

                throw ValidationException::withMessages([
                    'report' => 'You do not have permission to delete this report.',

                ]);

            }

        }

        if (

            $report->file_path &&

            Storage::disk('confidential')->exists(

                $report->file_path

            )

        ) {

            Storage::disk('confidential')->delete(

                $report->file_path

            );

        }

        $report->delete();

        $this->refreshSelectedPatient();

        session()->flash(

            'report_success',

            'Medical report deleted successfully.'

        );

    }

    /*

    |--------------------------------------------------------------------------

    | RESET REPORT FORM

    |--------------------------------------------------------------------------

    */

    public function resetReportForm(): void
    {

        $this->reset([

            'reportFile',

            'reportType',

            'reportName',

            'reportDate',

            'hospitalLab',

            'reportDescription',

            'reportConsultationId',

        ]);

    }

    protected function authorizeSelectedPatient(): void
    {

        abort_unless(static::canAccess(), 403);

        if ($this->selectedPatientId !== null) {

            $patient = Patient::query()->findOrFail($this->selectedPatientId);

            abort_unless(auth()->user()->canAccessPatient($patient), 403);

        }

    }

    /*

    |--------------------------------------------------------------------------

    | HELPER

    |--------------------------------------------------------------------------

    */

    public function getSymptomsAndNotes(

        Consultation $consultation

    ): ?string {

        $possibleFields = [

            'symptoms_and_notes',

            'symptoms_notes',

            'symptoms',

            'notes',

            'clinical_notes',

            'consultation_notes',

            'remarks',

        ];

        foreach ($possibleFields as $field) {

            $value = $consultation->getAttribute(

                $field

            );

            if (filled($value)) {

                return (string) $value;

            }

        }

        return null;

    }

    public function getDoctorName(

        Consultation $consultation

    ): string {

        $doctor = $consultation->doctor;

        if (! $doctor) {

            return 'Not Assigned';

        }

        $name =

            $doctor->getAttribute('name')

            ?? $doctor->getAttribute('full_name')

            ?? $doctor->getAttribute('doctor_name');

        if (filled($name)) {

            return (string) $name;

        }

        return 'Doctor #'.$doctor->getKey();

    }

    public function getBillNumber($bill): string
    {

        if (! $bill) {

            return 'Not Generated';

        }

        $possibleFields = [

            'bill_number',

            'invoice_number',

            'invoice_no',

            'bill_no',

            'number',

            'reference_number',

        ];

        foreach ($possibleFields as $field) {

            $value = $bill->getAttribute(

                $field

            );

            if (filled($value)) {

                return (string) $value;

            }

        }

        return 'BILL #'.$bill->getKey();

    }

    public function getBillTotal($bill): float
    {

        if (! $bill) {

            return 0;

        }

        $possibleFields = [

            'total_amount',

            'grand_total',

            'total',

            'amount',

        ];

        foreach ($possibleFields as $field) {

            $value = $bill->getAttribute(

                $field

            );

            if ($value !== null) {

                return (float) $value;

            }

        }

        return 0;

    }

    public function getCurrency($bill): string
    {

        if (! $bill) {

            return 'LKR';

        }

        $currency = $bill->getAttribute(

            'currency'

        );

        return filled($currency)

            ? strtoupper((string) $currency)

            : 'LKR';

    }

    public function money($value): string
    {

        return number_format(

            (float) ($value ?? 0),

            2

        );

    }

    public function getDoctorFee(

        Consultation $consultation

    ): float {

        return (float) (

            $consultation->doctor_fee ?? 0

        );

    }

    public function getTreatmentTotal(

        Consultation $consultation

    ): float {

        return (float) $consultation

            ->treatments

            ->sum(

                fn ($item) => (float) (

                    $item->price ?? 0

                )

            );

    }

    public function getMedicineTotal(

        Consultation $consultation

    ): float {

        return (float) $consultation

            ->medicines

            ->sum(

                fn ($item) => (float) (

                    $item->total_price ?? 0

                )

            );

    }

    public function getGrandTotal(

        Consultation $consultation

    ): float {

        $bill = $consultation->bill;

        $billTotal = $this->getBillTotal(

            $bill

        );

        if ($billTotal > 0) {

            return $billTotal;

        }

        $consultationTotal = $consultation->getAttribute('grand_total');

        if ($consultationTotal !== null) {
            return (float) $consultationTotal;
        }

        return

            $this->getDoctorFee($consultation)

            + $this->getTreatmentTotal($consultation)

            + $this->getMedicineTotal($consultation);

    }

    public function getAppointmentPaid(

        $bill

    ): float {

        if (! $bill) {

            return 0;

        }

        foreach ([

            'appointment_paid',

            'paid_at_appointment',

            'appointment_payment',

        ] as $field) {

            $value = $bill->getAttribute(

                $field

            );

            if ($value !== null) {

                return (float) $value;

            }

        }

        return 0;

    }

    public function getBillBalance(

        $bill,

        ?Consultation $consultation = null

    ): float {

        if ($bill) {

            $balanceDue =

                $bill->getAttribute(

                    'balance_due'

                );

            if ($balanceDue !== null) {

                return max(

                    0,

                    (float) $balanceDue

                );

            }

            $balance =

                $bill->getAttribute(

                    'balance'

                );

            if ($balance !== null) {

                return max(

                    0,

                    (float) $balance

                );

            }

        }

        if ($consultation) {

            return max(

                0,

                $this->getGrandTotal(

                    $consultation

                )

                - $this->getAppointmentPaid(

                    $bill

                )

            );

        }

        return 0;

    }

    public function getPaymentStatus(

        $bill

    ): string {

        if (! $bill) {

            return 'Not Generated';

        }

        $status =

            $bill->getAttribute(

                'payment_status'

            );

        if (filled($status)) {

            return ucfirst(

                str_replace(

                    '\_',

                    ' ',

                    (string) $status

                )

            );

        }

        $isPaid =

            $bill->getAttribute(

                'is_paid'

            );

        if (

            $isPaid === true ||

            $isPaid === 1

        ) {

            return 'Paid';

        }

        return $this->getBillBalance(

            $bill

        ) <= 0

            ? 'Paid'

            : 'Balance Due';

    }

    public function getMedicineName(

        $item

    ): string {

        if ($item->medicine) {

            $name =

                $item->medicine->getAttribute(

                    'name'

                )

                ?? $item->medicine->getAttribute(

                    'medicine_name'

                );

            if (filled($name)) {

                return (string) $name;

            }

            return 'Medicine #'.

                $item->medicine->getKey();

        }

        return 'Medicine #'.

            ($item->medicine_id ?? 'N/A');

    }

    public function getTreatmentName(

        $item

    ): string {

        if ($item->treatment) {

            $name =

                $item->treatment->getAttribute(

                    'name'

                )

                ?? $item->treatment->getAttribute(

                    'treatment_name'

                );

            if (filled($name)) {

                return (string) $name;

            }

            return 'Treatment #'.

                $item->treatment->getKey();

        }

        return 'Treatment #'.

            ($item->treatment_id ?? 'N/A');

    }

    /*

    |--------------------------------------------------------------------------

    | FORMAT DATE

    |--------------------------------------------------------------------------

    */

    public function formatDate($date): string
    {

        if (! $date) {

            return '-';

        }

        try {

            return Carbon::parse($date)

                ->format('d M Y');

        } catch (\Throwable) {

            return (string) $date;

        }

    }

    public function formatDateTime($date): string
    {

        if (! $date) {

            return '-';

        }

        try {

            return Carbon::parse($date)

                ->format('d M Y h:i A');

        } catch (\Throwable) {

            return (string) $date;

        }

    }

    /*

    |--------------------------------------------------------------------------

    | PATIENT AGE

    |--------------------------------------------------------------------------

    */

    public function patientAge(): string
    {

        if (! $this->selectedPatient) {

            return '-';

        }

        $dob =

            $this->selectedPatient->date_of_birth

            ?? $this->selectedPatient->dob

            ?? null;

        if (! $dob) {

            return '-';

        }

        try {

            return (string) Carbon::parse(

                $dob

            )->age;

        } catch (\Throwable) {

            return '-';

        }

    }

    /*

    |--------------------------------------------------------------------------

    | TOTAL PAYMENTS

    |--------------------------------------------------------------------------

    */

    public function getPatientPaymentTotal(): float
    {

        return (float) $this

            ->getPatientPaymentHistory()

            ->sum('amount');

    }

    /*

    |--------------------------------------------------------------------------

    | TOTAL CONSULTATIONS

    |--------------------------------------------------------------------------

    */

    public function getPatientConsultationCount(): int
    {

        return $this

            ->getPatientConsultations()

            ->count();

    }

    /*

    |--------------------------------------------------------------------------

    | TOTAL REPORTS

    |--------------------------------------------------------------------------

    */

    public function getPatientReportCount(): int
    {

        return $this

            ->getPatientReports()

            ->count();

    }
}
