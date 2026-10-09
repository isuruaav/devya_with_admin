<?php
require __DIR__ . '/includes/site-config.php';
$pageTitle = 'Facilities & Team | DEVYA CEYLON';
$pageDescription = 'Explore the proposed infrastructure, treatment spaces, digital systems and professional team roles of DEVYA CEYLON Ayurveda Centre.';
$currentPage = 'facilities';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
  <section class="page-hero">
    <div class="container page-hero-grid">
      <div class="reveal">
        <span class="eyebrow"><i class="fa-solid fa-building-circle-check"></i> Project components</span>
        <h1 class="page-title">Spaces and people designed for <span style="color:var(--gold-700)">quality care.</span></h1>
        <p>The proposed Centre combines patient-friendly infrastructure, dedicated consultation and therapy areas, and a multidisciplinary Ayurveda and support team.</p>
        <div class="breadcrumbs"><a href="<?= e(site_url('index.php')) ?>">Home</a><i class="fa-solid fa-chevron-right"></i><span>Facilities & Team</span></div>
      </div>
   <aside class="facility-hero-media reveal">

  <img
    src="<?= e(asset_url('images/facility-reception.webp', false)) ?>"
    alt="Premium reception and channelling desk at an Ayurveda centre in Kandy Sri Lanka"
    width="1400"
    height="900"
    loading="eager"
    fetchpriority="high"
    decoding="async"
  >

  <div class="facility-hero-caption">
    <span>
      <i class="fa-solid fa-bell-concierge"></i>
      Patient-friendly arrival
    </span>

    <h2>A complete care environment.</h2>

    <p>
      Reception, consultation, treatment, pharmacy and digital support
      are connected through one clear patient journey.
    </p>
  </div>

</aside>
    </div>
  </section>

  <section class="section intro-section">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-building"></i> Infrastructure development</span><h2>Purpose-built areas for consultation, therapy and support.</h2><p>The project proposes practical spaces that improve patient comfort, privacy, workflow and access to Ayurveda services.</p></div>
  <div class="facility-showcase-grid">

  <!-- Reception -->
  <article class="facility-showcase-card reveal">

    <div class="facility-showcase-image">
      <img
        src="<?= e(asset_url('images/facility-reception.webp', false)) ?>"
        alt="Reception and Ayurveda channelling desk in a modern Kandy wellness centre"
        width="1400"
        height="900"
        loading="lazy"
        decoding="async"
      >

      <span class="facility-image-icon">
        <i class="fa-solid fa-bell-concierge"></i>
      </span>
    </div>

    <div class="facility-showcase-content">
      <span class="facility-label">Patient Arrival</span>

      <h3>Reception &amp; Channelling Desk</h3>

      <p>
        A welcoming first point for enquiries, registration,
        appointment coordination and patient guidance.
      </p>
    </div>

  </article>

  <!-- Consultation Room -->
  <article class="facility-showcase-card reveal">

    <div class="facility-showcase-image">
      <img
        src="<?= e(asset_url('images/facility-consultation-room.webp', false)) ?>"
        alt="Private Ayurveda consultation room overlooking the green hills of Kandy"
        width="1400"
        height="900"
        loading="lazy"
        decoding="async"
      >

      <span class="facility-image-icon">
        <i class="fa-solid fa-user-doctor"></i>
      </span>
    </div>

    <div class="facility-showcase-content">
      <span class="facility-label">Clinical Care</span>

      <h3>Consultation Rooms</h3>

      <p>
        Private and professional spaces for general OPD consultations
        and visiting specialist Ayurveda clinics.
      </p>
    </div>

  </article>

  <!-- Therapy Room -->
  <article class="facility-showcase-card reveal">

    <div class="facility-showcase-image">
      <img
        src="<?= e(asset_url('images/facility-therapy-room.webp', false)) ?>"
        alt="Private Panchakarma therapy room with traditional wooden treatment table"
        width="1400"
        height="900"
        loading="lazy"
        decoding="async"
      >

      <span class="facility-image-icon">
        <i class="fa-solid fa-spa"></i>
      </span>
    </div>

    <div class="facility-showcase-content">
      <span class="facility-label">Wellness Therapy</span>

      <h3>Therapy &amp; Panchakarma Rooms</h3>

      <p>
        Dedicated private environments for appropriate Ayurveda therapies,
        Panchakarma and wellness programmes.
      </p>
    </div>

  </article>

  <!-- Pharmacy -->
  <article class="facility-showcase-card reveal">

    <div class="facility-showcase-image">
      <img
        src="<?= e(asset_url('images/facility-pharmacy.webp', false)) ?>"
        alt="Modern Ayurveda pharmacy and herbal product display in Kandy Sri Lanka"
        width="1400"
        height="900"
        loading="lazy"
        decoding="async"
      >

      <span class="facility-image-icon">
        <i class="fa-solid fa-prescription-bottle-medical"></i>
      </span>
    </div>

    <div class="facility-showcase-content">
      <span class="facility-label">Herbal Care</span>

      <h3>Pharmacy &amp; Herbal Display</h3>

      <p>
        An organized area supporting Ayurveda pharmacy services and
        the professional display of herbal products.
      </p>
    </div>

  </article>

