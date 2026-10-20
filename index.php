<?php
require __DIR__ . '/includes/site-config.php';
$pageTitle = 'DEVYA CEYLON | Ayurveda Channelling Centre';
$pageDescription = 'Appointment-based Ayurveda consultations, specialist clinics, wellness programmes, teleconsultation and digital patient support in Kandy, Sri Lanka.';
$currentPage = 'home';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
  <section class="hero">
    <div class="container hero-grid">

      <div class="hero-copy reveal">

        <span class="hero-kicker">
          <i class="fa-solid fa-location-dot"></i>
          <span data-hero-eyebrow><?= e(t('Kandy, Sri Lanka')) ?></span>
        </span>

        <h1 class="display-title" data-hero-title>
          <?= e(t('Authentic Ayurveda,')) ?>
          <em><?= e(t('thoughtfully connected.')) ?></em>
        </h1>

        <p class="lead" data-hero-text>
          <?= e(t('DEVYA CEYLON brings qualified Ayurveda consultations, specialist channelling, wellness therapies and modern appointment support into one trusted patient experience.')) ?>
        </p>

        <div class="hero-actions">
          <a class="button button-primary" href="<?= e(site_url('appointments.php')) ?>">
            <i class="fa-solid fa-calendar-check"></i>
            <?= e(t('Request Appointment')) ?>
          </a>

          <a class="button button-secondary" href="<?= e(site_url('services.php')) ?>">
            <i class="fa-solid fa-spa"></i>
            <?= e(t('Explore Services')) ?>
          </a>
          <a class="text-link hero-schedule-link" href="#doctor-schedule">
            <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
            <?= e(t('View Doctors & Schedule')) ?>
          </a>
        </div>

        <div class="hero-proof">

          <div class="proof-card">
            <i class="fa-solid fa-user-doctor"></i>
            <strong>6</strong>
            <span><?= e(t('Specialist consultation areas')) ?></span>
          </div>

          <div class="proof-card">
            <i class="fa-solid fa-hands-bubbles"></i>
            <strong>5</strong>
            <span><?= e(t('Wellness programme categories')) ?></span>
          </div>

          <div class="proof-card">
            <i class="fa-solid fa-laptop-medical"></i>
            <strong>5</strong>
            <span><?= e(t('Digital and supportive services')) ?></span>
          </div>

        </div>
      </div>

      <div class="hero-visual reveal" aria-label="<?= e(t('Ayurveda wellness image carousel')) ?>">

        <div class="hero-image-frame">

          <img
            class="hero-slide is-active"
            src="<?= e(asset_url('images/home-hero-consultation.webp', false)) ?>"
            alt="<?= e(t('Professional Ayurveda consultation at Devya Ceylon Ayurveda Centre in Kandy')) ?>"
            data-hero-slide="0"
            width="1400"
            height="1750"
            fetchpriority="high"
            decoding="async">

          <img
            class="hero-slide"
            src="<?= e(asset_url('images/home-hero-herbs.webp', false)) ?>"
            alt="<?= e(t('Traditional Sri Lankan Ayurveda herbs, oils and natural medicine preparation')) ?>"
            data-hero-slide="1"
            width="1400"
            height="1750"
            loading="lazy"
            decoding="async">

          <img
            class="hero-slide"
            src="<?= e(asset_url('images/home-hero-wellness.webp', false)) ?>"
            alt="<?= e(t('Calm Ayurveda wellness and treatment environment in Kandy Sri Lanka')) ?>"
            data-hero-slide="2"
            width="1400"
            height="1750"
            loading="lazy"
            decoding="async">

        </div>

        <div class="hero-badge">
          <span class="hero-badge-icon">
            <i class="fa-solid fa-stethoscope"></i>
          </span>

          <h2><?= e(t('Consultation comes first.')) ?></h2>

          <p>
            <?= e(t('Treatment pathways are guided by qualified Ayurveda practitioners and confirmed through an appointment-based process.')) ?>
          </p>
        </div>

        <div class="hero-dots" aria-label="<?= e(t('Hero carousel controls')) ?>">

          <button
            class="hero-dot is-active"
            type="button"
            aria-label="<?= e(t('Show consultation image')) ?>"
            aria-current="true"
            data-hero-dot="0">
          </button>

          <button
            class="hero-dot"
            type="button"
            aria-label="<?= e(t('Show Ayurveda herbs image')) ?>"
            aria-current="false"
            data-hero-dot="1">
          </button>

          <button
            class="hero-dot"
            type="button"
            aria-label="<?= e(t('Show wellness centre image')) ?>"
            aria-current="false"
            data-hero-dot="2">
          </button>

        </div>

        <div class="hero-leaf" aria-hidden="true"></div>

      </div>
    </div>
  </section>

  <script type="application/json" id="heroSlidesData">
    <?= json_encode([
        [
            'eyebrow' => t('Kandy, Sri Lanka'),
            'title' => t('Authentic Ayurveda, thoughtfully connected.'),
            'emphasis' => t('thoughtfully connected.'),
            'text' => t('DEVYA CEYLON brings qualified Ayurveda consultations, specialist channelling, wellness therapies and modern appointment support into one trusted patient experience.'),
        ],
        [
            'eyebrow' => t('Traditional Healing, Modern Access'),
            'title' => t('A trusted gateway to specialist Ayurveda care.'),
            'emphasis' => t('specialist Ayurveda care.'),
            'text' => t("Connect with appropriate consultation pathways for musculoskeletal care, Panchakarma, skin care, women's wellness, paediatric Ayurveda and lifestyle support."),
        ],
        [
            'eyebrow' => t('Preventive Health & Wellness'),
            'title' => t('Care designed around your wellness journey.'),
            'emphasis' => t('your wellness journey.'),
            'text' => t('From wellness consultations and stress management to detox, rejuvenation and digital follow-up, every step is designed to feel clear and patient-friendly.'),
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?>
  </script>

  <section class="trust-strip" aria-label="<?= e(t('Key service strengths')) ?>">
    <div class="container trust-grid">

      <div class="trust-item">
        <i class="fa-solid fa-user-doctor"></i>
        <?= e(t('Qualified practitioners')) ?>
      </div>

      <div class="trust-item">
        <i class="fa-solid fa-calendar-check"></i>
        <?= e(t('Appointment-based care')) ?>
      </div>

      <div class="trust-item">
        <i class="fa-solid fa-leaf"></i>
        <?= e(t('Authentic Sri Lankan Ayurveda')) ?>
      </div>

      <div class="trust-item">
        <i class="fa-solid fa-shield-heart"></i>
        <?= e(t('Patient-centred experience')) ?>
      </div>

      <div class="trust-item">
        <i class="fa-solid fa-location-dot"></i>
        <?= e(t('Kandy, Sri Lanka')) ?>
      </div>

    </div>
  </section>

  <?php require __DIR__ . '/includes/doctor-schedule.php'; ?>

  <section class="section intro-section">
    <div class="container intro-grid">

      <div class="image-stack reveal">

        <div class="image-stack-main">
          <img
            src="<?= e(asset_url('images/home-about-main.webp', false)) ?>"
            alt="<?= e(t('Traditional Sri Lankan Ayurveda medicine preparation with herbs, roots and herbal oils')) ?>"
            width="1400"
            height="1100"
            loading="lazy"
            decoding="async">
        </div>

        <div class="image-stack-small">
          <img
            src="<?= e(asset_url('images/home-about-detail.webp', false)) ?>"
            alt="<?= e(t('Ayurveda medicinal leaves, herbal oils and mortar and pestle preparation')) ?>"
            width="800"
            height="900"
            loading="lazy"
            decoding="async">
        </div>

        <div class="experience-badge">
          <div>
            <strong>01</strong>
            <span><?= e(t('Trusted gateway')) ?></span>
          </div>
        </div>

      </div>

      <div class="reveal">

        <span class="eyebrow">
          <i class="fa-solid fa-seedling"></i>
          <?= e(t('Welcome to DEVYA CEYLON')) ?>
        </span>

        <div class="section-head">
          <h2>
            <?= e(t('Traditional healing supported by a modern care journey.')) ?>
          </h2>

          <p>
            <?= e(t('DEVYA CEYLON is being developed as a professional Ayurveda Channelling Centre that combines traditional Sri Lankan consultation and wellness therapies with convenient appointment-based healthcare delivery.')) ?>
          </p>
        </div>

        <div class="feature-list">

          <article class="feature-row">
            <i class="fa-solid fa-user-doctor"></i>

            <div>
              <h3><?= e(t('Professional consultation access')) ?></h3>

              <p>
                <?= e(t('A structured gateway connecting patients with qualified Ayurveda medical practitioners and relevant specialist clinics.')) ?>
              </p>
            </div>
          </article>

          <article class="feature-row">
            <i class="fa-solid fa-spa"></i>

            <div>
              <h3><?= e(t('Integrated wellness programmes')) ?></h3>

              <p>
                <?= e(t('Therapeutic, preventive and rejuvenation services designed around individual wellness needs.')) ?>
              </p>
            </div>
          </article>

          <article class="feature-row">
            <i class="fa-solid fa-mobile-screen-button"></i>

            <div>
              <h3><?= e(t('Convenient digital support')) ?></h3>

              <p>
                <?= e(t('Appointment booking, teleconsultation, electronic prescriptions and digital medical records.')) ?>
              </p>
            </div>
          </article>

        </div>

        <a class="text-link" href="<?= e(site_url('about-us.php')) ?>">
          <?= e(t('Learn more about us')) ?>
          <i class="fa-solid fa-arrow-right"></i>
        </a>

      </div>

    </div>
  </section>

  <section class="section services-preview">
    <div class="container">

      <div class="section-head split reveal">
        <div>
          <span class="eyebrow">
            <i class="fa-solid fa-hands-holding-heart"></i>
            <?= e(t('Integrated care')) ?>
          </span>

          <h2>
            <?= e(t('Services built around consultation, therapy and prevention.')) ?>
          </h2>
        </div>

        <div>
          <p>
            <?= e(t('Explore the major service pathways proposed for the Centre. Final treatment decisions should follow an Ayurveda consultation and professional assessment.')) ?>
          </p>

          <a class="text-link" href="<?= e(site_url('services.php')) ?>">
            <?= e(t('View every service')) ?>
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="card-grid">

        <!-- Clinical Consultation -->
        <article class="service-card reveal">

          <div class="service-card-media">
            <img
              src="<?= e(asset_url('images/service-consultation.webp', false)) ?>"
              alt="<?= e(t('Professional Ayurveda consultation at DEVYA CEYLON in Kandy Sri Lanka')) ?>"
              width="1200"
              height="800"
              loading="lazy"
              decoding="async">
          </div>

          <div class="service-card-content">

            <span class="service-card-icon">
              <i class="fa-solid fa-stethoscope"></i>
            </span>

            <h3><?= e(t('Clinical Consultations')) ?></h3>

            <p>
              <?= e(t('General Ayurveda OPD consultations and structured access to specialist channelling clinics.')) ?>
            </p>

            <a class="text-link" href="<?= e(site_url('services.php#clinical')) ?>">
              <?= e(t('Explore clinical care')) ?>
              <i class="fa-solid fa-arrow-right"></i>
            </a>

          </div>
        </article>

        <!-- Wellness Therapies -->
        <article class="service-card reveal">

          <div class="service-card-media">
            <img
              src="<?= e(asset_url('images/service-panchakarma.webp', false)) ?>"
              alt="<?= e(t('Premium Ayurveda Panchakarma and wellness treatment environment')) ?>"
              width="1200"
              height="800"
              loading="lazy"
              decoding="async">
          </div>

          <div class="service-card-content">

            <span class="service-card-icon">
              <i class="fa-solid fa-spa"></i>
            </span>

            <h3><?= e(t('Wellness Therapies')) ?></h3>

            <p>
              <?= e(t('Massage therapy, Panchakarma packages, detox, rejuvenation and stress management programmes.')) ?>
            </p>

            <a class="text-link" href="<?= e(site_url('services.php#wellness')) ?>">
              <?= e(t('Explore wellness')) ?>
              <i class="fa-solid fa-arrow-right"></i>
            </a>

          </div>
        </article>

        <!-- Digital Support -->
        <article class="service-card reveal">

          <div class="service-card-media">
            <img
              src="<?= e(asset_url('images/service-digital-care.webp', false)) ?>"
              alt="<?= e(t('Digital appointment booking and patient support at an Ayurveda centre')) ?>"
              width="1200"
              height="800"
              loading="lazy"
              decoding="async">
          </div>

          <div class="service-card-content">

            <span class="service-card-icon">
              <i class="fa-solid fa-laptop-medical"></i>
            </span>

            <h3><?= e(t('Digital Support')) ?></h3>

            <p>
              <?= e(t('Digital booking, teleconsultation, electronic prescriptions, records and referral coordination.')) ?>
            </p>

            <a class="text-link" href="<?= e(site_url('services.php#support')) ?>">
              <?= e(t('Explore digital care')) ?>
              <i class="fa-solid fa-arrow-right"></i>
            </a>

          </div>
        </article>

      </div>
    </div>
  </section>

  <section class="section process-section">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow light"><i class="fa-solid fa-route"></i> <?= e(t('Patient journey')) ?></span>
        <h2><?= e(t('A simpler path from enquiry to care.')) ?></h2>
        <p><?= e(t('A clear appointment process helps patients understand the next step and gives the Centre time to coordinate the appropriate practitioner or service.')) ?></p>
      </div>
      <div class="process-grid">
        <article class="process-card reveal"><span class="process-number">01</span>
          <h3><?= e(t('Choose a service')) ?></h3>
          <p><?= e(t('Review consultations, specialist clinics, wellness programmes and supportive services.')) ?></p>
        </article>
        <article class="process-card reveal"><span class="process-number">02</span>
          <h3><?= e(t('Send a request')) ?></h3>
          <p><?= e(t('Share your contact details, preferred service and suitable appointment date.')) ?></p>
        </article>
        <article class="process-card reveal"><span class="process-number">03</span>
          <h3><?= e(t('Receive confirmation')) ?></h3>
          <p><?= e(t('Staff can verify practitioner availability and confirm the appointment or next action.')) ?></p>
        </article>
        <article class="process-card reveal"><span class="process-number">04</span>
          <h3><?= e(t('Begin your care')) ?></h3>
          <p><?= e(t('Attend the consultation and receive a professionally guided treatment or wellness pathway.')) ?></p>
        </article>
      </div>
    </div>
  </section>

  <section class="section digital-section">
    <div class="container digital-panel reveal">

      <div class="digital-copy">

        <span class="eyebrow">
          <i class="fa-solid fa-wifi"></i>
          <?= e(t('Modern service delivery')) ?>
        </span>

        <h2><?= e(t('Digital access that supports authentic care.')) ?></h2>

        <p>
          <?= e(t('The proposed Centre combines traditional healthcare with practical digital tools that improve convenience, record continuity and communication.')) ?>
        </p>

        <div class="digital-list">

          <span>
            <i class="fa-solid fa-calendar-check"></i>
            <?= e(t('Digital appointments')) ?>
          </span>

          <span>
            <i class="fa-solid fa-video"></i>
            <?= e(t('Teleconsultations')) ?>
          </span>

          <span>
            <i class="fa-solid fa-file-prescription"></i>
            <?= e(t('Electronic prescriptions')) ?>
          </span>

          <span>
            <i class="fa-solid fa-notes-medical"></i>
            <?= e(t('Digital medical records')) ?>
          </span>

          <span>
            <i class="fa-solid fa-arrow-right-arrow-left"></i>
            <?= e(t('Referral coordination')) ?>
          </span>

          <span>
            <i class="fa-solid fa-store"></i>
            <?= e(t('Pharmacy support')) ?>
          </span>

        </div>

        <div class="hero-actions">
          <a class="button button-primary" href="<?= e(site_url('appointments.php')) ?>">
            <i class="fa-solid fa-mobile-screen"></i>
            <?= e(t('Start a Request')) ?>
          </a>
        </div>

      </div>

      <div class="digital-visual" aria-hidden="true">

        <div class="phone-mockup">

          <div class="phone-notch"></div>

          <div class="phone-screen">

            <div class="phone-screen-header">

              <img
                src="<?= e(asset_url('images/logo.png', false)) ?>"
                alt=""
                width="44"
                height="44"
                loading="lazy"
                decoding="async">

              <div>
                <strong><?= e(t('DEVYA CEYLON')) ?></strong>
                <small><?= e(t('Appointment request')) ?></small>
              </div>

            </div>

            <div class="phone-appointment">
              <span><?= e(t('Next step')) ?></span>

              <h3><?= e(t('Select your care pathway')) ?></h3>

              <p>
                <?= e(t('Consultation · Wellness · Teleconsultation')) ?>
              </p>
            </div>

            <div class="phone-rows">

              <div class="phone-row">
                <i class="fa-solid fa-user-doctor"></i>
                <?= e(t('Specialist consultation')) ?>
              </div>

              <div class="phone-row">
                <i class="fa-solid fa-calendar-day"></i>
                <?= e(t('Preferred appointment date')) ?>
              </div>

              <div class="phone-row">
                <i class="fa-brands fa-whatsapp"></i>
                <?= e(t('Staff confirmation')) ?>
              </div>

            </div>

          </div>
        </div>

      </div>

    </div>
  </section>

  <section class="section audience-section">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-people-group"></i> <?= e(t('Who we serve')) ?></span>
        <h2><?= e(t('Accessible Ayurveda for diverse wellness needs.')) ?></h2>
        <p><?= e(t('The Centre is designed to support local patients, working professionals, long-term care needs and wellness travellers.')) ?></p>
      </div>
      <div class="audience-grid">
        <article class="audience-card reveal"><i class="fa-solid fa-house-user"></i>
          <h3><?= e(t('Local Community')) ?></h3>
          <p><?= e(t('People seeking accessible Ayurveda healthcare close to home.')) ?></p>
        </article>
        <article class="audience-card reveal"><i class="fa-solid fa-briefcase"></i>
          <h3><?= e(t('Busy Professionals')) ?></h3>
          <p><?= e(t('Appointment-based consultations that fit structured schedules.')) ?></p>
        </article>
        <article class="audience-card reveal"><i class="fa-solid fa-person-cane"></i>
          <h3><?= e(t('Elderly & Chronic Care')) ?></h3>
          <p><?= e(t('Patients requiring coordinated, ongoing consultation support.')) ?></p>
        </article>
        <article class="audience-card reveal"><i class="fa-solid fa-heart-pulse"></i>
          <h3><?= e(t('Wellness Clients')) ?></h3>
          <p><?= e(t('Health-conscious clients focused on prevention and balance.')) ?></p>
        </article>
        <article class="audience-card reveal"><i class="fa-solid fa-plane-arrival"></i>
          <h3><?= e(t('Wellness Tourists')) ?></h3>
          <p><?= e(t('Domestic and international visitors seeking Sri Lankan Ayurveda.')) ?></p>
        </article>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container cta-panel reveal">
      <div><span class="eyebrow light"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Begin with a consultation')) ?></span>
        <h2><?= e(t('Ready to take the next step?')) ?></h2>
        <p><?= e(t('Send an appointment request and allow the Centre to guide you towards the most suitable consultation or wellness pathway.')) ?></p>
      </div>
      <div class="cta-actions"><a class="button button-gold" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Request Appointment')) ?></a><a class="button button-secondary" href="<?= e(site_url('contact.php')) ?>"><i class="fa-solid fa-phone"></i> <?= e(t('Contact Centre')) ?></a></div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
