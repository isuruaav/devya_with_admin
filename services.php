<?php
require __DIR__ . '/includes/site-config.php';
$pageTitle = 'Ayurveda Services | DEVYA CEYLON';
$pageDescription = 'Explore Ayurveda OPD consultations, specialist clinics, Panchakarma, wellness programmes, teleconsultation and digital support services.';
$currentPage = 'services';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
  <section class="page-hero">
    <div class="container page-hero-grid">
      <div class="reveal">
        <span class="eyebrow"><i class="fa-solid fa-spa"></i> <?= e(t('Proposed services')) ?></span>
        <h1 class="page-title"><?= e(t('Integrated care from')) ?> <span style="color:var(--gold-700)"><?= e(t('consultation to wellness.')) ?></span></h1>
        <p><?= e(t('Explore the proposed clinical, specialist, wellness and supportive services of DEVYA CEYLON Ayurveda Channelling Centre.')) ?></p>
        <div class="breadcrumbs"><a href="<?= e(site_url('index.php')) ?>"><?= e(t('Home')) ?></a><i class="fa-solid fa-chevron-right"></i><span><?= e(t('Services')) ?></span></div>
      </div>
      <aside class="page-hero-card reveal"><span class="icon-box"><i class="fa-solid fa-notes-medical"></i></span><h2><?= e(t('Professional guidance first.')) ?></h2><p><?= e(t('Final therapies, programmes and treatment plans should be selected after consultation with a qualified Ayurveda practitioner.')) ?></p><a class="text-link" href="<?= e(site_url('index.php#doctor-schedule')) ?>"><?= e(t('View doctors & weekly clinics')) ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></aside>
    </div>
  </section>


<section class="section service-highlights-section">
  <div class="container">

    <div class="section-head split reveal">
      <div>
        <span class="eyebrow">
          <i class="fa-solid fa-star-of-life"></i>
          <?= e(t('Featured care pathways')) ?>
        </span>

        <h2>
          <?= e(t('Three essential parts of your Ayurveda care journey.')) ?>
        </h2>
      </div>

      <div>
        <p>
          <?= e(t('Begin with professional consultation, continue with suitable practitioner-guided wellness care and receive convenient digital appointment support throughout your journey.')) ?>
        </p>
      </div>
    </div>

    <div class="service-highlight-grid">

      <!-- Consultation -->
      <article class="service-highlight-card reveal">

        <a
          class="service-highlight-image"
          href="#clinical"
          aria-label="<?= e(t('View Ayurveda consultation services')) ?>"
        >
          <img
            src="<?= e(asset_url('images/service-consultation.webp', false)) ?>"
            alt="<?= e(t('Professional Ayurveda consultation at a modern wellness centre in Kandy Sri Lanka')) ?>"
            width="1200"
            height="800"
            loading="lazy"
            decoding="async"
          >

          <span class="service-highlight-number">01</span>
        </a>

        <div class="service-highlight-content">

          <span class="service-highlight-label">
            <i class="fa-solid fa-user-doctor"></i>
            <?= e(t('Clinical Care')) ?>
          </span>

          <h3><?= e(t('Professional Ayurveda Consultation')) ?></h3>

          <p>
            <?= e(t('Begin with a structured consultation and professional assessment from a qualified Ayurveda practitioner.')) ?>
          </p>

          <a class="text-link" href="#clinical">
            <?= e(t('Explore consultations')) ?>
            <i class="fa-solid fa-arrow-right"></i>
          </a>

        </div>
      </article>

      <!-- Panchakarma -->
      <article class="service-highlight-card reveal">

        <a
          class="service-highlight-image"
          href="#wellness"
          aria-label="<?= e(t('View Panchakarma and wellness services')) ?>"
        >
          <img
            src="<?= e(asset_url('images/service-panchakarma.webp', false)) ?>"
            alt="<?= e(t('Premium Ayurveda Panchakarma treatment room with herbal oils and medicinal leaves')) ?>"
            width="1200"
            height="800"
            loading="lazy"
            decoding="async"
          >

          <span class="service-highlight-number">02</span>
        </a>

        <div class="service-highlight-content">

          <span class="service-highlight-label">
            <i class="fa-solid fa-spa"></i>
            <?= e(t('Wellness Care')) ?>
          </span>

          <h3><?= e(t('Panchakarma & Rejuvenation')) ?></h3>

          <p>
            <?= e(t('Access consultation-led Panchakarma, detox, therapeutic and rejuvenation programmes in a private wellness environment.')) ?>
          </p>

          <a class="text-link" href="#wellness">
            <?= e(t('Explore wellness services')) ?>
            <i class="fa-solid fa-arrow-right"></i>
          </a>

        </div>
      </article>

      <!-- Digital Care -->
      <article class="service-highlight-card reveal">

        <a
          class="service-highlight-image"
          href="#support"
          aria-label="<?= e(t('View digital appointment and supportive services')) ?>"
        >
          <img
            src="<?= e(asset_url('images/service-digital-care.webp', false)) ?>"
            alt="<?= e(t('Digital appointment booking and patient support at an Ayurveda wellness centre')) ?>"
            width="1200"
            height="800"
            loading="lazy"
            decoding="async"
          >

          <span class="service-highlight-number">03</span>
        </a>

        <div class="service-highlight-content">

          <span class="service-highlight-label">
            <i class="fa-solid fa-laptop-medical"></i>
            <?= e(t('Digital Support')) ?>
          </span>

          <h3><?= e(t('Appointments & Digital Care')) ?></h3>

          <p>
            <?= e(t('Request appointments, access teleconsultation support and benefit from organized digital prescription and patient record workflows.')) ?>
          </p>

          <a class="text-link" href="#support">
            <?= e(t('Explore supportive services')) ?>
            <i class="fa-solid fa-arrow-right"></i>
          </a>

        </div>
      </article>

    </div>
  </div>
