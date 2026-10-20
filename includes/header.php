<?php
/** @var array $site */
/** @var array $navigation */
/** @var string $pageTitle */
/** @var string $pageDescription */
/** @var string $currentPage */
require_once __DIR__ . '/seo.php';
$seo = seo_metadata($currentPage, $pageTitle, $pageDescription);
if (!seo_is_production() && !headers_sent()) {
    header('X-Robots-Tag: noindex, follow');
}
?>
<!doctype html>
<html lang="<?= e($language) ?>" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($seo['title']) ?></title>
  <meta name="description" content="<?= e($seo['description']) ?>">
  <meta name="theme-color" content="#173f2b">
  <meta name="robots" content="<?= e($seo['robots']) ?>">
  <link rel="canonical" href="<?= e($seo['canonical']) ?>">
  <?php foreach ($seo['alternates'] as $locale => $url): ?>
  <link rel="alternate" hreflang="<?= e($locale) ?>" href="<?= e($url) ?>">
  <?php endforeach; ?>
  <link rel="icon" href="<?= e(site_url('favicon.ico')) ?>" sizes="16x16 32x32 48x48 64x64 128x128 256x256">
  <link rel="icon" type="image/png" href="<?= e(site_url('favicon-96x96.png')) ?>" sizes="96x96">
  <link rel="apple-touch-icon" href="<?= e(site_url('apple-touch-icon.png')) ?>" sizes="180x180">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= e($site['name']) ?>">
  <meta property="og:title" content="<?= e($seo['title']) ?>">
  <meta property="og:description" content="<?= e($seo['description']) ?>">
  <meta property="og:url" content="<?= e($seo['canonical']) ?>">
  <meta property="og:locale" content="<?= e($seo['locale']) ?>">
  <meta property="og:locale:alternate" content="<?= e($seo['alternate_locale']) ?>">
  <meta property="og:image" content="<?= e($seo['image']) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:alt" content="<?= e($seo['image_alt']) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($seo['title']) ?>">
  <meta name="twitter:description" content="<?= e($seo['description']) ?>">
  <meta name="twitter:image" content="<?= e($seo['image']) ?>">
  <meta name="twitter:image:alt" content="<?= e($seo['image_alt']) ?>">
  <script type="application/ld+json"><?= json_encode(seo_structured_data($seo), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="<?= e(asset_url('css/site.css', true)) ?>">
</head>
<body data-page="<?= e($currentPage) ?>">
<a class="skip-link" href="#main-content"><?= e(t('Skip to content')) ?></a>
<div class="page-progress" aria-hidden="true"><span id="pageProgressBar"></span></div>

<div class="announcement-bar">
  <div class="container announcement-inner">
    <p><i class="fa-solid fa-leaf" aria-hidden="true"></i> <?= e(t('Empowering Well-being through authentic Sri Lankan Ayurveda and modern appointment-based care in Kandy, Sri Lanka.')) ?></p>
    <div class="announcement-actions">
      <a class="announcement-book" href="<?= e(site_url('appointments.php')) ?>"><?= e(t('Request an appointment')) ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      <?php require __DIR__ . '/language-switch.php'; ?>
    </div>
  </div>
</div>

<header class="site-header" id="siteHeader">
  <div class="container header-inner">
  <a class="brand" href="<?= e(site_url('index.php')) ?>" aria-label="<?= e(t('DEVYA CEYLON home')) ?>">

  <img
    class="brand-logo"
    src="<?= e(asset_url('images/brand-logo.webp', false)) ?>"
    alt="<?= e(t('DEVYA CEYLON logo')) ?>"
    width="64"
    height="64"
    loading="eager"
  >

  <span class="brand-copy">
    <strong><?= e($site['name']) ?></strong>
    <small><?= e(t($site['subtitle'])) ?></small>
  </span>

</a>
    <nav class="desktop-nav" aria-label="<?= e(t('Primary navigation')) ?>">
      <?php foreach ($navigation as $item): ?>
        <a class="nav-link<?= active_nav($item['key'], $currentPage) ?>" href="<?= e(site_url($item['href'])) ?>">
          <i class="fa-solid <?= e($item['icon']) ?>" aria-hidden="true"></i>
          <span><?= e(t($item['label'])) ?></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="header-actions">
      <a class="icon-button phone-button" href="tel:<?= e($site['phone_href']) ?>" aria-label="<?= e(t('Call {phone}', ['phone' => $site['phone_display']])) ?>">
        <i class="fa-solid fa-phone" aria-hidden="true"></i>
      </a>
      <a class="button button-primary header-book" href="<?= e(site_url('appointments.php')) ?>">
        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i> <?= e(t('Book Now')) ?>
      </a>
      <button class="menu-toggle" id="menuToggle" type="button" aria-label="<?= e(t('Open menu')) ?>" aria-controls="mobileMenu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <div class="mobile-menu" id="mobileMenu">
    <nav class="mobile-nav container" aria-label="<?= e(t('Mobile navigation')) ?>">
      <?php foreach ($navigation as $item): ?>
        <a class="mobile-nav-link<?= active_nav($item['key'], $currentPage) ?>" href="<?= e(site_url($item['href'])) ?>" data-menu-link>
          <span class="mobile-nav-icon"><i class="fa-solid <?= e($item['icon']) ?>" aria-hidden="true"></i></span>
          <span><?= e(t($item['label'])) ?></span>
          <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      <?php endforeach; ?>
      <div class="mobile-quick-actions">
        <a class="button button-secondary" href="tel:<?= e($site['phone_href']) ?>"><i class="fa-solid fa-phone"></i> <?= e(t('Call')) ?></a>
        <a class="button button-whatsapp" href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> <?= e(t('WhatsApp')) ?></a>
      </div>
    </nav>
  </div>
</header>
