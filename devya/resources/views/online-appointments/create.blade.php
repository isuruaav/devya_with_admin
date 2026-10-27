<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book an Appointment | {{ config('branding.company_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        :root {
            --forest-950: #0a2117;
            --forest-900: #0f2e1f;
            --forest-800: #173f2b;
            --forest-700: #235b3d;
            --gold-600: #b5832f;
            --gold-500: #c89442;
            --cream-100: #fbf8f0;
            --line: #e3ded2;
            --line-strong: rgba(23, 63, 43, 0.22);
            --ink: #1b2620;
            --muted: #5e6f66;
            --shadow: 0 18px 45px rgba(15, 46, 31, 0.08);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: linear-gradient(180deg, #fbf9f2 0%, #edf3eb 100%);
            color: var(--ink);
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            padding: 36px 16px 56px;
        }
        .wrap {
            max-width: 740px;
            margin: 0 auto;
        }
        .header-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 28px;
            text-decoration: none;
        }
        .brand-logo {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 6px 16px rgba(15, 46, 31, 0.15);
        }
        .brand-text strong {
            display: block;
            color: var(--forest-900);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.35rem;
            letter-spacing: 0.05em;
            line-height: 1.2;
        }
        .brand-text small {
            display: block;
            color: var(--gold-600);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .card {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: clamp(24px, 5vw, 42px);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--forest-800), var(--gold-500), var(--forest-800));
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(200, 148, 66, 0.12);
            color: var(--forest-900);
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            margin-bottom: 12px;
        }
        .badge-pill i { color: var(--gold-600); }
        h1 {
            margin: 0 0 8px;
            color: var(--forest-900);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.75rem, 3.5vw, 2.2rem);
        }
        p.subtitle {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .errors {
            padding: 16px 20px;
            background: #fff0ed;
            color: #9d2a1f;
            border: 1px solid #fbcac5;
            border-radius: 16px;
            margin-bottom: 24px;
            font-size: 0.9rem;
        }
        .errors ul { margin: 8px 0 0; padding-left: 20px; }
        .errors li { margin-top: 4px; }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .field {
            display: grid;
            gap: 7px;
        }
        .full { grid-column: 1 / -1; }
        label {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--forest-900);
        }
        input, select, textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid var(--line-strong);
            border-radius: 14px;
            background: #fffefb;
            color: var(--ink);
            font: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--gold-600);
            box-shadow: 0 0 0 3px rgba(200, 148, 66, 0.15);
        }
        textarea {
            min-height: 110px;
            resize: vertical;
        }
        .button {
            border: 0;
            border-radius: 999px;
            padding: 16px 28px;
            background: linear-gradient(135deg, var(--forest-800) 0%, var(--forest-900) 100%);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            box-shadow: 0 12px 28px rgba(15, 46, 31, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(15, 46, 31, 0.32);
        }
        .footer-links {
            text-align: center;
            margin-top: 24px;
            font-size: 0.85rem;
            color: var(--muted);
        }
        .footer-links a {
            color: var(--forest-800);
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        @media(max-width: 640px) {
            .grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }
    </style>
</head>
<body>
<main class="wrap">
    <a href="{{ config('branding.website_url') }}" class="header-brand" aria-label="DEVYA CEYLON">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('branding.company_name') }}" class="brand-logo" onerror="this.src='{{ config('branding.website_url') }}assets/images/brand-logo.webp'">
        <div class="brand-text">
            <strong>{{ config('branding.company_name') }}</strong>
            <small>{{ config('branding.hospital_label') }}</small>
        </div>
    </a>

    <section class="card">
        <span class="badge-pill">
            <i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Online Appointment System
        </span>

        <h1>Book an Appointment</h1>
        <p class="subtitle">Submit your consultation request online. Reception staff will review and confirm availability.</p>

        @if ($errors->any())
            <div class="errors">
                <strong><i class="fa-solid fa-triangle-exclamation"></i> Please check the form errors below:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('online-appointments.store') }}" class="grid">
            @csrf
            <div class="field">
                <label for="full_name">Full Name *</label>
                <input id="full_name" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. K. Perera" required>
            </div>
            <div class="field">
                <label for="nic_or_passport">NIC / Passport Number *</label>
                <input id="nic_or_passport" name="nic_or_passport" value="{{ old('nic_or_passport') }}" placeholder="National Identity or Passport" required>
            </div>
            <div class="field">
                <label for="phone_number">Phone / WhatsApp *</label>
                <input id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="07X XXX XXXX" required>
            </div>
            <div class="field">
                <label for="email">Email Address (Optional)</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
            </div>
            <div class="field">
                <label for="patient_type">Patient Type *</label>
                <select id="patient_type" name="patient_type" required>
                    <option value="Local" @selected(old('patient_type', 'Local') === 'Local')>Local Patient (ශ්‍රී ලාංකික)</option>
                    <option value="Foreign" @selected(old('patient_type') === 'Foreign')>Foreign Patient (ජාත්‍යන්තර)</option>
                </select>
            </div>
            <div class="field">
                <label for="doctor_id">Doctor / Practitioner *</label>
                <select id="doctor_id" name="doctor_id" required>
                    <option value="">Select Doctor</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected((string) old('doctor_id') === (string) $doctor->id)>
                            Dr. {{ $doctor->name }}{{ $doctor->specialization ? ' — '.$doctor->specialization : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field full">
                <label for="appointment_date">Preferred Date &amp; Time *</label>
                <input id="appointment_date" type="datetime-local" name="appointment_date" value="{{ old('appointment_date') }}" min="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="field full">
                <label for="notes">Reason for Consultation / Special Requests (Optional)</label>
                <textarea id="notes" name="notes" placeholder="Briefly describe your symptoms or wellness goal...">{{ old('notes') }}</textarea>
            </div>
            <div class="full" style="margin-top: 8px;">
                <button class="button" type="submit">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Submit Appointment Request</span>
                </button>
            </div>
        </form>
    </section>

    <div class="footer-links">
        <a href="{{ config('branding.website_url') }}"><i class="fa-solid fa-arrow-left"></i> Return to Main Website</a> ·
        <a href="{{ config('branding.website_url') }}contact.php">Need Help? Contact Us</a>
    </div>
</main>
</body>
</html>
