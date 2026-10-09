<x-filament-panels::page class="dashboard-page">

    @php
        $stats = $this->getStats();
    @endphp


    {{-- ========================================================= --}}
    {{-- DOCTOR DASHBOARD --}}
    {{-- ========================================================= --}}

    @if ($this->isDoctor())

        @php
            $doctor = $this->getDoctor();

            $calendarDays = $this->getDoctorCalendarDays();

            $filteredAppointments =
                $this->getDoctorSelectedDateAppointments();

            $summary =
                $this->getDoctorFilteredSummary();
        @endphp


        <div class="admin-records-header dashboard-doctor-header">
            <div class="min-w-0">
                <h2 class="font-semibold">Dr. {{ $doctor?->name ?? 'Doctor' }}</h2>
                @if ($doctor?->specialization)
                    <p>{{ $doctor->specialization }}</p>
                @endif
            </div>
            <time class="text-sm text-gray-500 dark:text-gray-400" datetime="{{ now()->toDateString() }}">
                {{ now()->format('l, d M Y') }}
            </time>
        </div>

        {{-- ===================================================== --}}
        {{-- MAIN TODAY STATS --}}
        {{-- ===================================================== --}}

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

            {{-- Patients --}}

            <div class="dashboard-stat">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-sky-700">
                            My Patients
                        </p>

                        <p class="mt-2 text-3xl font-bold text-sky-900">
                            {{ $this->getMyPatientCount() }}
                        </p>

                    </div>

                    <div class="rounded-xl bg-sky-100 p-3">

                        <x-heroicon-o-users class="h-7 w-7 text-sky-600" />

                    </div>

                </div>

            </div>


            {{-- Today appointments --}}

            <div class="dashboard-stat">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-emerald-700">
                            Today's Appointments
                        </p>

                        <p class="mt-2 text-3xl font-bold text-emerald-900">
                            {{ $this->getTodayAppointmentCount() }}
                        </p>

                    </div>

                    <div class="rounded-xl bg-emerald-100 p-3">

                        <x-heroicon-o-calendar-days class="h-7 w-7 text-emerald-600" />

                    </div>

                </div>

            </div>


            {{-- Pending --}}

            <div class="dashboard-stat">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-amber-700">
                            Waiting
                        </p>

                        <p class="mt-2 text-3xl font-bold text-amber-900">
                            {{ $this->getPendingAppointmentCount() }}
                        </p>

                    </div>

                    <div class="rounded-xl bg-amber-100 p-3">

                        <x-heroicon-o-clock class="h-7 w-7 text-amber-600" />

                    </div>

                </div>

            </div>


            {{-- Seen --}}

            <div class="dashboard-stat">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-violet-700">
                            Seen Today
                        </p>

                        <p class="mt-2 text-3xl font-bold text-violet-900">
                            {{ $this->getSeenAppointmentCount() }}
                        </p>

                    </div>

                    <div class="rounded-xl bg-violet-100 p-3">

                        <x-heroicon-o-check-circle class="h-7 w-7 text-violet-600" />

                    </div>

                </div>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- SECONDARY STATS --}}
        {{-- ===================================================== --}}

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div class="dashboard-stat">

                <div class="flex items-center gap-4">

                    <div class="rounded-xl bg-blue-100 p-3">

                        <x-heroicon-o-user class="h-6 w-6 text-blue-600" />

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Local Patients Today
                        </p>

                        <p class="text-2xl font-bold text-gray-900">
                            {{ $this->getLocalPatientCount() }}
                        </p>

                    </div>

                </div>

            </div>


            <div class="dashboard-stat">

                <div class="flex items-center gap-4">

                    <div class="rounded-xl bg-purple-100 p-3">

                        <x-heroicon-o-globe-alt class="h-6 w-6 text-purple-600" />

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Foreign Patients Today
                        </p>

                        <p class="text-2xl font-bold text-gray-900">
                            {{ $this->getForeignPatientCount() }}
                        </p>

                    </div>

                </div>

            </div>


            <div class="dashboard-stat">

                <div class="flex items-center gap-4">

                    <div class="rounded-xl bg-green-100 p-3">

                        <x-heroicon-o-banknotes class="h-6 w-6 text-green-600" />

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Paid Appointments
                        </p>

                        <p class="text-2xl font-bold text-gray-900">
                            {{ $this->getPaidAppointmentCount() }}
                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- CALENDAR + FILTERS --}}
        {{-- ===================================================== --}}

        <div class="dashboard-calendar bg-white">

            {{-- Header --}}

            <div class="dashboard-light-panel border-b border-gray-200 p-5">

                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                    <div>

                        <div class="flex items-center gap-2">

                            <div class="rounded-xl bg-emerald-100 p-2">

                                <x-heroicon-o-calendar-days class="h-6 w-6 text-emerald-600" />

                            </div>

                            <div>

                                <h2 class="text-xl font-bold text-gray-900">

                                    Appointment Calendar

                                </h2>

                                <p class="text-sm text-gray-500">

                                    View and filter your appointments

                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="flex items-center gap-2">

                        <button
                            type="button"
                            wire:click="previousDoctorMonth"
                            class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                        >

                            <x-heroicon-o-chevron-left class="h-5 w-5" />

                        </button>


                        <button
                            type="button"
                            wire:click="todayDoctorCalendar"
                            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                        >

                            Today

                        </button>


                        <button
                            type="button"
                            wire:click="nextDoctorMonth"
                            class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                        >

                            <x-heroicon-o-chevron-right class="h-5 w-5" />

                        </button>

                    </div>

                </div>

            </div>



            {{-- Calendar --}}

            <div class="p-5">

                <div class="mb-4 flex items-center justify-center">

                    <h3 class="text-2xl font-bold text-gray-900">

                        {{ $this->getDoctorCalendarMonthTitle() }}

                    </h3>

                </div>


                {{-- Weekdays --}}

                <div class="grid grid-cols-7 border-b border-gray-200">

                    @foreach([
                        'Mon',
                        'Tue',
                        'Wed',
                        'Thu',
                        'Fri',
                        'Sat',
                        'Sun'
                    ] as $weekday)

                        <div class="px-2 py-3 text-center text-xs font-bold uppercase tracking-wide text-gray-500">

                            {{ $weekday }}

                        </div>

                    @endforeach

                </div>


                {{-- Days --}}

                <div class="grid grid-cols-7 border-l border-t border-gray-200">

                    @foreach($calendarDays as $day)

                        <button
                            type="button"
                            wire:click="selectDoctorCalendarDate('{{ $day['date'] }}')"
                            class="
                                relative
                                min-h-[105px]
                                border-b
                                border-r
                                border-gray-200
                                p-2
                                text-left
                                transition
                                hover:bg-emerald-50
                                {{ $day['isCurrentMonth'] ? 'bg-white' : 'bg-gray-50' }}
                                {{ $day['isSelected'] ? 'ring-2 ring-inset ring-emerald-500 bg-emerald-50' : '' }}
                            "
                        >

                            <div class="flex items-center justify-between">

                                <span
                                    class="
                                        flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold
                                        {{ $day['isToday'] ? 'bg-emerald-600 text-white' : ($day['isCurrentMonth'] ? 'text-gray-800' : 'text-gray-400') }}
                                    "
                                >

                                    {{ $day['day'] }}

                                </span>


                                @if($day['count'] > 0)

                                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-700">

                                        {{ $day['count'] }}

                                    </span>

                                @endif

                            </div>


                            @if($day['count'] > 0)

                                <div class="mt-3 space-y-1">

                                    @if($day['seen'] > 0)

                                        <div class="text-[11px] font-medium text-violet-600">

                                            {{ $day['seen'] }} Seen

                                        </div>

                                    @endif


                                    @if($day['waiting'] > 0)

                                        <div class="text-[11px] font-medium text-amber-600">

                                            {{ $day['waiting'] }} Waiting

                                        </div>

                                    @endif


                                    @if($day['paid'] > 0)

                                        <div class="text-[11px] font-medium text-emerald-600">

                                            {{ $day['paid'] }} Paid

                                        </div>

                                    @endif

                                </div>

                            @else

                                <div class="mt-5 text-[11px] text-gray-400">

                                    No appointments

                                </div>

                            @endif

                        </button>

                    @endforeach

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- FILTER PANEL --}}
            {{-- ================================================= --}}

            <div class="border-t border-gray-200 bg-gray-50 p-5">

                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="font-bold text-gray-900">

                            Appointment Filters

                        </h3>

                        <p class="text-xs text-gray-500">

                            Filter appointments without leaving the dashboard.

                        </p>

                    </div>


                    <button
                        type="button"
                        wire:click="resetDoctorFilters"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-100"
                    >

                        <x-heroicon-o-arrow-path class="h-4 w-4" />

                        Reset Filters

                    </button>

                </div>



                {{-- Quick filters --}}

                <div class="mb-5 flex flex-wrap gap-2">

                    <button
                        type="button"
                        wire:click="doctorFilterToday"
                        class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                    >
                        Today
                    </button>


                    <button
                        type="button"
                        wire:click="doctorFilterTomorrow"
                        class="rounded-xl bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700"
                    >
                        Tomorrow
                    </button>


                    <button
                        type="button"
                        wire:click="doctorFilterThisWeek"
                        class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        This Week
                    </button>


                    <button
                        type="button"
                        wire:click="doctorFilterThisMonth"
                        class="rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700"
                    >
                        This Month
                    </button>

                </div>



                {{-- Filters --}}

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">

                    {{-- Date From --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Date From
                        </label>

                        <input
                            type="date"
                            wire:model.live="doctorDateFrom"
                            class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                    </div>


                    {{-- Date To --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Date To
                        </label>

                        <input
                            type="date"
                            wire:model.live="doctorDateTo"
                            class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                    </div>


                    {{-- Patient Type --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Patient Type
                        </label>

                        <select
                            wire:model.live="doctorPatientTypeFilter"
                            class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                            <option value="all">
                                All Patients
                            </option>

                            <option value="Local">
                                Local
                            </option>

                            <option value="Foreign">
                                Foreign
                            </option>

                        </select>

                    </div>


                    {{-- Payment --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Payment
                        </label>

                        <select
                            wire:model.live="doctorPaymentFilter"
                            class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                            <option value="all">
                                All Payments
                            </option>

                            <option value="paid">
                                Paid
                            </option>

                            <option value="unpaid">
                                Unpaid
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Appointment Status
                        </label>

                        <select
                            wire:model.live="doctorStatusFilter"
                            class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                            <option value="all">
                                All
                            </option>

                            <option value="waiting">
                                Waiting
                            </option>

                            <option value="seen">
                                Seen
                            </option>

                        </select>

                    </div>


                    {{-- Time --}}

                    <div>

                        <label class="mb-1 block text-xs font-semibold text-gray-600">
                            Time
                        </label>

                        <select
                            wire:model.live="doctorTimeFilter"
                            class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                            <option value="all">
                                All Times
                            </option>

                            <option value="morning">
                                Morning
                            </option>

                            <option value="afternoon">
                                Afternoon
                            </option>

                            <option value="evening">
                                Evening
                            </option>

                        </select>

                    </div>

                </div>



                {{-- Search --}}

                <div class="mt-4">

                    <label class="mb-1 block text-xs font-semibold text-gray-600">

                        Search Patient / Phone / Token

                    </label>

                    <div class="relative">

                        <x-heroicon-o-magnifying-glass
                            class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                        />

                        <input
                            type="text"
                            wire:model.live.debounce.400ms="doctorSearch"
                            placeholder="Search patient name, phone number or token..."
                            class="w-full rounded-xl border-gray-300 bg-white py-3 pl-10 pr-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- FILTER SUMMARY --}}
            {{-- ================================================= --}}

            <div class="border-t border-gray-200 p-5">

                <div class="mb-4">

                    <h3 class="text-lg font-bold text-gray-900">

                        Appointment Summary

                    </h3>

                    <p class="text-sm text-gray-500">

                        {{ $this->getDoctorSelectedDateLabel() }}

                    </p>

                </div>


                <div class="grid grid-cols-2 gap-3 md:grid-cols-4 xl:grid-cols-7">

                    <div class="dashboard-stat dashboard-stat-compact">

                        <p class="text-xs text-gray-500">
                            Total
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900">
                            {{ $summary['total'] }}
                        </p>

                    </div>


                    <div class="dashboard-stat dashboard-stat-compact">

                        <p class="text-xs text-amber-700">
                            Waiting
                        </p>

                        <p class="mt-1 text-2xl font-bold text-amber-900">
                            {{ $summary['waiting'] }}
                        </p>

                    </div>


                    <div class="dashboard-stat dashboard-stat-compact">

                        <p class="text-xs text-violet-700">
                            Seen
                        </p>

                        <p class="mt-1 text-2xl font-bold text-violet-900">
                            {{ $summary['seen'] }}
                        </p>

                    </div>


                    <div class="dashboard-stat dashboard-stat-compact">

                        <p class="text-xs text-emerald-700">
                            Paid
                        </p>

                        <p class="mt-1 text-2xl font-bold text-emerald-900">
                            {{ $summary['paid'] }}
                        </p>

                    </div>


                    <div class="dashboard-stat dashboard-stat-compact">

                        <p class="text-xs text-red-700">
                            Unpaid
                        </p>

                        <p class="mt-1 text-2xl font-bold text-red-900">
                            {{ $summary['unpaid'] }}
                        </p>

                    </div>


                    <div class="dashboard-stat dashboard-stat-compact">

                        <p class="text-xs text-blue-700">
                            Local
                        </p>

                        <p class="mt-1 text-2xl font-bold text-blue-900">
                            {{ $summary['local'] }}
                        </p>

                    </div>


                    <div class="dashboard-stat dashboard-stat-compact">

                        <p class="text-xs text-purple-700">
                            Foreign
                        </p>

                        <p class="mt-1 text-2xl font-bold text-purple-900">
                            {{ $summary['foreign'] }}
                        </p>

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- APPOINTMENT TABLE --}}
            {{-- ================================================= --}}

            <div class="border-t border-gray-200">

                <div class="flex flex-col gap-2 border-b border-gray-200 bg-gray-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="font-bold text-gray-900">

                            Appointments

                        </h3>

                        <p class="text-xs text-gray-500">

                            {{ $filteredAppointments->count() }}
                            appointment(s) found

                        </p>

                    </div>

                </div>


                @if($filteredAppointments->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Time
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Token
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Patient
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Type
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Payment
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Status
                                    </th>

                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100 bg-white">

                                @foreach($filteredAppointments as $appointment)

                                    @php

                                        $isSeen =
                                            filled(
                                                $appointment->consultation_id
                                            );

                                        $patientType =
                                            $appointment->patient_type
                                            ?? $appointment->patient?->patient_type
                                            ?? 'Local';

                                    @endphp

                                    <tr class="transition hover:bg-emerald-50/40">

                                        {{-- Time --}}

                                        <td class="whitespace-nowrap px-5 py-4">

                                            <div class="font-semibold text-gray-900">

                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}

                                            </div>

                                            <div class="text-xs text-gray-400">

                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}

                                            </div>

                                        </td>


                                        {{-- Token --}}

                                        <td class="whitespace-nowrap px-5 py-4">

                                            <span class="inline-flex rounded-lg bg-slate-100 px-3 py-1 text-sm font-bold text-slate-700">

                                                {{ $appointment->token_number ?? '-' }}

                                            </span>

                                        </td>


                                        {{-- Patient --}}

                                        <td class="px-5 py-4">

                                            <div class="font-semibold text-gray-900">

                                                {{ $appointment->patient?->full_name ?? $appointment->full_name ?? 'Unknown Patient' }}

                                            </div>

                                            <div class="text-xs text-gray-500">

                                                {{ $appointment->phone_number ?? '-' }}

                                            </div>

                                        </td>


                                        {{-- Patient type --}}

                                        <td class="whitespace-nowrap px-5 py-4">

                                            @if(strcasecmp($patientType, 'Foreign') === 0)

                                                <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-700">

                                                    Foreign

                                                </span>

                                            @else

                                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">

                                                    Local

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Payment --}}

                                        <td class="whitespace-nowrap px-5 py-4">

                                            @if($appointment->payment_status === 'paid')

                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">

                                                    <x-heroicon-o-check-circle class="h-4 w-4" />

                                                    Paid

                                                </span>

                                            @else

                                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">

                                                    <x-heroicon-o-x-circle class="h-4 w-4" />

                                                    Unpaid

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Status --}}

                                        <td class="whitespace-nowrap px-5 py-4">

                                            @if($isSeen)

                                                <span class="inline-flex items-center gap-1 rounded-full bg-violet-100 px-3 py-1 text-xs font-bold text-violet-700">

                                                    <x-heroicon-o-check class="h-4 w-4" />

                                                    Seen

                                                </span>

                                            @else

                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">

                                                    <x-heroicon-o-clock class="h-4 w-4" />

                                                    Waiting

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Action --}}

                                        <td class="whitespace-nowrap px-5 py-4 text-right">

                                            @if($appointment->consultation_id)

                                                <a
                                                    href="{{ url('/admin/consultations/' . $appointment->consultation_id . '/edit') }}"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-700"
                                                >

                                                    <x-heroicon-o-eye class="h-4 w-4" />

                                                    View Consultation

                                                </a>

                                            @elseif($appointment->payment_status === 'paid')

                                                <a
                                                    href="{{ url('/admin/consultations/create?appointment_id=' . $appointment->id) }}"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700"
                                                >

                                                    <x-heroicon-o-plus-circle class="h-4 w-4" />

                                                    Start Consultation

                                                </a>

                                            @else

                                                <span class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-500">

                                                    <x-heroicon-o-clock class="h-4 w-4" />

                                                    Payment Pending

                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="px-6 py-14 text-center">

                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">

                            <x-heroicon-o-calendar-days class="h-8 w-8 text-gray-400" />

                        </div>

                        <h3 class="text-lg font-bold text-gray-900">

                            No Appointments Found

                        </h3>

                        <p class="mt-1 text-sm text-gray-500">

                            Try changing the date or filters.

                        </p>

                    </div>

                @endif

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- DOCTOR FOOTER --}}
        {{-- ===================================================== --}}

        <div class="dashboard-light-panel p-5">

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>

                    <p class="text-sm font-semibold text-emerald-700">

                        Doctor

                    </p>

                    <p class="text-lg font-bold text-gray-900">

                        Dr. {{ $doctor?->name ?? 'Doctor' }}

                    </p>

                    @if($doctor?->specialization)

                        <p class="text-sm text-gray-500">

                            {{ $doctor->specialization }}

                        </p>

                    @endif

                </div>


                <div class="flex flex-wrap gap-3">

                    <div class="rounded-xl bg-white px-4 py-3 shadow-sm">

                        <span class="text-xs text-gray-500">
                            Today
                        </span>

                        <span class="ml-2 font-bold text-emerald-700">
                            {{ $this->getTodayAppointmentCount() }}
                        </span>

                    </div>


                    <div class="rounded-xl bg-white px-4 py-3 shadow-sm">

                        <span class="text-xs text-gray-500">
                            Seen
                        </span>

                        <span class="ml-2 font-bold text-violet-700">
                            {{ $this->getSeenAppointmentCount() }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


    @else

        {{-- ===================================================== --}}
        {{-- ADMIN / SUPER ADMIN / RECEPTION DASHBOARD --}}
        {{-- ===================================================== --}}

        {{--
            IMPORTANT:
            මෙතන ඔබගේ දැනට තිබෙන Admin / Reception Dashboard
            section එක 그대로 තබන්න.
        --}}

        <div class="space-y-6">

            <div class="rounded-3xl bg-gradient-to-r from-emerald-700 to-teal-600 p-6 shadow-xl">

                <h1 class="text-3xl font-bold text-white">
                    AYU SYSTEM Dashboard
                </h1>

                <p class="mt-2 text-emerald-100">
                    Welcome to the Ayu System Management Dashboard
                </p>

            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm text-gray-500">
                        Total Patients
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $stats['patients'] }}
                    </p>

                </div>


                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm text-gray-500">
                        Today's Appointments
                    </p>

                    <p class="mt-2 text-3xl font-bold text-emerald-700">
                        {{ $stats['today_appointments'] }}
                    </p>

                </div>


                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm text-gray-500">
                        Today's Consultations
                    </p>

                    <p class="mt-2 text-3xl font-bold text-violet-700">
                        {{ $stats['today_consultations'] }}
                    </p>

                </div>


                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                    <p class="text-sm text-gray-500">
                        Waiting Queue
                    </p>

                    <p class="mt-2 text-3xl font-bold text-amber-700">
                        {{ $stats['waiting_queue'] }}
                    </p>

                </div>

            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                    <h2 class="text-lg font-bold text-gray-900">
                        Today's Income
                    </h2>

                    <div class="mt-4 grid grid-cols-2 gap-4">

                        <div class="rounded-xl bg-emerald-50 p-4">

                            <p class="text-xs text-emerald-700">
                                LKR
                            </p>

                            <p class="mt-1 text-2xl font-bold text-emerald-900">

                                {{ number_format($stats['today_income']['LKR'], 2) }}

                            </p>

                        </div>


                        <div class="rounded-xl bg-blue-50 p-4">

                            <p class="text-xs text-blue-700">
                                USD
                            </p>

                            <p class="mt-1 text-2xl font-bold text-blue-900">

                                {{ number_format($stats['today_income']['USD'], 2) }}

                            </p>

                        </div>

                    </div>

                </div>


                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                    <h2 class="text-lg font-bold text-gray-900">
                        Pending Balance
                    </h2>

                    <div class="mt-4 grid grid-cols-2 gap-4">

                        <div class="rounded-xl bg-amber-50 p-4">

                            <p class="text-xs text-amber-700">
                                LKR
                            </p>

                            <p class="mt-1 text-2xl font-bold text-amber-900">

                                {{ number_format($stats['pending_balance']['LKR'], 2) }}

                            </p>

                        </div>


                        <div class="rounded-xl bg-red-50 p-4">

                            <p class="text-xs text-red-700">
                                USD
                            </p>

                            <p class="mt-1 text-2xl font-bold text-red-900">

                                {{ number_format($stats['pending_balance']['USD'], 2) }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</x-filament-panels::page>
