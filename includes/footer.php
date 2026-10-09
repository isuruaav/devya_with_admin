<footer class="site-footer">
  <div class="footer-orb footer-orb-one" aria-hidden="true"></div>
  <div class="footer-orb footer-orb-two" aria-hidden="true"></div>
  <div class="container footer-grid">
  <div class="footer-brand-card">

  <a
    class="brand footer-brand"
    href="<?= e(site_url('index.php')) ?>"
    aria-label="<?= e($site['name']) ?> home"
  >

    <img
      class="brand-logo footer-logo"
      src="<?= e(asset_url('images/logo.png', false)) ?>"
      alt="<?= e($site['name']) ?> logo"
      width="64"
      height="64"
      loading="lazy"
      decoding="async"
    >

    <span class="brand-copy">
      <strong><?= e($site['name']) ?></strong>
      <small><?= e($site['subtitle']) ?></small>
    </span>

  </a>

  <p>
    A trusted gateway connecting patients with qualified Ayurveda
    practitioners, specialist consultation pathways, wellness programmes
    and modern digital support.
  </p>

  <div class="footer-socials">

    <a
      href="tel:<?= e($site['phone_href']) ?>"
      aria-label="Call us"
    >
      <i class="fa-solid fa-phone"></i>
    </a>

    <a
      href="https://wa.me/<?= e($site['whatsapp']) ?>"
      target="_blank"
      rel="noopener"
      aria-label="WhatsApp us"
    >
      <i class="fa-brands fa-whatsapp"></i>
    </a>

  </div>

</div>

    <div class="footer-column">
      <h2>Explore</h2>
      <?php foreach ($navigation as $item): ?>
        <a href="<?= e(site_url($item['href'])) ?>"><i class="fa-solid fa-chevron-right"></i><?= e($item['label']) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="footer-column">
      <h2>Care pathways</h2>
      <a href="<?= e(site_url('services.php#clinical')) ?>"><i class="fa-solid fa-chevron-right"></i>Clinical services</a>
      <a href="<?= e(site_url('services.php#specialist')) ?>"><i class="fa-solid fa-chevron-right"></i>Specialist clinics</a>
      <a href="<?= e(site_url('services.php#wellness')) ?>"><i class="fa-solid fa-chevron-right"></i>Wellness programmes</a>
      <a href="<?= e(site_url('services.php#support')) ?>"><i class="fa-solid fa-chevron-right"></i>Digital support</a>
    </div>

    <div class="footer-contact-card">
      <span class="eyebrow light">Start your care journey</span>
      <h2>Request an Ayurveda appointment.</h2>
      <p>Share your preferred service and date. A staff member can confirm the next available consultation.</p>
      <a class="button button-gold" href="<?= e(site_url('appointments.php')) ?>"><i class="fa-solid fa-calendar-check"></i> Request Appointment</a>
      <a class="footer-phone" href="tel:<?= e($site['phone_href']) ?>"><i class="fa-solid fa-phone-volume"></i><?= e($site['phone_display']) ?></a>
      <a class="footer-location" href="<?= e($site['maps_url']) ?>" target="_blank" rel="noopener"><i class="fa-solid fa-location-dot"></i><?= e($site['location']) ?></a>
    </div>
  </div>

  <div class="container footer-bottom">
    <span>&copy; <?= date('Y') ?> <?= e($site['name']) ?>. All rights reserved.</span>
    <span><?= e($site['tagline']) ?>.</span>
  </div>
</footer>

<a class="floating-whatsapp" href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <i class="fa-brands fa-whatsapp"></i><span>WhatsApp</span>
</a>

<script src="<?= e(asset_url('js/site.js', true)) ?>"></script>
</body>
</html>
