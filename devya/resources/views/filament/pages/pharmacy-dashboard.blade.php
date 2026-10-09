<x-filament-panels::page>

    <div class="space-y-6">

        {{-- =========================================================
             HERO HEADER
        ========================================================== --}}
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-700 via-emerald-600 to-teal-700 px-6 py-7 shadow-xl sm:px-8">

            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-24 right-28 h-72 w-72 rounded-full bg-teal-300/10"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-center gap-4">

                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15 shadow-lg ring-1 ring-white/20 backdrop-blur">

                        <svg class="h-9 w-9 text-white"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5V6.375a3.375 3.375 0 1 0-6.75 0V8.25h-1.5A3.375 3.375 0 0 0 3 11.625v2.625m16.5 0a3.375 3.375 0 0 1-3.375 3.375h-9.75A3.375 3.375 0 0 1 3 14.25m16.5 0v2.625A3.375 3.375 0 0 1 16.125 20.25h-8.25A3.375 3.375 0 0 1 4.5 16.875V14.25"/>
                        </svg>

                    </div>

                    <div>

                        <div class="flex items-center gap-2">

                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-100 ring-1 ring-white/10">
                                Pharmacy
                            </span>

                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>

                            <span class="text-xs text-emerald-100">
                                Live Dashboard
                            </span>

                        </div>

                        <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            Pharmacy Dashboard
                        </h1>

                        <p class="mt-1 text-sm text-emerald-100">
                            Sales, customers, medicines and inventory overview
                        </p>

                    </div>

                </div>


                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('filament.admin.resources.pharmacy-bills.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-emerald-700 shadow-lg transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-50 hover:shadow-xl">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>

                        New Sale

                    </a>


                    <a href="{{ route('filament.admin.resources.pharmacy-bills.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-5 py-3 text-sm font-semibold text-white shadow-sm backdrop-blur transition duration-200 hover:bg-white/20">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                        </svg>

                        All Bills

                    </a>

                </div>

            </div>

        </section>


      {{-- =========================================================
     DATE RANGE
========================================================== --}}
<section
    class="overflow-hidden rounded-2xl border border-gray-200
           bg-white shadow-sm
           dark:border-gray-700 dark:bg-gray-900"
>
    {{-- Header --}}
    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800">
        <div class="flex items-center gap-3">

            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center
                       rounded-xl bg-blue-50 dark:bg-blue-900/20"
            >
                <svg
                    class="h-5 w-5 text-blue-600 dark:text-blue-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A2.25 2.25 0 0121 7.5v11.25A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V7.5a2.25 2.25 0 012.25-2.25z"
                    />
                </svg>
            </div>

            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                    Sales Period
                </h2>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Select the period you want to analyse
                </p>
            </div>

        </div>
    </div>

    <div class="p-5">

        {{-- Quick Date Buttons --}}
        <div class="flex flex-wrap gap-2">

            <button
                wire:click="setToday"
                type="button"
                class="
                    inline-flex h-10 items-center justify-center
                    rounded-xl border border-emerald-200
                    bg-emerald-50 px-4
                    text-xs font-bold text-emerald-700
                    transition
                    hover:bg-emerald-100

                    dark:border-emerald-900/40
                    dark:bg-emerald-900/20
                    dark:text-emerald-300
                "
            >
                Today
            </button>

            <button
                wire:click="setYesterday"
                type="button"
                class="
                    inline-flex h-10 items-center justify-center
                    rounded-xl border border-gray-200
                    bg-gray-50 px-4
                    text-xs font-bold text-gray-700
                    transition
                    hover:bg-gray-100

                    dark:border-gray-700
                    dark:bg-gray-800
                    dark:text-gray-300
                "
            >
                Yesterday
            </button>

            <button
                wire:click="setThisWeek"
                type="button"
                class="
                    inline-flex h-10 items-center justify-center
                    rounded-xl border border-blue-200
                    bg-blue-50 px-4
                    text-xs font-bold text-blue-700
                    transition
                    hover:bg-blue-100

                    dark:border-blue-900/40
                    dark:bg-blue-900/20
                    dark:text-blue-300
                "
            >
                This Week
            </button>

            <button
                wire:click="setThisMonth"
                type="button"
                class="
                    inline-flex h-10 items-center justify-center
                    rounded-xl border border-violet-200
                    bg-violet-50 px-4
                    text-xs font-bold text-violet-700
                    transition
                    hover:bg-violet-100

                    dark:border-violet-900/40
                    dark:bg-violet-900/20
                    dark:text-violet-300
                "
            >
                This Month
            </button>

        </div>

        {{-- Date Inputs --}}
        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- From Date --}}
            <div>
                <label
                    for="dateFrom"
                    class="mb-2 block text-sm font-semibold
                           text-gray-700 dark:text-gray-300"
                >
                    From Date
                </label>

                <input
                    id="dateFrom"
                    type="date"
                    wire:model.live="dateFrom"
                    class="
                        block h-11 w-full
                        rounded-xl
                        border border-gray-300
                        bg-white
                        px-3.5 py-2.5
                        text-sm text-gray-900
                        shadow-sm
                        outline-none
                        transition

                        focus:border-emerald-500
                        focus:ring-2
                        focus:ring-emerald-500/20

                        dark:border-gray-700
                        dark:bg-gray-800
                        dark:text-white
                        dark:[color-scheme:dark]
                    "
                >
            </div>

            {{-- To Date --}}
            <div>
                <label
                    for="dateTo"
                    class="mb-2 block text-sm font-semibold
                           text-gray-700 dark:text-gray-300"
                >
                    To Date
                </label>

                <input
                    id="dateTo"
                    type="date"
                    wire:model.live="dateTo"
                    class="
                        block h-11 w-full
                        rounded-xl
                        border border-gray-300
                        bg-white
                        px-3.5 py-2.5
                        text-sm text-gray-900
                        shadow-sm
                        outline-none
                        transition

                        focus:border-emerald-500
                        focus:ring-2
                        focus:ring-emerald-500/20

                        dark:border-gray-700
                        dark:bg-gray-800
                        dark:text-white
                        dark:[color-scheme:dark]
                    "
                >
            </div>

        </div>

    </div>
