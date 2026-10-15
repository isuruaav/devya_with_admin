# AYU → DEVYA Merge Review (14 October 2026)

## තීරණය
**`devya` = ප්‍රධාන / stable base**. එහි Laravel 13.30.1, Filament 5.8.3, Spatie Permission 8.3.0, confidential document security, role-based access සහ current user flow එලෙසම තබා ඇත. `ayu-system` හි Filament 5.7.8 හා පැරණි role system එකෙන් ඒවා overwrite කර නැත.

## Merge කර ඇති දේ
1. Consultation prescription සඳහා **Breakfast / Lunch / Dinner** මාත්‍රා.
2. එක් වේලාවකට **Before meal / After meal** තේරීම.
3. **Duration (Days)** සහ `(උදේ+දවල්+රෑ) × දින ගණන` අනුව **Total Quantity** ස්වයංක්‍රීය ගණනය.
4. Consultation medicine database එකට අවශ්‍ය column හත එක් කරන **අලුත් migration** (historical migrations වෙනස් නොකර).
5. Model helper, attribute casts, සහ PDF prescription එකට readable meal-wise directions.
6. පරණ prescriptions සඳහා free-text dosage හා අතින් ඇතුළත් කරන quantity ඉවත් කර නැත; meal schedule යොදාගත් විට පමණක් quantity read-only හා auto-calculated වේ.
7. Consultation form හි **Clinical Notes** label.
8. Medicine එක database එකෙන් ඉවත් වී ඇති අවස්ථාවල billing / patient consultations UI එකේ null-safe lookups.
9. Dosage form/model/migration unit and feature tests; existing DEVYA tests නොවෙනස්ව පවතී.

## විමර්ශනයෙන් හමුවූ අවදානම් නිසා overwrite නොකළ දේ
- **Roles & permissions**: AYU uses legacy `users.role`; DEVYA uses Spatie roles and permission checks. Replacing these would reduce access controls.
- **Doctor/NIC sensitive documents**: AYU changes some uploads to public storage. DEVYA confidential/private download design retained.
- **StaffAttendance model defect**: AYU `app/Models/StaffAttendance.php` incorrectly declares `App\Filament\Pages\StaffAttendance` instead of the Eloquent model. This was not copied.
- **Website appointments & login/doctor routing**: DEVYA has newer doctor resolution, website-request handling and login safeguards. Reverted AYU logic was not copied.
- **CSS and pages**: AYU medical history, dashboard, login, pharmacy, billing etc. UI differs, but replacing them directly drops DEVYA styling/responsiveness and parts of its current functionality. These visual differences are intentionally **not merged**.
- **Composer dependency files, .env and migrations already in DEVYA** have not been replaced or reset.

**Scope:** This is a conservative functional integration, **not a blanket merge of all 142 changed files**. Do not interpret skipped AYU files as approved replacements. If a particular AYU visual module is desired, it needs a component-by-component port onto DEVYA's current access system.

## Validation performed in isolated extracted DEVYA source
- PHP syntax verification of **282** app/database/test PHP files: **all passed**.
- Laravel framework boots, `route:list --json`: **119 routes**, including `admin/login`.
- Filament ConsultationForm schema smoke check: meal fields, directions field and Clinical Notes label **passed**.
- Laravel model / actual Blade prescription HTML smoke check: calculation, Before/After, legacy text, rendered quantity **passed**.
- Full PHPUnit suite **not run**: the inspection environment has no PHP `dom`, `mbstring`, `xmlwriter` or SQLite extensions. `view:cache` console command also could not complete due missing DOMDocument in that environment. **These are environment limitations, not proof of fully passing tests.**

## යෙදවීමට පෙර අත්‍යවශ්‍ය දේ
- Real **database backup** (phpMyAdmin export), project backup, uploaded media backup.
- Development / staging environment තුළ මේ update සහ real database schema upgrade පරීක්ෂා කරන්න.
- User's current `.env`, `storage/`, `vendor/`, `composer.lock`, `config/access.php`, `config/permission.php` කිසිවක් patch මඟින් overwrite නොකෙරේ.
- `php artisan migrate:fresh`, `db:wipe`, `db:seed --force` වැනි data-destroying commands භාවිත නොකරන්න.
- Pharmacy stock issue/inventory and clinical quantities අතින් regression-test කරන්න.
