<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
        <div class="bold" style="font-size:12px;">PHARMACY BILL</div>
    </div>

    <div class="rule"></div>

    <div class="row">
        <span class="bold">Bill No</span>
        <span>{{ $bill->bill_number }}</span>
    </div>
    <div class="row">
        <span>Date</span>
        <span>{{ $bill->created_at?->format('d M Y h:i A') }}</span>
    </div>
    <div class="row">
        <span>Patient</span>
        <span>{{ $bill->patient?->full_name ?? 'Walk-in Customer' }}</span>
    </div>

    <div class="rule"></div>

    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th class="amount">Qty</th>
                <th class="amount">Price</th>
                <th class="amount">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bill->items as $item)
                <tr>
                    <td>
                        <span class="bold">{{ $item->medicine?->code ?? '-' }}</span><br>
                        <span>{{ $item->medicine_name }}</span>
                    </td>
                    <td class="amount">{{ rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ''), '0'), '.') }}</td>
                    <td class="amount">{{ number_format((float) $item->unit_price, 2) }}</td>
                    <td class="amount">{{ number_format((float) $item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="rule"></div>

    <div class="row">
        <span>Subtotal</span>
        <span>{{ $bill->currency }} {{ number_format((float) $bill->subtotal, 2) }}</span>
    </div>
    @if ((float) $bill->discount > 0)
        <div class="row">
            <span>Discount</span>
            <span>-{{ $bill->currency }} {{ number_format((float) $bill->discount, 2) }}</span>
        </div>
    @endif
    <div class="row total">
        <span>GRAND TOTAL</span>
        <span>{{ $bill->currency }} {{ number_format((float) $bill->grand_total, 2) }}</span>
    </div>
    @if ((float) $bill->amount_received > 0)
        <div class="row">
            <span>Paid</span>
            <span>{{ $bill->currency }} {{ number_format((float) $bill->amount_received, 2) }}</span>
        </div>
    @endif
    @if ((float) $bill->balance_due > 0)
        <div class="row">
            <span>Balance Due</span>
            <span>{{ $bill->currency }} {{ number_format((float) $bill->balance_due, 2) }}</span>
        </div>
    @endif

    <div class="rule"></div>
    <div class="center">Consultation and treatment charges are billed separately.</div>
    <div class="center" style="margin-top:8px;">Thank you.</div>
</body>
</html>
