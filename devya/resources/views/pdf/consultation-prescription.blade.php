<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Prescription {{ $consultation->consultation_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        .header { border-bottom: 3px solid #059669; padding-bottom: 16px; }
        h1 { color: #047857; margin: 0; font-size: 24px; }
        h2 { color: #065f46; margin-top: 28px; }
        .muted { color: #6b7280; }
        .details { width: 100%; margin: 22px 0; }
        .details td { width: 50%; padding: 4px 0; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #ecfdf5; color: #065f46; text-align: left; padding: 9px; }
        td { border-bottom: 1px solid #e5e7eb; padding: 10px 9px; }
        .footer { margin-top: 50px; text-align: center; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ayurvedic Hospital</h1>
        <div class="muted">Medicine Prescription</div>
    </div>
    <table class="details">
        <tr>
            <td><strong>Consultation No:</strong> {{ $consultation->consultation_number }}<br>
                <strong>Date:</strong> {{ $consultation->consultation_date?->format('d M Y, h:i A') }}</td>
            <td><strong>Patient:</strong> {{ $consultation->patient?->full_name ?? 'N/A' }}<br>
                <strong>Doctor:</strong> {{ $consultation->doctor?->name ?? 'N/A' }}</td>
        </tr>
    </table>
    <h2>Prescribed Medicines</h2>
    <table>
        <tr><th>Medicine</th><th>Dosage</th><th>Quantity</th><th>Unit</th></tr>
        @forelse ($consultation->medicines as $item)
            <tr>
                <td>{{ $item->medicine?->name ?? 'Medicine' }}</td>
                <td>{{ $item->dosage ?: 'As directed by doctor' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->medicine?->unit ?? 'pcs' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No medicines prescribed.</td></tr>
        @endforelse
    </table>
    @if ($consultation->symptoms_and_notes)
        <h2>Doctor's Notes</h2>
        <p>{{ $consultation->symptoms_and_notes }}</p>
    @endif
    <div class="footer">Please follow the doctor's instructions carefully.</div>
</body>
</html>
