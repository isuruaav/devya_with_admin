<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment status | DEVYA CEYLON</title>
    <style>
        body { margin: 0; background: #f6f7f2; color: #173f2b; font: 16px/1.6 Arial, sans-serif; }
        main { width: min(100% - 32px, 680px); margin: 48px auto; }
        section { border: 1px solid #dce4da; border-radius: 20px; background: #fff; padding: clamp(24px, 5vw, 40px); }
        a { color: #245a3c; }
    </style>
</head>
<body>
<main>
    <section>
        <h1>DEVYA CEYLON payment status</h1>
        @if ($payment->status === 'paid')
            <p>Your LKR {{ number_format((float) $payment->amount, 2) }} booking advance is confirmed.</p>
            <p>Booking number: {{ $payment->appointment->booking_number }}</p>
        @elseif ($payment->status === 'duplicate')
            <p lang="en">We received an additional payment notification. Please contact the Centre before paying again.</p>
            <p lang="si">අමතර ගෙවීම් දැනුම්දීමක් ලැබී ඇත. නැවත ගෙවීමට පෙර මධ්‍යස්ථානය අමතන්න.</p>
        @elseif ($result === 'cancel')
            <p lang="en">Checkout was cancelled or left incomplete. No successful payment has been confirmed. Contact the Centre to request a new payment link.</p>
            <p lang="si">ගෙවීම අවලංගු කර හෝ සම්පූර්ණ කර නැත. සාර්ථක ගෙවීමක් තහවුරු කර නැත. නව ගෙවීම් සබැඳියක් සඳහා මධ්‍යස්ථානය අමතන්න.</p>
        @else
            <p lang="en">PayHere is processing your payment notification. This page alone does not confirm payment. Please contact the Centre if the status does not update.</p>
            <p lang="si">PayHere ගෙවීම් දැනුම්දීම සැකසෙමින් පවතී. මෙම පිටුව පමණක් ගෙවීම තහවුරු නොකරයි. තත්ත්වය යාවත්කාලීන නොවන්නේ නම් මධ්‍යස්ථානය අමතන්න.</p>
        @endif
        <p><a href="{{ $websiteUrl }}/contact.php?lang=en">Contact the Centre</a></p>
    </section>
</main>
</body>
</html>