</div>

<div class="facility-support-grid">

  <!-- Waiting Lounge -->
  <article class="facility-support-card reveal">

    <span class="facility-support-icon">
      <i class="fa-solid fa-couch"></i>
    </span>

    <div>
      <h3>Waiting Lounge</h3>

      <p>
        A calm and comfortable waiting area designed to support a
        professional, accessible and client-friendly experience.
      </p>
    </div>

  </article>

  <!-- Digital System -->
  <article class="facility-support-card reveal">

    <span class="facility-support-icon">
      <i class="fa-solid fa-database"></i>
    </span>

    <div>
      <h3>Digital Records &amp; Booking System</h3>

      <p>
        A structured system supporting appointment management,
        patient records and service coordination.
      </p>
    </div>

  </article>

</div>
    </div>
  </section>

  <section class="section services-preview">
    <div class="container">
      <div class="section-head split reveal">
        <div><span class="eyebrow"><i class="fa-solid fa-people-group"></i> Human resources</span><h2>A coordinated team across clinical care and centre operations.</h2></div>
        <div><p>The proposed workforce combines Ayurveda medical expertise, therapy delivery, booking administration, marketing and technical support.</p></div>
      </div>
      <div class="team-role-grid">
        <article class="team-role-card reveal"><i class="fa-solid fa-user-doctor"></i><div><h3>Ayurveda Medical Officers</h3><p>Provide clinical consultations, assessment and professional guidance.</p></div></article>
        <article class="team-role-card reveal"><i class="fa-solid fa-stethoscope"></i><div><h3>Visiting Specialists</h3><p>Support scheduled specialist clinics across proposed care areas.</p></div></article>
        <article class="team-role-card reveal"><i class="fa-solid fa-hands-bubbles"></i><div><h3>Panchakarma Therapists</h3><p>Deliver approved therapy and wellness programmes under proper guidance.</p></div></article>
        <article class="team-role-card reveal"><i class="fa-solid fa-calendar-days"></i><div><h3>Reception & Booking Staff</h3><p>Coordinate appointments, enquiries, registration and patient communication.</p></div></article>
        <article class="team-role-card reveal"><i class="fa-solid fa-bullhorn"></i><div><h3>Marketing Officer</h3><p>Promote centre services, preventive health and wellness tourism initiatives.</p></div></article>
        <article class="team-role-card reveal"><i class="fa-solid fa-laptop-code"></i><div><h3>IT Support Staff</h3><p>Maintain digital appointments, records and technology-based service delivery.</p></div></article>
      </div>
    </div>
  </section>

