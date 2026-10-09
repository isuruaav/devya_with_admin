<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Pharmacy Receipt - {{ $bill->receipt_number ?? $bill->bill_number }}
    </title>

    @vite(['resources/css/app.css'])

    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        body {
            width: 80mm;
            margin: 0 auto;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
        }

        .receipt {
            width: 80mm;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        @media print {
            html,
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
                background: #ffffff;
            }

            .receipt {
                width: 80mm;
                padding: 3mm !important;
            }

            .print-button {
                display: none !important;
            }
        }
    </style>
</head>


<body class="bg-white text-gray-900">

<div class="receipt px-3 py-3">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="text-center">

        <div class="text-[18px] font-extrabold leading-tight tracking-tight">
            ISD Tech Hub (Pvt) Ltd
        </div>

        <div class="mt-0.5 text-[11px] font-semibold">
            Ayurveda &amp; Wellness Center
        </div>

        <div class="text-[10px] text-gray-700">
            Dambulla, Sri Lanka
        </div>

        <div class="text-[10px] text-gray-700">
            Pharmacy
        </div>

        <div class="mt-2 inline-block border border-gray-900 px-3 py-1">
            <div class="text-[13px] font-extrabold tracking-[1px]">
                PHARMACY RECEIPT
            </div>
        </div>

    </div>


    {{-- Divider --}}
    <div class="my-2 border-t border-dashed border-gray-900"></div>


    {{-- =====================================================
         BILL INFORMATION
    ====================================================== --}}
    <div class="space-y-1 text-[10px]">

        <div class="flex items-start justify-between gap-2">
            <span class="font-bold">
                Bill No
            </span>

            <span class="text-right font-semibold break-all">
                {{ $bill->bill_number ?? '-' }}
            </span>
        </div>


        <div class="flex items-start justify-between gap-2">
            <span class="font-bold">
                Receipt No
            </span>

            <span class="text-right font-semibold break-all">
                {{ $bill->receipt_number ?? '-' }}
            </span>
        </div>


        <div class="flex items-start justify-between gap-2">
            <span class="font-bold">
                Date
            </span>

            <span class="text-right">
                {{ optional($bill->paid_at)->format('d M Y') ?? now()->format('d M Y') }}
            </span>
        </div>


        <div class="flex items-start justify-between gap-2">
            <span class="font-bold">
                Time
            </span>

            <span class="text-right">
                {{ optional($bill->paid_at)->format('h:i A') ?? now()->format('h:i A') }}
            </span>
        </div>

    </div>


    {{-- Divider --}}
    <div class="my-2 border-t border-dashed border-gray-900"></div>


    {{-- =====================================================
         PATIENT INFORMATION
    ====================================================== --}}
    <div class="mb-1 text-[11px] font-extrabold uppercase tracking-wide">
        Patient Information
    </div>


    <div class="space-y-1 text-[10px]">

        <div class="flex items-start justify-between gap-2">
            <span class="font-bold">
                Name
            </span>

            <span class="max-w-[52mm] text-right font-semibold break-words">
                {{ $bill->patient?->full_name ?? 'Walk-in Patient' }}
            </span>
        </div>


        @if(!empty($bill->patient?->nic))

            <div class="flex items-start justify-between gap-2">

                <span class="font-bold">
                    NIC
                </span>

                <span class="text-right">
                    {{ $bill->patient->nic }}
                </span>

            </div>

        @elseif(!empty($bill->patient?->passport_number))

            <div class="flex items-start justify-between gap-2">

                <span class="font-bold">
                    Passport
                </span>

                <span class="text-right">
                    {{ $bill->patient->passport_number }}
                </span>

            </div>

        @endif


        @if($bill->patient?->patient_type)

            <div class="flex items-start justify-between gap-2">

                <span class="font-bold">
                    Type
                </span>

                <span class="text-right">
                    {{ $bill->patient->patient_type }}
                </span>

            </div>

        @endif

    </div>


    {{-- Divider --}}
    <div class="my-2 border-t border-dashed border-gray-900"></div>


    {{-- =====================================================
         MEDICINE ITEMS
    ====================================================== --}}
    <div class="mb-1 text-[11px] font-extrabold uppercase tracking-wide">
        Medicine Details
    </div>


    {{-- Table Header --}}
    <div
        class="
            grid
            grid-cols-[1fr_9mm_17mm_18mm]
            gap-1
            border-b-2
            border-gray-900
            pb-1
            text-[9px]
            font-extrabold
        "
    >

        <div>
            ITEM
        </div>

        <div class="text-right">
            QTY
        </div>

        <div class="text-right">
            UNIT
        </div>

        <div class="text-right">
            TOTAL
        </div>

    </div>


    {{-- Medicine Rows --}}
    <div class="mt-1">

        @forelse($bill->items as $item)

            <div
                class="
                    mb-2
                    grid
                    grid-cols-[1fr_9mm_17mm_18mm]
                    gap-1
                    text-[9px]
                "
            >

                {{-- Medicine --}}
                <div
                    class="
                        min-w-0
                        font-semibold
                        leading-tight
                        break-words
                    "
                >

                    {{ $item->medicine?->name ?? $item->medicine_name ?? 'Medicine' }}

                    <div class="mt-0.5 text-[8px] text-gray-600">
                        Code: {{ $item->medicine?->code ?? '-' }}
                    </div>
                </div>


                {{-- Quantity --}}
                <div class="text-right whitespace-nowrap">

                    {{ rtrim(
                        rtrim(
                            number_format((float) $item->quantity, 2, '.', ''),
                            '0'
                        ),
                        '.'
                    ) }}

                </div>


                {{-- Unit Price --}}
                <div class="text-right whitespace-nowrap">

                    {{ $bill->currency }}
                    {{ number_format((float) $item->unit_price, 2) }}

                </div>


                {{-- Total --}}
                <div class="text-right font-bold whitespace-nowrap">

                    {{ $bill->currency }}
                    {{ number_format((float) $item->total, 2) }}

                </div>

            </div>

        @empty

            <div class="py-2 text-center text-[10px] text-gray-600">
                No medicines
            </div>

        @endforelse

    </div>


    {{-- Divider --}}
    <div class="my-2 border-t-2 border-gray-900"></div>


    {{-- =====================================================
         PAYMENT SUMMARY
    ====================================================== --}}
    <div class="mb-1 text-[11px] font-extrabold uppercase tracking-wide">
        Payment Summary
    </div>


    <div class="space-y-1 text-[10px]">


        {{-- Subtotal --}}
        <div class="flex items-center justify-between gap-2">

            <span class="font-semibold">
                SUBTOTAL
            </span>

            <span class="font-semibold whitespace-nowrap">

                {{ $bill->currency }}
                {{ number_format((float) $bill->subtotal, 2) }}

            </span>

        </div>


        {{-- Discount --}}
        @if((float) $bill->discount > 0)

            <div class="flex items-center justify-between gap-2">

                <span class="font-semibold">
                    DISCOUNT
                </span>

                <span class="font-semibold whitespace-nowrap">

                    -
                    {{ $bill->currency }}
                    {{ number_format((float) $bill->discount, 2) }}

                </span>

            </div>

        @endif


        {{-- Grand Total --}}
        <div
            class="
                mt-1
                flex
                items-center
                justify-between
                gap-2
                border-t
                border-gray-400
                pt-1
            "
        >

            <span class="text-[13px] font-extrabold">
                GRAND TOTAL
            </span>

            <span class="text-[13px] font-extrabold whitespace-nowrap">

                {{ $bill->currency }}
                {{ number_format((float) $bill->grand_total, 2) }}

            </span>

        </div>

    </div>


    {{-- =====================================================
         PAYMENT DETAILS
    ====================================================== --}}
    <div class="my-2 border-t-2 border-gray-900"></div>


    <div class="mb-1 text-[11px] font-extrabold uppercase tracking-wide">
        Payment Details
    </div>


    <div class="space-y-1 text-[10px]">


        {{-- Amount Paid --}}
        <div class="flex items-center justify-between gap-2">

            <span class="font-bold">
                PAID
            </span>

            <span class="font-extrabold whitespace-nowrap">

                {{ $bill->currency }}
                {{ number_format((float) $bill->grand_total, 2) }}

            </span>

        </div>


        {{-- Received --}}
        <div class="flex items-center justify-between gap-2">

            <span class="font-semibold">
                RECEIVED
            </span>

            <span class="font-semibold whitespace-nowrap">

                {{ $bill->currency }}
                {{ number_format((float) $bill->amount_received, 2) }}

            </span>

        </div>


        {{-- Change --}}
        @if((float) $bill->change_amount > 0)

            <div
                class="
                    flex
                    items-center
                    justify-between
                    gap-2
                "
            >

                <span class="font-bold">
                    CHANGE
                </span>

                <span class="font-extrabold whitespace-nowrap">

                    {{ $bill->currency }}
                    {{ number_format((float) $bill->change_amount, 2) }}

                </span>

            </div>

        @endif


        {{-- Balance --}}
        <div class="flex items-center justify-between gap-2">

            <span class="font-semibold">
                BALANCE
            </span>

            <span class="font-semibold whitespace-nowrap">

                {{ $bill->currency }}
                {{ number_format((float) $bill->balance_due, 2) }}

            </span>

        </div>

    </div>


    {{-- =====================================================
         PAYMENT METHOD
    ====================================================== --}}
    <div class="my-2 border-t border-dashed border-gray-900"></div>


    <div class="space-y-1 text-[10px]">


        <div class="flex items-start justify-between gap-2">

            <span class="font-bold">
                Payment Method
            </span>

            <span class="text-right font-semibold">

                @switch($bill->payment_method)

                    @case('cash')
                        Cash
                        @break

                    @case('card')
                        Card
                        @break

                    @case('bank_transfer')
                        Bank Transfer
                        @break

                    @case('online')
                        Online Payment
                        @break

                    @default
                        {{ $bill->payment_method ?? '-' }}

                @endswitch

            </span>

        </div>


        @if(!empty($bill->payment_reference))

            <div class="flex items-start justify-between gap-2">

                <span class="font-bold">
                    Reference
                </span>

                <span class="max-w-[45mm] text-right break-all">
                    {{ $bill->payment_reference }}
                </span>

            </div>

        @endif

    </div>


    {{-- =====================================================
         PAYMENT STATUS
    ====================================================== --}}
    <div class="my-3 text-center">

        <div
            class="
                inline-block
                border-2
                border-gray-900
                px-5
                py-1
                text-[13px]
                font-extrabold
                tracking-[1px]
            "
        >
            {{ strtoupper($bill->payment_status ?? 'PAID') }}
        </div>

    </div>


    {{-- =====================================================
         CASHIER
    ====================================================== --}}
    @if($bill->paidBy)

        <div class="mb-2 flex items-center justify-between gap-2 text-[10px]">

            <span class="font-bold">
                Cashier
            </span>

            <span class="text-right font-semibold">
                {{ $bill->paidBy->name }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         FOOTER
    ====================================================== --}}
    <div class="my-2 border-t border-dashed border-gray-900"></div>


    <div class="avoid-break text-center">

        <div class="text-[13px] font-extrabold">
            THANK YOU!
        </div>

        <div class="mt-1 text-[9px] text-gray-700">
            Thank you for choosing ISD Tech Hub (Pvt) Ltd.
        </div>

        <div class="mt-0.5 text-[9px] text-gray-700">
            Please keep this receipt for your records.
        </div>

        <div class="mt-1 text-[9px] font-semibold">
            Ayurveda &amp; Wellness • Pharmacy
        </div>

    </div>


    {{-- =====================================================
         PRINT BUTTON
    ====================================================== --}}
    <button
        type="button"
        onclick="window.print()"
        class="
            print-button
            mt-4
            w-full
            rounded-lg
            bg-gray-900
            px-4
            py-2.5
            text-[12px]
            font-bold
            text-white
            shadow-sm
            hover:bg-gray-800
        "
    >
        🖨 Print Receipt
    </button>


</div>


<script>
    /*
     * Optional:
     * Automatically open print dialog when receipt page loads.
     *
     * Keep this commented for now.
     *
     * window.addEventListener('load', function () {
     *     window.print();
     * });
     */
</script>

</body>
</html>
