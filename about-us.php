<?php
require __DIR__ . '/includes/site-config.php';
$pageTitle = 'About Us | DEVYA CEYLON Ayurveda Centre, Kandy';
$pageDescription = 'Learn about DEVYA CEYLON Ayurveda Channelling Centre in Kandy, including its background, vision, mission and project objectives.';
$currentPage = 'about';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
<section class="page-hero about-page-hero">
  <div class="container page-hero-grid">

    <div class="reveal">
      <span class="eyebrow">
        <i class="fa-solid fa-circle-info"></i>
        <?= e(t('About DEVYA CEYLON')) ?>
      </span>

      <h1 class="page-title">
        <?= e(t('A trusted Ayurveda gateway in')) ?>
        <span style="color:var(--gold-700)"><?= e(t('Kandy, Sri Lanka.')) ?></span>
      </h1>

      <p>
        <?= e(t('DEVYA CEYLON is being developed as a modern Ayurveda Channelling Centre that connects authentic Sri Lankan healing traditions with professional, convenient and patient-centred appointment-based care.')) ?>
      </p>

      <div class="breadcrumbs">
        <a href="<?= e(site_url('index.php')) ?>"><?= e(t('Home')) ?></a>
        <i class="fa-solid fa-chevron-right"></i>
        <span><?= e(t('About Us')) ?></span>
      </div>
    </div>

    <aside class="about-hero-image reveal">
      <img
        src="<?= e(asset_url('images/about-kandy-ayurveda.webp', false)) ?>"
        alt="<?= e(t('Modern Ayurveda wellness centre inspired by Kandyan heritage in Kandy Sri Lanka')) ?>"
        width="1500"
        height="1100"
        loading="eager"
        fetchpriority="high"
        decoding="async"
      >

      <div class="about-location-badge">
        <i class="fa-solid fa-location-dot"></i>

        <span>
          <strong><?= e(t('Kandy')) ?></strong>
          <?= e(t('Sri Lanka')) ?>
        </span>
      </div>
    </aside>

  </div>
</section>

  <section class="section intro-section">
    <div class="container content-grid">
      <div class="sticky-copy reveal">
        <span class="eyebrow"><i class="fa-solid fa-file-lines"></i> <?= e(t('Executive summary')) ?></span>
        <div class="section-head"><h2><?= e(t('Traditional consultation, wellness and modern access in one patient journey.')) ?></h2><p><?= e(t('The Centre is designed to improve accessibility, convenience and quality in Ayurveda healthcare.')) ?></p></div>
      </div>
      <article class="prose-card reveal">
        <p><?= e(t('Devya Ceylon Ayurveda Centre proposes to establish a modern Ayurveda Channelling Centre that integrates traditional Sri Lankan Ayurveda consultation and wellness therapies with efficient appointment-based healthcare delivery.')) ?></p>
        <p><?= e(t('The Centre will serve as a trusted gateway connecting patients to qualified Ayurveda medical practitioners, specialized treatment packages, wellness consultations and preventive healthcare services in a professional, client-friendly environment.')) ?></p>
        <p><?= e(t('Located in Kandy, Sri Lanka, the project aims to improve accessibility, convenience and quality of Ayurveda healthcare while supporting preventive medicine and wellness tourism.')) ?></p>
      </article>
    </div>
  </section>

 <section class="section about-story-section">
  <div class="container intro-grid">

    <div class="image-stack reveal">

      <div class="image-stack-main">
        <img
          src="<?= e(asset_url('images/about-traditional-healing.webp', false)) ?>"
          alt="<?= e(t('Traditional Kandyan inspired Ayurveda healing and wellness environment')) ?>"
          width="1400"
          height="1100"
          loading="lazy"
          decoding="async"
        >
      </div>

      <div class="image-stack-small">
        <img
          src="<?= e(asset_url('images/home-about-detail.webp', false)) ?>"
          alt="<?= e(t('Traditional Ayurveda herbs, medicinal oils and natural healing ingredients')) ?>"
          width="800"
          height="900"
          loading="lazy"
          decoding="async"
        >
      </div>

      <div class="experience-badge">
        <div>
          <strong>01</strong>
          <span><?= e(t('Integrated centre')) ?></span>
        </div>
      </div>

    </div>

    <div class="reveal">

      <span class="eyebrow">
        <i class="fa-solid fa-chart-line"></i>
        <?= e(t('Background & rationale')) ?>
      </span>

      <div class="section-head">
        <h2><?= e(t('Bridging a service gap in structured Ayurveda care.')) ?></h2>

        <p>
          <?= e(t('DEVYA CEYLON responds to increasing demand for organized consultation, wellness and digital support services.')) ?>
        </p>
      </div>

      <div class="feature-list">

        <article class="feature-row">
          <i class="fa-solid fa-shield-heart"></i>

          <div>
            <h3><?= e(t('Preventive and holistic healthcare')) ?></h3>
            <p>
              <?= e(t('Growing public interest in preventive health, balanced lifestyles and holistic care.')) ?>
            </p>
          </div>
        </article>

        <article class="feature-row">
          <i class="fa-solid fa-user-doctor"></i>

          <div>
            <h3><?= e(t('Organized specialist access')) ?></h3>
            <p>
              <?= e(t('A need for structured specialist consultation systems within the Ayurveda sector.')) ?>
            </p>
          </div>
        </article>

        <article class="feature-row">
          <i class="fa-solid fa-calendar-check"></i>

          <div>
            <h3><?= e(t('Professional appointment services')) ?></h3>
            <p>
              <?= e(t('Limited availability of dedicated Ayurveda appointment and channelling platforms.')) ?>
            </p>
          </div>
        </article>

        <article class="feature-row">
          <i class="fa-solid fa-plane-arrival"></i>

          <div>
            <h3><?= e(t('Wellness tourism opportunity')) ?></h3>
            <p>
              <?= e(t('Increasing opportunities to serve domestic and international wellness clients.')) ?>
            </p>
          </div>
        </article>

      </div>
    </div>

  </div>
