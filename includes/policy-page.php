<?php
require __DIR__ . '/header.php';
?>

<main id="main-content">
  <section class="page-hero">
    <div class="container">
      <div class="reveal">
        <span class="eyebrow"><i class="fa-solid fa-file-shield" aria-hidden="true"></i> <?= e(t('DEVYA CEYLON')) ?></span>
        <h1 class="page-title"><?= e($policy['title']) ?></h1>
        <p><?= e($policy['intro']) ?></p>
        <div class="breadcrumbs">
          <a href="<?= e(site_url('index.php')) ?>"><?= e(t('Home')) ?></a>
          <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
          <span><?= e($policy['title']) ?></span>
        </div>
      </div>
    </div>
  </section>

  <section class="section intro-section">
    <div class="container">
      <article class="prose-card policy-copy">
        <p class="policy-updated"><?= e($policy['updated']) ?></p>
        <?php foreach ($policy['sections'] as $section): ?>
          <section>
            <h2><?= e($section['heading']) ?></h2>
            <?php foreach ($section['paragraphs'] as $paragraph): ?>
              <p><?= e($paragraph) ?></p>
            <?php endforeach; ?>
            <?php if (!empty($section['items'])): ?>
              <ul>
                <?php foreach ($section['items'] as $item): ?>
                  <li><?= e($item) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </section>
        <?php endforeach; ?>
        <section>
          <h2><?= e($language === 'si' ? 'අප හා සම්බන්ධ වන්න' : 'Contact us') ?></h2>
          <p><?= e($language === 'si'
              ? 'මෙම ප්‍රතිපත්ති හෝ ඔබගේ appointment/payment පිළිබඳ විමසීම් සඳහා DEVYA CEYLON අමතන්න.'
              : 'For questions about these policies or your appointment or payment, contact DEVYA CEYLON.') ?></p>
          <p><a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></p>
          <p><a href="tel:<?= e($site['phone_href']) ?>"><?= e($site['phone_display']) ?></a></p>
          <p><?= e(t($site['address'])) ?></p>
        </section>
      </article>
    </div>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>
