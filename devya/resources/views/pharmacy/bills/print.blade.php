<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>
        Pharmacy Bill - {{ $bill->bill_number }}
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css'])

    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .receipt {
                width: 80mm !important;
                margin: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 antialiased">

    {{-- =====================================================
         RECEIPT
    ====================================================== --}}

    <div class="receipt mx-auto w-[80mm] bg-white px-4 py-5">

        {{-- =================================================
             HEADER
        ================================================== --}}

        <div class="text-center">

            <div class="text-2xl font-black tracking-wider text-emerald-800">
                AYU SYSTEM
            </div>

            <div class="mt-1 text-xs font-bold uppercase tracking-wide text-gray-700">
                Pharmacy Bill
            </div>

            <div class="mt-1 text-[9px] text-gray-500">
                Ayurveda & Wellness Centre
            </div>

        </div>


        {{-- =================================================
             TOP LINE
        ================================================== --}}

        <div class="my-3 border-t-2 border-gray-800"></div>


        {{-- =================================================
             BILL INFORMATION
        ================================================== --}}

        <div class="space-y-1 text-[10px]">

            <div class="flex justify-between gap-3">
                <span class="font-bold text-gray-600">
                    Bill No
                </span>

                <span class="text-right font-bold">
                    {{ $bill->bill_number }}
                </span>
            </div>


            <div class="flex justify-between gap-3">
                <span class="font-bold text-gray-600">
                    Date
                </span>

                <span class="text-right">
                    {{ $bill->created_at?->format('d/m/Y') }}
                </span>
            </div>


            <div class="flex justify-between gap-3">
                <span class="font-bold text-gray-600">
                    Time
                </span>

                <span class="text-right">
                    {{ $bill->created_at?->format('h:i A') }}
                </span>
            </div>


            <div class="flex justify-between gap-3">
                <span class="font-bold text-gray-600">
                    Currency
                </span>

                <span class="text-right font-bold uppercase">
                    {{ $bill->currency ?? 'LKR' }}
                </span>
            </div>

        </div>


        {{-- =================================================
             DIVIDER
        ================================================== --}}

        <div class="my-3 border-t border-dashed border-gray-400"></div>


        {{-- =================================================
             CUSTOMER
        ================================================== --}}

        <div class="mb-2 text-[10px] font-black uppercase tracking-wide text-emerald-800">
            Customer Information
        </div>


        <div class="space-y-1 text-[10px]">

            <div class="flex justify-between gap-3">

                <span class="font-bold text-gray-600">
                    Name
                </span>

                <span class="max-w-[55%] text-right font-semibold break-words">

                    @if($bill->patient)
                        {{ $bill->patient->full_name }}
                    @else
                        Walk-in Customer
                    @endif

                </span>

            </div>


            @if($bill->patient)

                <div class="flex justify-between gap-3">

                    <span class="font-bold text-gray-600">
                        NIC / Passport
                    </span>

                    <span class="text-right">
                        {{ $bill->patient->nic_or_passport ?? '-' }}
                    </span>

                </div>


                <div class="flex justify-between gap-3">

                    <span class="font-bold text-gray-600">
                        Phone
                    </span>

                    <span class="text-right">
                        {{ $bill->patient->phone_number ?? '-' }}
                    </span>

                </div>


                @if(isset($bill->patient->patient_type))

                    <div class="flex justify-between gap-3">

                        <span class="font-bold text-gray-600">
                            Type
                        </span>

                        <span class="text-right font-semibold capitalize">
                            {{ $bill->patient->patient_type }}
                        </span>

                    </div>

                @endif

            @endif

        </div>


        {{-- =================================================
             DIVIDER
        ================================================== --}}

        <div class="my-3 border-t border-dashed border-gray-400"></div>


        {{-- =================================================
             MEDICINES
        ================================================== --}}

        <div class="mb-2 text-[10px] font-black uppercase tracking-wide text-emerald-800">
            Medicines
        </div>


        {{-- Table Header --}}

        <div class="grid grid-cols-[18%_34%_11%_18%_19%] border-y border-gray-800 py-1 text-[8px] font-black">

            <div class="text-left">
                Code
            </div>

            <div class="text-left">
                Medicine
            </div>

            <div class="text-center">
                Qty
            </div>

            <div class="text-right">
                Price
            </div>

            <div class="text-right">
                Total
            </div>

        </div>


        {{-- Medicine Rows --}}

        <div>

            @forelse($bill->items as $item)

                <div class="grid grid-cols-[18%_34%_11%_18%_19%] border-b border-dotted border-gray-300 py-1.5 text-[8px]">

                    {{-- Code --}}

                    <div class="break-words font-bold text-gray-600">
                        {{ $item->medicine?->code ?? '-' }}
                    </div>


                    {{-- Name --}}

                    <div class="break-words pr-1 font-semibold">
                        {{ $item->medicine_name }}
                    </div>


                    {{-- Qty --}}

                    <div class="text-center font-semibold">

                        {{ rtrim(
                            rtrim(
                                number_format((float) $item->quantity, 2, '.', ''),
                                '0'
                            ),
                            '.'
                        ) }}

                    </div>


                    {{-- Unit Price --}}

                    <div class="text-right">
                        {{ number_format((float) $item->unit_price, 2) }}
                    </div>


                    {{-- Total --}}

                    <div class="text-right font-bold">
                        {{ number_format((float) $item->total, 2) }}
                    </div>

                </div>

            @empty

                <div class="py-3 text-center text-[9px] text-gray-500">
                    No medicines
                </div>

            @endforelse

        </div>


        {{-- =================================================
             TOTALS
        ================================================== --}}

        <div class="mt-3 space-y-1 text-[10px]">

            {{-- Subtotal --}}

            <div class="flex justify-between">

                <span class="text-gray-600">
                    Subtotal
                </span>

                <span class="font-semibold">
                    {{ number_format((float) $bill->subtotal, 2) }}
                </span>

            </div>


            {{-- Discount --}}

            @if((float) $bill->discount > 0)

                <div class="flex justify-between">

                    <span class="text-gray-600">
                        Discount
                    </span>

                    <span class="font-semibold text-red-600">
                        - {{ number_format((float) $bill->discount, 2) }}
                    </span>

                </div>

            @endif


            {{-- Grand Total --}}

            <div class="my-2 flex items-center justify-between border-y-2 border-gray-900 py-2">

                <span class="text-sm font-black">
                    GRAND TOTAL
                </span>

                <span class="text-sm font-black">
                    {{ number_format((float) $bill->grand_total, 2) }}
                </span>

            </div>


            {{-- Paid --}}

            @if((float) $bill->amount_received > 0)

                <div class="flex justify-between">

                    <span class="font-semibold text-emerald-700">
                        Paid
                    </span>

                    <span class="font-bold text-emerald-700">
                        {{ number_format((float) $bill->amount_received, 2) }}
                    </span>

                </div>

            @endif


            {{-- Change --}}

            @if((float) $bill->change_amount > 0)

                <div class="flex justify-between">

                    <span class="font-semibold text-emerald-700">
                        Change
                    </span>

                    <span class="font-bold text-emerald-700">
                        {{ number_format((float) $bill->change_amount, 2) }}
                    </span>

                </div>

            @endif


            {{-- Balance --}}

            @if((float) $bill->balance_due > 0)

                <div class="flex justify-between">

                    <span class="font-semibold text-red-700">
                        Balance Due
                    </span>

                    <span class="font-bold text-red-700">
                        {{ number_format((float) $bill->balance_due, 2) }}
                    </span>

                </div>

            @endif

        </div>


        {{-- =================================================
             PAYMENT STATUS
        ================================================== --}}

        <div class="mt-3 rounded border border-emerald-700 px-2 py-1.5 text-center text-[10px] font-black uppercase tracking-wider text-emerald-800">

            {{ $bill->payment_status ?? 'UNPAID' }}

        </div>


        {{-- =================================================
             PAYMENT DETAILS
        ================================================== --}}

        @if($bill->payment_method)

            <div class="mt-3 space-y-1 text-[9px]">

                <div class="flex justify-between">

                    <span class="font-bold text-gray-600">
                        Payment Method
                    </span>

                    <span class="font-semibold capitalize">
                        {{ $bill->payment_method }}
                    </span>

                </div>


                @if($bill->receipt_number)

                    <div class="flex justify-between gap-2">

                        <span class="font-bold text-gray-600">
                            Receipt No
                        </span>

                        <span class="text-right font-semibold break-all">
                            {{ $bill->receipt_number }}
                        </span>

                    </div>

                @endif


                @if($bill->paid_at)

                    <div class="flex justify-between gap-2">

                        <span class="font-bold text-gray-600">
                            Paid At
                        </span>

                        <span class="text-right">
                            {{ $bill->paid_at->format('d/m/Y h:i A') }}
                        </span>

                    </div>

                @endif

            </div>

        @endif


        {{-- =================================================
             FOOTER
        ================================================== --}}

        <div class="my-3 border-t-2 border-gray-800"></div>


        <div class="text-center">

            <div class="text-[11px] font-black">
                Thank You!
            </div>

            <div class="mt-1 text-[8px] text-gray-500">
                Please keep this bill for your records.
            </div>

            <div class="mt-2 text-[7px] text-gray-400">
                Printed: {{ now()->format('d/m/Y h:i A') }}
            </div>

        </div>

    </div>


    {{-- =====================================================
         BUTTONS - NOT PRINTED
    ====================================================== --}}

    <div class="no-print mx-auto flex w-[80mm] gap-2 bg-gray-100 p-3">

        <button
            type="button"
            onclick="window.print()"
            class="flex-1 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white shadow hover:bg-emerald-800"
        >
            🖨 Print Bill
        </button>


        <button
            type="button"
            onclick="window.close()"
            class="rounded-lg bg-gray-700 px-4 py-2.5 text-sm font-bold text-white shadow hover:bg-gray-800"
        >
            Close
        </button>

    </div>


    {{-- =====================================================
         AUTO PRINT
    ====================================================== --}}

    @if ($autoPrint ?? false)
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    window.print();
                }, 500);
            });
        </script>
    @endif

</body>
</html>
