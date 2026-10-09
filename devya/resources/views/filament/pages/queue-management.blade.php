<x-filament-panels::page>

    <div class="w-full space-y-6">

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-emerald-600 to-teal-700 px-6 py-7 text-white shadow-xl shadow-emerald-900/10 sm:px-8">

            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white/10"></div>

            <div class="pointer-events-none absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-white/5"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-bold backdrop-blur">

                        <span class="h-2 w-2 rounded-full bg-emerald-200"></span>

                        Queue Management

                    </div>

                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">

                        Clinic Queue Control

                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-emerald-50/90">

                        Select a doctor room and open the live TV queue display in a separate browser tab.

                    </p>

                </div>

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 backdrop-blur">

                    <svg
                        class="h-7 w-7"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M3 5h18"/>
                        <path d="M3 12h18"/>
                        <path d="M3 19h18"/>
                        <circle cx="7" cy="5" r="1.5"/>
                        <circle cx="17" cy="12" r="1.5"/>
                        <circle cx="9" cy="19" r="1.5"/>
                    </svg>

                </div>

            </div>

        </div>


        {{-- =========================================================
            INSTRUCTION
        ========================================================== --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/50 dark:bg-blue-950/20">

            <div class="flex items-start gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 11v5"/>
                        <path d="M12 8h.01"/>
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-bold text-blue-900 dark:text-blue-200">

                        How Queue Display Works

                    </p>

                    <p class="mt-1 text-xs leading-5 text-blue-700 dark:text-blue-300">

                        Appointment Token Numbers are used for the queue. Open the required room display on the TV/browser. The display can show the current and next patient token.

                    </p>

                </div>

            </div>

        </div>


        {{-- =========================================================
            ROOM LIST
        ========================================================== --}}
        <div>

            <div class="mb-4 flex items-end justify-between gap-4">

                <div>

                    <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                        Doctor Rooms
                    </h2>

                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-gray-400">
                        Select a room to open its live queue display.
                    </p>

                </div>

                <div class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 dark:bg-gray-800 dark:text-gray-300">

                    {{ $this->getRooms()->count() }}

                    Room{{ $this->getRooms()->count() === 1 ? '' : 's' }}

                </div>

            </div>


            @if ($this->getRooms()->isEmpty())

                <div class="rounded-2xl bg-white px-6 py-14 text-center shadow-sm ring-1 ring-slate-200 dark:bg-gray-900 dark:ring-gray-800">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-gray-800 dark:text-gray-500">

                        <svg
                            class="h-7 w-7"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <path d="M8 9h8"/>
                            <path d="M8 13h5"/>
                        </svg>

                    </div>

                    <h3 class="mt-4 text-sm font-extrabold text-slate-900 dark:text-white">

                        No doctor rooms configured

                    </h3>

                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-gray-400">

                        Add a room number to an active doctor to create a queue display.

                    </p>

                </div>

            @else

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                    @foreach ($this->getRooms() as $room)

                        <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-lg dark:bg-gray-900 dark:ring-gray-800">

                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500"></div>

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400">

                                        <svg
                                            class="h-5 w-5"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                                            <path d="M7 8h10"/>
                                            <path d="M7 12h6"/>
                                            <path d="M7 16h4"/>
                                        </svg>

                                    </div>

                                </div>

                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">

                                    Live Queue

                                </span>

                            </div>


                            <div class="mt-5">

                                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-gray-500">

                                    Doctor Room

                                </p>

                                <h3 class="mt-1 text-xl font-extrabold text-slate-900 dark:text-white">

                                    {{ $room }}

                                </h3>

                                <p class="mt-1 text-xs font-medium text-slate-500 dark:text-gray-400">

                                    Token-based patient queue

                                </p>

                            </div>


                            <div class="mt-5">

                                <a
                                    href="{{ route('queue.public', ['room' => $room]) }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                                >

                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <rect x="3" y="4" width="18" height="14" rx="2"/>
                                        <path d="M8 21h8"/>
                                        <path d="M12 18v3"/>
                                    </svg>

                                    Open TV Display

                                    <svg
                                        class="h-3.5 w-3.5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path d="M14 5h5v5"/>
                                        <path d="M10 14L19 5"/>
                                        <path d="M19 13v5a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1h5"/>
                                    </svg>

                                </a>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

</x-filament-panels::page>
