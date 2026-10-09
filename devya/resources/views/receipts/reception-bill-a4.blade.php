<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Bill {{ $bill->bill_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 16mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #172033;
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }

        .invoice {
            max-width: 178mm;
            margin: 0 auto;
        }

        .header,
        .bill-meta,
        .totals {
            display: flex;
            justify-content: space-between;
            gap: 24px;
        }

        .header {
            align-items: flex-start;
            border-bottom: 2px solid #173b2c;
            padding-bottom: 18px;
        }

        .business-name {
            color: #173b2c;
            font-size: 24px;
            font-weight: 700;
        }

        .document-title {
            color: #173b2c;
            font-size: 20px;
            font-weight: 700;
            text-align: right;
        }

        .muted {
            margin-top: 5px;
            color: #64748b;
        }

        .bill-meta {
            margin: 24px 0;
        }

        .meta-label {
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
        }

        .meta-value {
            margin-top: 5px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #dbe2e8;
            padding: 10px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f1f5f3;
            color: #173b2c;
            font-size: 11px;
            text-transform: uppercase;
        }

        .numeric {
            text-align: right;
            white-space: nowrap;
        }

        .totals {
            justify-content: flex-end;
            margin-top: 20px;
        }

        .totals table {
            width: 270px;
        }

        .totals td {
            border: 0;
            padding: 6px 0;
        }

        .grand-total td {
            border-top: 2px solid #173b2c;
            color: #173b2c;
            font-size: 16px;
            font-weight: 700;
        }

        .payment-status {
            display: inline-block;
            margin-top: 24px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 7px 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 48px;
            border-top: 1px solid #dbe2e8;
            padding-top: 12px;
            color: #64748b;
            text-align: center;
        }

        .no-print {
            margin: 24px 0;
            text-align: center;
        }

        .no-print button {
            border: 0;
            border-radius: 5px;
            background: #166534;
            padding: 10px 18px;
            color: #fff;
            cursor: pointer;
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
    <main class="invoice">
        <header class="header">
            <div>
                <div class="business-name">ISD Tech Hub (Pvt) Ltd</div>
                <div class="muted">Ayurveda &amp; Wellness Center</div>
            </div>
            <div class="document-title">RECEPTION BILL</div>
        </header>

        <section class="bill-meta">
            <div>
                <div class="meta-label">Bill No.</div>
                <div class="meta-value">{{ $bill->bill_number }}</div>
            </div>
            <div>
                <div class="meta-label">Date</div>
                <div class="meta-value">{{ $bill->created_at?->format('d M Y h:i A') }}</div>
            </div>
            <div>
                <div class="meta-label">Customer</div>
                <div class="meta-value">{{ $bill->patient?->full_name ?: 'Walk-in sale' }}</div>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Category</th>
                    <th class="numeric">Qty</th>
                    <th class="numeric">Unit Price</th>
                    <th class="numeric">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bill->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $item->category)) }}</td>
                        <td class="numeric">{{ rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ''), '0'), '.') }}</td>
                        <td class="numeric">{{ $bill->currency }} {{ number_format((float) $item->unit_price, 2) }}</td>
                        <td class="numeric">{{ $bill->currency }} {{ number_format((float) $item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <section class="totals">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td class="numeric">{{ $bill->currency }} {{ number_format((float) $bill->subtotal, 2) }}</td>
                </tr>
                @if ((float) $bill->discount > 0)
                    <tr>
                        <td>Discount</td>
                        <td class="numeric">-{{ $bill->currency }} {{ number_format((float) $bill->discount, 2) }}</td>
                    </tr>
                @endif
                <tr class="grand-total">
                    <td>Grand Total</td>
                    <td class="numeric">{{ $bill->currency }} {{ number_format((float) $bill->grand_total, 2) }}</td>
                </tr>
                <tr>
                    <td>Amount Received</td>
                    <td class="numeric">{{ $bill->currency }} {{ number_format((float) $bill->amount_received, 2) }}</td>
                </tr>
                @if ((float) $bill->change_amount > 0)
                    <tr>
                        <td>Change</td>
                        <td class="numeric">{{ $bill->currency }} {{ number_format((float) $bill->change_amount, 2) }}</td>
                    </tr>
                @endif
                @if ((float) $bill->balance_due > 0)
                    <tr>
                        <td>Balance Due</td>
                        <td class="numeric">{{ $bill->currency }} {{ number_format((float) $bill->balance_due, 2) }}</td>
                    </tr>
                @endif
            </table>
        </section>

        <div class="payment-status">
            Payment Status: {{ $bill->payment_status }}
        </div>
        @if ($bill->receipt_number)
            <div class="muted">Receipt No.: {{ $bill->receipt_number }}</div>
        @endif

        <footer class="footer">Thank you. Please keep this bill for your records.</footer>

        <div class="no-print">
            <button type="button" onclick="window.print()">Print Bill</button>
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
