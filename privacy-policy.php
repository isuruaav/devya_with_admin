<?php
declare(strict_types=1);

require __DIR__ . '/includes/site-config.php';

$currentPage = 'privacy-policy';
$pageTitle = $language === 'si'
    ? 'පෞද්ගලිකත්ව ප්‍රතිපත්තිය | DEVYA CEYLON'
    : 'Privacy Policy | DEVYA CEYLON';
$pageDescription = $language === 'si'
    ? 'DEVYA CEYLON appointment ඉල්ලීම් සහ සෞඛ්‍ය තොරතුරු භාවිතා කරන හා ආරක්ෂා කරන ආකාරය.'
    : 'Learn how DEVYA CEYLON handles appointment details and information you provide.';
$policy = $language === 'si' ? [
    'title' => 'පෞද්ගලිකත්ව ප්‍රතිපත්තිය',
    'intro' => 'ඔබ අප වෙත ලබාදෙන පුද්ගලික සහ වෛද්‍ය තොරතුරු භාවිතා කරන හා ආරක්ෂා කරන ආකාරය මෙම ප්‍රතිපත්තියෙන් පැහැදිලි කරයි.',
    'updated' => 'අවසන් යාවත්කාලීන කිරීම: 2026 ඔක්තෝබර් 26',
    'sections' => [
        [
            'heading' => 'අප රැස් කරන තොරතුරු',
            'paragraphs' => [
                'ඔබ appointment ඉල්ලීමක් හෝ විමසීමක් කරන විට ඔබ ලබාදෙන නම, දුරකථන අංකය, email ලිපිනය, ලිපිනය/නගරය, කැමති දිනය/වේලාව සහ සේවාවට අදාළ තොරතුරු අපට ලැබිය හැක. PayHere checkout එක සඳහා අවශ්‍ය billing තොරතුරු ගෙවීම් සැකසීමට PayHere වෙත යවනු ලැබේ.',
                'ඔබ කැමැත්තෙන් ලබාදෙන සෞඛ්‍ය හෝ රෝග ලක්ෂණ පිළිබඳ තොරතුරුද appointment එක සම්බන්ධීකරණය කිරීමට හෝ වෛද්‍යවරයාට සූදානම් වීමට අවශ්‍ය වන සීමාව තුළ භාවිතා කළ හැක.',
            ],
        ],
        [
            'heading' => 'තොරතුරු භාවිතා කරන අරමුණු',
            'paragraphs' => [
                'ඔබගේ විමසීමට පිළිතුරු දීමට, appointment ඉල්ලීම සැලසුම් කර කාර්ය මණ්ඩලය මඟින් තහවුරු කිරීමට, ඔබ තෝරාගත් සත්කාරය සම්බන්ධීකරණය කිරීමට සහ ඔබ සමඟ සන්නිවේදනය කිරීමට තොරතුරු භාවිතා කරමු.',
                'ගෙවීම් සේවාවක් ලබාදෙන අවස්ථාවක ගෙවීම සහ ඒ සම්බන්ධ පරිපාලන කටයුතු සඳහා අවශ්‍ය අවම තොරතුරු භාවිතා කළ හැක. නීතියෙන් අවශ්‍ය වාර්තාද පවත්වාගෙන යා හැක.',
            ],
        ],
        [
            'heading' => 'තොරතුරු බෙදාගැනීම',
            'paragraphs' => [
                'ඔබගේ ඉල්ලීම කළමනාකරණය කිරීමට අවශ්‍ය අවස්ථාවල බලයලත් කාර්ය මණ්ඩලය සහ අදාළ වෛද්‍යවරයා සමඟ පමණක් අදාළ තොරතුරු බෙදාගත හැක.',
                'වෙබ් අඩවිය, සන්නිවේදනය හෝ ගෙවීම් සේවා සපයන ආයතනයකට සේවාව සැපයීමට අවශ්‍ය සීමිත තොරතුරු ලබාදිය හැක. නීතියෙන් අවශ්‍ය අවස්ථාවක හෝ අයිතිවාසිකම් හා ආරක්ෂාව රැකීමට අවශ්‍ය විටද තොරතුරු හෙළි කළ හැක. අපි ඔබගේ පුද්ගලික තොරතුරු විකුණන්නේ නැත.',
            ],
        ],
        [
            'heading' => 'තොරතුරු ආරක්ෂාව සහ රඳවාගැනීම',
            'paragraphs' => [
                'තොරතුරු අවසරලත් පුද්ගලයන්ට පමණක් ප්‍රවේශ විය හැකි ලෙස තබා, අනිසි ප්‍රවේශය, භාවිතය හෝ හෙළිදරව් වීම අවම කිරීමට සාධාරණ ආරක්ෂක පියවර ගනිමු. අන්තර්ජාලය හරහා යැවෙන කිසිදු ක්‍රමයක් සම්පූර්ණයෙන් අවදානම් රහිත බවට සහතික කළ නොහැක.',
                'සේවාව සැපයීමට, වාර්තා පවත්වාගෙන යාමට සහ අදාළ නීතිමය බැඳීම් ඉටු කිරීමට අවශ්‍ය කාලය පමණක් තොරතුරු රඳවාගනිමු. ඉන්පසු ඒවා මකා දැමීමට හෝ හඳුනාගත නොහැකි ආකාරයට තබාගැනීමට සුදුසු පියවර ගනිමු.',
            ],
        ],
        [
            'heading' => 'Cookies සහ ඔබගේ තේරීම්',
            'paragraphs' => [
                'වෙබ් අඩවිය භාවිතයට අවශ්‍ය තාක්ෂණික දත්ත සහ ඔබ තෝරාගත් භාෂාව මතක තබාගැනීමට browser cookie එකක් භාවිතා විය හැක. ඔබට browser settings මඟින් cookies පාලනය කළ හැකි නමුත් සමහර පහසුකම් නිසි ලෙස ක්‍රියා නොකළ හැක.',
                'ඔබගේ තොරතුරු වෙත ප්‍රවේශය, නිවැරදි කිරීම හෝ භාවිතය පිළිබඳ විමසීමක් සඳහා අප අමතන්න. නීතියෙන් හෝ වාර්තා පවත්වාගැනීමේ අවශ්‍යතාවයෙන් සමහර තොරතුරු මකා දැමිය නොහැකි විය හැක.',
            ],
        ],
    ],
] : [
    'title' => 'Privacy Policy',
    'intro' => 'This policy explains how we use and protect the personal and health information you provide to DEVYA CEYLON.',
    'updated' => 'Last updated: 26 October 2026',
    'sections' => [
        [
            'heading' => 'Information we collect',
            'paragraphs' => [
                'When you submit an appointment request or enquiry, we may receive the name, telephone number, email address, address and city, preferred date or time, and service details you provide. Billing details required for PayHere checkout are sent to PayHere to process the payment.',
                'We may also use health or symptom information you choose to share, only to the extent needed to coordinate your request or help the practitioner prepare.',
            ],
        ],
        [
            'heading' => 'How we use information',
            'paragraphs' => [
                'We use information to respond to enquiries, arrange and have staff confirm appointment requests, coordinate the care you ask about, and communicate with you.',
                'If a payment service is offered, we may use the minimum information needed to process that payment and related administration. We may also keep records required by law.',
            ],
        ],
        [
            'heading' => 'When information is shared',
            'paragraphs' => [
                'Relevant information may be shared with authorised staff and the practitioner concerned when needed to manage your request.',
                'We may provide limited information to service providers supporting the website, communications, or payment services where needed to provide the service. We may also disclose information when required by law or necessary to protect rights and safety. We do not sell your personal information.',
            ],
        ],
        [
            'heading' => 'Security and retention',
            'paragraphs' => [
                'We restrict access to authorised people and take reasonable steps to reduce unauthorised access, use, or disclosure. No method of sending information over the internet can be guaranteed to be completely secure.',
                'We keep information only for as long as needed to provide services, maintain records, and meet applicable legal obligations. We then take appropriate steps to delete it or make it no longer identifiable.',
            ],
        ],
        [
            'heading' => 'Cookies and your choices',
            'paragraphs' => [
                'The website may use technical data needed to operate and a browser cookie to remember your selected language. You can control cookies in your browser settings, though some features may not work as intended.',
                'Contact us to ask about accessing or correcting your information, or to raise a concern about its use. Some information may need to be retained where required by law or record-keeping obligations.',
            ],
        ],
    ],
];

require __DIR__ . '/includes/policy-page.php';
