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
                    <i class="fa-solid fa-list-ol text-2xl text-white"></i>
                </div>

            </div>

        </div>


        {{-- =========================================================
            INSTRUCTION
        ========================================================== --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/50 dark:bg-blue-950/20">

            <div class="flex items-start gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                    <i class="fa-solid fa-circle-info text-base"></i>
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
                        <i class="fa-solid fa-hospital-user mr-1.5 text-emerald-600"></i> Doctor Rooms
                    </h2>

                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-gray-400">
                        Select a room to open its live queue display.
                    </p>

                </div>

                <div class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 dark:bg-gray-800 dark:text-gray-300">
                    <i class="fa-solid fa-door-closed mr-1 text-xs"></i>
                    {{ $this->getRooms()->count() }}
                    Room{{ $this->getRooms()->count() === 1 ? '' : 's' }}
                </div>

            </div>


            @if ($this->getRooms()->isEmpty())

                <div class="rounded-2xl bg-white px-6 py-14 text-center shadow-sm ring-1 ring-slate-200 dark:bg-gray-900 dark:ring-gray-800">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-gray-800 dark:text-gray-500">
                        <i class="fa-solid fa-door-open text-2xl"></i>
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
                                        <i class="fa-solid fa-door-open text-lg"></i>
                                    </div>

                                </div>

                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                    <i class="fa-solid fa-circle text-[6px] mr-1 text-emerald-500 animate-pulse"></i> Live Queue
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
                                    data-tooltip="Open live full-screen TV queue screen for this room in a new window"
                                >
                                    <i class="fa-solid fa-tv text-sm"></i>
                                    <span>Open TV Display</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-75"></i>
                                </a>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

</x-filament-panels::page>