<section class="section patient-journey-section">
  <div class="container">

    <div class="patient-journey-panel reveal">

      <!-- Left Content -->
      <div class="patient-journey-copy">

        <span class="eyebrow">
          <i class="fa-solid fa-diagram-project"></i>
          Integrated Operations
        </span>

        <h2>
          One connected journey from booking to
          <span>continued care.</span>
        </h2>

        <p>
          DEVYA CEYLON connects its spaces, clinical team and digital
          systems through one coordinated patient journey. Every stage
          supports clear communication, professional care and convenient
          follow-up.
        </p>

        <div class="journey-benefits">

          <div>
            <i class="fa-solid fa-circle-check"></i>
            <span>Clear appointment coordination</span>
          </div>

          <div>
            <i class="fa-solid fa-circle-check"></i>
            <span>Consultation-led service selection</span>
          </div>

          <div>
            <i class="fa-solid fa-circle-check"></i>
            <span>Organized digital record continuity</span>
          </div>

        </div>

        <div class="journey-actions">

          <a class="button button-primary" href="<?= e(site_url('appointments.php')) ?>">
            <i class="fa-solid fa-calendar-check"></i>
            Request Appointment
          </a>

          <a class="text-link" href="<?= e(site_url('services.php')) ?>">
            Explore Care Services
            <i class="fa-solid fa-arrow-right"></i>
          </a>

        </div>

      </div>

      <!-- Right Patient Journey -->
      <div
        class="patient-journey-flow"
        aria-label="DEVYA CEYLON patient journey"
      >

        <div class="journey-flow-line" aria-hidden="true"></div>

        <!-- Step 01 -->
        <article class="journey-step">

          <div class="journey-step-marker">
            <span>01</span>
            <i class="fa-solid fa-calendar-check"></i>
          </div>

          <div class="journey-step-content">
            <span class="journey-step-label">Start</span>
            <h3>Appointment Request</h3>
            <p>
              The patient selects a service and submits preferred
              contact, date and appointment details.
            </p>
          </div>

        </article>

        <!-- Step 02 -->
        <article class="journey-step">

          <div class="journey-step-marker">
            <span>02</span>
            <i class="fa-solid fa-bell-concierge"></i>
          </div>

          <div class="journey-step-content">
            <span class="journey-step-label">Arrival</span>
            <h3>Reception & Registration</h3>
            <p>
              Reception staff confirm the appointment, guide registration
              and coordinate the next stage of care.
            </p>
          </div>

        </article>

        <!-- Step 03 -->
        <article class="journey-step is-featured">

          <div class="journey-step-marker">
            <span>03</span>
            <i class="fa-solid fa-user-doctor"></i>
          </div>

          <div class="journey-step-content">
            <span class="journey-step-label">Clinical Guidance</span>
            <h3>Ayurveda Consultation</h3>
            <p>
              A qualified Ayurveda practitioner evaluates the patient and
              recommends an appropriate care pathway.
            </p>
          </div>

        </article>

        <!-- Step 04 -->
        <article class="journey-step">

          <div class="journey-step-marker">
            <span>04</span>
            <i class="fa-solid fa-spa"></i>
          </div>

          <div class="journey-step-content">
            <span class="journey-step-label">Care Delivery</span>
            <h3>Therapy or Wellness Programme</h3>
            <p>
              Approved therapies, Panchakarma or wellness programmes are
              delivered according to practitioner guidance.
            </p>
          </div>

        </article>

        <!-- Step 05 -->
        <article class="journey-step">

          <div class="journey-step-marker">
            <span>05</span>
            <i class="fa-solid fa-prescription-bottle-medical"></i>
          </div>

          <div class="journey-step-content">
            <span class="journey-step-label">Support</span>
            <h3>Pharmacy & Herbal Care</h3>
            <p>
              Patients receive prescribed Ayurveda medicine, herbal
              products or additional care instructions when required.
            </p>
          </div>

        </article>

        <!-- Step 06 -->
        <article class="journey-step">

          <div class="journey-step-marker">
            <span>06</span>
            <i class="fa-solid fa-file-medical"></i>
          </div>

          <div class="journey-step-content">
            <span class="journey-step-label">Continuity</span>
            <h3>Digital Records & Follow-up</h3>
            <p>
              Records, prescriptions and follow-up information support
              organized and continuous patient care.
            </p>
          </div>

        </article>

      </div>

    </div>

  </div>
</section>

  <section class="cta-section">
    <div class="container cta-panel reveal"><div><span class="eyebrow light"><i class="fa-solid fa-calendar-check"></i> Plan your visit</span><h2>Request a consultation through the booking page.</h2><p>Choose the proposed service area and share your preferred date for staff confirmation.</p></div><div class="cta-actions"><a class="button button-gold" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> Request Appointment</a><a class="button button-secondary" href="<?= e(site_url('contact.php')) ?>"><i class="fa-solid fa-phone"></i> Contact Us</a></div></div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
