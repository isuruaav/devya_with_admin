<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your pharmacy invoice</title>
</head>
<body style="margin:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="max-width:620px;margin:32px auto;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
        <div style="background:#047857;padding:28px 32px;color:#ffffff;">
            <div style="font-size:12px;letter-spacing:1px;text-transform:uppercase;color:#a7f3d0;">ISD Tech Hub (Pvt) Ltd</div>
            <h1 style="margin:8px 0 0;font-size:24px;">Your pharmacy invoice</h1>
        </div>
        <div style="padding:32px;">
            <p>Dear {{ $bill->patient?->full_name ?? 'Customer' }},</p>
            <p>Your itemized pharmacy invoice is attached as an 80mm thermal PDF.</p>
            <div style="margin:24px 0;padding:20px;background:#ecfdf5;border-radius:12px;">
                <p style="margin:0 0 8px;color:#065f46;font-size:13px;">Invoice number</p>
                <strong style="font-size:18px;">{{ $bill->bill_number }}</strong>
                <p style="margin:16px 0 0;color:#065f46;font-size:13px;">Total amount</p>
                <strong style="font-size:22px;color:#047857;">
                    {{ $bill->currency }} {{ number_format((float) $bill->grand_total, 2) }}
                </strong>
            </div>
            <p style="margin:24px 0;">
                <a href="{{ $printUrl }}" style="display:inline-block;border-radius:8px;background:#047857;padding:12px 18px;color:#ffffff;text-decoration:none;font-weight:bold;">
                    View / Print 80mm Invoice
                </a>
            </p>
            <p style="font-size:14px;color:#6b7280;">The secure print link expires in 30 days. This invoice covers pharmacy medicines only; consultation and treatment charges are billed separately.</p>
            <p style="margin-top:28px;">Warm regards,<br><strong>ISD Tech Hub (Pvt) Ltd</strong></p>
        </div>
    </div>
</body>
</html>