</section>


     {{-- =========================================================
     FILTERS
========================================================== --}}
<section
    class="overflow-hidden rounded-2xl border border-gray-200
           bg-white shadow-sm
           dark:border-gray-700 dark:bg-gray-900"
>

    {{-- Header --}}
    <div
        class="flex items-center justify-between
               border-b border-gray-100 px-5 py-4
               dark:border-gray-800"
    >
        <div class="flex items-center gap-3">

            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center
                       rounded-xl bg-amber-50 dark:bg-amber-900/20"
            >
                <svg
                    class="h-5 w-5 text-amber-600 dark:text-amber-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 5h18M6 10h12M10 15h4M11 20h2"
                    />
                </svg>
            </div>

            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">
                    Filters
                </h2>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Refine the dashboard results
                </p>
            </div>

        </div>
    </div>

    {{-- Filter Fields --}}
    <div
        class="grid grid-cols-1 gap-5 p-5
               sm:grid-cols-2
               lg:grid-cols-3
               xl:grid-cols-6"
    >

        {{-- Payment Method --}}
        <div>
            <label
                for="paymentMethod"
                class="mb-2 block text-sm font-semibold
                       text-gray-700 dark:text-gray-300"
            >
                Payment Method
            </label>

            <select
                id="paymentMethod"
                wire:model.live="paymentMethod"
                class="
                    block h-11 w-full
                    rounded-xl
                    border border-gray-300
                    bg-white
                    px-3.5 py-2.5
                    text-sm text-gray-900
                    shadow-sm
                    outline-none
                    transition

                    focus:border-emerald-500
                    focus:ring-2
                    focus:ring-emerald-500/20

                    dark:border-gray-700
                    dark:bg-gray-800
                    dark:text-white
                "
            >
                <option value="all">All Methods</option>

                @foreach($this->paymentSummary as $payment)
                    @if($payment->payment_method)
                        <option value="{{ $payment->payment_method }}">
                            {{ ucfirst($payment->payment_method) }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        {{-- Payment Status --}}
        <div>
            <label
                for="paymentStatus"
                class="mb-2 block text-sm font-semibold
                       text-gray-700 dark:text-gray-300"
            >
                Payment Status
            </label>

            <select
                id="paymentStatus"
                wire:model.live="paymentStatus"
                class="
                    block h-11 w-full
                    rounded-xl
                    border border-gray-300
                    bg-white
                    px-3.5 py-2.5
                    text-sm text-gray-900
                    shadow-sm
                    outline-none
                    transition

                    focus:border-emerald-500
                    focus:ring-2
                    focus:ring-emerald-500/20

                    dark:border-gray-700
                    dark:bg-gray-800
                    dark:text-white
                "
            >
                <option value="all">All Status</option>
                <option value="paid">Paid</option>
                <option value="partial">Partial</option>
                <option value="unpaid">Unpaid</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        {{-- Medicine --}}
        <div>
            <label
                for="medicineId"
                class="mb-2 block text-sm font-semibold
                       text-gray-700 dark:text-gray-300"
            >
                Medicine
            </label>

            <select
                id="medicineId"
                wire:model.live="medicineId"
                class="
                    block h-11 w-full
                    rounded-xl
                    border border-gray-300
                    bg-white
                    px-3.5 py-2.5
                    text-sm text-gray-900
                    shadow-sm
                    outline-none
                    transition

                    focus:border-emerald-500
                    focus:ring-2
                    focus:ring-emerald-500/20

                    dark:border-gray-700
                    dark:bg-gray-800
                    dark:text-white
                "
            >
                <option value="all">All Medicines</option>

                @foreach($this->medicines as $medicine)
                    <option value="{{ $medicine->id }}">
                        {{ $medicine->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Category --}}
        <div>
            <label
                for="category"
                class="mb-2 block text-sm font-semibold
                       text-gray-700 dark:text-gray-300"
            >
                Category
            </label>

            <select
                id="category"
                wire:model.live="category"
                class="
                    block h-11 w-full
                    rounded-xl
                    border border-gray-300
                    bg-white
                    px-3.5 py-2.5
                    text-sm text-gray-900
                    shadow-sm
                    outline-none
                    transition

                    focus:border-emerald-500
                    focus:ring-2
                    focus:ring-emerald-500/20

                    dark:border-gray-700
                    dark:bg-gray-800
                    dark:text-white
                "
            >
                <option value="all">All Categories</option>

                @foreach($this->categories as $cat)
                    <option value="{{ $cat }}">
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Customer --}}
        <div>
            <label
                for="customerId"
                class="mb-2 block text-sm font-semibold
                       text-gray-700 dark:text-gray-300"
            >
                Customer
            </label>

            <select
                id="customerId"
                wire:model.live="customerId"
                class="
                    block h-11 w-full
                    rounded-xl
                    border border-gray-300
                    bg-white
                    px-3.5 py-2.5
                    text-sm text-gray-900
                    shadow-sm
                    outline-none
                    transition

                    focus:border-emerald-500
                    focus:ring-2
                    focus:ring-emerald-500/20

                    dark:border-gray-700
                    dark:bg-gray-800
                    dark:text-white
                "
            >
                <option value="all">All Customers</option>

                @foreach($this->customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->name ?? 'Customer #' . $customer->id }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Staff --}}
        <div>
            <label
                for="staffId"
                class="mb-2 block text-sm font-semibold
                       text-gray-700 dark:text-gray-300"
            >
                Staff
            </label>

            <select
                id="staffId"
                wire:model.live="staffId"
                class="
                    block h-11 w-full
                    rounded-xl
                    border border-gray-300
                    bg-white
                    px-3.5 py-2.5
                    text-sm text-gray-900
                    shadow-sm
                    outline-none
                    transition

                    focus:border-emerald-500
                    focus:ring-2
                    focus:ring-emerald-500/20

                    dark:border-gray-700
                    dark:bg-gray-800
                    dark:text-white
                "
            >
                <option value="all">All Staff</option>

                @foreach($this->staff as $staff)
                    <option value="{{ $staff->id }}">
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

