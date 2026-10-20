<footer class="site-footer">
  <div class="footer-orb footer-orb-one" aria-hidden="true"></div>
  <div class="footer-orb footer-orb-two" aria-hidden="true"></div>
  <div class="container footer-grid">
  <div class="footer-brand-card">

  <a
    class="brand footer-brand"
    href="<?= e(site_url('index.php')) ?>"
    aria-label="<?= e(t('DEVYA CEYLON home')) ?>"
  >

    <img
      class="brand-logo footer-logo"
      src="<?= e(asset_url('images/brand-logo.webp', false)) ?>"
      alt="<?= e(t('DEVYA CEYLON logo')) ?>"
      width="64"
      height="64"
      loading="lazy"
      decoding="async"
    >

    <span class="brand-copy">
      <strong><?= e($site['name']) ?></strong>
      <small><?= e(t($site['subtitle'])) ?></small>
    </span>

  </a>

  <p>
    <?= e(t('A trusted gateway connecting patients with qualified Ayurveda practitioners, specialist consultation pathways, wellness programmes and modern digital support.')) ?>
  </p>

  <div class="footer-socials">

    <a
      href="tel:<?= e($site['phone_href']) ?>"
      aria-label="<?= e(t('Call us')) ?>"
    >
      <i class="fa-solid fa-phone"></i>
    </a>

    <a
      href="https://wa.me/<?= e($site['whatsapp']) ?>"
      target="_blank"
      rel="noopener"
      aria-label="<?= e(t('WhatsApp us')) ?>"
    >
      <i class="fa-brands fa-whatsapp"></i>
    </a>

  </div>

</div>

    <div class="footer-column">
      <h2><?= e(t('Explore')) ?></h2>
      <?php foreach ($navigation as $item): ?>
        <a href="<?= e(site_url($item['href'])) ?>"><i class="fa-solid fa-chevron-right"></i><?= e(t($item['label'])) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="footer-column">
      <h2><?= e(t('Care pathways')) ?></h2>
      <a href="<?= e(site_url('services.php#clinical')) ?>"><i class="fa-solid fa-chevron-right"></i><?= e(t('Clinical services')) ?></a>
      <a href="<?= e(site_url('services.php#specialist')) ?>"><i class="fa-solid fa-chevron-right"></i><?= e(t('Specialist clinics')) ?></a>
      <a href="<?= e(site_url('services.php#wellness')) ?>"><i class="fa-solid fa-chevron-right"></i><?= e(t('Wellness programmes')) ?></a>
      <a href="<?= e(site_url('services.php#support')) ?>"><i class="fa-solid fa-chevron-right"></i><?= e(t('Digital support')) ?></a>
      <div class="footer-hours">
        <h3><?= e(t('Consultation Time')) ?></h3>
        <?php require __DIR__ . '/consultation-hours.php'; ?>
      </div>
    </div>

    <div class="footer-contact-card">
      <span class="eyebrow light"><?= e(t('Start your care journey')) ?></span>
      <h2><?= e(t('Request an Ayurveda appointment.')) ?></h2>
      <p><?= e(t('Share your preferred service and date. A staff member can confirm the next available consultation.')) ?></p>
      <a class="button button-gold" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> <?= e(t('Request Appointment')) ?></a>
      <a class="footer-phone" href="tel:<?= e($site['phone_href']) ?>"><i class="fa-solid fa-phone-volume" aria-hidden="true"></i><span><?= e(t($site['phone_contact'])) ?>: <?= e($site['phone_display']) ?></span></a>
      <a class="footer-phone" href="tel:<?= e($site['secondary_phone_href']) ?>"><i class="fa-solid fa-phone" aria-hidden="true"></i><span><?= e(t($site['secondary_phone_contact'])) ?>: <?= e($site['secondary_phone_display']) ?></span></a>
      <a class="footer-email" href="mailto:<?= e($site['email']) ?>"><i class="fa-solid fa-envelope"></i><?= e($site['email']) ?></a>
      <a class="footer-location" href="<?= e($site['maps_url']) ?>" target="_blank" rel="noopener"><i class="fa-solid fa-location-dot" aria-hidden="true"></i><?= e(t($site['address'])) ?></a>
    </div>
  </div>

  <div class="container footer-bottom">
    <span>&copy; <?= date('Y') ?> <?= e($site['name']) ?>. <?= e(t('All rights reserved.')) ?></span>
    <span><?= e(t($site['tagline'])) ?>.</span>
  </div>
</footer>

<a class="floating-whatsapp" href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener" aria-label="<?= e(t('Chat on WhatsApp')) ?>">
  <i class="fa-brands fa-whatsapp"></i><span><?= e(t('WhatsApp')) ?></span>
</a>

<script type="application/json" id="siteTranslations"><?= json_encode(browser_translations(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?></script>
<script src="<?= e(asset_url('js/site.js', true)) ?>"></script>
</body>
</html>
