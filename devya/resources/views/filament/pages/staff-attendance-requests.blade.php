<x-filament-panels::page>

    @php
        $pendingRequests = \App\Models\StaffAttendanceRequest::query()
            ->with(['staff.department'])
            ->where('status', 'Pending')
            ->orderByDesc('requested_at')
            ->get();

        $pendingCount = \App\Models\StaffAttendanceRequest::query()
            ->where('status', 'Pending')
            ->count();

        $approvedTodayCount = \App\Models\StaffAttendanceRequest::query()
            ->where('status', 'Approved')
            ->whereDate('approved_at', today())
            ->count();

        $rejectedTodayCount = \App\Models\StaffAttendanceRequest::query()
            ->where('status', 'Rejected')
            ->whereDate('approved_at', today())
            ->count();
    @endphp


    <div class="w-full space-y-5">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="w-full rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div class="min-w-0">
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                        Attendance Requests
                    </h1>

                    <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                        Review and approve manual NIC / Passport attendance requests.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="window.location.reload()"
                    class="flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M23 4v6h-6" />
                        <path d="M1 20v-6h6" />
                        <path d="M3.51 9a9 9 0 0114.85-3.36L23 10" />
                        <path d="M1 14l4.64 4.36A9 9 0 0020.49 15" />
                    </svg>

                    Refresh
                </button>

            </div>

        </div>


        {{-- =========================================================
             STATS
        ========================================================== --}}
        <div class="grid w-full grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- Pending --}}
            <div class="flex w-full items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/40 dark:bg-amber-950/20">

                <div class="min-w-0">

                    <p class="text-xs font-extrabold uppercase tracking-wide text-amber-800 dark:text-amber-400">
                        Pending Requests
                    </p>

                    <p class="mt-1.5 text-3xl font-extrabold text-amber-900 dark:text-amber-300">
                        {{ $pendingCount }}
                    </p>

                    <p class="mt-1.5 text-xs text-amber-700/75 dark:text-amber-400/70">
                        Waiting for approval
                    </p>

                </div>

                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm">

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        />

                        <path d="M12 7v5l3 2" />
                    </svg>

                </span>

            </div>


            {{-- Approved --}}
            <div class="flex w-full items-center justify-between gap-4 rounded-2xl border border-green-200 bg-green-50 p-5 dark:border-green-900/40 dark:bg-green-950/20">

                <div class="min-w-0">

                    <p class="text-xs font-extrabold uppercase tracking-wide text-green-800 dark:text-green-400">
                        Approved Today
                    </p>

                    <p class="mt-1.5 text-3xl font-extrabold text-green-900 dark:text-green-300">
                        {{ $approvedTodayCount }}
                    </p>

                    <p class="mt-1.5 text-xs text-green-700/75 dark:text-green-400/70">
                        Successfully approved
                    </p>

                </div>

                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-600 text-white shadow-sm">

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </span>

            </div>


            {{-- Rejected --}}
            <div class="flex w-full items-center justify-between gap-4 rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-900/40 dark:bg-red-950/20">

                <div class="min-w-0">

                    <p class="text-xs font-extrabold uppercase tracking-wide text-red-800 dark:text-red-400">
                        Rejected Today
                    </p>

                    <p class="mt-1.5 text-3xl font-extrabold text-red-900 dark:text-red-300">
                        {{ $rejectedTodayCount }}
                    </p>

                    <p class="mt-1.5 text-xs text-red-700/75 dark:text-red-400/70">
                        Requests rejected
                    </p>

                </div>

                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-600 text-white shadow-sm">

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </span>

            </div>

        </div>


        {{-- =========================================================
             PENDING REQUESTS TABLE
        ========================================================== --}}
        <div class="w-full overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">

            <div class="flex flex-col gap-3 border-b border-gray-100 p-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">

                <div class="min-w-0">

                    <h2 class="text-base font-extrabold text-gray-900 dark:text-white">
                        Pending Attendance Requests
                    </h2>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Manual attendance requests waiting for administrator approval.
                    </p>

                </div>

                <div class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-extrabold text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">

                    <span class="h-2 w-2 shrink-0 rounded-full bg-amber-500"></span>

                    {{ $pendingCount }} Pending

                </div>

            </div>


            {{-- EMPTY STATE --}}
            @if ($pendingRequests->isEmpty())

                <div class="flex flex-col items-center px-6 py-20 text-center">

                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">

                        <svg
                            width="28"
                            height="28"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                    <h3 class="mt-4 text-lg font-extrabold text-gray-900 dark:text-white">
                        No Pending Requests
                    </h3>

                    <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                        There are currently no manual attendance requests waiting for approval.
                    </p>

                </div>

            @else

                {{-- FULL WIDTH TABLE --}}
                <div class="w-full overflow-x-auto">

                    <table class="w-full min-w-[1100px] border-collapse">

                        <thead>

                            <tr class="bg-gray-50 dark:bg-gray-800/60">

                                <th class="border-b border-gray-200 px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    Staff
                                </th>

                                <th class="border-b border-gray-200 px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    Department
                                </th>

                                <th class="border-b border-gray-200 px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    NIC / Passport
                                </th>

                                <th class="border-b border-gray-200 px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    Attendance
                                </th>

                                <th class="border-b border-gray-200 px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    Requested
                                </th>

                                <th class="border-b border-gray-200 px-4 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($pendingRequests as $request)

                                <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40">

                                    {{-- Staff --}}
                                    <td class="border-b border-gray-100 px-4 py-4 align-middle dark:border-gray-800">

                                        <div class="flex items-center gap-3">

                                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-orange-600 text-sm font-extrabold text-white shadow-sm">
                                                {{ strtoupper(substr($request->staff?->full_name ?? 'S', 0, 1)) }}
                                            </span>

                                            <div class="min-w-0">

                                                <p class="truncate text-sm font-extrabold text-gray-900 dark:text-white">
                                                    {{ $request->staff?->full_name ?? 'Unknown Staff' }}
                                                </p>

                                                <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $request->staff?->staff_code ?? '-' }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Department --}}
                                    <td class="border-b border-gray-100 px-4 py-4 align-middle dark:border-gray-800">

                                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                            {{ $request->staff?->department?->name ?? 'Not Assigned' }}
                                        </p>

                                        @if ($request->staff?->department?->code)

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $request->staff->department->code }}
                                            </p>

                                        @endif

                                    </td>


                                    {{-- NIC / Passport --}}
                                    <td class="border-b border-gray-100 px-4 py-4 align-middle dark:border-gray-800">

                                        <span class="inline-block rounded-lg bg-gray-100 px-2.5 py-1.5 font-mono text-xs font-bold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                            {{ $request->staff?->nic_passport ?? '-' }}
                                        </span>

                                    </td>


                                    {{-- Attendance --}}
                                    <td class="border-b border-gray-100 px-4 py-4 align-middle dark:border-gray-800">

                                        @if ($request->attendance_type === 'IN')

                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-extrabold text-green-700 dark:bg-green-900/30 dark:text-green-400">

                                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-green-500"></span>

                                                IN

                                            </span>

                                        @else

                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-xs font-extrabold text-red-700 dark:bg-red-900/30 dark:text-red-400">

                                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>

                                                OUT

                                            </span>

                                        @endif

                                        <p class="mt-1.5 text-[11px] text-gray-400">
                                            Method: {{ $request->method ?? 'NIC' }}
                                        </p>

                                    </td>


                                    {{-- Requested --}}
                                    <td class="border-b border-gray-100 px-4 py-4 align-middle dark:border-gray-800">

                                        <p class="text-sm font-extrabold text-gray-900 dark:text-white">
                                            {{ $request->requested_at?->format('d M Y') }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $request->requested_at?->format('h:i A') }}
                                        </p>

                                        @if ($request->device_ip)

                                            <p class="mt-1 text-[11px] text-gray-400">
                                                IP: {{ $request->device_ip }}
                                            </p>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="border-b border-gray-100 px-4 py-4 text-right align-middle dark:border-gray-800">

                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Approve --}}
                                            <button
                                                type="button"
                                                wire:click="approve({{ $request->id }})"
                                                wire:confirm="Are you sure you want to approve this attendance request?"
                                                class="flex items-center gap-1.5 rounded-lg bg-green-600 px-3 py-2 text-xs font-extrabold text-white shadow-sm transition hover:bg-green-700"
                                            >

                                                <svg
                                                    width="14"
                                                    height="14"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="3"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7"
                                                    />
                                                </svg>

                                                Approve

                                            </button>


                                            {{-- Reject --}}
                                            <button
                                                type="button"
                                                wire:click="reject({{ $request->id }})"
                                                wire:confirm="Are you sure you want to reject this attendance request?"
                                                class="flex items-center gap-1.5 rounded-lg bg-red-600 px-3 py-2 text-xs font-extrabold text-white shadow-sm transition hover:bg-red-700"
                                            >

                                                <svg
                                                    width="14"
                                                    height="14"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2.5"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>

                                                Reject

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>


        {{-- =========================================================
             INFO BANNER
        ========================================================== --}}
        <div class="flex w-full items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-900/40 dark:bg-blue-950/20">

            <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#2563eb"
                stroke-width="2"
                class="mt-0.5 shrink-0"
            >
                <path d="M12 9v2m0 4h.01M12 3.75a8.25 8.25 0 100 16.5 8.25 8.25 0 000-16.5z" />
            </svg>

            <div class="min-w-0">

                <p class="text-sm font-extrabold text-blue-900 dark:text-blue-300">
                    Attendance Approval Process
                </p>

                <p class="mt-1 text-xs leading-5 text-blue-800/80 dark:text-blue-400/80">
                    QR attendance is recorded automatically.
                    Manual NIC / Passport attendance requests remain pending
                    until an administrator approves them.
                </p>

            </div>

        </div>

    </div>

</x-filament-panels::page>