</section>

  <section class="section intro-section">
    <div class="container">
      <div class="section-head split reveal">
        <div><span class="eyebrow"><i class="fa-solid fa-layer-group"></i> <?= e(t('Complete service portfolio')) ?></span><h2><?= e(t('Find the care pathway that matches your need.')) ?></h2></div>
        <div><p><?= e(t('Use the filters to review the proposed categories. Appointment confirmation and practitioner guidance are required before beginning a treatment programme.')) ?></p></div>
      </div>

      <div class="filter-bar" role="group" aria-label="<?= e(t('Filter services')) ?>">
        <button class="filter-button is-active" type="button" data-service-filter="all"><?= e(t('All services')) ?></button>
        <button class="filter-button" type="button" data-service-filter="clinical"><?= e(t('Clinical')) ?></button>
        <button class="filter-button" type="button" data-service-filter="specialist"><?= e(t('Specialist clinics')) ?></button>
        <button class="filter-button" type="button" data-service-filter="wellness"><?= e(t('Wellness')) ?></button>
        <button class="filter-button" type="button" data-service-filter="support"><?= e(t('Supportive')) ?></button>
      </div>

      <div class="service-detail-grid">
        <article class="service-detail-card reveal" data-service-card="clinical" id="clinical"><span class="service-detail-icon"><i class="fa-solid fa-stethoscope"></i></span><h3><?= e(t('General Ayurveda OPD Consultation')) ?></h3><p><?= e(t('Initial consultation and professional assessment for general Ayurveda healthcare needs.')) ?></p><span class="service-category"><?= e(t('Core clinical service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="specialist" id="specialist"><span class="service-detail-icon"><i class="fa-solid fa-bone"></i></span><h3><?= e(t('Joint & Musculoskeletal Disorders')) ?></h3><p><?= e(t('Specialist consultation pathway for joint, muscle, mobility and musculoskeletal concerns.')) ?></p><span class="service-category"><?= e(t('Specialist clinic')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="specialist"><span class="service-detail-icon"><i class="fa-solid fa-mortar-pestle"></i></span><h3><?= e(t('Panchakarma Consultation')) ?></h3><p><?= e(t('Professional evaluation before planning Panchakarma-based cleansing or restorative care.')) ?></p><span class="service-category"><?= e(t('Specialist clinic')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="specialist"><span class="service-detail-icon"><i class="fa-solid fa-hand-sparkles"></i></span><h3><?= e(t('Dermatology / Skin Care')) ?></h3><p><?= e(t('Ayurveda consultation pathway focused on skin wellness and dermatological concerns.')) ?></p><span class="service-category"><?= e(t('Specialist clinic')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="specialist"><span class="service-detail-icon"><i class="fa-solid fa-venus"></i></span><h3><?= e(t('Women\'s Wellness')) ?></h3><p><?= e(t('Patient-centred Ayurveda consultation support for women’s health and wellness needs.')) ?></p><span class="service-category"><?= e(t('Specialist clinic')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="specialist"><span class="service-detail-icon"><i class="fa-solid fa-child-reaching"></i></span><h3><?= e(t('Paediatric Ayurveda')) ?></h3><p><?= e(t('Age-appropriate Ayurveda consultation pathway for children, coordinated with guardians.')) ?></p><span class="service-category"><?= e(t('Specialist clinic')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="specialist"><span class="service-detail-icon"><i class="fa-solid fa-scale-balanced"></i></span><h3><?= e(t('Weight Management & Lifestyle Disorders')) ?></h3><p><?= e(t('Consultation support for healthy lifestyle practices, weight goals and related wellness needs.')) ?></p><span class="service-category"><?= e(t('Specialist clinic')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="wellness" id="wellness"><span class="service-detail-icon"><i class="fa-solid fa-hands-bubbles"></i></span><h3><?= e(t('Ayurveda Massage Therapy')) ?></h3><p><?= e(t('Traditional wellness massage therapy delivered within an appropriate consultation-led plan.')) ?></p><span class="service-category"><?= e(t('Wellness service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="wellness"><span class="service-detail-icon"><i class="fa-solid fa-spa"></i></span><h3><?= e(t('Panchakarma Packages')) ?></h3><p><?= e(t('Structured wellness packages planned according to assessment, suitability and programme goals.')) ?></p><span class="service-category"><?= e(t('Wellness service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="wellness"><span class="service-detail-icon"><i class="fa-solid fa-seedling"></i></span><h3><?= e(t('Detox & Rejuvenation Programmes')) ?></h3><p><?= e(t('Programmes focused on renewal, restorative wellness and healthy lifestyle support.')) ?></p><span class="service-category"><?= e(t('Wellness service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="wellness"><span class="service-detail-icon"><i class="fa-solid fa-brain"></i></span><h3><?= e(t('Stress Management Programmes')) ?></h3><p><?= e(t('Wellness support designed to encourage relaxation, balance and healthier daily routines.')) ?></p><span class="service-category"><?= e(t('Wellness service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="wellness"><span class="service-detail-icon"><i class="fa-solid fa-building-user"></i></span><h3><?= e(t('Corporate Wellness Packages')) ?></h3><p><?= e(t('Structured wellness initiatives for organizations, teams and workplace health programmes.')) ?></p><span class="service-category"><?= e(t('Wellness service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="support" id="support"><span class="service-detail-icon"><i class="fa-solid fa-calendar-check"></i></span><h3><?= e(t('Digital Appointment Booking')) ?></h3><p><?= e(t('Convenient appointment requests with staff confirmation and service coordination.')) ?></p><span class="service-category"><?= e(t('Supportive service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="support"><span class="service-detail-icon"><i class="fa-solid fa-video"></i></span><h3><?= e(t('Teleconsultation Services')) ?></h3><p><?= e(t('Remote consultation support when suitable and available through the Centre.')) ?></p><span class="service-category"><?= e(t('Supportive service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="support"><span class="service-detail-icon"><i class="fa-solid fa-file-prescription"></i></span><h3><?= e(t('Electronic Prescriptions & Records')) ?></h3><p><?= e(t('Digital prescription and medical record workflows supporting continuity and organization.')) ?></p><span class="service-category"><?= e(t('Supportive service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="support"><span class="service-detail-icon"><i class="fa-solid fa-store"></i></span><h3><?= e(t('Pharmacy & Herbal Product Sales')) ?></h3><p><?= e(t('Access to Ayurveda pharmacy services and a curated herbal product display area.')) ?></p><span class="service-category"><?= e(t('Supportive service')) ?></span></article>
        <article class="service-detail-card reveal" data-service-card="support"><span class="service-detail-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></span><h3><?= e(t('Diagnostic Referral Coordination')) ?></h3><p><?= e(t('Coordination support for external diagnostic referrals when recommended by a practitioner.')) ?></p><span class="service-category"><?= e(t('Supportive service')) ?></span></article>
      </div>

      <div class="notice-panel reveal"><div><h3><?= e(t('Unsure which service to select?')) ?></h3><p><?= e(t('Begin with a general consultation request and allow the Centre to guide the next appropriate step.')) ?></p></div><a class="button button-primary" href="<?= e(site_url('appointments.php?service=General%20Ayurveda%20OPD%20Consultation')) ?>"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Start with Consultation')) ?></a></div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container cta-panel reveal"><div><span class="eyebrow light"><i class="fa-solid fa-headset"></i> <?= e(t('Appointment support')) ?></span><h2><?= e(t('Choose a service and request a suitable date.')) ?></h2><p><?= e(t('Your request can be sent directly to WhatsApp for staff review and confirmation.')) ?></p></div><div class="cta-actions"><a class="button button-gold" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Request Appointment')) ?></a></div></div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
