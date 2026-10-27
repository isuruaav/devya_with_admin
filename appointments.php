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
          <?= e(t('Appointment request')) ?>
        </span>

        <h1 class="page-title">
          <?= e(t('Start your care journey with a')) ?>
          <span style="color:var(--gold-700)"><?= e(t('simple request.')) ?></span>
        </h1>

        <p>
          <?= e(t('Complete the form and send the request directly to the Devya appointment system. A staff member can review the details and confirm availability.')) ?>
        </p>

        <div class="breadcrumbs">
          <a href="<?= e(site_url('index.php')) ?>"><?= e(t('Home')) ?></a>
          <i class="fa-solid fa-chevron-right"></i>
          <span><?= e(t('Appointments')) ?></span>
        </div>

      </div>

      <aside class="appointment-hero-media reveal">

        <img
          src="<?= e(asset_url('images/appointment-support.webp', false)) ?>"
          alt="<?= e(t('Friendly appointment booking support at DEVYA CEYLON Ayurveda Centre in Kandy Sri Lanka')) ?>"
          width="1200"
          height="1200"
          loading="eager"
          fetchpriority="high"
          decoding="async">

        <div class="appointment-hero-overlay">

          <span class="appointment-hero-label">
            <i class="fa-solid fa-calendar-check"></i>
            <?= e(t('System Booking')) ?>
          </span>

          <h2><?= e(t('Fast, convenient confirmation.')) ?></h2>

          <p>
            <?= e(t('Complete the form and your request will be saved in the Devya system for staff review and confirmation.')) ?>
          </p>

        </div>

      </aside>

    </div>
  </section>

  <section class="section intro-section">
    <div class="container booking-layout">
      <aside class="booking-info reveal">
        <div class="consultation-card">
          <h2><i class="fa-regular fa-clock" aria-hidden="true"></i> <?= e(t('Consultation Time')) ?></h2>
          <?php require __DIR__ . '/includes/consultation-hours.php'; ?>
          <p><?= e(t('Appointments are recommended for special clinics.')) ?></p>
          <a class="text-link" href="<?= e(site_url('index.php#doctor-schedule')) ?>"><?= e(t('View doctors & weekly schedule')) ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="info-card">
          <span class="eyebrow light"><i class="fa-solid fa-circle-info"></i> <?= e(t('Before you submit')) ?></span>
          <h2><?= e(t('What happens next?')) ?></h2>
          <p><?= e(t('Your request is reviewed so the Centre can coordinate the appropriate service, practitioner and available time.')) ?></p>
          <div class="info-list">
            <div><i class="fa-solid fa-1"></i><span><?= e(t('Complete the appointment request form.')) ?></span></div>
            <div><i class="fa-solid fa-2"></i><span><?= e(t('Your request is saved in the Devya system.')) ?></span></div>
            <div><i class="fa-solid fa-3"></i><span><?= e(t('Wait for staff confirmation or a suggested alternative.')) ?></span></div>
          </div>
        </div>
        <div class="booking-note"><strong style="color:var(--forest-900)"><?= e(t('Important:')) ?></strong> <?= e(t('This page is for appointment requests only. Emergency medical concerns should be directed to an appropriate emergency healthcare provider.')) ?></div>
        <div class="booking-note">
          <strong style="color:var(--forest-900)"><?= e(t('Payment and confirmation:')) ?></strong>
          <?= e(t('Submitting this form does not confirm an appointment and does not require payment. After staff confirm your appointment, they will send an official link to pay the LKR 1,500 booking advance. The LKR 3,000 consultation fee is payable at the Centre on the appointment date.')) ?>
          <nav class="appointment-policy-links" aria-label="<?= e(t('Legal policies')) ?>">
            <a href="<?= e(site_url('privacy-policy.php')) ?>"><?= e(t('Privacy Policy')) ?></a>
            <a href="<?= e(site_url('refund-policy.php')) ?>"><?= e(t('Refund & Cancellation Policy')) ?></a>
            <a href="<?= e(site_url('terms-and-conditions.php')) ?>"><?= e(t('Terms & Conditions')) ?></a>
          </nav>
        </div>
      </aside>

      <form
        class="booking-form reveal"
        id="appointmentForm"
        action="<?= e(site_url($site['system_appointment_endpoint'])) ?>"
        method="post"
        data-endpoint="<?= e(site_url($site['system_appointment_endpoint'])) ?>">
        <span class="eyebrow"><i class="fa-solid fa-pen-to-square"></i> <?= e(t('Your request details')) ?></span>
        <div class="section-head" style="margin-bottom:28px">
          <h2 style="font-size:clamp(2rem,4vw,3.1rem)"><?= e(t('Request an appointment')) ?></h2>
          <p><?= e(t('Please provide accurate contact information so staff can respond.')) ?></p>
        </div>

        <div class="form-grid">
          <div class="form-group"><label for="fullName"><i class="fa-solid fa-user" data-tooltip="Enter full legal name" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Full name *')) ?></label><input class="form-control" id="fullName" name="fullName" type="text" autocomplete="name" required></div>
          <div class="form-group"><label for="phone"><i class="fa-solid fa-phone" data-tooltip="Active phone or WhatsApp number" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Phone / WhatsApp number *')) ?></label><input class="form-control" id="phone" name="phone" type="tel" autocomplete="tel" placeholder="<?= e(t('07X XXX XXXX')) ?>" required></div>
          <div class="form-group"><label for="email"><i class="fa-solid fa-envelope" data-tooltip="Email address for digital confirmation" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Email address *')) ?></label><input class="form-control" id="email" name="email" type="email" autocomplete="email" required></div>
          <div class="form-group"><label for="address"><i class="fa-solid fa-location-dot" data-tooltip="Residential or street address" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Address *')) ?></label><input class="form-control" id="address" name="address" type="text" autocomplete="street-address" required></div>
          <div class="form-group"><label for="city"><i class="fa-solid fa-city" data-tooltip="Nearest town or city" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('City *')) ?></label><input class="form-control" id="city" name="city" type="text" autocomplete="address-level2" required></div>
          <div class="form-group"><label for="service"><i class="fa-solid fa-stethoscope" data-tooltip="Select consultation clinic or wellness package" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Preferred service *')) ?></label><select class="form-control" id="service" name="service" required>
              <option value=""><?= e(t('Select a service')) ?></option>
              <optgroup label="<?= e(t('Weekly special clinics')) ?>">
                <?php foreach ($doctorSchedule as $entry): ?>
                  <option value="<?= e($entry['clinic']) ?>"><?= e(t($entry['day']) . ' - ' . t($entry['clinic'])) ?></option>
                <?php endforeach; ?>
              </optgroup>
              <optgroup label="<?= e(t('Consultations & wellness services')) ?>">
              <option value="General Ayurveda OPD Consultation"><?= e(t('General Ayurveda OPD Consultation')) ?></option>
              <option value="Joint &amp; Musculoskeletal Disorders"><?= e(t('Joint & Musculoskeletal Disorders')) ?></option>
              <option value="Panchakarma Consultation"><?= e(t('Panchakarma Consultation')) ?></option>
              <option value="Dermatology / Skin Care"><?= e(t('Dermatology / Skin Care')) ?></option>
              <option value="Women&#x27;s Wellness"><?= e(t('Women\'s Wellness')) ?></option>
              <option value="Paediatric Ayurveda"><?= e(t('Paediatric Ayurveda')) ?></option>
              <option value="Weight Management &amp; Lifestyle Disorders"><?= e(t('Weight Management & Lifestyle Disorders')) ?></option>
              <option value="Ayurveda Massage Therapy"><?= e(t('Ayurveda Massage Therapy')) ?></option>
              <option value="Panchakarma Package"><?= e(t('Panchakarma Package')) ?></option>
              <option value="Detox &amp; Rejuvenation Programme"><?= e(t('Detox & Rejuvenation Programme')) ?></option>
              <option value="Stress Management Programme"><?= e(t('Stress Management Programme')) ?></option>
              <option value="Corporate Wellness Package"><?= e(t('Corporate Wellness Package')) ?></option>
              <option value="Teleconsultation"><?= e(t('Teleconsultation')) ?></option>
              <option value="Other / Need guidance"><?= e(t('Other / Need guidance')) ?></option>
              </optgroup>
            </select></div>
          <div class="form-group"><label for="preferredDate"><i class="fa-regular fa-calendar-days" data-tooltip="Select consultation date" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Preferred date *')) ?></label><input class="form-control" id="preferredDate" name="preferredDate" type="date" required><span class="form-help"><?= e(t('The final date and time require staff confirmation.')) ?></span></div>
          <div class="form-group"><label for="preferredTime"><i class="fa-regular fa-clock" data-tooltip="Select preferred consultation time" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Preferred time')) ?></label><select class="form-control" id="preferredTime" name="preferredTime" aria-describedby="preferredTimeHelp" data-weekday-hours="<?= e(t($consultationHours[0]['time'])) ?>" data-sunday-hours="<?= e(t($consultationHours[1]['time'])) ?>">
              <option value="Any available time"><?= e(t('Any available time')) ?></option>
              <option value="Morning" data-consultation-day="sunday"><?= e(t('Morning (Sunday only)')) ?></option>
              <option value="Afternoon" data-consultation-day="sunday"><?= e(t('Afternoon (Sunday only)')) ?></option>
              <option value="Evening" data-consultation-day="weekday"><?= e(t('Evening (Monday to Saturday)')) ?></option>
            </select><span class="form-help" id="preferredTimeHelp"><?= e(t('Choose a date to see the available consultation period.')) ?></span></div>
          <div class="form-group"><label for="contactMethod"><i class="fa-solid fa-comments" data-tooltip="Preferred response communication channel" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Preferred response method')) ?></label><select class="form-control" id="contactMethod" name="contactMethod">
              <option value="WhatsApp"><?= e(t('WhatsApp')) ?></option>
              <option value="Phone call"><?= e(t('Phone call')) ?></option>
              <option value="SMS"><?= e(t('SMS')) ?></option>
            </select></div>
          <div class="form-group full"><label for="notes"><i class="fa-solid fa-notes-medical" data-tooltip="Describe symptoms or wellness requirements" style="color: var(--gold-600); margin-right: 6px;"></i><?= e(t('Reason for consultation / notes')) ?></label><textarea class="form-control" id="notes" name="notes" maxlength="1500" placeholder="<?= e(t('Briefly describe your wellness goal, consultation need or any useful details.')) ?>"></textarea></div>
        </div>

        <input type="text" name="website" tabindex="-1" autocomplete="off" class="honeypot-field" aria-hidden="true">

        <p class="form-help"><?= e(t('After your request is saved, WhatsApp opens with a message to the Centre. Press Send to share your booking number.')) ?></p>

        <div class="form-actions">
          <button class="button button-primary" type="submit" data-tooltip="Submit your consultation appointment request"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Submit Request')) ?></button>
          <a class="button button-secondary" href="tel:<?= e($site['phone_href']) ?>" data-tooltip="Direct phone call to reception"><i class="fa-solid fa-phone"></i> <?= e(t('Call Centre')) ?></a>
        </div>
        <div id="appointmentFollowUp" hidden>
          <a class="button button-whatsapp" id="appointmentWhatsAppLink" href="#" target="_blank" rel="noopener" data-tooltip="Direct WhatsApp chat with reception"><i class="fa-brands fa-whatsapp"></i> <?= e(t('Send request to the Centre on WhatsApp')) ?></a>
        </div>
        <div class="form-status" id="appointmentStatus" role="status"><?= e(t('Your request is being sent to the Devya system.')) ?></div>
      </form>
    </div>
  </section>

  <section class="section services-preview">
    <div class="container">
      <div class="section-head center reveal"><span class="eyebrow"><i class="fa-solid fa-shield-heart"></i> <?= e(t('Patient-friendly process')) ?></span>
        <h2><?= e(t('Clear, convenient and consultation-led.')) ?></h2>
        <p><?= e(t('The booking flow supports access and coordination while keeping clinical decisions with qualified practitioners.')) ?></p>
      </div>
      <div class="process-grid" style="color:var(--ink)">
        <article class="objective-card reveal"><span>01</span>
          <h3><?= e(t('Request')) ?></h3>
          <p><?= e(t('Choose the service area and submit your preferred date.')) ?></p>
        </article>
        <article class="objective-card reveal"><span>02</span>
          <h3><?= e(t('Review')) ?></h3>
          <p><?= e(t('Staff review the request and identify the appropriate care pathway.')) ?></p>
        </article>
        <article class="objective-card reveal"><span>03</span>
          <h3><?= e(t('Confirm')) ?></h3>
          <p><?= e(t('Receive an available date, time and any preparation guidance.')) ?></p>
        </article>
        <article class="objective-card reveal"><span>04</span>
          <h3><?= e(t('Consult')) ?></h3>
          <p><?= e(t('Attend the confirmed consultation before beginning a therapy plan.')) ?></p>
        </article>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
