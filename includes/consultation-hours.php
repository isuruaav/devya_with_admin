<div class="consultation-hours" aria-label="<?= e(t('Consultation times')) ?>">
  <?php foreach ($consultationHours as $hours): ?>
    <div class="consultation-hours-item">
      <i class="fa-regular fa-clock" aria-hidden="true"></i>
      <div>
        <strong><?= e(t($hours['days'])) ?></strong>
        <span><?= e(t($hours['time'])) ?></span>
      </div>
    </div>
  <?php endforeach; ?>
</div>
