<?php
require __DIR__ . '/includes/site-config.php';
$pageTitle = 'Request an Appointment | DEVYA CEYLON';
$pageDescription = 'Request an Ayurveda consultation, specialist clinic, wellness programme or teleconsultation appointment with DEVYA CEYLON.';
$currentPage = 'appointments';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
  <section class="page-hero">
    <div class="container page-hero-grid">

      <div class="reveal">

        <span class="eyebrow">
          <i class="fa-solid fa-calendar-check"></i>
          Appointment request
        </span>

        <h1 class="page-title">
          Start your care journey with a
          <span style="color:var(--gold-700)">simple request.</span>
        </h1>

        <p>
          Complete the form and send the request directly to the Devya
          appointment system. A staff member can review the details and
          confirm availability.
        </p>

        <div class="breadcrumbs">
          <a href="<?= e(site_url('index.php')) ?>">Home</a>
          <i class="fa-solid fa-chevron-right"></i>
          <span>Appointments</span>
        </div>

      </div>

      <aside class="appointment-hero-media reveal">

        <img
          src="<?= e(asset_url('images/appointment-support.webp', false)) ?>"
          alt="Friendly appointment booking support at DEVYA CEYLON Ayurveda Centre in Kandy Sri Lanka"
          width="1200"
          height="1200"
          loading="eager"
          fetchpriority="high"
          decoding="async">

        <div class="appointment-hero-overlay">

          <span class="appointment-hero-label">
            <i class="fa-solid fa-calendar-check"></i>
            System Booking
          </span>

          <h2>Fast, convenient confirmation.</h2>

          <p>
            Complete the form and your request will be saved in the Devya
            system for staff review and confirmation.
          </p>

        </div>

      </aside>

    </div>
  </section>

  <section class="section intro-section">
    <div class="container booking-layout">
      <aside class="booking-info reveal">
        <div class="info-card">
          <span class="eyebrow light"><i class="fa-solid fa-circle-info"></i> Before you submit</span>
          <h2>What happens next?</h2>
          <p>Your request is reviewed so the Centre can coordinate the appropriate service, practitioner and available time.</p>
          <div class="info-list">
            <div><i class="fa-solid fa-1"></i><span>Complete the appointment request form.</span></div>
            <div><i class="fa-solid fa-2"></i><span>Your request is saved in the Devya system.</span></div>
            <div><i class="fa-solid fa-3"></i><span>Wait for staff confirmation or a suggested alternative.</span></div>
          </div>
        </div>
        <div class="booking-note"><strong style="color:var(--forest-900)">Important:</strong> This page is for appointment requests only. Emergency medical concerns should be directed to an appropriate emergency healthcare provider.</div>
      </aside>

      <form
        class="booking-form reveal"
        id="appointmentForm"
        action="<?= e(site_url($site['system_appointment_endpoint'])) ?>"
        method="post"
        data-endpoint="<?= e(site_url($site['system_appointment_endpoint'])) ?>">
        <span class="eyebrow"><i class="fa-solid fa-pen-to-square"></i> Your request details</span>
        <div class="section-head" style="margin-bottom:28px">
          <h2 style="font-size:clamp(2rem,4vw,3.1rem)">Request an appointment</h2>
          <p>Please provide accurate contact information so staff can respond.</p>
        </div>

        <div class="form-grid">
          <div class="form-group"><label for="fullName">Full name *</label><input class="form-control" id="fullName" name="fullName" type="text" autocomplete="name" required></div>
          <div class="form-group"><label for="phone">Phone / WhatsApp number *</label><input class="form-control" id="phone" name="phone" type="tel" autocomplete="tel" placeholder="07X XXX XXXX" required></div>
          <div class="form-group"><label for="service">Preferred service *</label><select class="form-control" id="service" name="service" required>
              <option value="">Select a service</option>
              <option>General Ayurveda OPD Consultation</option>
              <option>Joint & Musculoskeletal Disorders</option>
              <option>Panchakarma Consultation</option>
              <option>Dermatology / Skin Care</option>
              <option>Women's Wellness</option>
              <option>Paediatric Ayurveda</option>
              <option>Weight Management & Lifestyle Disorders</option>
              <option>Ayurveda Massage Therapy</option>
              <option>Panchakarma Package</option>
              <option>Detox & Rejuvenation Programme</option>
              <option>Stress Management Programme</option>
              <option>Corporate Wellness Package</option>
              <option>Teleconsultation</option>
              <option>Other / Need guidance</option>
            </select></div>
          <div class="form-group"><label for="preferredDate">Preferred date *</label><input class="form-control" id="preferredDate" name="preferredDate" type="date" required><span class="form-help">The final date and time require staff confirmation.</span></div>
          <div class="form-group"><label for="preferredTime">Preferred time</label><select class="form-control" id="preferredTime" name="preferredTime">
              <option value="Any available time">Any available time</option>
              <option>Morning</option>
              <option>Afternoon</option>
              <option>Evening</option>
            </select></div>
          <div class="form-group"><label for="contactMethod">Preferred response method</label><select class="form-control" id="contactMethod" name="contactMethod">
              <option>WhatsApp</option>
              <option>Phone call</option>
              <option>SMS</option>
            </select></div>
          <div class="form-group full"><label for="notes">Reason for consultation / notes</label><textarea class="form-control" id="notes" name="notes" maxlength="1500" placeholder="Briefly describe your wellness goal, consultation need or any useful details."></textarea></div>
        </div>

        <input type="text" name="website" tabindex="-1" autocomplete="off" class="honeypot-field" aria-hidden="true">

        <div class="form-actions">
          <button class="button button-primary" type="submit"><i class="fa-solid fa-calendar-check"></i> Submit Request</button>
          <a class="button button-secondary" href="tel:<?= e($site['phone_href']) ?>"><i class="fa-solid fa-phone"></i> Call Centre</a>
        </div>
        <div class="form-status" id="appointmentStatus" role="status">Your request is being sent to the Devya system.</div>
      </form>
    </div>
  </section>

  <section class="section services-preview">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-shield-heart"></i> Patient-friendly process</span>
        <h2>Clear, convenient and consultation-led.</h2>
        <p>The booking flow supports access and coordination while keeping clinical decisions with qualified practitioners.</p>
      </div>
      <div class="process-grid" style="color:var(--ink)">
        <article class="objective-card reveal"><span>01</span>
          <h3>Request</h3>
          <p>Choose the service area and submit your preferred date.</p>
        </article>
        <article class="objective-card reveal"><span>02</span>
          <h3>Review</h3>
          <p>Staff review the request and identify the appropriate care pathway.</p>
        </article>
        <article class="objective-card reveal"><span>03</span>
          <h3>Confirm</h3>
          <p>Receive an available date, time and any preparation guidance.</p>
        </article>
        <article class="objective-card reveal"><span>04</span>
          <h3>Consult</h3>
          <p>Attend the confirmed consultation before beginning a therapy plan.</p>
        </article>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
