<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appointment Request Received | {{ config('branding.company_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
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
            padding: 40px 16px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .wrap {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .header-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 26px;
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
            padding: clamp(32px, 6vw, 48px);
            box-shadow: var(--shadow);
            text-align: center;
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
        .success-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #eaf8ef;
            color: #1a9e52;
            border: 2px solid #b7e8c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        h1 {
            margin: 0 0 10px;
            color: var(--forest-900);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.65rem, 3.5vw, 2.1rem);
        }
        .lead {
            margin: 0 0 20px;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .booking-box {
            background: var(--cream-100);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 18px;
            margin: 22px 0;
        }
        .booking-label {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold-600);
            display: block;
            margin-bottom: 6px;
        }
        .booking-code {
            display: block;
            font-family: monospace;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--forest-900);
            letter-spacing: 0.05em;
        }
        .actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 26px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 24px;
            border-radius: 999px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--forest-800) 0%, var(--forest-900) 100%);
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(15, 46, 31, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            color: #ffffff;
        }
        .btn-outline {
            border: 1px solid var(--line);
            background: #ffffff;
            color: var(--forest-900);
        }
        .btn-outline:hover {
            background: #f7f5ed;
        }
        .footer-note {
            margin-top: 24px;
            font-size: 0.85rem;
            color: var(--muted);
            text-align: center;
        }
        .footer-note a {
            color: var(--forest-700);
            text-decoration: underline;
        }
        /* Universal FontAwesome Tooltip */
        [data-tooltip] {
            position: relative;
            cursor: pointer;
        }
        [data-tooltip]::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%) translateY(4px);
            background: var(--forest-950);
            color: #ffffff;
            font-size: 0.73rem;
            font-weight: 600;
            line-height: 1.35;
            letter-spacing: 0.02em;
            padding: 5px 10px;
            border-radius: 8px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(200, 148, 66, 0.4);
            z-index: 99999;
        }
        [data-tooltip]::after {
            content: "";
            position: absolute;
            bottom: calc(100% + 3px);
            left: 50%;
            transform: translateX(-50%) translateY(4px);
            border-width: 5px 5px 0 5px;
            border-style: solid;
            border-color: var(--forest-950) transparent transparent transparent;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.18s ease, transform 0.18s ease;
            z-index: 99999;
        }
        [data-tooltip]:hover::before,
        [data-tooltip]:hover::after {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        .btn i {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn:hover i {
            transform: scale(1.18);
        }
    </style>
</head>
<body>
<main class="wrap">
    <a href="{{ config('branding.website_url') }}" class="header-brand" aria-label="DEVYA CEYLON" data-tooltip="DEVYA CEYLON Healthcare">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('branding.company_name') }}" class="brand-logo" onerror="this.src='{{ config('branding.website_url') }}assets/images/brand-logo.webp'">
        <div class="brand-text">
            <strong>{{ config('branding.company_name') }}</strong>
            <small>{{ config('branding.hospital_label') }}</small>
        </div>
    </a>

    <section class="card">
        <div class="success-icon" data-tooltip="Appointment request successfully submitted!">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <h1>Request received</h1>
        <p class="lead">Your appointment request is pending Reception confirmation. A staff member will review your preferred date and contact you shortly.</p>

        <div class="booking-box" data-tooltip="Reference number for tracking your consultation request">
            <span class="booking-label">
                <i class="fa-solid fa-receipt" style="color: var(--gold-600); margin-right: 5px;"></i>
                Booking Reference Number
            </span>
            <strong class="booking-code">{{ $onlineAppointment->booking_number }}</strong>
        </div>

        <p style="font-size: 0.88rem; color: var(--muted); margin: 0 0 16px;">
            <i class="fa-solid fa-circle-info" data-tooltip="Keep this reference code safe" style="color: var(--forest-800); margin-right: 6px;"></i>
            Please keep this booking number for reference. You will receive an SMS or WhatsApp notification once confirmed.
        </p>

        <div class="actions">
            <a href="{{ config('branding.website_url') }}" class="btn btn-primary" data-tooltip="Go back to DEVYA CEYLON homepage">
                <i class="fa-solid fa-house"></i> Return to Main Website
            </a>
            <a class="btn btn-outline" href="{{ route('online-appointments.create') }}" data-tooltip="Book another patient appointment">
                <i class="fa-solid fa-calendar-plus"></i> Book another appointment
            </a>
        </div>
    </section>

    <div class="footer-note">
        Need assistance? <a href="{{ config('branding.website_url') }}contact.php" data-tooltip="Get assistance from customer support"><i class="fa-solid fa-headset"></i> Contact Centre Support</a>
    </div>
</main>
</body>
</html>
