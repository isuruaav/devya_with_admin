<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your consultation invoice</title>
</head>
<body style="margin:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="max-width:620px;margin:32px auto;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
        <div style="background:#047857;padding:28px 32px;color:#ffffff;">
            <div style="font-size:12px;letter-spacing:1px;text-transform:uppercase;color:#a7f3d0;">ISD Tech Hub (Pvt) Ltd</div>
            <h1 style="margin:8px 0 0;font-size:24px;">Your consultation invoice</h1>
        </div>
        <div style="padding:32px;">
            <p>Dear {{ $bill->consultation?->patient?->full_name ?? 'Patient' }},</p>
            <p>Thank you for choosing ISD Tech Hub (Pvt) Ltd. Your 80mm thermal invoice is attached as a PDF.</p>
            <div style="margin:24px 0;padding:20px;background:#ecfdf5;border-radius:12px;">
                <p style="margin:0 0 8px;color:#065f46;font-size:13px;">Invoice number</p>
                <strong style="font-size:18px;">{{ $bill->bill_number }}</strong>
                <p style="margin:16px 0 0;color:#065f46;font-size:13px;">Total amount</p>
                <strong style="font-size:22px;color:#047857;">
                    {{ $bill->currency }}
                    {{ number_format((float) $bill->grand_total, 2) }}
                </strong>
            </div>
            <p style="margin:24px 0;">
                <a href="{{ $printUrl }}" style="display:inline-block;border-radius:8px;background:#047857;padding:12px 18px;color:#ffffff;text-decoration:none;font-weight:bold;">
                    View / Print 80mm Invoice
                </a>
            </p>
            <p style="font-size:14px;color:#6b7280;">The secure print link expires in 30 days. Please keep the attached invoice for your records. If you have any questions, contact our reception team.</p>
            <p style="margin-top:28px;">Warm regards,<br><strong>ISD Tech Hub (Pvt) Ltd</strong><br><span style="color:#6b7280;">{{ config('mail.from.address') }}</span></p>
        </div>
        <div style="padding:18px 32px;background:#f9fafb;color:#6b7280;font-size:12px;text-align:center;">
            This is an automated email. Please do not reply with sensitive medical information.
        </div>
    </div>
</body>
</html>
