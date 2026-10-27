<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pay booking advance | DEVYA CEYLON</title>
    <style>
        body { margin: 0; background: #f6f7f2; color: #173f2b; font: 16px/1.6 Arial, sans-serif; }
        main { width: min(100% - 32px, 680px); margin: 48px auto; }
        section { border: 1px solid #dce4da; border-radius: 20px; background: #fff; padding: clamp(24px, 5vw, 40px); }
        h1 { margin-top: 0; }
        .amount { color: #173f2b; font-size: 2rem; font-weight: 700; }
        .notice { border-left: 4px solid #c89442; background: #fff9eb; padding: 14px 18px; }
        button { border: 0; border-radius: 999px; background: #173f2b; color: #fff; cursor: pointer; font-size: 1rem; font-weight: 700; padding: 14px 24px; }
        a { color: #245a3c; }
        .policies { display: flex; flex-wrap: wrap; gap: 10px 18px; margin-top: 28px; }
    </style>
</head>
<body>
<main>
    <section>
        <h1>DEVYA CEYLON — booking advance</h1>
        <p>Your appointment has been confirmed by Centre staff.</p>
        <p><strong>Booking:</strong> {{ $appointment->booking_number }}</p>
        <p><strong>Booking advance payable now:</strong> <span class="amount">LKR {{ number_format((float) $payment->amount, 2) }}</span></p>
        <p>The LKR {{ number_format((float) config('appointments.website_consultation_fee_lkr'), 2) }} consultation fee is separate and payable at the Centre on your appointment date. This PayHere checkout collects the booking advance only.</p>
        <p class="notice">Use the secure PayHere checkout to complete payment. Your appointment advance is recorded only after the server verifies PayHere’s payment notification. Returning to this website alone does not confirm a successful payment.</p>
        <p lang="si">ඔබගේ වෛද්‍ය හමුව මධ්‍යස්ථානයේ කාර්ය මණ්ඩලය විසින් තහවුරු කර ඇත. දැන් ගෙවන්නේ වෙන්කිරීමේ අත්තිකාරම පමණි. LKR {{ number_format((float) config('appointments.website_consultation_fee_lkr'), 2) }} වෛද්‍ය උපදේශන ගාස්තුව හමුවීම් දිනයේදී මධ්‍යස්ථානයට ගෙවිය යුතුය.</p>
        <form method="post" action="{{ $endpoint }}">
            @foreach ($checkout as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endforeach
            <button type="submit">Continue to secure PayHere payment</button>
        </form>
        <nav class="policies" aria-label="Legal policies">
            <a href="{{ $websiteUrl }}/privacy-policy.php?lang=en">Privacy Policy</a>
            <a href="{{ $websiteUrl }}/refund-policy.php?lang=en">Refund &amp; Cancellation Policy</a>
            <a href="{{ $websiteUrl }}/terms-and-conditions.php?lang=en">Terms &amp; Conditions</a>
        </nav>
        <p lang="si">
            <a href="{{ $websiteUrl }}/privacy-policy.php?lang=si">පෞද්ගලිකත්ව ප්‍රතිපත්තිය</a> ·
            <a href="{{ $websiteUrl }}/refund-policy.php?lang=si">මුදල් ආපසු ගෙවීමේ ප්‍රතිපත්තිය</a> ·
            <a href="{{ $websiteUrl }}/terms-and-conditions.php?lang=si">කොන්දේසි සහ නියමයන්</a>
        </p>
    </section>
</main>
</body>
</html>
