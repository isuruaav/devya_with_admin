<?php
require __DIR__ . '/includes/site-config.php';
$pageTitle = 'Contact DEVYA CEYLON | Ayurveda Centre';
$pageDescription = 'Contact DEVYA CEYLON Ayurveda Channelling Centre in Kandy, Sri Lanka by phone or WhatsApp and find answers to appointment questions.';
$currentPage = 'contact';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
  <section class="page-hero">
    <div class="container page-hero-grid">
      <div class="reveal">
        <span class="eyebrow"><i class="fa-solid fa-phone-volume"></i> <?= e(t('Contact the Centre')) ?></span>
        <h1 class="page-title"><?= e(t('Helpful support for your')) ?> <span style="color:var(--gold-700)"><?= e(t('next step.')) ?></span></h1>
        <p><?= e(t('Contact DEVYA CEYLON in Kandy, Sri Lanka for appointment requests, service guidance and general information about consultation and wellness pathways.')) ?></p>
        <div class="breadcrumbs"><a href="<?= e(site_url('index.php')) ?>"><?= e(t('Home')) ?></a><i class="fa-solid fa-chevron-right"></i><span><?= e(t('Contact')) ?></span></div>
      </div>
      <aside class="page-hero-card reveal"><span class="icon-box"><i class="fa-solid fa-headset"></i></span><h2><?= e(t('Need help choosing a service?')) ?></h2><p><?= e(t('Request general guidance or start with a General Ayurveda OPD Consultation.')) ?></p><a class="button button-primary" style="margin-top:18px" href="<?= e(site_url('appointments.php?service=Other%20%2F%20Need%20guidance')) ?>"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Ask for Guidance')) ?></a></aside>
    </div>
  </section>

<section class="contact-location-section">
  <div class="container">

    <div class="contact-location-banner reveal">

      <img
        src="<?= e(asset_url('images/contact-kandy.webp', false)) ?>"
        alt="<?= e(t('Misty green hills surrounding DEVYA CEYLON Ayurveda Centre in Kandy Sri Lanka')) ?>"
        width="1600"
        height="900"
        loading="eager"
        fetchpriority="high"
        decoding="async"
      >

      <div class="contact-location-overlay">

        <span class="contact-location-label">
          <i class="fa-solid fa-location-dot"></i>
          <?= e(t('Kandy, Sri Lanka')) ?>
        </span>

        <h2>
          <?= e(t('Ayurveda care surrounded by the calm of Sri Lanka’s hill country.')) ?>
        </h2>

        <p>
          <?= e(t('DEVYA CEYLON Ayurveda Channelling Centre provides a peaceful, accessible setting for consultations, wellness services and appointment-based care in Kandy.')) ?>
        </p>

        <div class="contact-location-actions">

          <a
            class="button button-gold"
            href="<?= e($site['maps_url']) ?>"
            target="_blank"
            rel="noopener"
          >
            <i class="fa-solid fa-map-location-dot"></i>
            <?= e(t('View on Google Maps')) ?>
          </a>

          <a
            class="button button-light-outline"
            href="<?= e(site_url('appointments.php')) ?>"
          >
            <i class="fa-solid fa-calendar-check"></i>
            <?= e(t('Request Appointment')) ?>
          </a>

        </div>

      </div>

      <div class="contact-location-facts">

        <div>
          <i class="fa-solid fa-mountain-sun"></i>
          <span>
            <strong><?= e(t('Hill Country')) ?></strong>
            <?= e(t('Calm wellness setting')) ?>
          </span>
        </div>

        <div>
          <i class="fa-solid fa-leaf"></i>
          <span>
            <strong><?= e(t('Authentic Ayurveda')) ?></strong>
            <?= e(t('Sri Lankan healing traditions')) ?>
          </span>
        </div>

        <div>
          <i class="fa-solid fa-calendar-check"></i>
          <span>
            <strong><?= e(t('Appointment-Based')) ?></strong>
            <?= e(t('Organized patient support')) ?>
          </span>
        </div>

      </div>

    </div>

  </div>
