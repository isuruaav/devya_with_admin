<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Appointment Bill</title>

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
            width: 80mm;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            color: #111111;
        }

        body {
            font-size: 11px;
        }

        .receipt {
            width: 72mm;
            margin: 0 auto;
            padding: 5mm 0;
        }

        .center {
            text-align: center;
        }

        .clinic-name {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 2px;
        }

        .clinic-subtitle {
            font-size: 10px;
            color: #555555;
            margin-bottom: 8px;
        }

        .title {
            font-size: 14px;
            font-weight: 800;
            margin: 8px 0;
        }

        .line {
            border-top: 1px dashed #222222;
            margin: 7px 0;
        }

        .row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .label,
        .value {
            display: table-cell;
            vertical-align: top;
        }

        .label {
            width: 38%;
            font-weight: 700;
        }

        .value {
            width: 62%;
            text-align: right;
            word-break: break-word;
        }


        /*
        |--------------------------------------------------------------------------
        | TOKEN NUMBER
        |--------------------------------------------------------------------------
        */

        .token-box {
            margin: 8px 0 10px;
            padding: 8px 0 9px;
            text-align: center;
            border: 1.5px solid #111111;
            border-radius: 6px;
        }

        .token-label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .token-number {
            margin-top: 2px;
            font-size: 27px;
            line-height: 1;
            font-weight: 900;
        }


        /*
        |--------------------------------------------------------------------------
        | DOCTOR
        |--------------------------------------------------------------------------
        */

        .doctor-box {
            text-align: center;
            margin: 8px 0;
            padding: 7px 0;
        }

        .doctor-name {
            font-size: 13px;
            font-weight: 800;
        }

        .room {
            margin-top: 3px;
            font-size: 11px;
        }


        /*
        |--------------------------------------------------------------------------
        | AMOUNT
        |--------------------------------------------------------------------------
        */

        .amount-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .amount-table td {
            padding: 4px 0;
        }

        .amount-right {
            text-align: right;
        }

        .total {
            font-size: 15px;
            font-weight: 800;
            padding-top: 7px !important;
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        .status {
            margin: 10px 0;
            text-align: center;
            font-size: 13px;
            font-weight: 800;
            border: 1px solid #111111;
            padding: 6px;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .footer {
            text-align: center;
            margin-top: 12px;
            font-size: 9px;
            color: #666666;
            line-height: 1.5;
        }


        /*
        |--------------------------------------------------------------------------
        | PRINT BUTTON
        |--------------------------------------------------------------------------
        */

        .no-print {
            margin: 15px auto;
            width: 72mm;
            text-align: center;
        }

        .no-print button {
            border: 0;
            background: #111111;
            color: #ffffff;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }

        @media print {

            .no-print {
                display: none !important;
            }

            body {
                width: 80mm;
            }
        }
    </style>
</head>

<body>

<div class="receipt">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="center">

        <div class="clinic-name">
            AYU SYSTEM
        </div>

        <div class="clinic-subtitle">
            Ayurveda Wellness & Healthcare
        </div>

        <div class="title">
            APPOINTMENT BILL
        </div>

    </div>


    <div class="line"></div>


    {{-- =========================================================
         INVOICE
    ========================================================== --}}

    <div class="row">

        <div class="label">
            Invoice
        </div>

        <div class="value">
            {{ $appointment->invoice_number }}
        </div>

    </div>


    {{-- =========================================================
         TOKEN NUMBER
    ========================================================== --}}

    <div class="token-box">

        <div class="token-label">
            Token No
        </div>

        <div class="token-number">
            {{ filled($appointment->token_number)
                ? str_pad(
                    (string) $appointment->token_number,
                    2,
                    '0',
                    STR_PAD_LEFT
                )
                : '—'
            }}
        </div>

    </div>


    {{-- =========================================================
         BILL DATE
    ========================================================== --}}

    <div class="row">

        <div class="label">
            Date
        </div>

        <div class="value">
            {{ optional($appointment->invoice_issued_at)->format('d/m/Y') }}
        </div>

    </div>


    <div class="line"></div>


    {{-- =========================================================
         PATIENT
    ========================================================== --}}

    <div class="row">

        <div class="label">
            Patient
        </div>

        <div class="value">
            {{ $appointment->full_name }}
        </div>

    </div>


    <div class="row">

        <div class="label">
            NIC/Passport
        </div>

        <div class="value">
            {{ $appointment->nic_or_passport }}
        </div>

    </div>


    @if ($appointment->phone_number)

        <div class="row">

            <div class="label">
                Phone
            </div>

            <div class="value">
                {{ $appointment->phone_number }}
            </div>

        </div>

    @endif


    <div class="line"></div>


    {{-- =========================================================
         DOCTOR
    ========================================================== --}}

    <div class="doctor-box">

        <div class="doctor-name">

            {{ $appointment->doctor?->name ?? 'Doctor' }}

        </div>


        <div class="room">

            {{
                $appointment->doctor?->room_number
                    ? 'Consulting Room: Room ' .
                        $appointment->doctor->room_number
                    : 'Consulting Room: Not Assigned'
            }}

        </div>


        <div class="room">

            {{
                optional(
                    $appointment->appointment_date
                )->format(
                    'd M Y, h:i A'
                )
            }}

        </div>

    </div>


    <div class="line"></div>


    {{-- =========================================================
         AMOUNT
    ========================================================== --}}

    <table class="amount-table">

        <tr>

            <td>
                Doctor Appointment Fee
            </td>

            <td class="amount-right">

                {{ $appointment->invoice_currency }}

                {{
                    number_format(
                        (float)
                            $appointment->appointment_fee,
                        2
                    )
                }}

            </td>

        </tr>

        <tr>

            <td>
                Facilities / Service Fee
            </td>

            <td class="amount-right">

                {{ $appointment->invoice_currency }}

                {{
                    number_format(
                        (float)
                            $appointment->facility_service_fee,
                        2
                    )
                }}

            </td>

        </tr>


        <tr>

            <td class="total">
                TOTAL
            </td>

            <td class="amount-right total">

                {{ $appointment->invoice_currency }}

                {{
                    number_format(
                        (float)
                            $appointment->invoice_total,
                        2
                    )
                }}

            </td>

        </tr>

    </table>


    {{-- =========================================================
         PAYMENT STATUS
    ========================================================== --}}

    <div class="status">

        PAYMENT:

        {{
            strtoupper(
                $appointment->payment_status
                ?? 'UNPAID'
            )
        }}

    </div>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <div class="footer">

        Please keep this bill for your records.<br>

        Thank you.

    </div>

</div>


{{-- =============================================================
     PRINT BUTTON
============================================================= --}}

<div class="no-print">

    <button onclick="window.print()">
        PRINT BILL
    </button>

</div>


<script>

    window.addEventListener(
        'load',
        function () {

            setTimeout(
                function () {

                    window.print();

                },
                400
            );

        }
    );

</script>

</body>
</html>
