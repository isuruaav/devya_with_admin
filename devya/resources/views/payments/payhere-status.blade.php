<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Status | DEVYA CEYLON</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        :root {
            --forest-950: #081911;
            --forest-900: #0f2e1f;
            --forest-800: #173f2b;
            --gold-600: #b5832f;
            --gold-500: #c89442;
            --gold-100: #fbf5e8;
            --cream-50: #fcfbf7;
            --line: #e3ded2;
            --ink: #1b2620;
            --muted: #53645b;
            --shadow: 0 16px 40px rgba(15, 46, 31, 0.08);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: linear-gradient(180deg, #f7f5ed 0%, #edf2ea 100%);
            color: var(--ink);
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 32px 16px 48px;
        }
        .container {
            width: 100%;
            max-width: 620px;
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
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 6px 18px rgba(15, 46, 31, 0.15);
        }
        .brand-text strong {
            display: block;
            color: var(--forest-900);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.35rem;
            letter-spacing: 0.06em;
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
        .status-card {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: clamp(28px, 5vw, 44px);
            box-shadow: var(--shadow);
            text-align: center;
        }
        .status-icon-wrapper {
            width: 76px;
            height: 76px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        .status-paid {
            background: #eaf8ef;
            color: #1a9e52;
            border: 2px solid #b7e8c7;
        }
        .status-duplicate {
            background: #fff8e8;
            color: #d97706;
            border: 2px solid #fde68a;
        }
        .status-cancel {
            background: #fff1f2;
            color: #e11d48;
            border: 2px solid #fecdd3;
        }
        .status-processing {
            background: #eff6ff;
            color: #2563eb;
            border: 2px solid #bfdbfe;
        }
        h1 {
            margin: 0 0 12px;
            color: var(--forest-900);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.6rem, 3.5vw, 2rem);
            line-height: 1.25;
        }
        .status-lead {
            font-size: 1.05rem;
            color: var(--forest-900);
            font-weight: 600;
            margin: 12px 0 6px;
        }
        .status-lead-sinhala {
            font-family: 'Noto Sans Sinhala', sans-serif;
            font-size: 0.92rem;
            color: var(--muted);
            margin: 4px 0 18px;
            line-height: 1.7;
        }
        .appointment-box {
            background: var(--cream-50);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px 20px;
            margin: 22px 0;
            text-align: left;
        }
        .booking-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.92rem;
        }
        .booking-badge {
            background: var(--forest-900);
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.95rem;
            font-weight: 700;
        }
        .actions-group {
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
            background: linear-gradient(135deg, #173f2b 0%, #0f2e1f 100%);
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(15, 46, 31, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            color: #ffffff;
        }
        .btn-whatsapp {
            background: #1fa855;
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(31, 168, 85, 0.2);
        }
        .btn-whatsapp:hover {
            transform: translateY(-2px);
            color: #ffffff;
        }
        .btn-outline {
            border: 1px solid var(--line);
            background: #ffffff;
            color: var(--forest-900);
        }
        .btn-outline:hover {
            background: var(--cream-50);
        }
        .footer-note {
            margin-top: 24px;
            font-size: 0.82rem;
            color: var(--muted);
        }
        .footer-note a { color: var(--forest-700); text-decoration: underline; }

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
        .status-icon-wrapper i,
        .btn i {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .status-icon-wrapper:hover i,
        .btn:hover i {
            transform: scale(1.15);
        }
    </style>
</head>
<body>
<main class="container">
    <a href="{{ $websiteUrl }}/index.php" class="header-brand" aria-label="DEVYA CEYLON home">
        <img src="{{ asset('images/logo.png') }}" alt="DEVYA CEYLON" class="brand-logo" onerror="this.src='{{ $websiteUrl }}/assets/images/brand-logo.webp'">
        <div class="brand-text">
            <strong>DEVYA CEYLON</strong>
            <small>Ayurveda Channelling Centre</small>
        </div>
    </a>

    <section class="status-card">
        @if ($payment->status === 'paid')
            <div class="status-icon-wrapper status-paid" data-tooltip="Payment Confirmed Successfully">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
            </div>
            <h1>DEVYA CEYLON payment status</h1>
            <p class="status-lead">Your LKR {{ number_format((float) $payment->amount, 2) }} booking advance is confirmed.</p>
            <p class="status-lead-sinhala" lang="si">ඔබගේ LKR {{ number_format((float) $payment->amount, 2) }} වෙන්කිරීමේ අත්තිකාරම් ගෙවීම සාර්ථකව තහවුරු විය.</p>

            <div class="appointment-box" data-tooltip="Confirmed Booking Reference Code">
                <div class="booking-row">
                    <span style="color: var(--muted);"><i class="fa-solid fa-receipt text-gold" style="margin-right: 5px;"></i> Booking Reference:</span>
                    <span class="booking-badge">Booking number: {{ $payment->appointment->booking_number }}</span>
                </div>
            </div>

            <p style="color: var(--muted); font-size: 0.9rem; margin-top: 14px;">
                Our team has marked your consultation slot as reserved. Please present your booking reference at the reception desk on arrival.
            </p>

            <div class="actions-group">
                <a href="{{ $websiteUrl }}/index.php" class="btn btn-primary" data-tooltip="Return to DEVYA CEYLON homepage">
                    <i class="fa-solid fa-house" aria-hidden="true"></i> Return to Home
                </a>
                <a href="https://wa.me/94713423173?text={{ urlencode('Hello DEVYA CEYLON, I have paid the booking advance for booking number ' . $payment->appointment->booking_number) }}" class="btn btn-whatsapp" target="_blank" rel="noopener" data-tooltip="Notify Centre receptionist on WhatsApp">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Notify Centre on WhatsApp
                </a>
            </div>

        @elseif ($payment->status === 'duplicate')
            <div class="status-icon-wrapper status-duplicate" data-tooltip="Duplicate Payment Received">
                <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
            </div>
            <h1>DEVYA CEYLON payment status</h1>
            <p class="status-lead" lang="en">We received an additional payment notification. Please contact the Centre before paying again.</p>
            <p class="status-lead-sinhala" lang="si">අමතර ගෙවීම් දැනුම්දීමක් ලැබී ඇත. නැවත ගෙවීමට පෙර මධ්‍යස්ථානය අමතන්න.</p>

            <div class="appointment-box" data-tooltip="Booking Reference Code">
                <div class="booking-row">
                    <span style="color: var(--muted);"><i class="fa-solid fa-receipt text-gold" style="margin-right: 5px;"></i> Booking Reference:</span>
                    <span class="booking-badge">{{ $payment->appointment->booking_number }}</span>
                </div>
            </div>

            <div class="actions-group">
                <a href="tel:+94722000008" class="btn btn-primary" data-tooltip="Call Centre reception desk">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i> Call Centre (072 200 0008)
                </a>
                <a href="https://wa.me/94713423173" class="btn btn-whatsapp" target="_blank" rel="noopener" data-tooltip="Chat with support on WhatsApp">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
                </a>
            </div>

        @elseif ($result === 'cancel')
            <div class="status-icon-wrapper status-cancel" data-tooltip="Payment Cancelled or Incomplete">
                <i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>
            </div>
            <h1>DEVYA CEYLON payment status</h1>
            <p class="status-lead" lang="en">Checkout was cancelled or left incomplete. No successful payment has been confirmed. Contact the Centre to request a new payment link.</p>
            <p class="status-lead-sinhala" lang="si">ගෙවීම අවලංගු කර හෝ සම්පූර්ණ කර නැත. සාර්ථක ගෙවීමක් තහවුරු කර නැත. නව ගෙවීම් සබැඳියක් සඳහා මධ්‍යස්ථානය අමතන්න.</p>

            <div class="appointment-box" data-tooltip="Booking Reference Code">
                <div class="booking-row">
                    <span style="color: var(--muted);"><i class="fa-solid fa-receipt text-gold" style="margin-right: 5px;"></i> Booking Reference:</span>
                    <span class="booking-badge">{{ $payment->appointment->booking_number }}</span>
                </div>
            </div>

            <div class="actions-group">
                <a href="https://wa.me/94713423173?text={{ urlencode('Hello DEVYA CEYLON, my checkout was cancelled for booking ' . $payment->appointment->booking_number . '. Please assist me.') }}" class="btn btn-whatsapp" target="_blank" rel="noopener" data-tooltip="Request new payment link via WhatsApp">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Request Assistance via WhatsApp
                </a>
                <a href="{{ $websiteUrl }}/contact.php?lang=en" class="btn btn-outline" data-tooltip="Contact the Centre for help">
                    <i class="fa-solid fa-headset" aria-hidden="true"></i> Contact the Centre
                </a>
            </div>

        @else
            <div class="status-icon-wrapper status-processing" data-tooltip="Payment is Processing">
                <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
            </div>
            <h1>DEVYA CEYLON payment status</h1>
            <p class="status-lead" lang="en">PayHere is processing your payment notification. This page alone does not confirm payment. Please contact the Centre if the status does not update.</p>
            <p class="status-lead-sinhala" lang="si">PayHere ගෙවීම් දැනුම්දීම සැකසෙමින් පවතී. මෙම පිටුව පමණක් ගෙවීම තහවුරු නොකරයි. තත්ත්වය යාවත්කාලීන නොවන්නේ නම් මධ්‍යස්ථානය අමතන්න.</p>

            <div class="appointment-box" data-tooltip="Booking Reference Code">
                <div class="booking-row">
                    <span style="color: var(--muted);"><i class="fa-solid fa-receipt text-gold" style="margin-right: 5px;"></i> Booking Reference:</span>
                    <span class="booking-badge">{{ $payment->appointment->booking_number }}</span>
                </div>
            </div>

            <div class="actions-group">
                <a href="{{ $websiteUrl }}/contact.php?lang=en" class="btn btn-primary" data-tooltip="Contact the Centre support desk">
                    <i class="fa-solid fa-headset" aria-hidden="true"></i> Contact the Centre
                </a>
            </div>
        @endif

        <div class="footer-note">
            <a href="{{ $websiteUrl }}/contact.php?lang=en" data-tooltip="Contact reception desk"><i class="fa-solid fa-headset"></i> Contact the Centre</a> ·
            <a href="{{ $websiteUrl }}/index.php" data-tooltip="Go to homepage"><i class="fa-solid fa-house"></i> DEVYA CEYLON Home</a>
        </div>
    </section>
</main>
</body>
</html>