</section>


  <section class="section intro-section">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-address-card"></i> <?= e(t('Contact options')) ?></span><h2><?= e(t('Reach the Centre in the way that suits you.')) ?></h2><p><?= e(t('Use phone, WhatsApp or email for appointment support. The Centre operates through confirmed appointments.')) ?></p></div>
      <div class="contact-grid">
        <article class="contact-card reveal"><i class="fa-solid fa-phone-volume"></i><h3><?= e(t('Call the Centre')) ?></h3><p><?= e(t('Contact the team about services, availability and appointment coordination.')) ?></p><a class="contact-phone" href="tel:<?= e($site['phone_href']) ?>"><span><?= e(t($site['phone_contact'])) ?></span><?= e($site['phone_display']) ?></a><a class="contact-phone" href="tel:<?= e($site['secondary_phone_href']) ?>"><span><?= e(t($site['secondary_phone_contact'])) ?></span><?= e($site['secondary_phone_display']) ?></a><a class="contact-email" href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></article>
        <article class="contact-card reveal"><i class="fa-brands fa-whatsapp"></i><h3><?= e(t('WhatsApp Support')) ?></h3><p><?= e(t('Send a structured appointment request or a short service enquiry.')) ?></p><a href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener"><?= e(t('Start WhatsApp chat')) ?></a></article>
        <article class="contact-card reveal"><i class="fa-solid fa-calendar-check"></i><h3><?= e(t('Online Request')) ?></h3><p><?= e(t('Select your preferred service, date and response method through the booking page.')) ?></p><a href="<?= e(site_url('appointments.php')) ?>"><?= e(t('Open appointment form')) ?></a></article>
        <article class="contact-card reveal"><i class="fa-solid fa-location-dot"></i><h3><?= e(t('Centre Location')) ?></h3><p><?= e(t($site['address'])) ?></p><a href="<?= e($site['maps_url']) ?>" target="_blank" rel="noopener"><?= e(t('Find the Centre on Google Maps')) ?></a></article>
      </div>
      <div class="consultation-card contact-consultation-card reveal">
        <h2><i class="fa-regular fa-clock" aria-hidden="true"></i> <?= e(t('Consultation Time')) ?></h2>
        <?php require __DIR__ . '/includes/consultation-hours.php'; ?>
        <p><?= e(t('Appointments are recommended for special clinics.')) ?></p>
        <a class="text-link" href="<?= e(site_url('index.php#doctor-schedule')) ?>"><?= e(t('View doctors & weekly schedule')) ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      </div>
    </div>
  </section>

  <section class="section services-preview">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-circle-question"></i> <?= e(t('Frequently asked questions')) ?></span><h2><?= e(t('Useful answers before you request an appointment.')) ?></h2></div>
      <div class="faq-list">
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span><?= e(t('Does submitting the form confirm my appointment?')) ?></span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p><?= e(t('No. The form creates an appointment request. A staff member must confirm practitioner availability, date and time.')) ?></p></div></article>
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span><?= e(t('What should I select if I do not know the correct service?')) ?></span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p><?= e(t('Select “General Ayurveda OPD Consultation” or “Other / Need guidance.” The Centre can recommend the next appropriate step.')) ?></p></div></article>
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span><?= e(t('Are wellness programmes available without consultation?')) ?></span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p><?= e(t('Programme suitability should be confirmed through an Ayurveda consultation, especially for Panchakarma, detox and therapeutic services.')) ?></p></div></article>
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span><?= e(t('Can I request a teleconsultation?')) ?></span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p><?= e(t('You may enquire about teleconsultation, but the Centre must confirm whether it is currently available and suitable for your needs.')) ?></p></div></article>
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span><?= e(t('Are all services listed on the website currently available?')) ?></span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p><?= e(t('No. The list describes appointment and care pathways. Staff will confirm which practitioner and service are currently available before confirming your appointment.')) ?></p></div></article>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container cta-panel reveal"><div><span class="eyebrow light"><i class="fa-brands fa-whatsapp"></i> <?= e(t('Direct support')) ?></span><h2><?= e(t('Send a clear appointment request today.')) ?></h2><p><?= e(t('Use the booking form to prepare your service, contact and preferred date details in one message.')) ?></p></div><div class="cta-actions"><a class="button button-gold" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Open Booking Form')) ?></a><a class="button button-secondary" href="tel:<?= e($site['phone_href']) ?>"><i class="fa-solid fa-phone"></i> <?= e($site['phone_display']) ?></a></div></div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
