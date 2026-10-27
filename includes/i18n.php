<?php

declare(strict_types=1);

// Public website copy lives in languages/en.json and languages/si.json.
// JSON values are plain text. Templates escape them before displaying them.
$supportedLanguages = ['en', 'si'];
$requestedLanguage = $_GET['lang'] ?? null;
$rememberedLanguage = $_COOKIE['devya_language'] ?? null;
$language = is_string($requestedLanguage) && in_array($requestedLanguage, $supportedLanguages, true)
    ? $requestedLanguage
    : (is_string($rememberedLanguage) && in_array($rememberedLanguage, $supportedLanguages, true) ? $rememberedLanguage : 'en');

if (!function_exists('load_translations')) {
function load_translations(string $locale): array
{
    $file = dirname(__DIR__) . '/languages/' . $locale . '.json';
    if (!is_file($file)) {
        return [];
    }

    try {
        $messages = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($messages)) {
            throw new RuntimeException('The translation file must contain a JSON object.');
        }

        return array_filter($messages, static fn ($value): bool => is_string($value) && trim($value) !== '');
    } catch (JsonException | RuntimeException $error) {
        error_log('DEVYA translations (' . $locale . '): ' . $error->getMessage());
        return [];
    }
}
}

$englishTranslations = load_translations('en');
$translations = $language === 'en' ? $englishTranslations : load_translations($language);

if (!headers_sent()) {
    header('Content-Language: ' . $language);
    header('Vary: Cookie', false);
    if (is_string($requestedLanguage) && in_array($requestedLanguage, $supportedLanguages, true)) {
        setcookie('devya_language', $language, [
            'expires' => time() + 60 * 60 * 24 * 365,
            'path' => $site['base_path'] ?: '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}

if (!function_exists('t')) {
function t(string $message, array $replacements = []): string
{
    global $translations, $englishTranslations;

    $key = trim((string) preg_replace('/\s+/u', ' ', $message));
    $value = $translations[$key] ?? $englishTranslations[$key] ?? $key;
    if (!$replacements) {
        return $value;
    }

    $tokens = [];
    foreach ($replacements as $name => $replacement) {
        $tokens['{' . $name . '}'] = (string) $replacement;
    }

    return strtr($value, $tokens);
}
}

if (!function_exists('localized_page_path')) {
function localized_page_path(string $path): string
{
    global $language;

    $publicPages = ['', 'index.php', 'about.php', 'about-us.php', 'vision-mission.php', 'services.php', 'facilities.php', 'appointments.php', 'contact.php', 'privacy-policy.php', 'refund-policy.php', 'terms-and-conditions.php'];
    $parts = parse_url($path);
    if ($parts === false || !in_array(ltrim($parts['path'] ?? '', '/'), $publicPages, true)) {
        return $path;
    }

    parse_str($parts['query'] ?? '', $query);
    if (!isset($query['lang']) || !in_array($query['lang'], ['en', 'si'], true)) {
        $query['lang'] = $language;
    }

    return ($parts['path'] ?? '') . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986)
        . (isset($parts['fragment']) ? '#' . $parts['fragment'] : '');
}
}

if (!function_exists('language_url')) {
function language_url(string $locale): string
{
    $publicPages = ['index.php', 'about.php', 'about-us.php', 'vision-mission.php', 'services.php', 'facilities.php', 'appointments.php', 'contact.php', 'privacy-policy.php', 'refund-policy.php', 'terms-and-conditions.php'];
    $page = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    if (!in_array($page, $publicPages, true)) {
        $page = 'index.php';
    }

    $query = $_GET;
    $query['lang'] = in_array($locale, ['en', 'si'], true) ? $locale : 'en';
    return site_url($page . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986));
}
}

if (!function_exists('browser_translations')) {
function browser_translations(): array
{
    $messages = [
        'Open menu', 'Close menu',
        'Choose a date to see the available consultation period.',
        'Consultation time: {time}. Final time requires staff confirmation.',
        'Submitting your appointment request...',
        'Unable to submit the appointment request.',
        'Please check the highlighted fields and try again.',
        'Unable to confirm your booking. Please call the Centre before submitting again.',
        'Your appointment request has been received. Booking number: {booking_number}. Staff will contact you to confirm availability.',
        'Your request is saved. Booking number: {booking_number}. Opening WhatsApp — press Send to notify the Centre.',
        'Unable to submit the appointment request. Please call the Centre.',
        'The service is temporarily unavailable. Please try again shortly or call the Centre.',
    ];

    return array_combine($messages, array_map(static fn (string $message): string => t($message), $messages));
}
}
