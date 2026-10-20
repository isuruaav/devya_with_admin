<section class="section doctor-schedule-section" id="doctor-schedule" aria-labelledby="doctor-schedule-title">
  <div class="container">
    <div class="section-head schedule-heading">
      <span class="eyebrow"><i class="fa-solid fa-user-doctor" aria-hidden="true"></i> <?= e(t('Plan your visit')) ?></span>
      <h2 id="doctor-schedule-title"><?= e(t('Doctors Attending')) ?> <span><?= e(t('Weekly Schedule')) ?></span></h2>
      <p><?= e(t('Meet our Ayurveda doctors and find the special clinic that suits your needs.')) ?></p>
    </div>

    <div class="schedule-panel">
      <div class="schedule-hours">
        <h3><?= e(t('Consultation Time')) ?></h3>
        <?php require __DIR__ . '/consultation-hours.php'; ?>
      </div>

      <table class="doctor-schedule-table" role="table">
        <caption class="visually-hidden"><?= e(t('Doctors Attending - Weekly Schedule. Monday to Saturday: {weekday_time}. Sunday: {sunday_time}.', ['weekday_time' => t($consultationHours[0]['time']), 'sunday_time' => t($consultationHours[1]['time'])])) ?></caption>
        <thead role="rowgroup">
          <tr role="row">
            <th scope="col" role="columnheader"><?= e(t('Day')) ?></th>
            <th scope="col" role="columnheader"><?= e(t('Doctor')) ?></th>
            <th scope="col" role="columnheader"><?= e(t('Designation')) ?></th>
            <th scope="col" role="columnheader"><?= e(t('Special Clinic')) ?></th>
          </tr>
        </thead>
        <tbody role="rowgroup">
          <?php foreach ($doctorSchedule as $entry): ?>
            <tr role="row"<?= $entry['day'] === 'Sunday' ? ' class="schedule-sunday"' : '' ?>>
              <th scope="row" role="rowheader"><span class="schedule-day"><?= e(t($entry['day'])) ?></span></th>
              <td data-label="<?= e(t('Doctor')) ?>" role="cell"><strong class="schedule-doctor"><?= e(t($entry['doctor'])) ?></strong></td>
              <td data-label="<?= e(t('Designation')) ?>" role="cell"><?= e(t($entry['designation'])) ?></td>
              <td data-label="<?= e(t('Special Clinic')) ?>" role="cell"><a class="schedule-clinic-link" href="<?= e(site_url('appointments.php?' . http_build_query(['service' => $entry['clinic']]))) ?>" aria-label="<?= e(t('Request an appointment for {clinic} with {doctor}', ['clinic' => t($entry['clinic']), 'doctor' => t($entry['doctor'])])) ?>"><?= e(t($entry['clinic'])) ?><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="schedule-footer">
        <p><i class="fa-regular fa-calendar-check" aria-hidden="true"></i> <?= e(t('Appointments are recommended for special clinics.')) ?></p>
        <a class="button button-primary" href="<?= e(site_url('appointments.php')) ?>"><?= e(t('Request Appointment')) ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      </div>
    </div>
  </div>
</section>
