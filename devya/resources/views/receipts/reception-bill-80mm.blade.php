<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Bill {{ $bill->bill_number }}</title>
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
            width: 80mm;
            margin: 0;
            padding: 0;
            color: #111;
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            font-size: 10px;
        }

        .receipt {
            width: 80mm;
            padding: 4mm;
        }

        .center {
            text-align: center;
        }

        .muted {
            color: #555;
        }

        .bold {
            font-weight: 700;
        }

        .divider {
            margin: 9px 0;
            border-top: 1px dashed #222;
        }

        .line {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 2px 0;
        }

        .description {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .amount {
            flex-shrink: 0;
            text-align: right;
            white-space: nowrap;
        }

        .item-meta {
            display: flex;
            justify-content: space-between;
            gap: 5px;
            margin-top: 2px;
            color: #555;
            font-size: 9px;
        }

        .total {
            border-top: 1px solid #222;
            padding-top: 5px;
            font-size: 12px;
            font-weight: 700;
        }

        .status {
            margin-top: 9px;
            border: 1px solid #222;
            padding: 5px;
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
        }

        .no-print {
            margin-top: 12px;
            text-align: center;
        }

        .no-print button {
            border: 0;
            border-radius: 5px;
            background: #166534;
            padding: 9px 16px;
            color: #fff;
            font-weight: 700;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <main class="receipt">
        <header class="center">
            <div class="bold" style="font-size:17px;">ISD Tech Hub (Pvt) Ltd</div>
            <div style="margin-top:3px;">Ayurveda &amp; Wellness Center</div>
            <div class="divider"></div>
            <div class="bold" style="font-size:14px;">RECEPTION BILL</div>
        </header>

        <div class="divider"></div>

        <div class="line"><span class="bold">Bill No</span><span class="amount">{{ $bill->bill_number }}</span></div>
        <div class="line"><span>Date</span><span class="amount">{{ $bill->created_at?->format('d M Y h:i A') }}</span></div>
        <div class="line"><span>Customer</span><span class="amount">{{ $bill->patient?->full_name ?: 'Walk-in sale' }}</span></div>

        <div class="divider"></div>

        @foreach ($bill->items as $item)
            <div class="line">
                <div class="description">
                    <div class="bold">{{ $item->description }}</div>
                    <div class="item-meta">
                        <span>{{ ucfirst(str_replace('_', ' ', $item->category)) }} · {{ rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ''), '0'), '.') }} × {{ $bill->currency }} {{ number_format((float) $item->unit_price, 2) }}</span>
                    </div>
                </div>
                <span class="amount">{{ $bill->currency }} {{ number_format((float) $item->total, 2) }}</span>
            </div>
        @endforeach

        <div class="divider"></div>
        <div class="line"><span>Subtotal</span><span class="amount">{{ $bill->currency }} {{ number_format((float) $bill->subtotal, 2) }}</span></div>
        @if ((float) $bill->discount > 0)
            <div class="line"><span>Discount</span><span class="amount">-{{ $bill->currency }} {{ number_format((float) $bill->discount, 2) }}</span></div>
        @endif
        <div class="line total"><span>TOTAL</span><span class="amount">{{ $bill->currency }} {{ number_format((float) $bill->grand_total, 2) }}</span></div>
        @if ((float) $bill->amount_received > 0)
            <div class="line"><span>Amount Received</span><span class="amount">{{ $bill->currency }} {{ number_format((float) $bill->amount_received, 2) }}</span></div>
        @endif
        @if ((float) $bill->change_amount > 0)
            <div class="line"><span>Change</span><span class="amount">{{ $bill->currency }} {{ number_format((float) $bill->change_amount, 2) }}</span></div>
        @endif
        @if ((float) $bill->balance_due > 0)
            <div class="line bold"><span>Balance Due</span><span class="amount">{{ $bill->currency }} {{ number_format((float) $bill->balance_due, 2) }}</span></div>
        @endif

        <div class="status">{{ $bill->payment_status }}</div>
        @if ($bill->receipt_number)
            <div class="line"><span>Receipt No</span><span class="amount">{{ $bill->receipt_number }}</span></div>
        @endif

        <div class="divider"></div>
        <div class="center">Thank you. Please keep this bill for your records.</div>

        <div class="no-print">
            <button type="button" onclick="window.print()" aria-label="Print reception bill">Print Bill</button>
        </div>
    </main>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
