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
          <span data-hero-eyebrow>Kandy, Sri Lanka</span>
        </span>

        <h1 class="display-title" data-hero-title>
          Authentic Ayurveda,
          <em>thoughtfully connected.</em>
        </h1>

        <p class="lead" data-hero-text>
          DEVYA CEYLON brings qualified Ayurveda consultations,
          specialist channelling, wellness therapies and modern
          appointment support into one trusted patient experience.
        </p>

        <div class="hero-actions">
          <a class="button button-primary" href="<?= e(site_url('appointments.php')) ?>">
            <i class="fa-solid fa-calendar-check"></i>
            Request Appointment
          </a>

          <a class="button button-secondary" href="<?= e(site_url('services.php')) ?>">
            <i class="fa-solid fa-spa"></i>
            Explore Services
          </a>
        </div>

        <div class="hero-proof">

          <div class="proof-card">
            <i class="fa-solid fa-user-doctor"></i>
            <strong>6</strong>
            <span>Specialist consultation areas</span>
          </div>

          <div class="proof-card">
            <i class="fa-solid fa-hands-bubbles"></i>
            <strong>5</strong>
            <span>Wellness programme categories</span>
          </div>

          <div class="proof-card">
            <i class="fa-solid fa-laptop-medical"></i>
            <strong>5</strong>
            <span>Digital and supportive services</span>
          </div>

        </div>
      </div>

      <div class="hero-visual reveal" aria-label="Ayurveda wellness image carousel">

        <div class="hero-image-frame">

          <img
            class="hero-slide is-active"
            src="<?= e(asset_url('images/home-hero-consultation.webp', false)) ?>"
            alt="Professional Ayurveda consultation at Devya Ceylon Ayurveda Centre in Kandy"
            data-hero-slide="0"
            width="1400"
            height="1750"
            fetchpriority="high"
            decoding="async">

          <img
            class="hero-slide"
            src="<?= e(asset_url('images/home-hero-herbs.webp', false)) ?>"
            alt="Traditional Sri Lankan Ayurveda herbs, oils and natural medicine preparation"
            data-hero-slide="1"
            width="1400"
            height="1750"
            loading="lazy"
            decoding="async">

          <img
            class="hero-slide"
            src="<?= e(asset_url('images/home-hero-wellness.webp', false)) ?>"
            alt="Calm Ayurveda wellness and treatment environment in Kandy Sri Lanka"
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

          <h2>Consultation comes first.</h2>

          <p>
            Treatment pathways are guided by qualified Ayurveda practitioners
            and confirmed through an appointment-based process.
          </p>
        </div>

        <div class="hero-dots" aria-label="Hero carousel controls">

          <button
            class="hero-dot is-active"
            type="button"
            aria-label="Show consultation image"
            aria-current="true"
            data-hero-dot="0">
          </button>

          <button
            class="hero-dot"
            type="button"
            aria-label="Show Ayurveda herbs image"
            aria-current="false"
            data-hero-dot="1">
          </button>

          <button
            class="hero-dot"
            type="button"
            aria-label="Show wellness centre image"
            aria-current="false"
            data-hero-dot="2">
          </button>

        </div>

        <div class="hero-leaf" aria-hidden="true"></div>

      </div>
    </div>
  </section>

  <script type="application/json" id="heroSlidesData">
    [{
        "eyebrow": "Kandy, Sri Lanka",
        "title": "Authentic Ayurveda, thoughtfully connected.",
        "text": "DEVYA CEYLON brings qualified Ayurveda consultations, specialist channelling, wellness therapies and modern appointment support into one trusted patient experience."
      },
      {
        "eyebrow": "Traditional Healing, Modern Access",
        "title": "A trusted gateway to specialist Ayurveda care.",
        "text": "Connect with appropriate consultation pathways for musculoskeletal care, Panchakarma, skin care, women's wellness, paediatric Ayurveda and lifestyle support."
      },
      {
        "eyebrow": "Preventive Health & Wellness",
        "title": "Care designed around your wellness journey.",
        "text": "From wellness consultations and stress management to detox, rejuvenation and digital follow-up, every step is designed to feel clear and patient-friendly."
      }
    ]
  </script>

  <section class="trust-strip" aria-label="Key service strengths">
    <div class="container trust-grid">

      <div class="trust-item">
        <i class="fa-solid fa-user-doctor"></i>
        Qualified practitioners
      </div>

      <div class="trust-item">
        <i class="fa-solid fa-calendar-check"></i>
        Appointment-based care
      </div>

      <div class="trust-item">
        <i class="fa-solid fa-leaf"></i>
        Authentic Sri Lankan Ayurveda
      </div>

      <div class="trust-item">
        <i class="fa-solid fa-shield-heart"></i>
        Patient-centred experience
      </div>

      <div class="trust-item">
        <i class="fa-solid fa-location-dot"></i>
        Kandy, Sri Lanka
      </div>

    </div>
  </section>

  <script type="application/json" id="heroSlidesData">
    [{
        "eyebrow": "Kandy, Sri Lanka",
        "title": "Authentic Ayurveda, thoughtfully connected.",
        "text": "DEVYA CEYLON brings qualified Ayurveda consultations, specialist channelling, wellness therapies and modern appointment support into one trusted patient experience."
      },
      {
        "eyebrow": "Traditional Healing, Modern Access",
        "title": "A trusted gateway to specialist Ayurveda care.",
        "text": "Connect with appropriate consultation pathways for musculoskeletal care, Panchakarma, skin care, women’s wellness, paediatric Ayurveda and lifestyle support."
      },
      {
        "eyebrow": "Preventive Health & Wellness",
        "title": "Care designed around your wellness journey.",
        "text": "From wellness consultations and stress management to detox, rejuvenation and digital follow-up, every step is designed to feel clear and patient-friendly."
      }
    ]
  </script>

  <section class="trust-strip" aria-label="Key service strengths">
    <div class="container trust-grid">
      <div class="trust-item"><i class="fa-solid fa-user-doctor"></i> Qualified practitioners</div>
      <div class="trust-item"><i class="fa-solid fa-calendar-check"></i> Appointment-based care</div>
      <div class="trust-item"><i class="fa-solid fa-leaf"></i> Authentic Sri Lankan Ayurveda</div>
      <div class="trust-item"><i class="fa-solid fa-shield-heart"></i> Patient-centred experience</div>
      <div class="trust-item"><i class="fa-solid fa-location-dot"></i> Kandy, Sri Lanka</div>
    </div>
  </section>

  <section class="section intro-section">
    <div class="container intro-grid">

      <div class="image-stack reveal">

        <div class="image-stack-main">
          <img
            src="<?= e(asset_url('images/home-about-main.webp', false)) ?>"
            alt="Traditional Sri Lankan Ayurveda medicine preparation with herbs, roots and herbal oils"
            width="1400"
            height="1100"
            loading="lazy"
            decoding="async">
        </div>

        <div class="image-stack-small">
          <img
            src="<?= e(asset_url('images/home-about-detail.webp', false)) ?>"
            alt="Ayurveda medicinal leaves, herbal oils and mortar and pestle preparation"
            width="800"
            height="900"
            loading="lazy"
            decoding="async">
        </div>

        <div class="experience-badge">
          <div>
            <strong>01</strong>
            <span>Trusted gateway</span>
          </div>
        </div>

      </div>

      <div class="reveal">

        <span class="eyebrow">
          <i class="fa-solid fa-seedling"></i>
          Welcome to DEVYA CEYLON
        </span>

        <div class="section-head">
          <h2>
            Traditional healing supported by a modern care journey.
          </h2>

          <p>
            DEVYA CEYLON is being developed as a professional Ayurveda
            Channelling Centre that combines traditional Sri Lankan
            consultation and wellness therapies with convenient
            appointment-based healthcare delivery.
          </p>
        </div>

        <div class="feature-list">

          <article class="feature-row">
            <i class="fa-solid fa-user-doctor"></i>

            <div>
              <h3>Professional consultation access</h3>

              <p>
                A structured gateway connecting patients with qualified
                Ayurveda medical practitioners and relevant specialist clinics.
              </p>
            </div>
          </article>

          <article class="feature-row">
            <i class="fa-solid fa-spa"></i>

            <div>
              <h3>Integrated wellness programmes</h3>

              <p>
                Therapeutic, preventive and rejuvenation services designed
                around individual wellness needs.
              </p>
            </div>
          </article>

          <article class="feature-row">
            <i class="fa-solid fa-mobile-screen-button"></i>

            <div>
              <h3>Convenient digital support</h3>

              <p>
                Appointment booking, teleconsultation, electronic
                prescriptions and digital medical records.
              </p>
            </div>
          </article>

        </div>

        <a class="text-link" href="<?= e(site_url('about-us.php')) ?>">
          Learn more about us
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
            Integrated care
          </span>

          <h2>
            Services built around consultation, therapy and prevention.
          </h2>
        </div>

        <div>
          <p>
            Explore the major service pathways proposed for the Centre.
            Final treatment decisions should follow an Ayurveda consultation
            and professional assessment.
          </p>

          <a class="text-link" href="<?= e(site_url('services.php')) ?>">
            View every service
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
              alt="Professional Ayurveda consultation at DEVYA CEYLON in Kandy Sri Lanka"
              width="1200"
              height="800"
              loading="lazy"
              decoding="async">
          </div>

          <div class="service-card-content">

            <span class="service-card-icon">
              <i class="fa-solid fa-stethoscope"></i>
            </span>

            <h3>Clinical Consultations</h3>

            <p>
              General Ayurveda OPD consultations and structured access to
              specialist channelling clinics.
            </p>

            <a class="text-link" href="<?= e(site_url('services.php#clinical')) ?>">
              Explore clinical care
              <i class="fa-solid fa-arrow-right"></i>
            </a>

          </div>
        </article>

        <!-- Wellness Therapies -->
        <article class="service-card reveal">

          <div class="service-card-media">
            <img
              src="<?= e(asset_url('images/service-panchakarma.webp', false)) ?>"
              alt="Premium Ayurveda Panchakarma and wellness treatment environment"
              width="1200"
              height="800"
              loading="lazy"
              decoding="async">
          </div>

          <div class="service-card-content">

            <span class="service-card-icon">
              <i class="fa-solid fa-spa"></i>
            </span>

            <h3>Wellness Therapies</h3>

            <p>
              Massage therapy, Panchakarma packages, detox, rejuvenation and
              stress management programmes.
            </p>

            <a class="text-link" href="<?= e(site_url('services.php#wellness')) ?>">
              Explore wellness
              <i class="fa-solid fa-arrow-right"></i>
            </a>

          </div>
        </article>

        <!-- Digital Support -->
        <article class="service-card reveal">

          <div class="service-card-media">
            <img
              src="<?= e(asset_url('images/service-digital-care.webp', false)) ?>"
              alt="Digital appointment booking and patient support at an Ayurveda centre"
              width="1200"
              height="800"
              loading="lazy"
              decoding="async">
          </div>

          <div class="service-card-content">

            <span class="service-card-icon">
              <i class="fa-solid fa-laptop-medical"></i>
            </span>

            <h3>Digital Support</h3>

            <p>
              Digital booking, teleconsultation, electronic prescriptions,
              records and referral coordination.
            </p>

            <a class="text-link" href="<?= e(site_url('services.php#support')) ?>">
              Explore digital care
              <i class="fa-solid fa-arrow-right"></i>
            </a>

          </div>
        </article>

      </div>
    </div>
  </section>

  <section class="section process-section">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow light"><i class="fa-solid fa-route"></i> Patient journey</span>
        <h2>A simpler path from enquiry to care.</h2>
        <p>A clear appointment process helps patients understand the next step and gives the Centre time to coordinate the appropriate practitioner or service.</p>
      </div>
      <div class="process-grid">
        <article class="process-card reveal"><span class="process-number">01</span>
          <h3>Choose a service</h3>
          <p>Review consultations, specialist clinics, wellness programmes and supportive services.</p>
        </article>
        <article class="process-card reveal"><span class="process-number">02</span>
          <h3>Send a request</h3>
          <p>Share your contact details, preferred service and suitable appointment date.</p>
        </article>
        <article class="process-card reveal"><span class="process-number">03</span>
          <h3>Receive confirmation</h3>
          <p>Staff can verify practitioner availability and confirm the appointment or next action.</p>
        </article>
        <article class="process-card reveal"><span class="process-number">04</span>
          <h3>Begin your care</h3>
          <p>Attend the consultation and receive a professionally guided treatment or wellness pathway.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section digital-section">
    <div class="container digital-panel reveal">

      <div class="digital-copy">

        <span class="eyebrow">
          <i class="fa-solid fa-wifi"></i>
          Modern service delivery
        </span>

        <h2>Digital access that supports authentic care.</h2>

        <p>
          The proposed Centre combines traditional healthcare with practical
          digital tools that improve convenience, record continuity and
          communication.
        </p>

        <div class="digital-list">

          <span>
            <i class="fa-solid fa-calendar-check"></i>
            Digital appointments
          </span>

          <span>
            <i class="fa-solid fa-video"></i>
            Teleconsultations
          </span>

          <span>
            <i class="fa-solid fa-file-prescription"></i>
            Electronic prescriptions
          </span>

          <span>
            <i class="fa-solid fa-notes-medical"></i>
            Digital medical records
          </span>

          <span>
            <i class="fa-solid fa-arrow-right-arrow-left"></i>
            Referral coordination
          </span>

          <span>
            <i class="fa-solid fa-store"></i>
            Pharmacy support
          </span>

        </div>

        <div class="hero-actions">
          <a class="button button-primary" href="<?= e(site_url('appointments.php')) ?>">
            <i class="fa-solid fa-mobile-screen"></i>
            Start a Request
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
                <strong>DEVYA CEYLON</strong>
                <small>Appointment request</small>
              </div>

            </div>

            <div class="phone-appointment">
              <span>Next step</span>

              <h3>Select your care pathway</h3>

              <p>
                Consultation · Wellness · Teleconsultation
              </p>
            </div>

            <div class="phone-rows">

              <div class="phone-row">
                <i class="fa-solid fa-user-doctor"></i>
                Specialist consultation
              </div>

              <div class="phone-row">
                <i class="fa-solid fa-calendar-day"></i>
                Preferred appointment date
              </div>

              <div class="phone-row">
                <i class="fa-brands fa-whatsapp"></i>
                Staff confirmation
              </div>

            </div>

          </div>
        </div>

      </div>

    </div>
  </section>

  <section class="section audience-section">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-people-group"></i> Who we serve</span>
        <h2>Accessible Ayurveda for diverse wellness needs.</h2>
        <p>The Centre is designed to support local patients, working professionals, long-term care needs and wellness travellers.</p>
      </div>
      <div class="audience-grid">
        <article class="audience-card reveal"><i class="fa-solid fa-house-user"></i>
          <h3>Local Community</h3>
          <p>People seeking accessible Ayurveda healthcare close to home.</p>
        </article>
        <article class="audience-card reveal"><i class="fa-solid fa-briefcase"></i>
          <h3>Busy Professionals</h3>
          <p>Appointment-based consultations that fit structured schedules.</p>
        </article>
        <article class="audience-card reveal"><i class="fa-solid fa-person-cane"></i>
          <h3>Elderly & Chronic Care</h3>
          <p>Patients requiring coordinated, ongoing consultation support.</p>
        </article>
        <article class="audience-card reveal"><i class="fa-solid fa-heart-pulse"></i>
          <h3>Wellness Clients</h3>
          <p>Health-conscious clients focused on prevention and balance.</p>
        </article>
        <article class="audience-card reveal"><i class="fa-solid fa-plane-arrival"></i>
          <h3>Wellness Tourists</h3>
          <p>Domestic and international visitors seeking Sri Lankan Ayurveda.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container cta-panel reveal">
      <div><span class="eyebrow light"><i class="fa-solid fa-calendar-check"></i> Begin with a consultation</span>
        <h2>Ready to take the next step?</h2>
        <p>Send an appointment request and allow the Centre to guide you towards the most suitable consultation or wellness pathway.</p>
      </div>
      <div class="cta-actions"><a class="button button-gold" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> Request Appointment</a><a class="button button-secondary" href="<?= e(site_url('contact.php')) ?>"><i class="fa-solid fa-phone"></i> Contact Centre</a></div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
