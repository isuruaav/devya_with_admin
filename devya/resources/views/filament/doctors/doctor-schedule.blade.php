<div class="space-y-5">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex items-center gap-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 p-5 text-white shadow-lg">

        {{-- Doctor Icon --}}
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white/20">
            <svg
                class="h-8 w-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.686 0 5.17.755 7.121 2.05M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                />
            </svg>
        </div>

        <div class="min-w-0">
            <h2 class="truncate text-xl font-bold">
                {{ $doctor->name }}
            </h2>

            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-emerald-50">
                @if($doctor->specialization)
                    <span>
                        {{ $doctor->specialization }}
                    </span>
                @endif

                @if($doctor->room_number)
                    <span>•</span>
                    <span>{{ $doctor->room_number }}</span>
                @endif
            </div>
        </div>

    </div>


    {{-- =========================================================
        WEEKLY SCHEDULE
    ========================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Section Header --}}
        <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>

                </div>

                <div>
                    <h3 class="font-semibold text-gray-900">
                        Weekly Schedule
                    </h3>

                    <p class="text-xs text-gray-500">
                        Regular working days and consultation times
                    </p>
                </div>

            </div>

        </div>


        @php
            $days = [
                'monday' => 'Monday',
                'tuesday' => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
                'saturday' => 'Saturday',
                'sunday' => 'Sunday',
            ];
        @endphp


        <div class="divide-y divide-gray-100">

            @foreach($days as $dayKey => $dayName)

                @php
                    $schedule = $doctor->weeklySchedules
                        ->firstWhere('day_of_week', $dayKey);
                @endphp


                <div class="flex items-center justify-between gap-4 px-5 py-4">

                    {{-- Day --}}
                    <div class="flex min-w-0 items-center gap-3">

                        @if($schedule && $schedule->is_available)

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">

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
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                            </div>

                        @else

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-400">

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
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>

                            </div>

                        @endif


                        <div>
                            <div class="font-medium text-gray-900">
                                {{ $dayName }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ ucfirst($dayKey) }}
                            </div>
                        </div>

                    </div>


                    {{-- Time / Status --}}
                    <div class="shrink-0 text-right">

                        @if($schedule && $schedule->is_available)

                            <div class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">

                                {{ \Illuminate\Support\Str::of($schedule->start_time)->substr(0, 5) }}

                                <span class="mx-1 text-emerald-400">
                                    –
                                </span>

                                {{ \Illuminate\Support\Str::of($schedule->end_time)->substr(0, 5) }}

                            </div>

                            <div class="mt-1 text-xs font-medium text-emerald-600">
                                Available
                            </div>

                        @else

                            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-semibold text-gray-500">
                                Closed
                            </span>

                            <div class="mt-1 text-xs text-gray-400">
                                Not Available
                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    </div>


    {{-- =========================================================
        DATE SPECIFIC SCHEDULES
    ========================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Section Header --}}
        <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

                <div>
                    <h3 class="font-semibold text-gray-900">
                        Date-specific Schedule
                    </h3>

                    <p class="text-xs text-gray-500">
                        Leave, temporary changes and special working times
                    </p>
                </div>

            </div>

        </div>


        @php
            $specialSchedules = $doctor->schedules()
                ->whereDate(
                    'schedule_date',
                    '>=',
                    now()->toDateString()
                )
                ->orderBy('schedule_date')
                ->get();
        @endphp


        @if($specialSchedules->isEmpty())

            {{-- Empty State --}}
            <div class="px-5 py-8 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">

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
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>

                </div>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No special schedules
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    No upcoming leave or date-specific changes have been added.
                </p>

            </div>

        @else

            <div class="divide-y divide-gray-100">

                @foreach($specialSchedules as $special)

                    @php
                        $statusClasses = match($special->status) {
                            'available' => 'bg-emerald-50 text-emerald-700',
                            'on_leave' => 'bg-red-50 text-red-700',
                            'temporarily_unavailable' => 'bg-amber-50 text-amber-700',
                            default => 'bg-gray-100 text-gray-600',
                        };

                        $statusLabel = match($special->status) {
                            'available' => 'Available',
                            'on_leave' => 'On Leave',
                            'temporarily_unavailable' => 'Temporarily Unavailable',
                            default => ucfirst(str_replace('_', ' ', $special->status)),
                        };
                    @endphp


                    <div class="px-5 py-4">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                            {{-- Date --}}
                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>

                                </div>


                                <div>

                                    <div class="font-semibold text-gray-900">
                                        {{ $special->schedule_date->format('d M Y') }}
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        {{ $special->schedule_date->format('l') }}
                                    </div>

                                </div>

                            </div>


                            {{-- Status --}}
                            <div class="flex flex-wrap items-center gap-2">

                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>


                                @if(
                                    $special->status === 'available'
                                    &&
                                    ($special->start_time || $special->end_time)
                                )

                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">

                                        {{ $special->start_time
                                            ? \Illuminate\Support\Str::of($special->start_time)->substr(0, 5)
                                            : '--'
                                        }}

                                        <span class="mx-1 text-blue-400">
                                            –
                                        </span>

                                        {{ $special->end_time
                                            ? \Illuminate\Support\Str::of($special->end_time)->substr(0, 5)
                                            : '--'
                                        }}

                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Note --}}
                        @if($special->note)

                            <div class="mt-3 rounded-xl bg-gray-50 px-4 py-3">

                                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    Note
                                </div>

                                <div class="mt-1 text-sm text-gray-700">
                                    {{ $special->note }}
                                </div>

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        @endif

    </div>


    {{-- =========================================================
        QUICK INFORMATION
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

        {{-- Status --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

            <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Doctor Status
            </div>

            <div class="mt-2">

                @if($doctor->is_active && $doctor->availability_status === 'available')

                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">
                        Active & Available
                    </span>

                @elseif(!$doctor->is_active)

                    <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-sm font-semibold text-red-700">
                        Inactive
                    </span>

                @else

                    <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-sm font-semibold text-amber-700">
                        {{ ucfirst(str_replace('_', ' ', $doctor->availability_status)) }}
                    </span>

                @endif

            </div>

        </div>


        {{-- Room --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

            <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Consulting Room
            </div>

            <div class="mt-2 text-lg font-bold text-gray-900">
                {{ $doctor->room_number ?: 'Not Assigned' }}
            </div>

        </div>


        {{-- Daily Capacity --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

            <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Daily Patient Limit
            </div>

            <div class="mt-2 text-lg font-bold text-gray-900">
                {{ $doctor->max_patients_per_day ?: 'Unlimited' }}
            </div>

        </div>

    </div>

</div>
