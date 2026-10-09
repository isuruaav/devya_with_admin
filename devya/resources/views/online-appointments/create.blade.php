<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book an Appointment | Ayu System</title>
    <style>
        body{margin:0;background:#f1f8f5;color:#16332d;font-family:Arial,sans-serif}.wrap{max-width:760px;margin:40px auto;padding:24px}.card{background:#fff;border-radius:20px;padding:32px;box-shadow:0 16px 45px #17463f18}h1{margin:0 0 8px;font-size:32px}p{color:#58746d}.grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.field{display:grid;gap:7px}.full{grid-column:1/-1}label{font-weight:700;font-size:14px}input,select,textarea{width:100%;padding:12px;border:1px solid #c9ddd7;border-radius:10px;font:inherit;box-sizing:border-box}textarea{min-height:100px;resize:vertical}.button{border:0;border-radius:10px;padding:14px 20px;background:#087f5b;color:#fff;font-weight:800;cursor:pointer}.errors{padding:14px;background:#fff0f0;color:#a12828;border-radius:10px;margin-bottom:18px}@media(max-width:640px){.grid{grid-template-columns:1fr}.full{grid-column:auto}}
    </style>
</head>
<body>
<main class="wrap">
    <section class="card">
        <h1>Book an Appointment</h1>
        <p>Submit your request online. Reception will review and confirm your appointment.</p>
        @if ($errors->any())
            <div class="errors"><strong>Please check the form:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('online-appointments.store') }}" class="grid">
            @csrf
            <div class="field"><label for="full_name">Full name</label><input id="full_name" name="full_name" value="{{ old('full_name') }}" required></div>
            <div class="field"><label for="nic_or_passport">NIC / Passport</label><input id="nic_or_passport" name="nic_or_passport" value="{{ old('nic_or_passport') }}" required></div>
            <div class="field"><label for="phone_number">Phone / WhatsApp</label><input id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required></div>
            <div class="field"><label for="email">Email (optional)</label><input id="email" type="email" name="email" value="{{ old('email') }}"></div>
            <div class="field"><label for="patient_type">Patient type</label><select id="patient_type" name="patient_type" required><option value="Local" @selected(old('patient_type', 'Local') === 'Local')>Local</option><option value="Foreign" @selected(old('patient_type') === 'Foreign')>Foreign</option></select></div>
            <div class="field"><label for="doctor_id">Doctor</label><select id="doctor_id" name="doctor_id" required><option value="">Select doctor</option>@foreach ($doctors as $doctor)<option value="{{ $doctor->id }}" @selected((string) old('doctor_id') === (string) $doctor->id)>{{ $doctor->name }}{{ $doctor->specialization ? ' - '.$doctor->specialization : '' }}</option>@endforeach</select></div>
            <div class="field full"><label for="appointment_date">Preferred date and time</label><input id="appointment_date" type="datetime-local" name="appointment_date" value="{{ old('appointment_date') }}" min="{{ now()->format('Y-m-d\TH:i') }}" required></div>
            <div class="field full"><label for="notes">Reason / notes (optional)</label><textarea id="notes" name="notes">{{ old('notes') }}</textarea></div>
            <div class="full"><button class="button" type="submit">Submit Appointment Request</button></div>
        </form>
    </section>
</main>
</body>
</html>
