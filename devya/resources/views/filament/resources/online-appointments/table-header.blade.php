@php
    $stats = $livewire->getDateStats();
@endphp

<div class="w-full border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">

    <div class="w-full px-4 py-4 sm:px-6">

        {{-- =========================================================
             TOP ROW
        ========================================================== --}}

        <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">

            {{-- LEFT: TITLE --}}

            <div class="flex min-w-0 items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="17"
                            rx="2"
                        />

                        <path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>

                </div>

                <div class="min-w-0">

                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-white">
                        {{ $livewire->isShowingWebsiteRequests() ? 'Pending Website Requests' : 'Appointment Overview' }}
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">

                        @if ($livewire->isShowingWebsiteRequests())
                            All preferred dates
                        @else
                            Appointments for

                        <span class="font-semibold text-primary-600 dark:text-primary-400">
                            {{ $livewire->getSelectedDateLabel() }}
                        </span>
                        @endif

                    </p>

                </div>

            </div>


            {{-- RIGHT: DATE PICKER --}}

            @if (! $livewire->isShowingWebsiteRequests())
            <div class="flex w-full items-center gap-2 xl:w-auto">

                <div class="flex min-w-0 flex-1 items-center gap-2 xl:w-[240px] xl:flex-none">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <rect
                                x="3"
                                y="4"
                                width="18"
                                height="17"
                                rx="2"
                            />

                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>

                    </div>

                    <div class="min-w-0 flex-1">

                        <label
                            for="appointment-date-picker"
                            class="mb-1 block text-[10px] font-bold uppercase tracking-wide text-gray-400"
                        >
                            Appointment Date
                        </label>

                        <input
                            id="appointment-date-picker"
                            type="date"
                            wire:model.live="selectedDate"
                            class="block h-9 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-800 shadow-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        >

                    </div>

                </div>


                {{-- TODAY BUTTON --}}

                <button
                    type="button"
                    wire:click="$set('selectedDate', '{{ today()->toDateString() }}')"
                    class="inline-flex h-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 px-3 text-xs font-semibold text-gray-600 transition hover:border-gray-300 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Today
                </button>

            </div>
            @endif

        </div>


        {{-- =========================================================
             STATISTICS ROW
        ========================================================== --}}

        <div class="mt-4 flex w-full gap-3 overflow-x-auto pb-1">


            {{-- TOTAL --}}

            <div class="min-w-[135px] flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800/70">

                <div class="flex items-center justify-between">

                    <span class="text-[10px] font-bold uppercase tracking-wide text-gray-400">
                        Total
                    </span>

                    <span class="rounded-md bg-gray-200 px-1.5 py-0.5 text-[9px] font-bold text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        ALL
                    </span>

                </div>

                <div class="mt-1 text-2xl font-black text-gray-900 dark:text-white">
                    {{ number_format($stats['total']) }}
                </div>

            </div>


            {{-- PAID --}}

            <div class="min-w-[135px] flex-1 rounded-xl border border-green-200 bg-green-50 px-4 py-3 dark:border-green-900/40 dark:bg-green-950/20">

                <span class="text-[10px] font-bold uppercase tracking-wide text-green-600 dark:text-green-400">
                    Paid
                </span>

                <div class="mt-1 text-2xl font-black text-green-700 dark:text-green-300">
                    {{ number_format($stats['paid']) }}
                </div>

            </div>


            {{-- CANCELLED --}}

            <div class="min-w-[135px] flex-1 rounded-xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-900/40 dark:bg-red-950/20">

                <span class="text-[10px] font-bold uppercase tracking-wide text-red-600 dark:text-red-400">
                    Cancelled
                </span>

                <div class="mt-1 text-2xl font-black text-red-700 dark:text-red-300">
                    {{ number_format($stats['cancelled']) }}
                </div>

            </div>


            {{-- ONLINE --}}

            <div class="min-w-[135px] flex-1 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 dark:border-blue-900/40 dark:bg-blue-950/20">

                <span class="text-[10px] font-bold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                    Online
                </span>

                <div class="mt-1 text-2xl font-black text-blue-700 dark:text-blue-300">
                    {{ number_format($stats['online']) }}
                </div>

            </div>


            {{-- RECEPTION --}}

            <div class="min-w-[135px] flex-1 rounded-xl border border-violet-200 bg-violet-50 px-4 py-3 dark:border-violet-900/40 dark:bg-violet-950/20">

                <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 dark:text-violet-400">
                    Reception
                </span>

                <div class="mt-1 text-2xl font-black text-violet-700 dark:text-violet-300">
                    {{ number_format($stats['reception']) }}
                </div>

            </div>


            {{-- LOCAL --}}

            <div class="min-w-[135px] flex-1 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 dark:border-amber-900/40 dark:bg-amber-950/20">

                <span class="text-[10px] font-bold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                    Local
                </span>

                <div class="mt-1 text-2xl font-black text-amber-700 dark:text-amber-300">
                    {{ number_format($stats['local']) }}
                </div>

            </div>


            {{-- FOREIGN --}}

            <div class="min-w-[135px] flex-1 rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-3 dark:border-cyan-900/40 dark:bg-cyan-950/20">

                <span class="text-[10px] font-bold uppercase tracking-wide text-cyan-600 dark:text-cyan-400">
                    Foreign
                </span>

                <div class="mt-1 text-2xl font-black text-cyan-700 dark:text-cyan-300">
                    {{ number_format($stats['foreign']) }}
                </div>

            </div>

        </div>

    </div>

</div>
