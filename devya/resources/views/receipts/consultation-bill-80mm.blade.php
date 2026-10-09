<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Consultation Bill - {{ $bill->bill_number }}
    </title>

    @vite(['resources/css/app.css'])

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 80mm;
            margin: 0;
            padding: 0;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="m-0 bg-white p-0 font-sans text-[12px] text-black">

    <div class="mx-auto w-[80mm] px-[5mm] py-[4mm]">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="text-center">

            <div class="text-[17px] font-bold leading-tight">
                ISD Tech Hub (Pvt) Ltd
            </div>

            <div class="mt-1 text-[11px]">
                Ayurveda & Wellness Center
            </div>

            <div class="my-2 border-y border-dashed border-black py-1.5 text-[15px] font-bold">
                CONSULTATION BILL
            </div>

        </div>


        {{-- =====================================================
             BILL INFORMATION
        ====================================================== --}}

        <div class="mb-2">

            <div class="flex justify-between gap-2 py-0.5">

                <span class="font-bold">
                    Bill No
                </span>

                <span class="text-right">
                    {{ $bill->bill_number }}
                </span>

            </div>


            <div class="flex justify-between gap-2 py-0.5">

                <span>
                    Consultation
                </span>

                <span class="text-right">
                    {{ $bill->consultation?->consultation_number ?? '-' }}
                </span>

            </div>


            <div class="flex justify-between gap-2 py-0.5">

                <span>
                    Date
                </span>

                <span class="text-right">
                    {{ $bill->created_at?->format('d M Y h:i A') }}
                </span>

            </div>

        </div>


        <div class="my-2 border-t border-dashed border-black"></div>


        {{-- =====================================================
             PATIENT INFORMATION
        ====================================================== --}}

        <div class="mb-2">

            <div class="flex justify-between gap-2 py-0.5">

                <span class="font-bold">
                    Patient
                </span>

                <span class="max-w-[48mm] text-right">
                    {{ $bill->consultation?->patient?->full_name ?? '-' }}
                </span>

            </div>


            @if(!empty($bill->consultation?->patient?->nic))

                <div class="flex justify-between gap-2 py-0.5">

                    <span>
                        NIC
                    </span>

                    <span class="text-right">
                        {{ $bill->consultation->patient->nic }}
                    </span>

                </div>

            @endif


            @if(!empty($bill->consultation?->patient?->passport_number))

                <div class="flex justify-between gap-2 py-0.5">

                    <span>
                        Passport
                    </span>

                    <span class="text-right">
                        {{ $bill->consultation->patient->passport_number }}
                    </span>

                </div>

            @endif


            <div class="flex justify-between gap-2 py-0.5">

                <span>
                    Doctor
                </span>

                <span class="max-w-[48mm] text-right">
                    {{ $bill->consultation?->doctor?->name ?? '-' }}
                </span>

            </div>

        </div>


        <div class="my-2 border-t border-dashed border-black"></div>


        {{-- =====================================================
             BILL ITEMS
             
             ONLY:
             Doctor Fee
             Treatments

             NO MEDICINE PRICE
        ====================================================== --}}

        <table class="w-full border-collapse">

            <thead>

                <tr class="border-b border-black">

                    <th class="py-1 text-left font-bold">
                        Description
                    </th>

                    <th class="whitespace-nowrap py-1 text-right font-bold">
                        Amount
                    </th>

                </tr>

            </thead>


            <tbody>

                {{-- DOCTOR FEE --}}

                @if((float) $bill->doctor_fee > 0)

                    <tr>

                        <td class="py-1 align-top">
                            Doctor Consultation Fee
                        </td>

                        <td class="whitespace-nowrap py-1 text-right align-top">

                            {{ $bill->currency }}

                            {{ number_format(
                                (float) $bill->doctor_fee,
                                2
                            ) }}

                        </td>

                    </tr>

                @endif


                {{-- TREATMENTS --}}

                @foreach(
                    $bill->consultation?->treatments ?? []
                    as $item
                )

                    <tr>

                        <td class="py-1 align-top">

                            @php
                                $quantity = (float) ($item->quantity ?? 1);
                                $quantity = $quantity > 0 ? $quantity : 1;
                                $unitPrice = (float) ($item->price ?? 0);
                                $lineTotal = $unitPrice * $quantity;
                            @endphp

                            {{ $item->treatment?->name ?? 'Treatment' }}

                            <div class="text-[10px]">
                                Qty: {{ $quantity }}
                                × {{ $bill->currency }}
                                {{ number_format($unitPrice, 2) }}
                            </div>

                        </td>


                        <td class="whitespace-nowrap py-1 text-right align-top">

                            {{ $bill->currency }}

                            {{ number_format(
                                $lineTotal,
                                2
                            ) }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>


        {{-- =====================================================
             TOTALS
        ====================================================== --}}

        <div class="my-2 border-t border-dashed border-black"></div>


        {{-- GROSS TOTAL --}}

        <div class="flex justify-between gap-2 py-1 text-[13px] font-bold">

            <span>
                GROSS TOTAL
            </span>

            <span class="whitespace-nowrap">

                {{ $bill->currency }}

                {{ number_format(
                    (float) $bill->grand_total,
                    2
                ) }}

            </span>

        </div>


        {{-- CHANNELING PAID --}}

        @if((float) $bill->appointment_paid > 0)

            <div class="flex justify-between gap-2 py-1 font-bold">

                <span>
                    CHANNELING PAID
                </span>

                <span class="whitespace-nowrap">

                    -{{ $bill->currency }}

                    {{ number_format(
                        (float) $bill->appointment_paid,
                        2
                    ) }}

                </span>

            </div>

        @endif


        {{-- BALANCE DUE --}}

        @if((float) $bill->amount_received > 0)
            <div class="flex justify-between gap-2 py-1 font-bold">
                <span>Amount Received</span>
                <span class="whitespace-nowrap">
                    {{ $bill->currency }}
                    {{ number_format((float) $bill->amount_received, 2) }}
                </span>
            </div>
        @endif

        @if((float) $bill->change_amount > 0)
            <div class="flex justify-between gap-2 py-1">
                <span>Change</span>
                <span class="whitespace-nowrap">
                    {{ $bill->currency }}
                    {{ number_format((float) $bill->change_amount, 2) }}
                </span>
            </div>
        @endif

        <div class="my-1 flex justify-between gap-2 border-y border-black py-2 text-[14px] font-bold">

            <span>
                BALANCE DUE
            </span>

            <span class="whitespace-nowrap">

                {{ $bill->currency }}

                {{ number_format(
                    (float) $bill->balance_due,
                    2
                ) }}

            </span>

        </div>


        <div class="my-2 border-t border-dashed border-black"></div>
        <div class="text-center text-[10px]">
            Medicine charges are billed separately by Pharmacy.
        </div>


        {{-- =====================================================
             PAYMENT STATUS
        ====================================================== --}}

        <div class="my-2 border-t border-dashed border-black"></div>


        <div class="flex justify-between gap-2 py-1">

            <span>
                Payment Status
            </span>

            <span class="font-bold">

                @if($bill->payment_status === 'paid')

                    PAID

                @else

                    UNPAID

                @endif

            </span>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="mt-4 text-center text-[10px]">

            <div>
                Thank you.
            </div>

            <div class="mt-1">
                Please keep this bill for your records.
            </div>

        </div>


        {{-- =====================================================
             PRINT BUTTON
        ====================================================== --}}

        <button
            type="button"
            onclick="window.print()"
            class="no-print mt-4 w-full rounded bg-gray-800 px-3 py-2.5 text-[13px] font-semibold text-white hover:bg-gray-700"
        >
            PRINT BILL
        </button>

    </div>

    @if ($autoPrint ?? false)
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    window.print();
                }, 300);
            });
        </script>
    @endif
</body>

</html>
