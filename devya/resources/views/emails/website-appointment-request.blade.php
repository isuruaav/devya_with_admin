<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>New appointment request</title></head>
<body style="margin:0;padding:24px;background:#fdfcf8;color:#1b2a22;font-family:Arial,sans-serif;line-height:1.6;">
    <div style="max-width:640px;margin:auto;padding:28px;background:#ffffff;border:1px solid #dce7d7;border-top:3px solid #c9933a;border-radius:16px;">
        <p style="margin:0;color:#173f2b;font-weight:bold;">{{ config('branding.company_name') }}</p>
        <p style="margin:0 0 24px;color:#68746d;">{{ config('branding.hospital_label') }}</p>
        <h1 style="font-size:24px;color:#173f2b;">New website appointment request</h1>
        <p>A patient has submitted an appointment request. Please review it and confirm practitioner availability.</p>
        <table role="presentation" style="width:100%;border-collapse:collapse;text-align:left;">
            <tr><th style="padding:8px 12px 8px 0;">Booking number</th><td>{{ $appointment->booking_number }}</td></tr>
            <tr><th style="padding:8px 12px 8px 0;">Patient name</th><td>{{ $appointment->full_name }}</td></tr>
            <tr><th style="padding:8px 12px 8px 0;">Phone / WhatsApp</th><td>{{ $appointment->phone_number }}</td></tr>
            <tr><th style="padding:8px 12px 8px 0;">Email</th><td>{{ $appointment->email }}</td></tr>
            <tr><th style="padding:8px 12px 8px 0;">Address</th><td>{{ $appointment->address }}, {{ $appointment->city }}</td></tr>
            <tr><th style="padding:8px 12px 8px 0;">Preferred date</th><td>{{ $appointment->appointment_date->format('d M Y') }}</td></tr>
        </table>
        <h2 style="font-size:18px;">Request details</h2>
        <div style="white-space:pre-line;overflow-wrap:anywhere;">{{ $appointment->notes }}</div>
        <p style="margin:24px 0;"><a href="{{ $adminUrl }}" style="display:inline-block;padding:12px 20px;border-radius:8px;background:#235b3d;color:#ffffff;text-decoration:none;">Review website requests</a></p>
        <p style="color:#68746d;font-size:13px;">This is an appointment request. The date and time still require staff confirmation.</p>
    </div>
</body>
</html>
