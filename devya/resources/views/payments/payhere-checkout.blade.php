<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pay Booking Advance | DEVYA CEYLON</title>
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
            --forest-700: #20533a;
            --gold-600: #b5832f;
            --gold-500: #c89442;
            --gold-400: #d8a858;
            --gold-100: #fbf5e8;
            --cream-50: #fcfbf7;
            --cream-100: #f5f2e9;
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
            max-width: 660px;
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
        .checkout-card {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: clamp(24px, 5vw, 42px);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        .checkout-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--forest-800), var(--gold-500), var(--forest-800));
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gold-100);
            color: var(--forest-900);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            margin-bottom: 14px;
        }
        .badge-pill i { color: var(--gold-600); }
        h1 {
            margin: 0 0 10px;
            color: var(--forest-900);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.65rem, 3.5vw, 2.1rem);
            line-height: 1.25;
        }
        .appointment-info {
            background: var(--cream-50);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px 20px;
            margin: 22px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 6px 0;
            font-size: 0.92rem;
        }
        .info-row + .info-row { border-top: 1px dashed var(--line); margin-top: 4px; padding-top: 10px; }
        .info-label { color: var(--muted); }
        .info-val { font-weight: 700; color: var(--forest-900); text-align: right; }
        .booking-badge {
            background: var(--forest-900);
            color: #ffffff;
            padding: 3px 10px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.95rem;
            letter-spacing: 0.05em;
        }
        .pricing-table {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 18px;
            margin: 22px 0;
            overflow: hidden;
        }
        .pricing-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
        }
        .pricing-row + .pricing-row { border-top: 1px solid var(--line); }
        .pricing-row.highlight {
            background: linear-gradient(135deg, rgba(200, 148, 66, 0.12) 0%, rgba(23, 63, 43, 0.06) 100%);
            border-top: 2px solid var(--gold-500);
        }
        .pricing-row.highlight .amount {
            color: var(--forest-900);
            font-size: 1.65rem;
            font-weight: 800;
        }
        .fee-explanation {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
            margin: 14px 0;
        }
        .notice-box {
            border-left: 4px solid var(--gold-500);
            background: #fffbf2;
            padding: 14px 18px;
            border-radius: 0 14px 14px 0;
            font-size: 0.85rem;
            color: var(--forest-900);
            line-height: 1.6;
            margin: 18px 0;
        }
        .sinhala-notice {
            font-family: 'Noto Sans Sinhala', sans-serif;
            background: var(--cream-100);
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 0.84rem;
            color: var(--forest-900);
            line-height: 1.7;
            margin: 16px 0 24px;
        }
        .pay-button {
            width: 100%;
            background: linear-gradient(135deg, #173f2b 0%, #0f2e1f 100%);
            color: #ffffff;
            border: none;
            border-radius: 999px;
            padding: 16px 28px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 12px 28px rgba(15, 46, 31, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(15, 46, 31, 0.35);
        }
        .pay-button i { color: var(--gold-400); font-size: 1.15rem; }
        .security-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 18px;
            font-size: 0.78rem;
            color: var(--muted);
        }
        .security-badges span { display: inline-flex; align-items: center; gap: 6px; }
        .security-badges i { color: #1fa855; }
        .policies {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px 18px;
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
            font-size: 0.82rem;
        }
        .policies a { color: var(--forest-700); text-decoration: underline; text-underline-offset: 3px; }
        .policies a:hover { color: var(--gold-600); }
        .sinhala-links {
            text-align: center;
            font-family: 'Noto Sans Sinhala', sans-serif;
            font-size: 0.8rem;
            margin-top: 10px;
        }
        .sinhala-links a { color: var(--forest-700); text-decoration: none; margin: 0 6px; }
        .sinhala-links a:hover { text-decoration: underline; }
        .footer-help {
            text-align: center;
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 24px;
        }
        .footer-help a { color: var(--forest-800); font-weight: 700; text-decoration: none; }
        .footer-help a:hover { text-decoration: underline; }

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
        i[data-tooltip],
        .security-badges span i {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        i[data-tooltip]:hover,
        .security-badges span:hover i {
            transform: scale(1.18);
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

    <section class="checkout-card">
        <span class="badge-pill" data-tooltip="Certified Official Payment Portal">
            <i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Official Payment Portal
        </span>

        <h1>DEVYA CEYLON — booking advance</h1>
        <p style="margin: 0; color: var(--muted); font-size: 0.95rem;">
            Your appointment has been confirmed by Centre staff.
        </p>

        <div class="appointment-info">
            <div class="info-row">
                <span class="info-label">
                    <i class="fa-solid fa-receipt" style="color: var(--gold-600); margin-right: 5px;" data-tooltip="Unique booking reference code" aria-hidden="true"></i>
                    Booking Reference:
                </span>
                <span class="booking-badge"><strong>Booking:</strong> {{ $appointment->booking_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">
                    <i class="fa-solid fa-user" style="color: var(--gold-600); margin-right: 5px;" data-tooltip="Registered patient name" aria-hidden="true"></i>
                    Patient Name:
                </span>
                <span class="info-val">{{ $appointment->full_name }}</span>
            </div>
            @if ($appointment->appointment_date)
            <div class="info-row">
                <span class="info-label">
                    <i class="fa-regular fa-calendar-check" style="color: var(--gold-600); margin-right: 5px;" data-tooltip="Confirmed consultation date" aria-hidden="true"></i>
                    Appointment Date:
                </span>
                <span class="info-val">{{ $appointment->appointment_date->format('l, d M Y') }}</span>
            </div>
            @endif
        </div>

        <div class="pricing-table">
            <div class="pricing-row highlight">
                <div>
                    <strong style="color: var(--forest-900); display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-hand-holding-dollar" style="color: var(--gold-600);" data-tooltip="Online deposit required to lock slot" aria-hidden="true"></i>
                        Booking advance payable now:
                    </strong>
                    <small style="color: var(--muted);">Required to confirm and reserve slot</small>
                </div>
                <div class="amount">LKR {{ number_format((float) $payment->amount, 2) }}</div>
            </div>
            <div class="pricing-row">
                <div>
                    <span style="color: var(--ink); display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-stethoscope" style="color: var(--gold-600);" data-tooltip="Doctor Consultation Fee payable at Centre" aria-hidden="true"></i>
                        Doctor Consultation Fee:
                    </span>
                    <small style="display: block; color: var(--muted);">Payable at the Centre on appointment date</small>
                </div>
                <div style="font-weight: 700; color: var(--forest-900);">
                    LKR {{ number_format((float) config('appointments.website_consultation_fee_lkr'), 2) }}
                </div>
            </div>
        </div>

        <p class="fee-explanation">
            The LKR {{ number_format((float) config('appointments.website_consultation_fee_lkr'), 2) }} consultation fee is separate and payable at the Centre on your appointment date. This PayHere checkout collects the booking advance only.
        </p>

        <div class="notice-box">
            <i class="fa-solid fa-lock" style="color: var(--gold-600); margin-right: 6px;" data-tooltip="256-Bit Encrypted Payment Verification" aria-hidden="true"></i>
            <strong>Payment Verification:</strong> Use the secure PayHere checkout to complete payment. Your appointment advance is recorded only after the server verifies PayHere’s payment notification. Returning to this website alone does not confirm a successful payment.
        </div>

        <div class="sinhala-notice" lang="si">
            <i class="fa-solid fa-circle-info" style="color: var(--gold-600); margin-right: 6px;" data-tooltip="විස්තරාත්මක දැනුම්දීම" aria-hidden="true"></i>
            ඔබගේ වෛද්‍ය හමුව මධ්‍යස්ථානයේ කාර්ය මණ්ඩලය විසින් තහවුරු කර ඇත. දැන් ගෙවන්නේ වෙන්කිරීමේ අත්තිකාරම පමණි. LKR {{ number_format((float) config('appointments.website_consultation_fee_lkr'), 2) }} වෛද්‍ය උපදේශන ගාස්තුව හමුවීම් දිනයේදී මධ්‍යස්ථානයට ගෙවිය යුතුය.
        </div>

        <form method="post" action="{{ $endpoint }}">
            @foreach ($checkout as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endforeach
            <button type="submit" class="pay-button" data-tooltip="Proceed to PayHere secure payment portal">
                <i class="fa-solid fa-lock" aria-hidden="true"></i>
                <span>Continue to secure PayHere payment</span>
            </button>
        </form>

        <div class="security-badges">
            <span data-tooltip="Protected with 256-bit bank-grade SSL encryption"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> 256-Bit SSL Encryption</span>
            <span data-tooltip="Licensed & Central Bank approved payment service provider"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> PayHere Certified Gateway</span>
            <span data-tooltip="Credit Cards, Debit Cards, and Mobile Wallets"><i class="fa-regular fa-credit-card" aria-hidden="true"></i> Cards / Wallets Accepted</span>
        </div>

        <nav class="policies" aria-label="Legal policies">
            <a href="{{ $websiteUrl }}/privacy-policy.php?lang=en">Privacy Policy</a>
            <a href="{{ $websiteUrl }}/refund-policy.php?lang=en">Refund &amp; Cancellation Policy</a>
            <a href="{{ $websiteUrl }}/terms-and-conditions.php?lang=en">Terms &amp; Conditions</a>
        </nav>

        <div class="sinhala-links" lang="si">
            <a href="{{ $websiteUrl }}/privacy-policy.php?lang=si">පෞද්ගලිකත්ව ප්‍රතිපත්තිය</a> ·
            <a href="{{ $websiteUrl }}/refund-policy.php?lang=si">මුදල් ආපසු ගෙවීමේ ප්‍රතිපත්තිය</a> ·
            <a href="{{ $websiteUrl }}/terms-and-conditions.php?lang=si">කොන්දේසි සහ නියමයන්</a>
        </div>
    </section>

    <div class="footer-help">
        Need assistance with this payment?
        <a href="tel:+94722000008" data-tooltip="Direct phone line to reception"><i class="fa-solid fa-phone" aria-hidden="true"></i> 072 200 0008</a> or
        <a href="https://wa.me/94713423173" target="_blank" rel="noopener" data-tooltip="Chat directly with Centre staff"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp Support</a>
    </div>
</main>
</body>
</html>
