<x-filament-panels::page>

    <div class="space-y-6">

        <div class="admin-workspace">
            <div class="admin-filter-bar">
                <label class="admin-filter-field" for="searchPatient">
                    <span>Search Patient</span>
                    <input id="searchPatient" type="search" wire:model.live.debounce.400ms="search"
                        placeholder="Patient name or NIC / Passport" class="admin-filter-input">
                </label>
                <label class="admin-filter-field" for="searchDate">
                    <span>Consultation Date</span>
                    <input id="searchDate" type="date" wire:model.live="searchDate" class="admin-filter-input">
                </label>
                <label class="admin-filter-field" for="perPage">
                    <span>Records Per Page</span>
                    <select id="perPage" wire:model.live="perPage" class="admin-filter-input">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </label>
                <x-filament::icon-button color="gray" icon="heroicon-m-arrow-path" wire:click="clearFilters"
                    label="Clear filters" tooltip="Clear filters" class="admin-filter-clear" />
            </div>
        </div>

        {{-- ================================================================
            CONSULTATION LIST
        ================================================================= --}}
        @php
            $records = $this->getMedicalRecords();
        @endphp

        <div class="admin-workspace medical-records">
            <div class="admin-records-header">
                <h2>Patient Medical Records</h2>
                <span class="admin-record-count">{{ $records->total() }} Records</span>
            </div>

            <div class="admin-records-body">

                <table class="admin-responsive-table" role="table" aria-label="Patient medical records">

                    <thead class="bg-gray-50 dark:bg-gray-800">

                        <tr>

                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                Date
                            </th>

                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                Patient
                            </th>

                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                Consultation
                            </th>

                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                Doctor
                            </th>

                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                Queue
                            </th>

                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        @forelse($records as $record)

                            @php
                                $patient = $record->patient;
                            @endphp

                            <tr class="transition hover:bg-emerald-50/40 dark:hover:bg-emerald-900/10">

                                <td data-label="Date" class="whitespace-nowrap px-6 py-4">

                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $this->formatDate($record->consultation_date) }}
                                    </div>

                                    @if($record->created_at)
                                        <div class="text-xs text-gray-500">
                                            {{ $record->created_at->format('h:i A') }}
                                        </div>
                                    @endif

                                </td>


                                <td data-label="Patient" class="px-6 py-4">

                                    <div class="font-bold text-gray-900 dark:text-white">
                                        {{ $patient?->full_name ?? 'Unknown Patient' }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $patient?->nic_or_passport ?? '-' }}
                                    </div>

                                </td>


                                <td data-label="Consultation" class="px-6 py-4">

                                    <div class="font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $record->consultation_number ?? ('CONS-' . $record->id) }}
                                    </div>

                                    @if($record->room)
                                        <div class="text-xs text-gray-500">
                                            {{ $record->room }}
                                        </div>
                                    @endif

                                </td>


                                <td data-label="Doctor" class="px-6 py-4">

                                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $this->getDoctorName($record) }}
                                    </div>

                                </td>


                                <td data-label="Queue" class="px-6 py-4">

                                    <span class="inline-flex rounded-lg bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $record->queue_number ?? '-' }}
                                    </span>

                                </td>


                                <td data-label="Action" class="whitespace-nowrap px-6 py-4 text-right">

                                    @if ($patient)
                                        <x-filament::icon-button color="success" icon="heroicon-m-eye"
                                            wire:click="openPatient({{ $patient->id }})"
                                            label="View medical record" tooltip="View medical record"
                                            class="admin-record-action" />
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Unavailable</span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-12 text-center"
                                >

                                    <div class="flex flex-col items-center">

                                        <x-heroicon-o-folder-open class="h-12 w-12 text-gray-400"/>

                                        <h3 class="mt-3 font-semibold text-gray-900 dark:text-white">
                                            No medical records found
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Try changing the search or date filter.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if($records->hasPages())

                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                    {{ $records->links() }}
                </div>

            @endif

        </div>


        {{-- ================================================================
            PATIENT MEDICAL RECORD
        ================================================================= --}}
        @if($selectedPatient)

            @php
                $patient = $selectedPatient;

                $consultations = $this->getPatientConsultations();
                $appointments = $this->getPatientAppointments();
                $medicineHistory = $this->getPatientMedicineHistory();
                $treatmentHistory = $this->getPatientTreatmentHistory();
                $doctorNotes = $this->getPatientDoctorNotes();
                $payments = $this->getPatientPaymentHistory();
                $reports = $this->getPatientReports();
            @endphp


            <div class="fixed inset-0 z-[100] overflow-y-auto bg-gray-950/70 p-3 sm:p-6"
                x-data="{}" x-trap.inert.noscroll="true" x-on:keydown.escape.window="$wire.closePatient()"
                role="dialog" aria-modal="true" aria-labelledby="medical-patient-name">

                <div class="mx-auto max-w-7xl">

                    <div class="admin-workspace medical-history-dialog rounded-lg shadow-2xl">


                        {{-- ========================================================
                            PATIENT HEADER
                        ========================================================= --}}
                        <div class="admin-records-header medical-history-header">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-emerald-50 dark:bg-emerald-950">
                                    @if ($patient->patient_photo ?? $patient->photo ?? $patient->profile_photo ?? $patient->photo_path)
                                        <img src="{{ asset('storage/'.ltrim($patient->patient_photo ?? $patient->photo ?? $patient->profile_photo ?? $patient->photo_path, '/')) }}"
                                            class="h-full w-full object-cover" alt="{{ $patient->full_name }}">
                                    @else
                                        <x-filament::icon icon="heroicon-o-user" class="h-8 w-8 text-emerald-600 dark:text-emerald-400" />
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h2 id="medical-patient-name" class="break-words">{{ $patient->full_name }}</h2>
                                    <p class="mt-1 flex flex-wrap gap-x-3 gap-y-1">
                                        <span>{{ $patient->nic_or_passport ?? 'No NIC / Passport' }}</span>
                                        <span>{{ $patient->patient_type ?? 'Local' }}</span>
                                        @if ($this->patientAge() !== '-')
                                            <span>{{ $this->patientAge() }} years</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <x-filament::icon-button color="gray" icon="heroicon-m-x-mark" wire:click="closePatient"
                                label="Close patient record" tooltip="Close patient record" class="admin-modal-close" />
                        </div>

                        <div class="medical-history-summary grid grid-cols-2 border-b border-gray-200 dark:border-gray-700 md:grid-cols-4">

                            <div class="border-r border-gray-200 p-5 dark:border-gray-700">

                                <div class="text-xs font-semibold uppercase text-gray-500">
                                    Consultations
                                </div>

                                <div class="mt-2 text-2xl font-bold text-emerald-600">
                                    {{ $this->getPatientConsultationCount() }}
                                </div>

                            </div>


                            <div class="border-r border-gray-200 p-5 dark:border-gray-700">

                                <div class="text-xs font-semibold uppercase text-gray-500">
                                    Appointments
                                </div>

                                <div class="mt-2 text-2xl font-bold text-blue-600">
                                    {{ $appointments->count() }}
                                </div>

                            </div>


                            <div class="border-r border-gray-200 p-5 dark:border-gray-700">

                                <div class="text-xs font-semibold uppercase text-gray-500">
                                    Medical Reports
                                </div>

                                <div class="mt-2 text-2xl font-bold text-purple-600">
                                    {{ $this->getPatientReportCount() }}
                                </div>

                            </div>


                            <div class="p-5">

                                <div class="text-xs font-semibold uppercase text-gray-500">
                                    Total Payments
                                </div>

                                <div class="mt-2 text-xl font-bold text-amber-600">
                                    LKR {{ $this->money($this->getPatientPaymentTotal()) }}
                                </div>

                            </div>

                        </div>


                        {{-- ========================================================
                            TABS
                        ========================================================= --}}
                        <div class="overflow-x-auto border-b border-gray-200 dark:border-gray-700">

                            <div class="flex min-w-max px-4" role="tablist" aria-label="Patient medical history">

                                @php
                                    $tabs = [
                                        'overview' => ['Overview', 'heroicon-o-home'],
                                        'consultations' => ['Consultations', 'heroicon-o-clipboard-document-list'],
                                        'appointments' => ['Appointments', 'heroicon-o-calendar-days'],
                                        'medicines' => ['Medicines', 'heroicon-o-beaker'],
                                        'treatments' => ['Treatments', 'heroicon-o-heart'],
                                        'notes' => ['Doctor Notes', 'heroicon-o-document-text'],
                                        'payments' => ['Payments', 'heroicon-o-banknotes'],
                                        'reports' => ['Reports', 'heroicon-o-document-arrow-up'],
                                    ];
                                @endphp

                                @foreach($tabs as $key => $tab)

                                    <button
                                        type="button"
                                        wire:click="setTab('{{ $key }}')"
                                        role="tab" id="medical-tab-{{ $key }}" aria-controls="medical-history-panel"
                                        aria-selected="{{ $activeTab === $key ? 'true' : 'false' }}"
                                        tabindex="{{ $activeTab === $key ? '0' : '-1' }}"
                                        x-on:keydown.arrow-right.prevent="($el.nextElementSibling ?? $el.parentElement.firstElementChild).focus()"
                                        x-on:keydown.arrow-left.prevent="($el.previousElementSibling ?? $el.parentElement.lastElementChild).focus()"
                                        x-on:keydown.home.prevent="$el.parentElement.firstElementChild.focus()"
                                        x-on:keydown.end.prevent="$el.parentElement.lastElementChild.focus()"
                                        class="inline-flex items-center gap-2 border-b-2 px-4 py-4 text-sm font-bold transition
                                            {{ $activeTab === $key
                                                ? 'border-emerald-600 text-emerald-600'
                                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400'
                                            }}"
                                    >

                                        @svg($tab[1], 'h-4 w-4')

                                        {{ $tab[0] }}

                                    </button>

                                @endforeach

                            </div>

                        </div>


                        {{-- ========================================================
                            TAB CONTENT
                        ========================================================= --}}
                        <div class="medical-history-content" role="tabpanel" id="medical-history-panel"
                            aria-labelledby="medical-tab-{{ $activeTab }}" tabindex="0">


                            {{-- ====================================================
                                OVERVIEW
                            ===================================================== --}}
                            @if($activeTab === 'overview')

                                <div class="grid gap-6 lg:grid-cols-2">

                                    <div class="rounded-2xl border border-gray-200 p-5 dark:border-gray-700">

                                        <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 dark:text-white">
                                            <x-heroicon-o-user class="h-5 w-5 text-emerald-600"/>
                                            Patient Information
                                        </h3>

                                        <div class="mt-5 grid gap-4 sm:grid-cols-2">

                                            <div>
                                                <div class="text-xs font-semibold uppercase text-gray-500">
                                                    Full Name
                                                </div>

                                                <div class="mt-1 font-semibold">
                                                    {{ $patient->full_name }}
                                                </div>
                                            </div>

                                            <div>
                                                <div class="text-xs font-semibold uppercase text-gray-500">
                                                    NIC / Passport
                                                </div>

                                                <div class="mt-1 font-semibold">
                                                    {{ $patient->nic_or_passport ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <div class="text-xs font-semibold uppercase text-gray-500">
                                                    Patient Type
                                                </div>

                                                <div class="mt-1 font-semibold">
                                                    {{ $patient->patient_type ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <div class="text-xs font-semibold uppercase text-gray-500">
                                                    Age
                                                </div>

                                                <div class="mt-1 font-semibold">
                                                    {{ $this->patientAge() }}
                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-900/50 dark:bg-red-900/10">

                                        <h3 class="flex items-center gap-2 text-lg font-bold text-red-700 dark:text-red-300">
                                            <x-heroicon-o-exclamation-triangle class="h-5 w-5"/>
                                            Allergies / Important Information
                                        </h3>

                                        <div class="mt-4 whitespace-pre-line text-sm text-red-800 dark:text-red-200">

                                            {{ $patient->allergies ?? 'No allergy information recorded.' }}

                                        </div>

                                    </div>


                                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/50 dark:bg-amber-900/10 lg:col-span-2">

                                        <h3 class="flex items-center gap-2 text-lg font-bold text-amber-700 dark:text-amber-300">
                                            <x-heroicon-o-clipboard-document class="h-5 w-5"/>
                                            Previous Medical History
                                        </h3>

                                        <div class="mt-4 whitespace-pre-line text-sm text-amber-900 dark:text-amber-100">

                                            {{ $patient->medical_history ?? 'No previous medical history recorded.' }}

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- ====================================================
                                CONSULTATIONS
                            ===================================================== --}}
                            @if($activeTab === 'consultations')

                                <div class="space-y-4">

                                    @forelse($consultations as $consultation)

                                        <div class="rounded-2xl border border-gray-200 p-5 dark:border-gray-700">

                                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                                                <div>

                                                    <div class="flex flex-wrap items-center gap-2">

                                                        <span class="rounded-lg bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                                            {{ $consultation->consultation_number ?? ('CONS-' . $consultation->id) }}
                                                        </span>

                                                        <span class="text-sm font-semibold text-gray-500">
                                                            {{ $this->formatDate($consultation->consultation_date) }}
                                                        </span>

                                                    </div>

                                                    <div class="mt-2 font-bold text-gray-900 dark:text-white">
                                                        {{ $this->getDoctorName($consultation) }}
                                                    </div>

                                                </div>

                                                <div class="text-right">

                                                    <div class="text-xs uppercase text-gray-500">
                                                        Grand Total
                                                    </div>

                                                    <div class="text-lg font-bold text-emerald-600">
                                                        {{ $this->getCurrency($consultation->bill) }}
                                                        {{ $this->money($this->getGrandTotal($consultation)) }}
                                                    </div>

                                                </div>

                                            </div>


                                            <div class="mt-5 grid gap-4 border-t border-gray-100 pt-4 sm:grid-cols-3 dark:border-gray-800">

                                                <div>
                                                    <div class="text-xs uppercase text-gray-500">
                                                        Doctor Fee
                                                    </div>

                                                    <div class="font-semibold">
                                                        {{ $this->money($this->getDoctorFee($consultation)) }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="text-xs uppercase text-gray-500">
                                                        Treatments
                                                    </div>

                                                    <div class="font-semibold">
                                                        {{ $this->money($this->getTreatmentTotal($consultation)) }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="text-xs uppercase text-gray-500">
                                                        Medicines
                                                    </div>

                                                    <div class="font-semibold">
                                                        {{ $this->money($this->getMedicineTotal($consultation)) }}
                                                    </div>
                                                </div>

                                            </div>


                                            @if($this->getSymptomsAndNotes($consultation))

                                                <div class="mt-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-800">

                                                    <div class="text-xs font-bold uppercase text-gray-500">
                                                        Symptoms / Notes
                                                    </div>

                                                    <div class="mt-2 whitespace-pre-line text-sm">
                                                        {{ $this->getSymptomsAndNotes($consultation) }}
                                                    </div>

                                                </div>

                                            @endif

                                        </div>

                                    @empty

                                        <div class="py-12 text-center text-gray-500">
                                            No consultation history found.
                                        </div>

                                    @endforelse

                                </div>

                            @endif


                            {{-- ====================================================
                                APPOINTMENTS
                            ===================================================== --}}
                            @if($activeTab === 'appointments')

                                <div class="admin-workspace admin-records-body">

                                    <table class="admin-responsive-table" role="table" aria-label="Patient appointments">

                                        <thead class="bg-gray-50 dark:bg-gray-800">

                                            <tr>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Date
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Doctor
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Token
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Invoice
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Payment
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                            @forelse($appointments as $appointment)

                                                <tr>

                                                    <td data-label="Date" class="px-5 py-4 text-sm">
                                                        {{ $this->formatDateTime($appointment->appointment_date) }}
                                                    </td>

                                                    <td data-label="Doctor" class="px-5 py-4 text-sm font-semibold">
                                                        {{ $appointment->doctor?->name ?? 'Not Assigned' }}
                                                    </td>

                                                    <td data-label="Token" class="px-5 py-4">

                                                        <span class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                                            {{ $appointment->token_number ?? '-' }}
                                                        </span>

                                                    </td>

                                                    <td data-label="Invoice" class="px-5 py-4 text-sm">

                                                        <div class="font-semibold">
                                                            {{ $appointment->invoice_number ?? '-' }}
                                                        </div>

                                                        <div class="text-xs text-gray-500">
                                                            {{ strtoupper($appointment->invoice_currency ?? 'LKR') }}
                                                            {{ $this->money($appointment->invoice_total) }}
                                                        </div>

                                                    </td>

                                                    <td data-label="Payment" class="px-5 py-4">

                                                        @if($appointment->payment_status === 'paid')

                                                            <span class="rounded-lg bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                                                Paid
                                                            </span>

                                                        @else

                                                            <span class="rounded-lg bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                                                {{ ucfirst($appointment->payment_status ?? 'Unpaid') }}
                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @empty

                                                <tr>
                                                    <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                                                        No appointment history found.
                                                    </td>
                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>

                            @endif


                            {{-- ====================================================
                                MEDICINES
                            ===================================================== --}}
                            @if($activeTab === 'medicines')

                                <div class="admin-workspace admin-records-body">

                                    <table class="admin-responsive-table" role="table" aria-label="Patient medicines">

                                        <thead class="bg-gray-50 dark:bg-gray-800">

                                            <tr>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Date
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Medicine
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase text-gray-500">
                                                    Quantity
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase text-gray-500">
                                                    Unit Price
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase text-gray-500">
                                                    Total
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                            @forelse($medicineHistory as $item)

                                                <tr>

                                                    <td data-label="Date" class="px-5 py-4 text-sm">
                                                        {{ $this->formatDate($item->date) }}
                                                    </td>

                                                    <td data-label="Medicine" class="px-5 py-4">

                                                        <div class="font-semibold">
                                                            {{ $item->medicine?->name ?? 'Medicine' }}
                                                        </div>

                                                        <div class="text-xs text-gray-500">
                                                            {{ $item->consultation->consultation_number ?? 'Consultation' }}
                                                        </div>

                                                    </td>

                                                    <td data-label="Quantity" class="px-5 py-4 text-right text-sm">
                                                        {{ $item->quantity }}
                                                    </td>

                                                    <td data-label="Unit Price" class="px-5 py-4 text-right text-sm">
                                                        {{ $this->money($item->unit_price) }}
                                                    </td>

                                                    <td data-label="Total" class="px-5 py-4 text-right font-bold text-emerald-600">
                                                        {{ $this->money($item->total_price) }}
                                                    </td>

                                                </tr>

                                            @empty

                                                <tr>
                                                    <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                                                        No medicine history found.
                                                    </td>
                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>

                            @endif


                            {{-- ====================================================
                                TREATMENTS
                            ===================================================== --}}
                            @if($activeTab === 'treatments')

                                <div class="admin-workspace admin-records-body">

                                    <table class="admin-responsive-table" role="table" aria-label="Patient treatments">

                                        <thead class="bg-gray-50 dark:bg-gray-800">

                                            <tr>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Date
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Treatment
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                    Consultation
                                                </th>

                                                <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase text-gray-500">
                                                    Price
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                            @forelse($treatmentHistory as $item)

                                                <tr>

                                                    <td data-label="Date" class="px-5 py-4 text-sm">
                                                        {{ $this->formatDate($item->date) }}
                                                    </td>

                                                    <td data-label="Treatment" class="px-5 py-4 font-semibold">
                                                        {{ $item->treatment?->name ?? 'Treatment' }}
                                                    </td>

                                                    <td data-label="Consultation" class="px-5 py-4 text-sm text-gray-500">
                                                        {{ $item->consultation->consultation_number ?? '-' }}
                                                    </td>

                                                    <td data-label="Price" class="px-5 py-4 text-right font-bold text-emerald-600">
                                                        {{ $this->money($item->price) }}
                                                    </td>

                                                </tr>

                                            @empty

                                                <tr>
                                                    <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                                                        No treatment history found.
                                                    </td>
                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>

                            @endif


                            {{-- ====================================================
                                DOCTOR NOTES
                            ===================================================== --}}
                            @if($activeTab === 'notes')

                                <div class="space-y-4">

                                    @forelse($doctorNotes as $note)

                                        <div class="relative rounded-2xl border border-gray-200 p-5 pl-6 dark:border-gray-700">

                                            <div class="absolute left-0 top-5 h-10 w-1 rounded-r-full bg-emerald-500"></div>

                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                                                <div class="font-bold text-gray-900 dark:text-white">
                                                    {{ $note->doctor }}
                                                </div>

                                                <div class="text-xs font-semibold text-gray-500">
                                                    {{ $this->formatDate($note->date) }}
                                                </div>

                                            </div>

                                            <div class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700 dark:text-gray-300">
                                                {{ $note->notes }}
                                            </div>

                                        </div>

                                    @empty

                                        <div class="py-12 text-center text-gray-500">
                                            No doctor notes recorded.
                                        </div>

                                    @endforelse

                                </div>

                            @endif


                            {{-- ====================================================
                                PAYMENTS
                            ===================================================== --}}
                            @if($activeTab === 'payments')

                                <div class="space-y-5">

                                    <div class="rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 p-5 text-white">

                                        <div class="text-sm text-emerald-100">
                                            Total Recorded Payments
                                        </div>

                                        <div class="mt-1 text-3xl font-bold">
                                            LKR {{ $this->money($this->getPatientPaymentTotal()) }}
                                        </div>

                                    </div>


                                    <div class="admin-workspace admin-records-body">

                                        <table class="admin-responsive-table" role="table" aria-label="Patient payments">

                                            <thead class="bg-gray-50 dark:bg-gray-800">

                                                <tr>

                                                    <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                        Date
                                                    </th>

                                                    <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                        Type
                                                    </th>

                                                    <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                        Reference
                                                    </th>

                                                    <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                        Method
                                                    </th>

                                                    <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase text-gray-500">
                                                        Amount
                                                    </th>

                                                </tr>

                                            </thead>

                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                                @forelse($payments as $payment)

                                                    <tr>

                                                        <td data-label="Date" class="px-5 py-4 text-sm">
                                                            {{ $this->formatDateTime($payment->date) }}
                                                        </td>

                                                        <td data-label="Type" class="px-5 py-4">

                                                            <span class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                                                {{ $payment->type }}
                                                            </span>

                                                        </td>

                                                        <td data-label="Reference" class="px-5 py-4 text-sm font-semibold">
                                                            {{ $payment->reference }}
                                                        </td>

                                                        <td data-label="Method" class="px-5 py-4 text-sm">
                                                            {{ $payment->method }}
                                                        </td>

                                                        <td data-label="Amount" class="px-5 py-4 text-right font-bold text-emerald-600">
                                                            {{ $payment->currency }}
                                                            {{ $this->money($payment->amount) }}
                                                        </td>

                                                    </tr>

                                                @empty

                                                    <tr>
                                                        <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                                                            No payment history found.
                                                        </td>
                                                    </tr>

                                                @endforelse

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            @endif


                            {{-- ====================================================
                                REPORTS
                            ===================================================== --}}
                            @if($activeTab === 'reports')

                                <div class="space-y-6">


                                    {{-- SUCCESS --}}
                                    @if(session('report_success'))

                                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-300">

                                            {{ session('report_success') }}

                                        </div>

                                    @endif


                                    {{-- UPLOAD FORM --}}
                                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 dark:border-emerald-900/50 dark:bg-emerald-900/10">

                                        <div class="mb-5">

                                            <h3 class="flex items-center gap-2 text-lg font-bold text-emerald-800 dark:text-emerald-300">

                                                <x-heroicon-o-cloud-arrow-up class="h-5 w-5"/>

                                                Upload Medical Report

                                            </h3>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Blood reports, X-Ray, MRI, CT, Ultrasound, ECG, prescriptions and previous records.
                                            </p>

                                        </div>


                                        <div class="grid gap-4 md:grid-cols-2">


                                            <div>

                                                <label class="mb-2 block text-sm font-semibold">
                                                    Report Type
                                                </label>

                                                <select
                                                    wire:model="reportType"
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                                >

                                                    <option value="">
                                                        Select Report Type
                                                    </option>

                                                    <option value="Blood Report">
                                                        Blood Report
                                                    </option>

                                                    <option value="X-Ray">
                                                        X-Ray
                                                    </option>

                                                    <option value="MRI">
                                                        MRI
                                                    </option>

                                                    <option value="CT Scan">
                                                        CT Scan
                                                    </option>

                                                    <option value="Ultrasound">
                                                        Ultrasound
                                                    </option>

                                                    <option value="ECG">
                                                        ECG
                                                    </option>

                                                    <option value="Prescription">
                                                        Prescription
                                                    </option>

                                                    <option value="Previous Medical Record">
                                                        Previous Medical Record
                                                    </option>

                                                    <option value="Other">
                                                        Other
                                                    </option>

                                                </select>

                                                @error('reportType')
                                                    <div class="mt-1 text-xs text-red-600">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>


                                            <div>

                                                <label class="mb-2 block text-sm font-semibold">
                                                    Report Name
                                                </label>

                                                <input
                                                    type="text"
                                                    wire:model="reportName"
                                                    placeholder="e.g. Full Blood Count"
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                                >

                                                @error('reportName')
                                                    <div class="mt-1 text-xs text-red-600">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>


                                            <div>

                                                <label class="mb-2 block text-sm font-semibold">
                                                    Report Date
                                                </label>

                                                <input
                                                    type="date"
                                                    wire:model="reportDate"
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                                >

                                            </div>


                                            <div>

                                                <label class="mb-2 block text-sm font-semibold">
                                                    Hospital / Laboratory
                                                </label>

                                                <input
                                                    type="text"
                                                    wire:model="hospitalLab"
                                                    placeholder="Hospital or Laboratory name"
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                                >

                                            </div>


                                            <div>

                                                <label class="mb-2 block text-sm font-semibold">
                                                    Related Consultation
                                                </label>

                                                <select
                                                    wire:model="reportConsultationId"
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                                >

                                                    <option value="">
                                                        Not Linked
                                                    </option>

                                                    @foreach($consultations as $consultation)

                                                        <option value="{{ $consultation->id }}">

                                                            {{ $this->formatDate($consultation->consultation_date) }}

                                                            -
                                                            {{ $consultation->consultation_number ?? ('CONS-' . $consultation->id) }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>


                                            <div>

                                                <label class="mb-2 block text-sm font-semibold">
                                                    File
                                                </label>

                                                <input
                                                    type="file"
                                                    wire:model="reportFile"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    class="block w-full rounded-xl border border-gray-300 bg-white text-sm dark:border-gray-700 dark:bg-gray-800"
                                                >

                                                <div class="mt-1 text-xs text-gray-500">
                                                    PDF, JPG, JPEG, PNG — Maximum 10 MB
                                                </div>

                                                @error('reportFile')
                                                    <div class="mt-1 text-xs text-red-600">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>


                                            <div class="md:col-span-2">

                                                <label class="mb-2 block text-sm font-semibold">
                                                    Description / Notes
                                                </label>

                                                <textarea
                                                    wire:model="reportDescription"
                                                    rows="3"
                                                    placeholder="Additional information about this report..."
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                                ></textarea>

                                            </div>

                                        </div>


                                        <div class="mt-5 flex justify-end">

                                            <button
                                                type="button"
                                                wire:click="uploadReport"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 disabled:opacity-50"
                                            >

                                                <x-heroicon-o-arrow-up-tray class="h-5 w-5"/>

                                                <span wire:loading.remove wire:target="uploadReport">
                                                    Upload Report
                                                </span>

                                                <span wire:loading wire:target="uploadReport">
                                                    Uploading...
                                                </span>

                                            </button>

                                        </div>

                                    </div>


                                    {{-- REPORT LIST --}}
                                    <div>

                                        <div class="mb-4 flex items-center justify-between">

                                            <div>

                                                <h3 class="text-lg font-bold">
                                                    Medical Reports
                                                </h3>

                                                <p class="text-sm text-gray-500">
                                                    {{ $reports->count() }} documents
                                                </p>

                                            </div>

                                        </div>


                                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">

                                            @forelse($reports as $report)

                                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

                                                    <div class="flex items-center gap-3 border-b border-gray-100 p-4 dark:border-gray-700">

                                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl
                                                            {{ strtolower($report->file_extension ?? '') === 'pdf'
                                                                ? 'bg-red-100 text-red-600'
                                                                : 'bg-blue-100 text-blue-600'
                                                            }}"
                                                        >

                                                            @if(strtolower($report->file_extension ?? '') === 'pdf')

                                                                <x-heroicon-o-document-text class="h-6 w-6"/>

                                                            @else

                                                                <x-heroicon-o-photo class="h-6 w-6"/>

                                                            @endif

                                                        </div>


                                                        <div class="min-w-0 flex-1">

                                                            <div class="truncate font-bold">
                                                                {{ $report->report_name }}
                                                            </div>

                                                            <div class="text-xs text-gray-500">
                                                                {{ $report->report_type }}
                                                            </div>

                                                        </div>

                                                    </div>


                                                    <div class="space-y-2 p-4 text-sm">

                                                        <div class="flex justify-between gap-3">

                                                            <span class="text-gray-500">
                                                                Date
                                                            </span>

                                                            <span class="font-semibold">
                                                                {{ $this->formatDate($report->report_date) }}
                                                            </span>

                                                        </div>


                                                        @if($report->hospital_lab)

                                                            <div class="flex justify-between gap-3">

                                                                <span class="text-gray-500">
                                                                    Hospital / Lab
                                                                </span>

                                                                <span class="text-right font-semibold">
                                                                    {{ $report->hospital_lab }}
                                                                </span>

                                                            </div>

                                                        @endif


                                                        <div class="flex justify-between gap-3">

                                                            <span class="text-gray-500">
                                                                File
                                                            </span>

                                                            <span class="font-semibold uppercase">
                                                                {{ $report->file_extension }}
                                                                ·
                                                                {{ $report->formatted_file_size }}
                                                            </span>

                                                        </div>


                                                        @if($report->description)

                                                            <div class="rounded-xl bg-gray-50 p-3 text-xs dark:bg-gray-900">

                                                                {{ $report->description }}

                                                            </div>

                                                        @endif

                                                    </div>


                                                    <div class="flex gap-2 border-t border-gray-100 p-4 dark:border-gray-700">

                                                        <a
                                                            href="{{ $report->file_url }}"
                                                            target="_blank"
                                                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700"
                                                        >

                                                            <x-heroicon-o-eye class="h-4 w-4"/>

                                                            View

                                                        </a>


                                                        <a
                                                            href="{{ $report->file_url }}"
                                                            download="{{ $report->original_file_name }}"
                                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-100 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200"
                                                        >

                                                            <x-heroicon-o-arrow-down-tray class="h-4 w-4"/>

                                                        </a>


                                                        <button
                                                            type="button"
                                                            wire:click="deleteReport({{ $report->id }})"
                                                            wire:confirm="Are you sure you want to delete this medical report?"
                                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-50 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400"
                                                        >

                                                            <x-heroicon-o-trash class="h-4 w-4"/>

                                                        </button>

                                                    </div>

                                                </div>

                                            @empty

                                                <div class="md:col-span-2 lg:col-span-3">

                                                    <div class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center dark:border-gray-700">

                                                        <x-heroicon-o-document-arrow-up class="mx-auto h-12 w-12 text-gray-400"/>

                                                        <h4 class="mt-3 font-bold">
                                                            No medical reports
                                                        </h4>

                                                        <p class="mt-1 text-sm text-gray-500">
                                                            Upload Blood Reports, X-Ray, MRI, CT, ECG and other patient documents here.
                                                        </p>

                                                    </div>

                                                </div>

                                            @endforelse

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>


                        {{-- ========================================================
                            FOOTER
                        ========================================================= --}}
                        <div class="flex justify-end border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800">

                            <button
                                type="button"
                                wire:click="closePatient"
                                class="inline-flex items-center gap-2 rounded-xl bg-gray-800 px-5 py-2.5 text-sm font-bold text-white hover:bg-gray-900 dark:bg-gray-700"
                            >

                                <x-heroicon-o-x-mark class="h-5 w-5"/>

                                Close Medical Record

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>

</x-filament-panels::page>
