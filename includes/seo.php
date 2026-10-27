<?php

declare(strict_types=1);

function seo_pages(): array
{
    return [
        'home' => ['path' => '', 'label' => 'Home', 'type' => 'WebPage'],
        'about' => ['path' => 'about-us.php', 'label' => 'About Us', 'type' => 'AboutPage'],
        'services' => ['path' => 'services.php', 'label' => 'Services', 'type' => 'CollectionPage'],
        'facilities' => ['path' => 'facilities.php', 'label' => 'Facilities & Team', 'type' => 'WebPage'],
        'appointments' => ['path' => 'appointments.php', 'label' => 'Appointments', 'type' => 'WebPage'],
        'contact' => ['path' => 'contact.php', 'label' => 'Contact', 'type' => 'ContactPage'],
        'privacy-policy' => ['path' => 'privacy-policy.php', 'label' => 'Privacy Policy', 'type' => 'WebPage'],
        'refund-policy' => ['path' => 'refund-policy.php', 'label' => 'Refund & Cancellation Policy', 'type' => 'WebPage'],
        'terms-and-conditions' => ['path' => 'terms-and-conditions.php', 'label' => 'Terms & Conditions', 'type' => 'WebPage'],
    ];
}

function seo_origin(): string
{
    global $site;

    $url = rtrim($site['url'] ?? 'https://devyaceylon.lk', '/');
    $parts = parse_url($url);
    if ($parts === false || ($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])
        || isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])) {
        return 'https://devyaceylon.lk';
    }

    return $url;
}

function seo_absolute_url(string $path = ''): string
{
    return seo_origin() . '/' . ltrim($path, '/');
}

function seo_page_url(string $page, string $locale): string
{
    $pages = seo_pages();
    $path = $pages[$page]['path'] ?? '';
    return seo_absolute_url($path) . '?lang=' . (in_array($locale, ['en', 'si'], true) ? $locale : 'en');
}

function seo_is_production(): bool
{
    $expectedHost = strtolower((string) parse_url(seo_origin(), PHP_URL_HOST));
    $requestHost = strtolower((string) parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST));
    return $requestHost === $expectedHost || $requestHost === 'www.' . $expectedHost;
}

function seo_copy(): array
{
    static $copy = null;
    if ($copy !== null) {
        return $copy;
    }

    $file = dirname(__DIR__) . '/seo.json';
    if (!is_readable($file)) {
        return $copy = [];
    }

    try {
        $decoded = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        return $copy = is_array($decoded) ? $decoded : [];
    } catch (JsonException $error) {
        error_log('DEVYA SEO copy: ' . $error->getMessage());
        return $copy = [];
    }
}

function seo_metadata(string $page, string $fallbackTitle, string $fallbackDescription): array
{
    global $language;

    $pages = seo_pages();
    $page = isset($pages[$page]) ? $page : 'home';
    $copy = seo_copy()[$page][$language] ?? [];
    $copy = is_array($copy) ? $copy : [];
    $title = is_string($copy['title'] ?? null) && trim($copy['title']) !== '' ? $copy['title'] : t($fallbackTitle);
    $description = is_string($copy['description'] ?? null) && trim($copy['description']) !== '' ? $copy['description'] : t($fallbackDescription);

    return [
        'page' => $page,
        'title' => $title,
        'description' => $description,
        'canonical' => seo_page_url($page, $language),
        'alternates' => ['en' => seo_page_url($page, 'en'), 'si' => seo_page_url($page, 'si'), 'x-default' => seo_page_url($page, 'en')],
        'image' => seo_absolute_url('assets/images/social-preview.jpg'),
        'image_alt' => 'Devya Ceylon Ayurveda Centre, Kandy, Sri Lanka',
        'locale' => $language === 'si' ? 'si_LK' : 'en_LK',
        'alternate_locale' => $language === 'si' ? 'en_LK' : 'si_LK',
        'robots' => seo_is_production() ? 'index, follow, max-image-preview:large' : 'noindex, follow',
    ];
}

function seo_structured_data(array $seo): array
{
    global $site, $consultationHours, $language;

    $home = seo_absolute_url();
    $clinicId = $home . '#clinic';
    $websiteId = $home . '#website';
    $hours = [];
    foreach ($consultationHours as $period) {
        $hours[] = [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => $period['schema_days'],
            'opens' => $period['opens'],
            'closes' => $period['closes'],
        ];
    }

    $graph = [
        [
            '@type' => 'MedicalClinic',
            '@id' => $clinicId,
            'name' => $site['legal_name'],
            'alternateName' => $site['name'],
            'url' => $home,
            'logo' => ['@type' => 'ImageObject', 'url' => seo_absolute_url('assets/images/brand-logo.webp'), 'width' => 192, 'height' => 192],
            'image' => $seo['image'],
            'telephone' => $site['phone_href'],
            'email' => $site['email'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $site['street_address'],
                'addressLocality' => $site['address_locality'],
                'addressCountry' => $site['address_country'],
            ],
            'hasMap' => $site['maps_url'],
            'contactPoint' => [
                ['@type' => 'ContactPoint', 'name' => $site['phone_contact'], 'telephone' => $site['phone_href'], 'contactType' => 'appointments'],
                ['@type' => 'ContactPoint', 'name' => $site['secondary_phone_contact'], 'telephone' => $site['secondary_phone_href'], 'contactType' => 'appointments'],
            ],
            'openingHoursSpecification' => $hours,
        ],
        [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $home,
            'name' => $site['name'],
            'alternateName' => $site['legal_name'],
            'inLanguage' => ['en', 'si'],
            'publisher' => ['@id' => $clinicId],
        ],
    ];

    $page = [
        '@type' => seo_pages()[$seo['page']]['type'],
        '@id' => $seo['canonical'] . '#webpage',
        'url' => $seo['canonical'],
        'name' => $seo['title'],
        'description' => $seo['description'],
        'inLanguage' => $language,
        'isPartOf' => ['@id' => $websiteId],
        'about' => ['@id' => $clinicId],
        'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $seo['image'], 'width' => 1200, 'height' => 630],
    ];

    if ($seo['page'] !== 'home') {
        $breadcrumbId = $seo['canonical'] . '#breadcrumb';
        $page['breadcrumb'] = ['@id' => $breadcrumbId];
        $graph[] = [
            '@type' => 'BreadcrumbList',
            '@id' => $breadcrumbId,
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => t('Home'), 'item' => seo_page_url('home', $language)],
                ['@type' => 'ListItem', 'position' => 2, 'name' => t(seo_pages()[$seo['page']]['label']), 'item' => $seo['canonical']],
            ],
        ];
    }
    $graph[] = $page;

    return ['@context' => 'https://schema.org', '@graph' => $graph];
}
