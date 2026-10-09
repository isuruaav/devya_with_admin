<?php
/** @var array $site */
/** @var array $navigation */
/** @var string $pageTitle */
/** @var string $pageDescription */
/** @var string $currentPage */
?>
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDescription) ?>">
  <meta name="theme-color" content="#173f2b">
  <meta name="robots" content="index, follow">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDescription) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="<?= e(asset_url('css/site.css', true)) ?>">
</head>
<body data-page="<?= e($currentPage) ?>">
<a class="skip-link" href="#main-content">Skip to content</a>
<div class="page-progress" aria-hidden="true"><span id="pageProgressBar"></span></div>

<div class="announcement-bar">
  <div class="container announcement-inner">
    <p><i class="fa-solid fa-leaf" aria-hidden="true"></i> <?= e($site['tagline']) ?> through authentic Sri Lankan Ayurveda and modern appointment-based care in <?= e($site['location']) ?>.</p>
    <a href="<?= e(site_url('appointments.php')) ?>">Request an appointment <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
  </div>
</div>

<header class="site-header" id="siteHeader">
  <div class="container header-inner">
  <a class="brand" href="<?= e(site_url('index.php')) ?>" aria-label="<?= e($site['name']) ?> home">

  <img
    class="brand-logo"
    src="<?= e(asset_url('images/logo.png', false)) ?>"
    alt="<?= e($site['name']) ?> logo"
    width="64"
    height="64"
    loading="eager"
    fetchpriority="high"
  >

  <span class="brand-copy">
    <strong><?= e($site['name']) ?></strong>
    <small><?= e($site['subtitle']) ?></small>
  </span>

</a>
    <nav class="desktop-nav" aria-label="Primary navigation">
      <?php foreach ($navigation as $item): ?>
        <a class="nav-link<?= active_nav($item['key'], $currentPage) ?>" href="<?= e(site_url($item['href'])) ?>">
          <i class="fa-solid <?= e($item['icon']) ?>" aria-hidden="true"></i>
          <span><?= e($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="header-actions">
      <a class="icon-button phone-button" href="tel:<?= e($site['phone_href']) ?>" aria-label="Call <?= e($site['phone_display']) ?>">
        <i class="fa-solid fa-phone" aria-hidden="true"></i>
      </a>
      <a class="button button-primary header-book" href="<?= e(site_url('appointments.php')) ?>">
        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Book Now
      </a>
      <button class="menu-toggle" id="menuToggle" type="button" aria-label="Open menu" aria-controls="mobileMenu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <div class="mobile-menu" id="mobileMenu">
    <nav class="mobile-nav container" aria-label="Mobile navigation">
      <?php foreach ($navigation as $item): ?>
        <a class="mobile-nav-link<?= active_nav($item['key'], $currentPage) ?>" href="<?= e(site_url($item['href'])) ?>" data-menu-link>
          <span class="mobile-nav-icon"><i class="fa-solid <?= e($item['icon']) ?>" aria-hidden="true"></i></span>
          <span><?= e($item['label']) ?></span>
          <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      <?php endforeach; ?>
      <div class="mobile-quick-actions">
        <a class="button button-secondary" href="tel:<?= e($site['phone_href']) ?>"><i class="fa-solid fa-phone"></i> Call</a>
        <a class="button button-whatsapp" href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
      </div>
    </nav>
  </div>
</header>