</section>

  <section class="section digital-section" id="vision-mission">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-bullseye"></i> <?= e(t('Vision & mission')) ?></span><h2><?= e(t('Empowering Well-being through accessible and authentic care.')) ?></h2><p><?= e(t('Our strategic direction combines traditional healthcare values with professional service delivery.')) ?></p></div>
      <div class="vision-grid">
        <article class="vision-card vision reveal"><i class="fa-solid fa-eye"></i><h3><?= e(t('Our Vision')) ?></h3><p><?= e(t('To become Sri Lanka\'s leading trusted Ayurveda channelling and integrated wellness centre, delivering accessible, authentic and modernized traditional healthcare.')) ?></p></article>
        <article class="vision-card mission reveal"><i class="fa-solid fa-compass"></i><h3><?= e(t('Our Mission')) ?></h3><p><?= e(t('To provide professional, convenient and patient-centred Ayurveda consultation and wellness services through qualified practitioners, innovative service delivery and authentic Sri Lankan healing traditions.')) ?></p></article>
      </div>
    </div>
  </section>

  <section class="section audience-section" id="objectives">
    <div class="container">
      <div class="section-head split reveal">
        <div><span class="eyebrow"><i class="fa-solid fa-list-check"></i> <?= e(t('Project objectives')) ?></span><h2><?= e(t('Building a sustainable Ayurveda Channelling Centre.')) ?></h2></div>
        <div><p><strong style="color:var(--forest-900)"><?= e(t('General objective:')) ?></strong> <?= e(t('To establish a sustainable Ayurveda Channelling Centre delivering quality traditional healthcare and wellness services.')) ?></p></div>
      </div>
      <div class="objective-grid">
        <article class="objective-card reveal"><span><?= e(t('OBJECTIVE 01')) ?></span><h3><?= e(t('Qualified consultations')) ?></h3><p><?= e(t('Facilitate appointment-based consultations with qualified Ayurveda doctors.')) ?></p></article>
        <article class="objective-card reveal"><span><?= e(t('OBJECTIVE 02')) ?></span><h3><?= e(t('Specialized programmes')) ?></h3><p><?= e(t('Provide specialized wellness and therapeutic treatment packages.')) ?></p></article>
        <article class="objective-card reveal"><span><?= e(t('OBJECTIVE 03')) ?></span><h3><?= e(t('Preventive health')) ?></h3><p><?= e(t('Promote preventive health and healthy lifestyle practices.')) ?></p></article>
        <article class="objective-card reveal"><span><?= e(t('OBJECTIVE 04')) ?></span><h3><?= e(t('Wellness tourism')) ?></h3><p><?= e(t('Support medical and wellness tourism initiatives in Sri Lanka.')) ?></p></article>
        <article class="objective-card reveal"><span><?= e(t('OBJECTIVE 05')) ?></span><h3><?= e(t('Digital transformation')) ?></h3><p><?= e(t('Digitalize appointment booking and patient record systems.')) ?></p></article>
        <article class="objective-card reveal"><span><?= e(t('OBJECTIVE 06')) ?></span><h3><?= e(t('Employment creation')) ?></h3><p><?= e(t('Create employment opportunities in the Ayurveda and wellness sectors.')) ?></p></article>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container cta-panel reveal">
      <div><span class="eyebrow light"><i class="fa-solid fa-leaf"></i> <?= e(t('Continue your journey')) ?></span><h2><?= e(t('Explore our proposed care services in Kandy.')) ?></h2><p><?= e(t('Review the clinical, specialist, wellness and supportive service pathways or request an appointment.')) ?></p></div>
      <div class="cta-actions"><a class="button button-gold" href="<?= e(site_url('services.php')) ?>"><i class="fa-solid fa-spa"></i> <?= e(t('View Services')) ?></a><a class="button button-secondary" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Appointment')) ?></a></div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
