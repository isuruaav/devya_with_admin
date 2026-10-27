<?php
declare(strict_types=1);

require __DIR__ . '/includes/site-config.php';

$currentPage = 'terms-and-conditions';
$pageTitle = $language === 'si'
    ? 'කොන්දේසි සහ නියමයන් | DEVYA CEYLON'
    : 'Terms & Conditions | DEVYA CEYLON';
$pageDescription = $language === 'si'
    ? 'DEVYA CEYLON වෙබ් අඩවිය, appointment ඉල්ලීම් සහ සත්කාර සේවා භාවිතයට අදාළ කොන්දේසි.'
    : 'Terms for using the DEVYA CEYLON website, requesting appointments, and receiving services.';
$policy = $language === 'si' ? [
    'title' => 'කොන්දේසි සහ නියමයන්',
    'intro' => 'DEVYA CEYLON වෙබ් අඩවිය භාවිතා කිරීම සහ appointment ඉල්ලීමක් කිරීමේදී අදාළ වන කොන්දේසි මෙහි දැක්වේ.',
    'updated' => 'අවසන් යාවත්කාලීන කිරීම: 2026 ඔක්තෝබර් 26',
    'sections' => [
        [
            'heading' => 'වෙබ් අඩවිය සහ appointment ඉල්ලීම්',
            'paragraphs' => [
                'මෙම වෙබ් අඩවියේ ඇති තොරතුරු සේවා පිළිබඳ සාමාන්‍ය දැනුවත් කිරීම සඳහාය. Appointment ඉල්ලීමක් යැවීමෙන් එය ලැබුණු බව පමණක් තහවුරු වන අතර, කාර්ය මණ්ඩලය ඔබට සම්බන්ධ වී වේලාව තහවුරු කරන තුරු appointment එකක් වෙන් කර නැත.',
                'ඔබ ඉල්ලීමක් කරන විට සත්‍ය සහ යාවත්කාලීන සම්බන්ධතා තොරතුරු ලබාදෙන්න. ඉල්ලීම සම්බන්ධයෙන් වැඩිදුර තොරතුරු ලබාගැනීමට අප ඔබ අමතන්නෙමු.',
            ],
        ],
        [
            'heading' => 'වෛද්‍ය උපදෙස් සහ සේවා',
            'paragraphs' => [
                'සත්කාර, පරීක්ෂණ හෝ ප්‍රතිකාර තීරණ සුදුසුකම් ලත් වෛද්‍යවරයෙකුගේ තක්සේරුව සහ උපදෙස් අනුව ගනු ලැබේ. ප්‍රතිකාරයක සුදුසුකම, සැලැස්ම සහ ගාස්තුව පුද්ගලික අවශ්‍යතා අනුව වෙනස් විය හැක.',
                'වෙබ් අඩවියේ ඇති තොරතුරු වෘත්තීය වෛද්‍ය උපදෙස් වෙනුවට භාවිතා නොකරන්න. හදිසි වෛද්‍ය අවස්ථාවකදී මෙම වෙබ් අඩවිය හෝ appointment form එක භාවිතා නොකර වහාම හදිසි වෛද්‍ය සේවාවක් ලබාගන්න.',
            ],
        ],
        [
            'heading' => 'ගාස්තු සහ ගෙවීම්',
            'paragraphs' => [
                'දැනට පෙන්වන වෛද්‍ය උපදේශන ගාස්තුව රු. 3,000කි. ඊට අමතර වෙන්කිරීමේ අත්තිකාරම රු. 1,500කි; ගාස්තු දෙකම අදාළ වන විට එකතුව රු. 4,500කි. කාර්ය මණ්ඩලය ඔබගේ හමුව සහ ගෙවිය යුතු මුදල තහවුරු කර ගෙවීමට පෙර ගෙවීම් උපදෙස් ලබාදෙනු ඇත. වෛද්‍ය පරීක්ෂාවෙන් පසුව තීරණය කරන ප්‍රතිකාර/වැඩසටහන් ගාස්තු එම තක්සේරුවෙන් පසුව පැහැදිලි කරනු ඇත.',
                'Appointment එක කාර්ය මණ්ඩලය විසින් තහවුරු කර ගෙවීම් ඉල්ලීමක් එවන තුරු ගෙවීමක් නොකරන්න. ගෙවීම් ඉල්ලීමේ දක්වා ඇති මුදල, කාල සීමාව සහ කොන්දේසි අදාළ වේ. Refund සහ cancellation විස්තර සඳහා Refund & Cancellation Policy බලන්න.',
            ],
        ],
        [
            'heading' => 'ඔබගේ වගකීම් සහ තොරතුරු',
            'paragraphs' => [
                'ඔබගේ සත්කාරයට අදාළ තොරතුරු වෛද්‍යවරයා සමඟ අවංකව බෙදාගන්න. Appointment ඉල්ලීම හෝ ගෙවීම සම්බන්ධ පණිවිඩවල සත්‍යතාව තහවුරු කරන්න; අපගේ නිල සම්බන්ධතා මාර්ග පිටුවේ දක්වා ඇත.',
                'ඔබගේ තොරතුරු භාවිතා කරන ආකාරය සඳහා Privacy Policy අදාළ වේ. මෙම වෙබ් අඩවියට හා එහි අන්තර්ගතයට අනවසර ප්‍රවේශයක් හෝ බාධාවක් ඇති කිරීමට උත්සාහ නොකරන්න.',
            ],
        ],
        [
            'heading' => 'ලබාගත හැකි බව සහ වෙනස්කම්',
            'paragraphs' => [
                'වෛද්‍යවරුන්ගේ වේලාවන් සහ සේවා ලබාගත හැකි බව වෙනස් විය හැක. අවශ්‍ය වූ විට appointment වේලාවක් හෝ සේවා සැලැස්මක් වෙනස් කිරීමට අප ඔබ සමඟ සම්බන්ධ වී සුදුසු විකල්පයක් සාකච්ඡා කරන්නෙමු.',
                'වෙබ් අඩවියේ තොරතුරු යාවත්කාලීන කිරීමට හෝ මෙම කොන්දේසි සංශෝධනය කිරීමට අපට හැකිය. නවතම අනුවාදය මෙම පිටුවේ පළ කරනු ඇත.',
            ],
        ],
    ],
] : [
    'title' => 'Terms & Conditions',
    'intro' => 'These terms apply when using the DEVYA CEYLON website and submitting an appointment request.',
    'updated' => 'Last updated: 26 October 2026',
    'sections' => [
        [
            'heading' => 'Website and appointment requests',
            'paragraphs' => [
                'Information on this website is provided for general information about services. Submitting an appointment request confirms only that it was received; no appointment is booked until staff contact you and confirm a time.',
                'Provide accurate and current contact details when making a request. We may contact you for further information about it.',
            ],
        ],
        [
            'heading' => 'Medical advice and services',
            'paragraphs' => [
                'Care, examinations, or treatment decisions are made following assessment and advice from a qualified practitioner. Suitability, treatment plans, and charges may vary according to individual needs.',
                'Website information is not a substitute for professional medical advice. In a medical emergency, do not use this website or its appointment form; seek emergency medical care immediately.',
            ],
        ],
        [
            'heading' => 'Fees and payments',
            'paragraphs' => [
                'The currently listed consultation fee is LKR 3,000. An additional booking advance is LKR 1,500; when both charges apply, the total is LKR 4,500. Staff will confirm your appointment and the amount due and provide payment instructions before you pay. Charges for treatments or programmes determined after examination will be explained after that assessment.',
                'Do not pay until staff confirm your appointment and send a payment request. The amount, deadline, and terms in that request apply. See the Refund & Cancellation Policy for cancellation and refund information.',
            ],
        ],
        [
            'heading' => 'Your responsibilities and information',
            'paragraphs' => [
                'Share relevant information about your care honestly with the practitioner. Verify messages about appointment requests or payments; our official contact channels are listed on this website.',
                'Our Privacy Policy explains how information is used. Do not attempt to gain unauthorised access to, or disrupt, this website or its content.',
            ],
        ],
        [
            'heading' => 'Availability and changes',
            'paragraphs' => [
                'Practitioner schedules and service availability may change. If necessary, we will contact you to discuss an appointment time or service change and an appropriate alternative.',
                'We may update website information or revise these terms. The latest version will be published on this page.',
            ],
        ],
    ],
];

require __DIR__ . '/includes/policy-page.php';
