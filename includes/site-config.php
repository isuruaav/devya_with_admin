<?php

declare(strict_types=1);

$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$basePath = in_array($scriptDirectory, ['/', '.'], true) ? '' : rtrim($scriptDirectory, '/');

$site = [
    'base_path' => $basePath,
    'name' => 'DEVYA CEYLON',
    'url' => 'https://devyaceylon.lk',
    'legal_name' => 'Devya Ceylon Ayurveda Centre',
    'legal_name_is_example' => true,
    'consultation_fee_lkr' => 3000,
    'booking_advance_lkr' => 1500,
    'subtitle' => 'Ayurveda Channelling Centre',
    'tagline' => 'Empowering Well-being',
    'phone_display' => '072 200 0008',
    'phone_href' => '+94722000008',
    'phone_contact' => 'Dr. Varuni',
    'secondary_phone_display' => '076 766 7644',
    'secondary_phone_href' => '+94767667644',
    'secondary_phone_contact' => 'Ms. Chathurika',
    'whatsapp' => '94713423173',
    'email' => 'info@devyaceylon.lk',
    'location' => 'Kandy, Sri Lanka',
    'address' => '853/1/A, Old Peradeniya Road, Kandy, Sri Lanka',
    'street_address' => '853/1/A, Old Peradeniya Road',
    'address_locality' => 'Kandy',
    'address_country' => 'LK',
    'maps_url' => 'https://www.google.com/maps/search/?api=1&query=853%2F1%2FA%2C%20Old%20Peradeniya%20Road%2C%20Kandy%2C%20Sri%20Lanka',
    'system_appointment_endpoint' => getenv('DEVYA_APPOINTMENT_ENDPOINT') ?: 'devya/public/website-appointment',
];

$consultationHours = [
    [
        'days' => 'Monday to Saturday',
        'time' => '4:30 PM - 9:00 PM',
        'schema_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'opens' => '16:30',
        'closes' => '21:00',
    ],
    [
        'days' => 'Sunday',
        'time' => '9:00 AM - 5:00 PM',
        'schema_days' => ['Sunday'],
        'opens' => '09:00',
        'closes' => '17:00',
    ],
];

$doctorSchedule = [
    ['day' => 'Monday', 'doctor' => 'Dr. Siril Wickramathilake', 'designation' => 'Ayurveda General Physician', 'clinic' => 'Paralysis & Orthopedic Clinic'],
    ['day' => 'Tuesday', 'doctor' => 'Dr. Y D S S Suraweera', 'designation' => 'Consultant Ayurveda Visiting Physician', 'clinic' => 'Diabetes, Heart Diseases & Weight Management Clinic'],
    ['day' => 'Wednesday', 'doctor' => 'Dr. Manjula Kadudunkorala', 'designation' => 'Consultant Ayurveda VOG', 'clinic' => "Women's Diseases & Children's Diseases Clinic"],
    ['day' => 'Thursday', 'doctor' => 'Dr. Chandani Herath', 'designation' => 'Ayurveda General Physician', 'clinic' => 'Skin Diseases & Acupuncture Clinic'],
    ['day' => 'Friday', 'doctor' => 'Dr. Darshani Dissanayake', 'designation' => 'Ayurveda General Physician', 'clinic' => 'Gastritis & Piles Clinic'],
    ['day' => 'Saturday', 'doctor' => 'Dr. Lakshmi Bandara', 'designation' => 'Ayurveda General Physician', 'clinic' => 'Joint Diseases & Orthopedic Clinic'],
    ['day' => 'Sunday', 'doctor' => 'Dr. Varuni Weerasinghe', 'designation' => 'Ayurveda General Physician', 'clinic' => 'Diabetes Clinic & Consultation for International Clients'],
];

$navigation = [
    ['key' => 'home', 'label' => 'Home', 'href' => 'index.php', 'icon' => 'fa-house'],
    ['key' => 'about', 'label' => 'About Us', 'href' => 'about-us.php', 'icon' => 'fa-circle-info'],
    ['key' => 'services', 'label' => 'Services', 'href' => 'services.php', 'icon' => 'fa-spa'],
    ['key' => 'doctors', 'label' => 'Doctors', 'href' => 'index.php#doctor-schedule', 'icon' => 'fa-user-doctor'],
    ['key' => 'facilities', 'label' => 'Facilities & Team', 'href' => 'facilities.php', 'icon' => 'fa-building-circle-check'],
    ['key' => 'appointments', 'label' => 'Appointments', 'href' => 'appointments.php', 'icon' => 'fa-calendar-check'],
    ['key' => 'contact', 'label' => 'Contact', 'href' => 'contact.php', 'icon' => 'fa-phone-volume'],
];

require __DIR__ . '/i18n.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function active_nav(string $key, string $currentPage): string
{
    return $key === $currentPage ? ' is-active' : '';
}

function site_url(string $path = ''): string
{
    global $site;

    if (str_starts_with($path, '#') || preg_match('~^(?:[a-z][a-z0-9+.-]*:|//)~i', $path)) {
        return $path;
    }

    return $site['base_path'] . '/' . ltrim(localized_page_path($path), '/');
}

function asset_url(string $path, bool $versioned = false): string
{
    $relativePath = 'assets/' . ltrim($path, '/');
    $url = site_url($relativePath);
    $file = dirname(__DIR__) . '/' . $relativePath;

    if ($versioned && is_file($file)) {
        $url .= '?v=' . filemtime($file);
    }

    return $url;
}
