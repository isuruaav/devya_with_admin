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
        <span class="eyebrow"><i class="fa-solid fa-phone-volume"></i> Contact the Centre</span>
        <h1 class="page-title">Helpful support for your <span style="color:var(--gold-700)">next step.</span></h1>
        <p>Contact DEVYA CEYLON in Kandy, Sri Lanka for appointment requests, service guidance and general information about consultation and wellness pathways.</p>
        <div class="breadcrumbs"><a href="<?= e(site_url('index.php')) ?>">Home</a><i class="fa-solid fa-chevron-right"></i><span>Contact</span></div>
      </div>
      <aside class="page-hero-card reveal"><span class="icon-box"><i class="fa-solid fa-headset"></i></span><h2>Need help choosing a service?</h2><p>Request general guidance or start with a General Ayurveda OPD Consultation.</p><a class="button button-primary" style="margin-top:18px" href="<?= e(site_url('appointments.php?service=Other%20%2F%20Need%20guidance')) ?>"><i class="fa-solid fa-calendar-check"></i> Ask for Guidance</a></aside>
    </div>
  </section>

<section class="contact-location-section">
  <div class="container">

    <div class="contact-location-banner reveal">

      <img
        src="<?= e(asset_url('images/contact-kandy.webp', false)) ?>"
        alt="Misty green hills surrounding DEVYA CEYLON Ayurveda Centre in Kandy Sri Lanka"
        width="1600"
        height="900"
        loading="eager"
        fetchpriority="high"
        decoding="async"
      >

      <div class="contact-location-overlay">

        <span class="contact-location-label">
          <i class="fa-solid fa-location-dot"></i>
          Kandy, Sri Lanka
        </span>

        <h2>
          Ayurveda care surrounded by the calm of Sri Lanka’s hill country.
        </h2>

        <p>
          DEVYA CEYLON Ayurveda Channelling Centre provides a peaceful,
          accessible setting for consultations, wellness services and
          appointment-based care in Kandy.
        </p>

        <div class="contact-location-actions">

          <a
            class="button button-gold"
            href="<?= e($site['maps_url']) ?>"
            target="_blank"
            rel="noopener"
          >
            <i class="fa-solid fa-map-location-dot"></i>
            View on Google Maps
          </a>

          <a
            class="button button-light-outline"
            href="<?= e(site_url('appointments.php')) ?>"
          >
            <i class="fa-solid fa-calendar-check"></i>
            Request Appointment
          </a>

        </div>

      </div>

      <div class="contact-location-facts">

        <div>
          <i class="fa-solid fa-mountain-sun"></i>
          <span>
            <strong>Hill Country</strong>
            Calm wellness setting
          </span>
        </div>

        <div>
          <i class="fa-solid fa-leaf"></i>
          <span>
            <strong>Authentic Ayurveda</strong>
            Sri Lankan healing traditions
          </span>
        </div>

        <div>
          <i class="fa-solid fa-calendar-check"></i>
          <span>
            <strong>Appointment-Based</strong>
            Organized patient support
          </span>
        </div>

      </div>

    </div>

  </div>
</section>


  <section class="section intro-section">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-address-card"></i> Contact options</span><h2>Reach the Centre in the way that suits you.</h2><p>Use phone or WhatsApp for appointment support. The Centre operates through confirmed appointments.</p></div>
      <div class="contact-grid">
        <article class="contact-card reveal"><i class="fa-solid fa-phone-volume"></i><h3>Call the Centre</h3><p>Speak with the team about services, availability and appointment coordination.</p><a href="tel:<?= e($site['phone_href']) ?>"><?= e($site['phone_display']) ?></a></article>
        <article class="contact-card reveal"><i class="fa-brands fa-whatsapp"></i><h3>WhatsApp Support</h3><p>Send a structured appointment request or a short service enquiry.</p><a href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener">Start WhatsApp chat</a></article>
        <article class="contact-card reveal"><i class="fa-solid fa-calendar-check"></i><h3>Online Request</h3><p>Select your preferred service, date and response method through the booking page.</p><a href="<?= e(site_url('appointments.php')) ?>">Open appointment form</a></article>
        <article class="contact-card reveal"><i class="fa-solid fa-location-dot"></i><h3>Centre Location</h3><p>DEVYA CEYLON Ayurveda Channelling Centre is located in Kandy, Sri Lanka.</p><a href="<?= e($site['maps_url']) ?>" target="_blank" rel="noopener">View Kandy on Google Maps</a></article>
      </div>
    </div>
  </section>

  <section class="section services-preview">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-circle-question"></i> Frequently asked questions</span><h2>Useful answers before you request an appointment.</h2></div>
      <div class="faq-list">
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span>Does submitting the form confirm my appointment?</span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p>No. The form creates an appointment request. A staff member must confirm practitioner availability, date and time.</p></div></article>
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span>What should I select if I do not know the correct service?</span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p>Select “General Ayurveda OPD Consultation” or “Other / Need guidance.” The Centre can recommend the next appropriate step.</p></div></article>
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span>Are wellness programmes available without consultation?</span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p>Programme suitability should be confirmed through an Ayurveda consultation, especially for Panchakarma, detox and therapeutic services.</p></div></article>
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span>Can I request a teleconsultation?</span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p>Yes. Teleconsultation is listed as a proposed supportive service. Availability and suitability must be confirmed by the Centre.</p></div></article>
        <article class="faq-item reveal"><button class="faq-question" type="button" aria-expanded="false"><span>Does the Centre support wellness tourists?</span><i class="fa-solid fa-plus"></i></button><div class="faq-answer"><p>The project aims to support domestic and international wellness tourists through authentic Sri Lankan Ayurveda and organized appointment-based services.</p></div></article>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container cta-panel reveal"><div><span class="eyebrow light"><i class="fa-brands fa-whatsapp"></i> Direct support</span><h2>Send a clear appointment request today.</h2><p>Use the booking form to prepare your service, contact and preferred date details in one message.</p></div><div class="cta-actions"><a class="button button-gold" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> Open Booking Form</a><a class="button button-secondary" href="tel:<?= e($site['phone_href']) ?>"><i class="fa-solid fa-phone"></i> <?= e($site['phone_display']) ?></a></div></div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
