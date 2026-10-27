<?php
declare(strict_types=1);

require __DIR__ . '/includes/site-config.php';

$currentPage = 'refund-policy';
$pageTitle = $language === 'si'
    ? 'මුදල් ආපසු ගෙවීමේ හා අවලංගු කිරීමේ ප්‍රතිපත්තිය | DEVYA CEYLON'
    : 'Refund & Cancellation Policy | DEVYA CEYLON';
$pageDescription = $language === 'si'
    ? 'DEVYA CEYLON appointment අවලංගු කිරීම, නැවත දිනයක් ලබාගැනීම සහ refund ක්‍රියාවලිය.'
    : 'Read how appointment cancellations, rescheduling, refund eligibility, and refund processing work.';
$policy = $language === 'si' ? [
    'title' => 'මුදල් ආපසු ගෙවීමේ හා අවලංගු කිරීමේ ප්‍රතිපත්තිය',
    'intro' => 'Appointment අවලංගු කිරීම, නැවත දිනයක් ලබාගැනීම සහ ගෙවූ මුදල් ආපසු ලබාදීම සම්බන්ධ ක්‍රියාවලිය මෙහි දැක්වේ.',
    'updated' => 'අවසන් යාවත්කාලීන කිරීම: 2026 ඔක්තෝබර් 26',
    'sections' => [
        [
            'heading' => 'Appointment තහවුරු කිරීම සහ ගෙවීම',
            'paragraphs' => [
                'වෙබ් අඩවියෙන් එවන appointment ඉල්ලීමක් ලැබුණු බව පමණක් තහවුරු වේ; එය appointment එකක් තහවුරු කිරීමක් නොවේ. කාර්ය මණ්ඩලය ලබාගත හැකි වේලාව තහවුරු කළ පසුව පමණක් ගෙවීමක් ඉල්ලා සිටිනු ඇත.',
                'ගෙවීමට පෙර එවන ගෙවීම් ඉල්ලීමේ මුදල, ගෙවීමේ අවසන් දිනය සහ අදාළ අවලංගු කිරීමේ/ආපසු ගෙවීමේ කොන්දේසි පැහැදිලි කරනු ඇත. එම කොන්දේසි කියවා තේරුම් ගත් පසුව පමණක් ගෙවන්න.',
            ],
        ],
        [
            'heading' => 'අවලංගු කිරීම සහ නැවත දිනයක් ලබාගැනීම',
            'paragraphs' => [
                'Appointment එක අවලංගු කිරීමට හෝ දිනය වෙනස් කිරීමට අවශ්‍ය නම් හැකි ඉක්මනින් අප අමතන්න. සම්බන්ධ වීමට වෙබ් අඩවියේ ඇති දුරකථන, WhatsApp හෝ email විස්තර භාවිතා කරන්න.',
                'නැවත දිනයක් ලබාදීම වෛද්‍යවරයාගේ සහ මධ්‍යස්ථානයේ ලබාගත හැකි වේලාව මත රඳා පවතී. අදාළ ගෙවීම් ඉල්ලීමේ දක්වා ඇති දැනුම්දීමේ කාලය හෝ වෙනත් කොන්දේසි අදාළ වේ.',
            ],
        ],
        [
            'heading' => 'Refund සඳහා සුදුසුකම්',
            'paragraphs' => [
                'මධ්‍යස්ථානය විසින් appointment එක අවලංගු කළහොත් හෝ තහවුරු කළ සේවාව සැපයීමට නොහැකි වුවහොත්, අදාළ ගෙවීම ආපසු ලබාදීම හෝ ඔබගේ එකඟතාවයෙන් නැවත දිනයක් ලබාදීම සිදු කරනු ඇත.',
                'එකම ගෙවීම දෙවරක් අයවීමක් හෝ ගෙවීම් දෝෂයක් ඇති බව පෙනේ නම් අප අමතන්න. ඔබ විසින් අවලංගු කරන අවස්ථාවක refund සුදුසුකම ගෙවීමට පෙර ඔබට ලබාදුන් කොන්දේසි සහ අදාළ නීති අනුව තීරණය වේ. ගෙවීමට පෙර පැහැදිලි නොකළ ගාස්තුවක් අඩු නොකරමු.',
            ],
        ],
        [
            'heading' => 'Refund සැකසීමේ කාලය',
            'paragraphs' => [
                'Refund එකක් අනුමත කළ පසු මුල් ගෙවීම් ක්‍රමය හරහා එය සැකසීම ආරම්භ කර, ඒ පිළිබඳ ඔබට දැනුම් දෙන්නෙමු. මුදල ඔබගේ ගිණුමට බැර වීමට ගතවන කාලය payment provider සහ ඔබගේ බැංකුව අනුව වෙනස් විය හැක; ඔබගේ ඉල්ලීම තහවුරු කරන විට අදාළ ඇස්තමේන්තුගත කාලය ලබාදෙන්නෙමු.',
                'ගෙවීම පිළිබඳ සාක්ෂිය සහ appointment විස්තර සමඟ අප හා සම්බන්ධ වන්න. Refund ඉල්ලීම් එක් එක් අවස්ථාව අනුව සමාලෝචනය කර දැනුම්දෙනු ඇත.',
            ],
        ],
    ],
] : [
    'title' => 'Refund & Cancellation Policy',
    'intro' => 'This policy explains how appointment cancellations, rescheduling, and refunds for payments are handled.',
    'updated' => 'Last updated: 26 October 2026',
    'sections' => [
        [
            'heading' => 'Appointment confirmation and payment',
            'paragraphs' => [
                'Submitting an appointment request confirms only that we received it; it does not confirm a booking. Payment will be requested only after staff confirm an available appointment.',
                'Before you pay, the payment request will state the amount, payment deadline, and applicable cancellation and refund terms. Please review those terms before paying.',
            ],
        ],
        [
            'heading' => 'Cancellation and rescheduling',
            'paragraphs' => [
                'If you need to cancel or change an appointment, contact us as soon as possible using the telephone, WhatsApp, or email details on this website.',
                'Rescheduling depends on practitioner and Centre availability. Any notice period or other conditions stated in the payment request for your appointment will apply.',
            ],
        ],
        [
            'heading' => 'Refund eligibility',
            'paragraphs' => [
                'If the Centre cancels an appointment or cannot provide the confirmed service, we will arrange a refund of the relevant payment or offer another appointment with your agreement.',
                'Contact us if you believe a payment was duplicated or made in error. If you cancel, refund eligibility will be assessed under the terms provided before payment and applicable law. We will not deduct a fee that was not disclosed before payment.',
            ],
        ],
        [
            'heading' => 'Refund processing time',
            'paragraphs' => [
                'Once a refund is approved, we will initiate it through the original payment method and notify you. The time for funds to appear depends on the payment provider and your bank; we will provide an applicable estimate when confirming your request.',
                'Contact us with your payment evidence and appointment details. Refund requests are reviewed individually and we will communicate the outcome.',
            ],
        ],
    ],
];

require __DIR__ . '/includes/policy-page.php';