</section>


        {{-- =========================================================
             KPI CARDS
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


            {{-- SALES --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-5 shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>

                <div class="relative">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-medium text-emerald-100">
                            Total Sales
                        </span>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15">
                            <svg class="h-5 w-5 text-white"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0 0a4 4 0 100-8m0 8a4 4 0 100-8"/>
                            </svg>
                        </div>

                    </div>

                    <p class="mt-5 text-2xl font-extrabold tracking-tight text-white">
                        LKR {{ number_format($this->totalSales, 2) }}
                    </p>

                    <p class="mt-1 text-xs text-emerald-100">
                        Completed paid sales
                    </p>

                </div>

            </div>


            {{-- BILLS --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-5 shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>

                <div class="relative">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-medium text-blue-100">
                            Pharmacy Bills
                        </span>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15">
                            <svg class="h-5 w-5 text-white"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-white">
                        {{ number_format($this->billCount) }}
                    </p>

                    <p class="mt-1 text-xs text-blue-100">
                        Bills in selected period
                    </p>

                </div>

            </div>


            {{-- CUSTOMERS --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 to-purple-700 p-5 shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>

                <div class="relative">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-medium text-violet-100">
                            Customers
                        </span>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15">
                            <svg class="h-5 w-5 text-white"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-white">
                        {{ number_format($this->customerCount) }}
                    </p>

                    <p class="mt-1 text-xs text-violet-100">
                        Unique customers
                    </p>

                </div>

            </div>


            {{-- ITEMS --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-5 shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>

                <div class="relative">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-medium text-amber-100">
                            Items Sold
                        </span>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15">
                            <svg class="h-5 w-5 text-white"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 1.5A1 1 0 006.2 16H17m0 0a2 2 0 11-4 0m4 0a2 2 0 11-4 0"/>
                            </svg>
                        </div>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-white">
                        {{ number_format($this->itemsSold) }}
                    </p>

                    <p class="mt-1 text-xs text-amber-100">
                        Units sold
                    </p>

                </div>

            </div>

        </div>


        {{-- =========================================================
             SECONDARY CARDS
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">


            <div class="rounded-2xl border border-cyan-100 bg-gradient-to-br from-cyan-50 to-white p-5 shadow-sm dark:border-cyan-900/30 dark:from-cyan-950/20 dark:to-gray-900">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-100 dark:bg-cyan-900/40">

                        <svg class="h-5 w-5 text-cyan-600 dark:text-cyan-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2"/>
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Average Sale
                        </p>

                        <p class="mt-1 text-lg font-extrabold text-gray-900 dark:text-white">
                            LKR {{ number_format($this->averageSale, 2) }}
                        </p>
                    </div>

                </div>

            </div>


            <div class="rounded-2xl border border-rose-100 bg-gradient-to-br from-rose-50 to-white p-5 shadow-sm dark:border-rose-900/30 dark:from-rose-950/20 dark:to-gray-900">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 dark:bg-rose-900/40">

                        <svg class="h-5 w-5 text-rose-600 dark:text-rose-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 14l6-6m-5.5-5.5h5A2.5 2.5 0 0117 5v14a2.5 2.5 0 01-2.5 2.5h-5A2.5 2.5 0 017 19V5a2.5 2.5 0 012.5-2.5z"/>
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Total Discount
                        </p>

                        <p class="mt-1 text-lg font-extrabold text-gray-900 dark:text-white">
                            LKR {{ number_format($this->discountTotal, 2) }}
                        </p>
                    </div>

                </div>

            </div>


            <div class="rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm dark:border-amber-900/30 dark:from-amber-950/20 dark:to-gray-900">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/40">

                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 9v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Low Stock
                        </p>

                        <p class="mt-1 text-lg font-extrabold text-amber-600 dark:text-amber-400">
                            {{ number_format($this->lowStockCount) }}
                        </p>
                    </div>

                </div>

            </div>


            <div class="rounded-2xl border border-red-100 bg-gradient-to-br from-red-50 to-white p-5 shadow-sm dark:border-red-900/30 dark:from-red-950/20 dark:to-gray-900">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/40">

                        <svg class="h-5 w-5 text-red-600 dark:text-red-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 9v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Out of Stock
                        </p>

                        <p class="mt-1 text-lg font-extrabold text-red-600 dark:text-red-400">
                            {{ number_format($this->outOfStockCount) }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             PAYMENT + STOCK
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


            {{-- PAYMENT SUMMARY --}}
            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">

                <div class="flex items-center gap-3 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white px-5 py-4 dark:border-gray-800 dark:from-emerald-950/20 dark:to-gray-900">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">

                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m4-6h-6m0 0l2-2m-2 2l2 2"/>
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900 dark:text-white">
                            Payment Summary
                        </h2>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Revenue grouped by payment method
                        </p>
                    </div>

                </div>


                <div class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse($this->paymentSummary as $payment)

                        <div class="flex items-center justify-between px-5 py-4 transition hover:bg-gray-50 dark:hover:bg-gray-800/40">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">

                                    <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M3 10h18M7 15h2m3 0h2m-9 5h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                        {{ ucfirst($payment->payment_method ?? 'Unknown') }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Payment method
                                    </p>

                                </div>

                            </div>

                            <p class="text-sm font-extrabold text-gray-900 dark:text-white">
                                LKR {{ number_format((float) $payment->total, 2) }}
                            </p>

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center text-sm text-gray-500">
                            No payment data available.
                        </div>

                    @endforelse

                </div>

            </section>


            {{-- STOCK ALERTS --}}
            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">

                <div class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-amber-50 to-white px-5 py-4 dark:border-gray-800 dark:from-amber-950/20 dark:to-gray-900">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">

                            <svg class="h-5 w-5 text-amber-600 dark:text-amber-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 9v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                            </svg>

                        </div>

                        <div>

                            <h2 class="font-bold text-gray-900 dark:text-white">
                                Stock Alerts
                            </h2>

                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Medicines requiring attention
                            </p>

                        </div>

                    </div>


                    <div class="flex gap-2">

                        <span class="rounded-full bg-amber-100 px-3 py-1 text-[11px] font-bold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                            {{ $this->lowStockCount }} Low
                        </span>

                        <span class="rounded-full bg-red-100 px-3 py-1 text-[11px] font-bold text-red-700 dark:bg-red-900/30 dark:text-red-300">
                            {{ $this->outOfStockCount }} Out
                        </span>

                    </div>

                </div>


                <div class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse($this->lowStockMedicines as $medicine)

                        <div class="flex items-center justify-between px-5 py-3.5 transition hover:bg-gray-50 dark:hover:bg-gray-800/40">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-xs font-extrabold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                    {{ strtoupper(substr($medicine->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">

                                    <p class="truncate text-sm font-bold text-gray-800 dark:text-gray-200">
                                        {{ $medicine->name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Reorder level: {{ $medicine->reorder_level }}
                                    </p>

                                </div>

                            </div>


                            @if($medicine->stock_quantity <= 0)

                                <span class="ml-3 shrink-0 rounded-full bg-red-100 px-3 py-1 text-[10px] font-extrabold text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                    OUT OF STOCK
                                </span>

                            @else

                                <span class="ml-3 shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                    {{ $medicine->stock_quantity }} {{ $medicine->unit }}
                                </span>

                            @endif

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center">

                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">

                                <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M5 13l4 4L19 7"/>
                                </svg>

                            </div>

                            <p class="mt-3 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Stock levels are healthy
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                No low-stock medicines found.
                            </p>

                        </div>

                    @endforelse

                </div>

            </section>

        </div>


        {{-- =========================================================
             TOP SELLING
        ========================================================== --}}
        <section class="admin-workspace">

            <div class="admin-records-header">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 dark:bg-violet-900/30">

                        <x-filament::icon icon="heroicon-m-chart-bar" class="h-5 w-5 text-violet-600 dark:text-violet-400" />

                    </div>

                    <div>

                        <h2 class="font-bold text-gray-900 dark:text-white">
                            Top Selling Medicines
                        </h2>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Best performing medicines for the selected period
                        </p>

                    </div>

                </div>

            </div>


            <div class="admin-records-body">

                <table class="admin-responsive-table" role="table" aria-label="Top selling medicines">

                    <thead class="bg-gray-50 dark:bg-gray-800/50">

                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">

                            <th scope="col" class="px-5 py-3.5">
                                #
                            </th>

                            <th scope="col" class="px-5 py-3.5">
                                Medicine
                            </th>

                            <th scope="col" class="px-5 py-3.5 text-center">
                                Quantity
                            </th>

                            <th scope="col" class="px-5 py-3.5 text-right">
                                Sales
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @forelse($this->topSellingMedicines as $index => $medicine)

                            <tr class="transition hover:bg-violet-50/40 dark:hover:bg-violet-950/10">

                                <td data-label="Rank" class="px-5 py-4">

                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg text-xs font-extrabold
                                        {{ $index === 0
                                            ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300'
                                            : ($index === 1
                                                ? 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200'
                                                : ($index === 2
                                                    ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300'
                                                    : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400')) }}">

                                        {{ $index + 1 }}

                                    </div>

                                </td>


                                <td data-label="Medicine" class="px-5 py-4">

                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ $medicine->medicine_name }}
                                    </p>

                                </td>


                                <td data-label="Quantity" class="px-5 py-4 text-center">

                                    <span class="inline-flex min-w-16 justify-center rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-extrabold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                        {{ number_format((float) $medicine->total_quantity) }}
                                    </span>

                                </td>


                                <td data-label="Sales" class="px-5 py-4 text-right">

                                    <span class="font-extrabold text-gray-900 dark:text-white">
                                        LKR {{ number_format((float) $medicine->total_sales, 2) }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-500">
                                    No sales data available for the selected period.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- =========================================================
             RECENT SALES
        ========================================================== --}}
        <section class="admin-workspace">

            <div class="admin-records-header">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">

                        <x-filament::icon icon="heroicon-m-clock" class="h-5 w-5 text-blue-600 dark:text-blue-400" />

                    </div>

                    <div>

                        <h2 class="font-bold text-gray-900 dark:text-white">
                            Recent Pharmacy Sales
                        </h2>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Latest transactions
                        </p>

                    </div>

                </div>


                <a href="{{ route('filament.admin.resources.pharmacy-bills.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400">

                    View All

                    <svg class="h-4 w-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>

                </a>

            </div>


            <div class="admin-records-body">

                <table class="admin-responsive-table" role="table" aria-label="Recent pharmacy sales">

                    <thead class="bg-gray-50 dark:bg-gray-800/50">

                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">

                            <th scope="col" class="px-5 py-3.5">Bill</th>
                            <th scope="col" class="px-5 py-3.5">Customer</th>
                            <th scope="col" class="px-5 py-3.5">Date</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Items</th>
                            <th scope="col" class="px-5 py-3.5 text-right">Total</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Status</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @forelse($this->recentSales as $sale)

                            @php
                                $status = strtolower($sale->payment_status ?? 'unknown');

                                $statusClasses = match ($status) {
                                    'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                    'partial' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                                    'unpaid' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                    'cancelled' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                    default => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                };
                            @endphp

                            <tr class="transition hover:bg-blue-50/30 dark:hover:bg-blue-950/10">

                                <td data-label="Bill" class="px-5 py-4">

                                    <span class="font-extrabold text-emerald-600 dark:text-emerald-400">
                                        {{ $sale->bill_number }}
                                    </span>

                                </td>


                                <td data-label="Customer" class="px-5 py-4">

                                    <span class="font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $sale->patient?->full_name ?? 'Walk-in Customer' }}
                                    </span>

                                </td>


                                <td data-label="Date" class="px-5 py-4 text-xs font-medium text-gray-500 dark:text-gray-400">

                                    {{ $sale->created_at?->format('d M Y, h:i A') }}

                                </td>


                                <td data-label="Items" class="px-5 py-4 text-center">

                                    <span class="inline-flex min-w-9 justify-center rounded-lg bg-gray-100 px-2.5 py-1.5 text-xs font-bold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        {{ $sale->items->sum('quantity') }}
                                    </span>

                                </td>


                                <td data-label="Total" class="px-5 py-4 text-right">

                                    <span class="font-extrabold text-gray-900 dark:text-white">
                                        {{ $sale->currency ?? 'LKR' }}
                                        {{ number_format((float) $sale->grand_total, 2) }}
                                    </span>

                                </td>


                                <td data-label="Status" class="px-5 py-4 text-center">

                                    <span class="inline-flex rounded-full px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide {{ $statusClasses }}">
                                        {{ ucfirst($status) }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="px-5 py-14 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">

                                        <svg class="h-7 w-7 text-gray-400"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                                        </svg>

                                    </div>

                                    <p class="mt-3 text-sm font-bold text-gray-700 dark:text-gray-300">
                                        No pharmacy sales found
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Try changing the date range or filters.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- =========================================================
             FOOTER
        ========================================================== --}}
        <div class="flex flex-col gap-2 rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-50 to-teal-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-emerald-900/30 dark:from-emerald-950/20 dark:to-teal-950/20">

            <div class="flex items-center gap-2.5">

                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30">

                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>

                </div>

                <span class="text-xs text-emerald-800 dark:text-emerald-300">

                    Showing data from

                    <strong>
                        {{ \Illuminate\Support\Carbon::parse($dateFrom)->format('d M Y') }}
                    </strong>

                    to

                    <strong>
                        {{ \Illuminate\Support\Carbon::parse($dateTo)->format('d M Y') }}
                    </strong>

                </span>

            </div>


            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">

                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                Live Database

            </span>

        </div>

    </div>

</x-filament-panels::page>
