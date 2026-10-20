# SEO and favicon update

Production URL: https://devyaceylon.lk

## මේ update එකෙන් ලැබෙන දේ

- පිටු හය සඳහා English/Sinhala titles සහ meta descriptions.
- භාෂා දෙක සඳහා වෙන වෙනම canonical URLs සහ සම්පූර්ණ `hreflang` links.
- දින/වේලාවන්, ලිපිනය සහ දුරකථන අංක සහිත MedicalClinic structured data, WebSite/WebPage සහ breadcrumbs.
- Facebook/WhatsApp සහ වෙනත් social previews සඳහා 1200 × 630 image එකක් සහ Open Graph/Twitter metadata.
- Browser tab සඳහා favicon.ico සහ 96 × 96 PNG; Apple home-screen සඳහා 180 × 180 icon.
- භාෂා දෙකේ public URLs 12ක් සහිත sitemap.xml සහ robots.txt.
- මුල් logo එකෙන් සකස් කළ සැහැල්ලු header/footer logo එකක්.

Local/staging host එකේ pages `noindex` ලෙස පෙන්වයි. `devyaceylon.lk` හෝ `www.devyaceylon.lk` host එකේ pages index කළ හැකි ලෙස පෙන්වයි. Canonical URLs සෑම විටම HTTPS සහ www නැති domain එක භාවිත කරයි.

## Local preview

- English: http://localhost/devya_ceylon/index.php?lang=en
- සිංහල: http://localhost/devya_ceylon/index.php?lang=si
- Favicon: http://localhost/devya_ceylon/favicon-96x96.png
- Sitemap: http://localhost/devya_ceylon/sitemap.xml

## cPanel deployment

1. `devya-seo-update-2026-10-20.zip` file එක `/home/isdtech1/devyaceylon.lk/` වෙත upload කරන්න.
2. Terminal එකේ පහත commands run කරන්න. මුලින් public website එකේ backup එකක් Home folder එක යටතේ සුරැකේ.

```bash
cd "$HOME/devyaceylon.lk" &&
mkdir -p "$HOME/devya-backups" &&
tar --exclude='./devya' --exclude='./.git' --exclude='./*.zip' \
  -czf "$HOME/devya-backups/before-seo-$(date +%Y%m%d-%H%M%S).tar.gz" . &&
unzip -o devya-seo-update-2026-10-20.zip
```

මෙම ZIP එක SEO update එක සඳහාය. කලින් bilingual update එක install කර තිබිය යුතුය. `languages/en.json`, `languages/si.json`, database සහ Laravel admin files ZIP එකට ඇතුළත් නොවේ. Existing cPanel `.htaccess` settings මේ update එකෙන් replace නොවේ.

## Upload කළ පසු

1. Website එක Ctrl + F5 කර බලන්න. Tab icon එක තවම පරණ නම් tab එක වසා නැවත අරින්න හෝ private window එකකින් බලන්න.
2. `https://devyaceylon.lk/favicon-96x96.png` සහ `https://devyaceylon.lk/sitemap.xml` විවෘත කර files load වෙන බව බලන්න.
3. Google Search Console හි verified domain property එකට `https://devyaceylon.lk/sitemap.xml` submit කරන්න. Property එක verified නැති නම් Google ලබාදෙන DNS TXT record එක cPanel Zone Editor එකට එක් කර verify කරන්න.
4. Search Console URL Inspection මඟින් home page එක පරීක්ෂා කර indexing ඉල්ලන්න. සෑම භාෂාවක්ම තමන්ගේ canonical URL එක භාවිත කරන බව පරීක්ෂා කරන්න.
5. Google Rich Results Test මඟින් live page එක පරීක්ෂා කරන්න. Favicon සහ search-result titles යාවත්කාලීන වීමට Google නැවත crawl කළ යුතුය; ranking හෝ විශේෂ search display එකක් ස්වයංක්‍රීයව සහතික නොවේ.

## SEO wording පසුව වෙනස් කිරීම

`seo.json` හි අදාළ page එකේ `en` හෝ `si` කොටසේ `title` සහ `description` values වෙනස් කරන්න. සාමාන්‍ය website wording සඳහා පෙර පරිදි `languages/en.json` හා `languages/si.json` භාවිත කරන්න. JSON quotes සහ commas රැකගන්න. `seo.json` වැරදි නම් මුල් translated page titles/descriptions වෙත fallback වේ.

Business URL, address සහ phone numbers `includes/site-config.php` හි ඇත. Consultation hours වෙනස් කළොත් එහි display `time`, `schema_days`, `opens` සහ `closes` values ද ගැළපෙන ලෙස වෙනස් කරන්න.

පසුව pages හෝ domain එක වෙනස් කළොත් local server එකේ `php tools/build-seo-assets.php` run කර sitemap/robots හා generated image assets නැවත සකස් කරන්න. මෙම build script එකට GD සහ TrueType fonts අවශ්‍යය. cPanel වෙත සකස් කර අවසන් files upload කිරීම ප්‍රමාණවත්ය.

## References

- [Google: localized versions and hreflang](https://developers.google.com/search/docs/specialty/international/localized-versions)
- [Google: favicons](https://developers.google.com/search/docs/appearance/favicon-in-search)
- [Google: build and submit a sitemap](https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap)
- [Google: local business structured data](https://developers.google.com/search/docs/appearance/structured-data/local-business)
