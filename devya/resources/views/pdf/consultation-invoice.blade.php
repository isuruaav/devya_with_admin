<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $bill->bill_number }}</title>
    <style>
        @page {
            margin: 4mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            width: 72mm;
            margin: 0 auto;
            color: #111;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .muted {
            color: #555;
        }

        .rule {
            border-top: 1px dashed #111;
            margin: 7px 0;
        }

        .row {
            display: table;
            width: 100%;
            table-layout: fixed;
            padding: 2px 0;
        }

        .row > span:first-child {
            display: table-cell;
            width: 42%;
        }

        .row > span:last-child {
            display: table-cell;
            text-align: right;
            word-wrap: break-word;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 3px 0;
            vertical-align: top;
        }

        th {
            border-bottom: 1px solid #111;
            text-align: left;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        .total {
            border-top: 1px solid #111;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="center">
        <div class="bold" style="font-size:15px;">ISD Tech Hub (Pvt) Ltd</div>
        <div>Ayurveda &amp; Wellness Center</div>
        <div class="rule"></div>
        <div class="bold" style="font-size:12px;">CONSULTATION BILL</div>
    </div>

    <div class="rule"></div>

    <div class="row">
        <span class="bold">Bill No</span>
        <span>{{ $bill->bill_number }}</span>
    </div>
    <div class="row">
        <span>Consultation</span>
        <span>{{ $bill->consultation?->consultation_number ?? '-' }}</span>
    </div>
    <div class="row">
        <span>Date</span>
        <span>{{ $bill->created_at?->format('d M Y h:i A') }}</span>
    </div>
    <div class="row">
        <span>Patient</span>
        <span>{{ $bill->consultation?->patient?->full_name ?? '-' }}</span>
    </div>
    <div class="row">
        <span>Doctor</span>
        <span>{{ $bill->consultation?->doctor?->name ?? '-' }}</span>
    </div>

    <div class="rule"></div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @if ((float) $bill->doctor_fee > 0)
                <tr>
                    <td>Doctor Consultation Fee</td>
                    <td class="amount">{{ $bill->currency }} {{ number_format((float) $bill->doctor_fee, 2) }}</td>
                </tr>
            @endif

            @foreach ($bill->consultation?->treatments ?? [] as $item)
                @php
                    $quantity = (float) ($item->quantity ?? 1);
                    $quantity = $quantity > 0 ? $quantity : 1;
                    $unitPrice = (float) ($item->price ?? 0);
                    $lineTotal = $unitPrice * $quantity;
                @endphp
                <tr>
                    <td>
                        <span class="bold">{{ $item->treatment?->name ?? 'Treatment' }}</span><br>
                        <span class="muted">
                            Qty {{ rtrim(rtrim(number_format($quantity, 2, '.', ''), '0'), '.') }}
                            × {{ $bill->currency }} {{ number_format($unitPrice, 2) }}
                        </span>
                    </td>
                    <td class="amount">{{ $bill->currency }} {{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="rule"></div>

    <div class="row total">
        <span>GROSS TOTAL</span>
        <span>{{ $bill->currency }} {{ number_format((float) $bill->grand_total, 2) }}</span>
    </div>
    @if ((float) $bill->appointment_paid > 0)
        <div class="row">
            <span>CHANNELING PAID</span>
            <span>-{{ $bill->currency }} {{ number_format((float) $bill->appointment_paid, 2) }}</span>
        </div>
    @endif
    <div class="row total">
        <span>BALANCE DUE</span>
        <span>{{ $bill->currency }} {{ number_format((float) $bill->balance_due, 2) }}</span>
    </div>

    <div class="rule"></div>
    <div class="center">Medicine charges are billed separately by Pharmacy.</div>
    <div class="center" style="margin-top:8px;">Thank you.</div>
</body>
</html>
