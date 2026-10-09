<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Payment Receipt</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .paid {
            margin: 20px auto;
            width: 180px;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border: 2px solid #15803d;
            color: #15803d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 7px;
            vertical-align: top;
        }

        .amount {
            margin-top: 25px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }

        .footer {
            margin-top: 45px;
            text-align: center;
            color: #777;
            font-size: 10px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>PAYMENT RECEIPT</h1>
    <p>Ayu System</p>
</div>

<div class="paid">
    PAID
</div>

<table>
    <tr>
        <td>
            <strong>Receipt No:</strong><br>
            {{ $appointment->receipt_number }}
        </td>

        <td>
            <strong>Invoice No:</strong><br>
            {{ $appointment->invoice_number }}
        </td>
    </tr>

    <tr>
        <td>
            <strong>Booking No:</strong><br>
            {{ $appointment->booking_number }}
        </td>

        <td>
            <strong>Paid At:</strong><br>
            {{ optional($appointment->paid_at)->format('d M Y h:i A') }}
        </td>
    </tr>

    <tr>
        <td>
            <strong>Patient:</strong><br>
            {{ $appointment->full_name }}
        </td>

        <td>
            <strong>Payment Method:</strong><br>
            {{ $appointment->payment_method }}
        </td>
    </tr>

    @if($appointment->payment_reference)
        <tr>
            <td colspan="2">
                <strong>Payment Reference:</strong><br>
                {{ $appointment->payment_reference }}
            </td>
        </tr>
    @endif

    <tr>
        <td>
            <strong>Doctor:</strong><br>
            {{ $appointment->doctor?->name ?? 'N/A' }}
        </td>

        <td>
            <strong>Room:</strong><br>
            {{
                $appointment->doctor?->room_number
                    ? 'Room ' . $appointment->doctor->room_number
                    : 'Not Assigned'
            }}
        </td>
    </tr>
</table>

<div class="amount">
    Doctor Appointment Fee:
    {{ $appointment->invoice_currency }}
    {{ number_format((float) $appointment->appointment_fee, 2) }}
    <br>
    Facilities / Service Fee:
    {{ $appointment->invoice_currency }}
    {{ number_format((float) $appointment->facility_service_fee, 2) }}
    <br>
    Paid Total:
    {{ $appointment->invoice_currency }}
    {{ number_format((float) $appointment->invoice_total, 2) }}
</div>

<div class="footer">
    Payment received successfully. Thank you.
</div>

</body>
</html>
