# Website wording / වෙබ් අඩවියේ වචන වෙනස් කිරීම

The public website supports English (`en`) and Sinhala (`si`). The language switch keeps the current page, service query and page section. Your choice is remembered in a cookie. English is the default for a new visitor.

## සිංහල වචන වෙනස් කරන්නේ මෙහෙමයි

1. Local server එකේ `languages/si.json` file එක UTF-8 සහාය ඇති editor එකකින් විවෘත කරන්න.
2. වම් පැත්තේ ඇති English වචනයෙන් අවශ්‍ය කොටස සොයාගන්න.
3. `:` ලකුණට පසුව, දකුණු පැත්තේ තිබෙන සිංහල වචන පමණක් වෙනස් කරන්න.
4. Save කර සිංහල පිටුව refresh කරන්න. Build command එකක් අවශ්‍ය නැහැ.

```json
"Request Appointment": "වෛද්‍ය හමුවක් ඉල්ලන්න"
```

මෙහි `"Request Appointment"` යන වම් පැත්තේ key එක ඒ ලෙසම තබන්න. `"වෛද්‍ය හමුවක් ඉල්ලන්න"` යන දකුණු පැත්තේ වචන ඔබ කැමති ලෙස වෙනස් කළ හැකිය.

English wording වෙනස් කිරීමට `languages/en.json` file එකේ දකුණු පැත්තේ values වෙනස් කරන්න. English keys වෙනස් නොකරන්න.

## cPanel වෙත එක් කළ පසුව

cPanel → File Manager → `devyaceylon.lk/languages/si.json` → Edit.

වෙනස් කිරීමට පෙර file එකේ පිටපතක් තබාගන්න. File එක UTF-8 ලෙස save කරන්න. Double quotes, commas සහ braces නොමකා තබන්න. Value එකක් ඇතුළේ double quote අවශ්‍ය නම් `\"` යොදන්න.

`{time}`, `{phone}`, `{clinic}`, `{doctor}`, `{weekday_time}`, `{sunday_time}` සහ `{booking_number}` වැනි placeholders ඒ ලෙසම තබන්න. ඒ තැන්වලට අවශ්‍ය දත්ත website එකෙන් එකතු කරයි. HTML tags භාවිත නොකර සාමාන්‍ය වචන යොදන්න.

File එකේ JSON වැරැද්දක් තිබුණොත් website එක English වචන පෙන්වා PHP error log එකේ හේතුව සටහන් කරයි. Translation එකක් නැති විටද English වචනය පෙන්වයි.

## Local preview

- English: `http://localhost/devya_ceylon/index.php?lang=en`
- සිංහල: `http://localhost/devya_ceylon/index.php?lang=si`

Optional JSON validation from the website folder:

```sh
php -r 'json_decode(file_get_contents("languages/si.json"), true, 512, JSON_THROW_ON_ERROR); echo "Sinhala JSON OK\n";'
```

Booking service identifiers, form field names, phone numbers, links and appointment submission values remain in their existing format. Changing a translation changes the displayed wording. Doctor days and clinic details are configured in `includes/site-config.php`; their displayed translations are in the language files.
