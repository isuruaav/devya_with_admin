<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Payment Receipt</title>

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
        }

        .subtitle {
            font-size: 10px;
            color: #555555;
            margin-top: 2px;
        }

        .title {
            font-size: 14px;
            font-weight: 800;
            margin-top: 8px;
        }

        .paid {
            margin: 10px 0;
            padding: 6px;
            border: 2px solid #111111;
            text-align: center;
            font-size: 15px;
            font-weight: 900;
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
            width: 40%;
            font-weight: 700;
        }

        .value {
            width: 60%;
            text-align: right;
            word-break: break-word;
        }

        .total-box {
            margin-top: 10px;
            border-top: 1px solid #111111;
            border-bottom: 1px solid #111111;
            padding: 8px 0;
        }

        .total {
            display: table;
            width: 100%;
            font-size: 15px;
            font-weight: 900;
        }

        .total-label,
        .total-value {
            display: table-cell;
        }

        .total-value {
            text-align: right;
        }

        .footer {
            margin-top: 14px;
            text-align: center;
            font-size: 9px;
            color: #666666;
            line-height: 1.5;
        }

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
            cursor: pointer;
            font-size: 12px;
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

    <div class="center">

        <div class="clinic-name">
            AYU SYSTEM
        </div>

        <div class="subtitle">
            Ayurveda Wellness & Healthcare
        </div>

        <div class="title">
            PAYMENT RECEIPT
        </div>

    </div>

    <div class="paid">
        PAID
    </div>

    <div class="line"></div>

    <div class="row">
        <div class="label">Receipt No</div>

        <div class="value">
            {{ $appointment->receipt_number }}
        </div>
    </div>

    <div class="row">
        <div class="label">Invoice No</div>

        <div class="value">
            {{ $appointment->invoice_number }}
        </div>
    </div>

    <div class="row">
        <div class="label">Booking No</div>

        <div class="value">
            {{ $appointment->booking_number }}
        </div>
    </div>

    <div class="row">
        <div class="label">Paid At</div>

        <div class="value">
            {{ optional($appointment->paid_at)->format('d/m/Y h:i A') }}
        </div>
    </div>

    <div class="line"></div>

    <div class="row">
        <div class="label">Patient</div>

        <div class="value">
            {{ $appointment->full_name }}
        </div>
    </div>

    <div class="row">
        <div class="label">Doctor</div>

        <div class="value">
            {{ $appointment->doctor?->name ?? 'N/A' }}
        </div>
    </div>

    <div class="row">
        <div class="label">Room</div>

        <div class="value">
            {{
                $appointment->doctor?->room_number
                    ? 'Room ' . $appointment->doctor->room_number
                    : 'Not Assigned'
            }}
        </div>
    </div>

    <div class="row">
        <div class="label">Payment</div>

        <div class="value">
            {{ $appointment->payment_method }}
        </div>
    </div>

    @if($appointment->payment_reference)
        <div class="row">
            <div class="label">Reference</div>

            <div class="value">
                {{ $appointment->payment_reference }}
            </div>
        </div>
    @endif

    <div class="total-box">

        <div class="total">

            <div class="total-label">
                Doctor Appointment Fee
            </div>

            <div class="total-value">
                {{ $appointment->invoice_currency }}
                {{ number_format((float) $appointment->appointment_fee, 2) }}
            </div>

        </div>

        <div class="total">

            <div class="total-label">
                Facilities / Service Fee
            </div>

            <div class="total-value">
                {{ $appointment->invoice_currency }}
                {{ number_format((float) $appointment->facility_service_fee, 2) }}
            </div>

        </div>

        <div class="total">

            <div class="total-label">
                Paid Total
            </div>

            <div class="total-value">
                {{ $appointment->invoice_currency }}
                {{ number_format((float) $appointment->invoice_total, 2) }}
            </div>

        </div>

    </div>

    <div class="footer">
        Payment received successfully.<br>
        Thank you for choosing AYU SYSTEM.
    </div>

</div>

<div class="no-print">
    <button onclick="window.print()">
        PRINT RECEIPT
    </button>
</div>

<script>
    window.addEventListener('load', function () {
        setTimeout(function () {
            window.print();
        }, 400);
    });
</script>

</body>
</html>
