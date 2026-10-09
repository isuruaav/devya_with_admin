<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
    <title>Patient Queue | Ayu System</title>
    <style>
        :root { color-scheme: dark; font-family: Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; background: #071b1a; color: #f4fffb; }
        .page { width: min(1500px, 100%); margin: 0 auto; padding: 36px; }
        .topbar { display: flex; justify-content: space-between; gap: 24px; align-items: end; margin-bottom: 32px; }
        .eyebrow { margin: 0 0 10px; color: #74f1c1; font-size: 14px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; }
        h1 { margin: 0; font-size: clamp(32px, 5vw, 68px); line-height: 1; }
        .date { color: #b5d8cc; font-size: clamp(18px, 2vw, 28px); font-weight: 700; white-space: nowrap; }
        .rooms { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; }
        .room { overflow: hidden; border: 1px solid #1d5149; border-radius: 24px; background: #0d2b28; box-shadow: 0 18px 50px #0004; }
        .room-header { padding: 22px 26px; background: linear-gradient(110deg, #105c4d, #0e3934); }
        .room-label { margin: 0 0 6px; color: #a7f8d6; font-size: 13px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h2 { margin: 0; font-size: clamp(28px, 3vw, 42px); }
        .doctor { margin: 8px 0 0; color: #d4eee6; font-size: 18px; }
        .content { padding: 26px; }
        .current { padding: 22px; border: 2px solid #f2bb55; border-radius: 18px; background: #3a2b12; text-align: center; }
        .current-label { margin: 0; color: #ffd985; font-size: 15px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .current-number { margin: 10px 0 4px; color: #fff0be; font-size: clamp(64px, 8vw, 108px); font-weight: 900; line-height: 1; }
        .current-help { margin: 0; color: #ffe6a1; font-size: 16px; }
        .next-title { margin: 26px 0 12px; color: #a7f8d6; font-size: 14px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .next-list { display: flex; flex-wrap: wrap; gap: 10px; }
        .next-number { min-width: 76px; padding: 14px 16px; border-radius: 12px; background: #17463f; color: #effff9; font-size: 30px; font-weight: 900; text-align: center; }
        .empty { padding: 70px 24px; border: 1px dashed #397267; border-radius: 24px; color: #b5d8cc; text-align: center; font-size: 24px; }
        .footer { margin-top: 34px; color: #82aa9e; font-size: 14px; text-align: center; }
        @media (max-width: 650px) { .page { padding: 22px 16px; } .topbar { align-items: start; flex-direction: column; gap: 14px; } .rooms { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main class="page">
        <header class="topbar">
            <div>
                <p class="eyebrow">Live Patient Queue · සජීවී රෝගී පෝලිම</p>
                <h1>Now Serving · දැන් කැඳවනු ලබන්නේ</h1>
            </div>
            <div class="date">{{ $date }}</div>
        </header>

        @if ($queues->isEmpty())
            <div class="empty">{{ $room !== '' ? 'මෙම කාමරයේ දැනට පෝලිමේ රෝගීන් නොමැත' : 'දැනට පෝලිමේ රෝගීන් නොමැත' }}</div>
        @else
            <section class="rooms">
                @foreach ($queues as $room => $patients)
                    @php
                        $current = $patients->first(fn ($patient) => in_array($patient->queue_status, ['in_consultation', 'called'], true));
                        $next = $patients->filter(fn ($patient) => $patient->queue_status === 'waiting')->take(4);
                    @endphp
                    <article class="room">
                        <header class="room-header">
                            <p class="room-label">Consultation Room · ප්‍රතිකාර කාමරය</p>
                            <h2>{{ $room }}</h2>
                            <p class="doctor">{{ $patients->first()->doctor?->name ?: 'Doctor not assigned' }}</p>
                        </header>
                        <div class="content">
                            <div class="current">
                                <p class="current-label">දැන් කැඳවනු ලබන්නේ</p>
                                <div class="current-number">{{ $current?->queue_number ?: '—' }}</div>
                                <p class="current-help">{{ $current ? 'මෙම අංකය ප්‍රතිකාර කාමරයට පැමිණෙන්න' : 'කරුණාකර රැඳී සිටින්න' }}</p>
                            </div>
                            <p class="next-title">ඊළඟ අංක · Next numbers</p>
                            <div class="next-list">
                                @forelse ($next as $patient)
                                    <span class="next-number">{{ $patient->queue_number }}</span>
                                @empty
                                    <span class="current-help">ඊළඟ පෝලිම් අංක නොමැත</span>
                                @endforelse
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif

    </main>
</body>
</html>
