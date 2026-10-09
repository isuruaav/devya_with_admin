<x-filament-panels::page>

    @php
        $patientHistory = $this->getPatientHistory();
    @endphp

    <div
        x-data="{ openHistory: null }"
        class="space-y-6"
    >

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">

            <div class="bg-gradient-to-r from-indigo-700 via-indigo-800 to-purple-900 px-6 py-6">

                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                    <div class="flex items-center gap-4">

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15">

                            <svg
                                class="h-8 w-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M15 19a4 4 0 00-8 0m8 0h5m-5 0H7m10-8a3 3 0 11-6 0 3 3 0 016 0zM5 21h14M5 9a3 3 0 116 0 3 3 0 01-6 0z"
                                />
                            </svg>

                        </div>

                        <div>

                            <h1 class="text-2xl font-bold text-white">
                                Patient History
                            </h1>

                            <p class="mt-1 text-sm text-indigo-100">
                                Complete patient consultation and treatment history
                            </p>

                        </div>

                    </div>


                    <div class="rounded-xl border border-white/20 bg-white/10 px-5 py-3">

                        <div class="text-xs font-medium uppercase tracking-wide text-indigo-100">
                            Total Records
                        </div>

                        <div class="mt-1 text-2xl font-bold text-white">
                            {{ number_format($patientHistory->total()) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             SEARCH
        ========================================================== --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">

            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        Search Patient History
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Search by patient name, NIC or Passport.
                    </p>

                </div>

                <button
                    type="button"
                    wire:click="clearFilters"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Clear Filters
                </button>

            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- SEARCH PATIENT --}}
                <div class="md:col-span-2">

                    <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Search Patient
                    </label>

                    <div class="relative">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">

                            <svg
                                class="h-5 w-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61z"
                                />
                            </svg>

                        </div>

                        <input
                            type="text"
                            wire:model.live.debounce.400ms="search"
                            placeholder="Patient Name / NIC / Passport"
                            class="w-full rounded-xl border border-gray-300 bg-white py-3.5 pl-11 pr-4 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        >

                    </div>

                </div>


                {{-- DATE --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Consultation Date
                    </label>

                    <input
                        type="date"
                        wire:model.live="searchDate"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3.5 text-sm text-gray-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>

            </div>

        </div>


        {{-- =========================================================
             TABLE
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">

            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800">

                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                    <div>

                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            Consultation Records
                        </h2>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Open any consultation to view complete medical details.
                        </p>

                    </div>


                    <select
                        wire:model.live="perPage"
                        class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 outline-none focus:border-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200"
                    >
                        <option value="10">10 Records</option>
                        <option value="15">15 Records</option>
                        <option value="25">25 Records</option>
                        <option value="50">50 Records</option>
                    </select>

                </div>

            </div>


            @if($patientHistory->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-white dark:bg-gray-900">

                            <tr class="border-b border-gray-200 dark:border-gray-700">

                                <th class="whitespace-nowrap px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Patient
                                </th>

                                <th class="whitespace-nowrap px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Consultation
                                </th>

                                <th class="whitespace-nowrap px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Doctor
                                </th>

                                <th class="whitespace-nowrap px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Date
                                </th>

                                <th class="whitespace-nowrap px-5 py-4 text-right text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Total
                                </th>

                                <th class="whitespace-nowrap px-5 py-4 text-right text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Details
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                            @foreach($patientHistory as $consultation)

                                @php
                                    $patient = $consultation->patient;
                                    $doctor = $consultation->doctor;
                                    $bill = $consultation->bill;

                                    $currency = $this->getCurrency(
                                        $consultation,
                                        $bill
                                    );

                                    $doctorFee = $this->getDoctorFee(
                                        $consultation,
                                        $bill
                                    );

                                    $medicineTotal = $this->getMedicineTotal(
                                        $consultation,
                                        $bill
                                    );

                                    $treatmentTotal = $this->getTreatmentTotal(
                                        $consultation,
                                        $bill
                                    );

                                    $grandTotal = $this->getGrandTotal(
                                        $consultation,
                                        $bill
                                    );

                                    $appointmentPaid = $this->getAppointmentPaid(
                                        $consultation,
                                        $bill
                                    );

                                    $balanceDue = $this->getBalanceDue(
                                        $consultation,
                                        $bill
                                    );

                                    $paymentStatus = $this->getPaymentStatus(
                                        $bill,
                                        $balanceDue
                                    );
                                @endphp


                                <tr class="group transition hover:bg-indigo-50/40 dark:hover:bg-gray-800/60">

                                    {{-- PATIENT --}}
                                    <td class="px-5 py-5">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">

                                                {{ strtoupper(substr($patient?->full_name ?? 'P', 0, 1)) }}

                                            </div>

                                            <div>

                                                <div class="font-bold text-gray-900 dark:text-white">
                                                    {{ $patient?->full_name ?? 'Unknown Patient' }}
                                                </div>

                                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $patient?->nic_or_passport ?? 'NIC / Passport not available' }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- CONSULTATION --}}
                                    <td class="px-5 py-5">

                                        <div class="font-bold text-indigo-700 dark:text-indigo-300">
                                            {{ $consultation->consultation_number ?? '—' }}
                                        </div>

                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Queue:
                                            {{ $consultation->queue_number ?? '—' }}
                                        </div>

                                        @if($bill)

                                            <div class="mt-2 text-xs font-semibold text-gray-600 dark:text-gray-300">
                                                {{ $this->getBillNumber($bill) }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- DOCTOR --}}
                                    <td class="px-5 py-5">

                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $this->getDoctorName($consultation) }}
                                        </div>

                                        @if($doctor?->room_number)

                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Room {{ $doctor->room_number }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- DATE --}}
                                    <td class="whitespace-nowrap px-5 py-5">

                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $consultation->consultation_date?->format('d M Y') ?? '—' }}
                                        </div>

                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $consultation->consultation_date?->format('h:i A') ?? '—' }}
                                        </div>

                                    </td>


                                    {{-- TOTAL --}}
                                    <td class="whitespace-nowrap px-5 py-5 text-right">

                                        <div class="font-bold text-gray-900 dark:text-white">
                                            {{ $currency }}
                                            {{ $this->money($grandTotal) }}
                                        </div>

                                        @if($bill)

                                            @if($balanceDue <= 0)

                                                <span class="mt-2 inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                    Paid
                                                </span>

                                            @else

                                                <span class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                                    Balance
                                                </span>

                                            @endif

                                        @endif

                                    </td>


                                    {{-- DETAILS --}}
                                    <td class="px-5 py-5 text-right">

                                        <button
                                            type="button"
                                            @click="openHistory = {{ $consultation->id }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >

                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.25 12s3.75-7 9.75-7 9.75 7 9.75 7-3.75 7-9.75 7-9.75-7-9.75-7z"
                                                />

                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="2.5"
                                                    stroke-width="2"
                                                />
                                            </svg>

                                            View Details

                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                @if($patientHistory->hasPages())

                    <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">

                        {{ $patientHistory->links() }}

                    </div>

                @endif

            @else

                {{-- EMPTY --}}
                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800">

                        <svg
                            class="h-8 w-8 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.414 4.414A2 2 0 0119 9.414V19a2 2 0 01-2 2z"
                            />
                        </svg>

                    </div>

                    <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">
                        No Patient History Found
                    </h3>

                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Try another patient name, NIC, Passport or date.
                    </p>

                </div>

            @endif

        </div>


        {{-- =========================================================
             FULL MEDICAL HISTORY MODALS
        ========================================================== --}}

        @foreach($patientHistory as $consultation)

            @php
                $patient = $consultation->patient;
                $doctor = $consultation->doctor;
                $bill = $consultation->bill;

                $currency = $this->getCurrency(
                    $consultation,
                    $bill
                );

                $doctorFee = $this->getDoctorFee(
                    $consultation,
                    $bill
                );

                $medicineTotal = $this->getMedicineTotal(
                    $consultation,
                    $bill
                );

                $treatmentTotal = $this->getTreatmentTotal(
                    $consultation,
                    $bill
                );

                $grandTotal = $this->getGrandTotal(
                    $consultation,
                    $bill
                );

                $appointmentPaid = $this->getAppointmentPaid(
                    $consultation,
                    $bill
                );

                $balanceDue = $this->getBalanceDue(
                    $consultation,
                    $bill
                );

                $paymentStatus = $this->getPaymentStatus(
                    $bill,
                    $balanceDue
                );

                $symptomsAndNotes = $this->getSymptomsAndNotes(
                    $consultation
                );
            @endphp


            <div
                x-show="openHistory === {{ $consultation->id }}"
                x-cloak
                class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-6"
            >

                {{-- BACKDROP --}}
                <div
                    class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm"
                    @click="openHistory = null"
                ></div>


                {{-- MODAL --}}
                <div
                    class="relative z-10 flex max-h-[94vh] w-full max-w-6xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900"
                    @click.stop
                >

                    {{-- =============================================
                         MODAL HEADER
                    ============================================== --}}
                    <div class="shrink-0 bg-gradient-to-r from-indigo-700 via-indigo-800 to-purple-900 px-5 py-5 sm:px-7">

                        <div class="flex items-start justify-between gap-4">

                            <div class="flex min-w-0 items-center gap-4">

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/15 text-lg font-bold text-white">

                                    {{ strtoupper(substr($patient?->full_name ?? 'P', 0, 1)) }}

                                </div>

                                <div class="min-w-0">

                                    <div class="text-xs font-semibold uppercase tracking-wider text-indigo-200">
                                        Complete Patient Visit
                                    </div>

                                    <h2 class="mt-1 truncate text-xl font-bold text-white sm:text-2xl">
                                        {{ $patient?->full_name ?? 'Unknown Patient' }}
                                    </h2>

                                    <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-indigo-100">

                                        <span>
                                            {{ $consultation->consultation_date?->format('d M Y') ?? '—' }}
                                        </span>

                                        <span>
                                            {{ $consultation->consultation_date?->format('h:i A') ?? '—' }}
                                        </span>

                                        <span>
                                            Queue {{ $consultation->queue_number ?? '—' }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <button
                                type="button"
                                @click="openHistory = null"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-white transition hover:bg-white/20"
                            >

                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18 18 6M6 6l12 12"
                                    />
                                </svg>

                            </button>

                        </div>

                    </div>


                    {{-- =============================================
                         SCROLL AREA
                    ============================================== --}}
                    <div class="min-h-0 flex-1 overflow-y-auto">

                        <div class="space-y-6 p-5 sm:p-7">


                            {{-- =====================================
                                 VISIT SUMMARY
                            ====================================== --}}
                            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">

                                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4 dark:border-indigo-900/50 dark:bg-indigo-900/20">

                                    <div class="text-xs font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-300">
                                        Consultation
                                    </div>

                                    <div class="mt-2 truncate text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $consultation->consultation_number ?? '—' }}
                                    </div>

                                </div>


                                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 dark:border-blue-900/50 dark:bg-blue-900/20">

                                    <div class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-300">
                                        Bill Number
                                    </div>

                                    <div class="mt-2 truncate text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $this->getBillNumber($bill) }}
                                    </div>

                                </div>


                                <div class="rounded-2xl border border-purple-100 bg-purple-50 p-4 dark:border-purple-900/50 dark:bg-purple-900/20">

                                    <div class="text-xs font-semibold uppercase tracking-wide text-purple-600 dark:text-purple-300">
                                        Doctor
                                    </div>

                                    <div class="mt-2 truncate text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $this->getDoctorName($consultation) }}
                                    </div>

                                </div>


                                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 dark:border-emerald-900/50 dark:bg-emerald-900/20">

                                    <div class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-300">
                                        Total
                                    </div>

                                    <div class="mt-2 truncate text-sm font-bold text-emerald-700 dark:text-emerald-300">
                                        {{ $currency }} {{ $this->money($grandTotal) }}
                                    </div>

                                </div>

                            </div>


                            {{-- =====================================
                                 PATIENT INFORMATION
                            ====================================== --}}
                            <section>

                                <div class="mb-3 flex items-center gap-3">

                                    <div class="h-8 w-1 rounded-full bg-indigo-600"></div>

                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                        Patient Information
                                    </h3>

                                </div>


                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-800">

                                        <div class="space-y-4">

                                            <div class="flex justify-between gap-4">

                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    Full Name
                                                </span>

                                                <span class="text-right text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $patient?->full_name ?? '—' }}
                                                </span>

                                            </div>


                                            <div class="flex justify-between gap-4">

                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    NIC / Passport
                                                </span>

                                                <span class="text-right text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $patient?->nic_or_passport ?? '—' }}
                                                </span>

                                            </div>


                                            <div class="flex justify-between gap-4">

                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    Consultation Date
                                                </span>

                                                <span class="text-right text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $consultation->consultation_date?->format('d M Y, h:i A') ?? '—' }}
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-900/50 dark:bg-red-900/10">

                                        <div class="mb-3 flex items-center gap-2">

                                            <svg
                                                class="h-5 w-5 text-red-600 dark:text-red-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 9v4m0 4h.01M10.3 3.8l-8 14A2 2 0 004 21h16a2 2 0 001.7-3.2l-8-14a2 2 0 00-3.4 0z"
                                                />
                                            </svg>

                                            <h4 class="font-bold text-red-800 dark:text-red-300">
                                                Allergy Information
                                            </h4>

                                        </div>

                                        <p class="whitespace-pre-line text-sm leading-6 text-red-700 dark:text-red-300">
                                            {{ $patient?->allergies ?: 'No allergies recorded.' }}
                                        </p>

                                    </div>

                                </div>

                            </section>


                            {{-- =====================================
                                 SYMPTOMS & NOTES
                            ====================================== --}}
                            <section>

                                <div class="mb-3 flex items-center gap-3">

                                    <div class="h-8 w-1 rounded-full bg-amber-500"></div>

                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                        Symptoms & Notes
                                    </h3>

                                </div>


                                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/50 dark:bg-amber-900/10">

                                    @if($symptomsAndNotes)

                                        <div class="whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                                            {{ $symptomsAndNotes }}
                                        </div>

                                    @else

                                        <div class="text-sm italic text-gray-500 dark:text-gray-400">
                                            No symptoms or notes recorded for this visit.
                                        </div>

                                    @endif

                                </div>

                            </section>


                            {{-- =====================================
                                 MEDICAL HISTORY
                            ====================================== --}}
                            <section>

                                <div class="mb-3 flex items-center gap-3">

                                    <div class="h-8 w-1 rounded-full bg-gray-500"></div>

                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                        Previous Medical History
                                    </h3>

                                </div>


                                <div class="whitespace-pre-line rounded-2xl border border-gray-200 bg-gray-50 p-5 text-sm leading-7 text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">

                                    {{ $patient?->medical_history ?: 'No previous medical history recorded.' }}

                                </div>

                            </section>


                            {{-- =====================================
                                 MEDICINES
                            ====================================== --}}
                            <section class="overflow-hidden rounded-2xl border border-blue-200 dark:border-blue-900/50">

                                <div class="flex items-center justify-between bg-blue-50 px-5 py-4 dark:bg-blue-900/20">

                                    <div>

                                        <h3 class="font-bold text-blue-900 dark:text-blue-200">
                                            Medicines Given
                                        </h3>

                                        <p class="mt-1 text-xs text-blue-700 dark:text-blue-300">
                                            Medicines recorded for this specific consultation
                                        </p>

                                    </div>

                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                                        {{ $consultation->medicines->count() }} Items
                                    </span>

                                </div>


                                @if($consultation->medicines->count())

                                    <div class="overflow-x-auto">

                                        <table class="min-w-full">

                                            <thead class="bg-gray-50 dark:bg-gray-800">

                                                <tr>

                                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                        #
                                                    </th>

                                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                        Medicine
                                                    </th>

                                                    <th class="px-5 py-3 text-center text-xs font-bold uppercase text-gray-500">
                                                        Quantity
                                                    </th>

                                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase text-gray-500">
                                                        Total
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                                @foreach($consultation->medicines as $index => $item)

                                                    <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-800">

                                                        <td class="px-5 py-4 text-sm text-gray-500">
                                                            {{ $index + 1 }}
                                                        </td>

                                                        <td class="px-5 py-4">

                                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                                {{ $item->medicine?->name ?? 'Medicine #' . $item->medicine_id }}
                                                            </div>

                                                            @if($item->medicine?->unit)

                                                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                                    Unit: {{ $item->medicine->unit }}
                                                                </div>

                                                            @endif

                                                        </td>

                                                        <td class="px-5 py-4 text-center">

                                                            <span class="inline-flex rounded-lg bg-blue-100 px-3 py-1.5 text-sm font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                                {{ $item->quantity }}
                                                            </span>

                                                        </td>

                                                        <td class="px-5 py-4 text-right">

                                                            <span class="font-bold text-gray-900 dark:text-white">
                                                                {{ $currency }}
                                                                {{ $this->money($item->total_price) }}
                                                            </span>

                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                @else

                                    <div class="p-6 text-center text-sm italic text-gray-500 dark:text-gray-400">
                                        No medicines recorded for this consultation.
                                    </div>

                                @endif

                            </section>


                            {{-- =====================================
                                 TREATMENTS
                            ====================================== --}}
                            <section class="overflow-hidden rounded-2xl border border-purple-200 dark:border-purple-900/50">

                                <div class="flex items-center justify-between bg-purple-50 px-5 py-4 dark:bg-purple-900/20">

                                    <div>

                                        <h3 class="font-bold text-purple-900 dark:text-purple-200">
                                            Treatments Given
                                        </h3>

                                        <p class="mt-1 text-xs text-purple-700 dark:text-purple-300">
                                            Treatments recorded for this specific consultation
                                        </p>

                                    </div>

                                    <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-700 dark:bg-purple-900/50 dark:text-purple-300">
                                        {{ $consultation->treatments->count() }} Items
                                    </span>

                                </div>


                                @if($consultation->treatments->count())

                                    <div class="overflow-x-auto">

                                        <table class="min-w-full">

                                            <thead class="bg-gray-50 dark:bg-gray-800">

                                                <tr>

                                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                        #
                                                    </th>

                                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase text-gray-500">
                                                        Treatment
                                                    </th>

                                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase text-gray-500">
                                                        Price
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                                @foreach($consultation->treatments as $index => $item)

                                                    <tr class="hover:bg-purple-50/40 dark:hover:bg-gray-800">

                                                        <td class="px-5 py-4 text-sm text-gray-500">
                                                            {{ $index + 1 }}
                                                        </td>

                                                        <td class="px-5 py-4">

                                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                                {{ $item->treatment?->name ?? 'Treatment #' . $item->treatment_id }}
                                                            </div>

                                                        </td>

                                                        <td class="px-5 py-4 text-right">

                                                            <span class="font-bold text-gray-900 dark:text-white">
                                                                {{ $currency }}
                                                                {{ $this->money($item->price) }}
                                                            </span>

                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                @else

                                    <div class="p-6 text-center text-sm italic text-gray-500 dark:text-gray-400">
                                        No treatments recorded for this consultation.
                                    </div>

                                @endif

                            </section>


                            {{-- =====================================
                                 BILLING
                            ====================================== --}}
                            <section class="overflow-hidden rounded-2xl border border-emerald-200 dark:border-emerald-900/50">

                                <div class="bg-emerald-50 px-5 py-4 dark:bg-emerald-900/20">

                                    <div class="flex items-center justify-between">

                                        <div>

                                            <h3 class="font-bold text-emerald-900 dark:text-emerald-200">
                                                Billing Summary
                                            </h3>

                                            <p class="mt-1 text-xs text-emerald-700 dark:text-emerald-300">
                                                Complete payment information for this visit
                                            </p>

                                        </div>

                                        @if($bill)

                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                                                {{ $paymentStatus }}
                                            </span>

                                        @endif

                                    </div>

                                </div>


                                @if($bill)

                                    <div class="grid grid-cols-2 gap-px bg-gray-200 sm:grid-cols-3 lg:grid-cols-6 dark:bg-gray-700">

                                        <div class="bg-white p-4 dark:bg-gray-900">

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Doctor Fee
                                            </div>

                                            <div class="mt-2 text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $currency }} {{ $this->money($doctorFee) }}
                                            </div>

                                        </div>


                                        <div class="bg-white p-4 dark:bg-gray-900">

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Treatments
                                            </div>

                                            <div class="mt-2 text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $currency }} {{ $this->money($treatmentTotal) }}
                                            </div>

                                        </div>


                                        <div class="bg-white p-4 dark:bg-gray-900">

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Medicines
                                            </div>

                                            <div class="mt-2 text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $currency }} {{ $this->money($medicineTotal) }}
                                            </div>

                                        </div>


                                        <div class="bg-white p-4 dark:bg-gray-900">

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Grand Total
                                            </div>

                                            <div class="mt-2 text-sm font-black text-emerald-700 dark:text-emerald-400">
                                                {{ $currency }} {{ $this->money($grandTotal) }}
                                            </div>

                                        </div>


                                        <div class="bg-white p-4 dark:bg-gray-900">

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Appointment Paid
                                            </div>

                                            <div class="mt-2 text-sm font-bold text-blue-700 dark:text-blue-400">
                                                {{ $currency }} {{ $this->money($appointmentPaid) }}
                                            </div>

                                        </div>


                                        <div class="bg-white p-4 dark:bg-gray-900">

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Balance Due
                                            </div>

                                            <div class="mt-2 text-sm font-black text-red-600 dark:text-red-400">
                                                {{ $currency }} {{ $this->money($balanceDue) }}
                                            </div>

                                        </div>

                                    </div>


                                    <div class="flex flex-col gap-3 border-t border-emerald-100 bg-emerald-50 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-emerald-900/50 dark:bg-emerald-900/10">

                                        <div>

                                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                                Bill Number
                                            </div>

                                            <div class="mt-1 text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $this->getBillNumber($bill) }}
                                            </div>

                                        </div>


                                        <div class="text-left sm:text-right">

                                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                                Payment Status
                                            </div>

                                            <div class="mt-1 text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $paymentStatus }}
                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <div class="p-7 text-center">

                                        <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                            No bill has been generated for this consultation.
                                        </div>

                                    </div>

                                @endif

                            </section>


                            {{-- =====================================
                                 CLOSE BUTTON
                            ====================================== --}}
                            <div class="flex justify-end border-t border-gray-200 pt-5 dark:border-gray-700">

                                <button
                                    type="button"
                                    @click="openHistory = null"
                                    class="rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                >
                                    Close Details
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</x-filament-panels::page>