<?php

declare(strict_types=1);

// Run locally with: php tools/build-seo-assets.php
// Builds deterministic favicon, logo and share-card variants from the existing logo.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$root = dirname(__DIR__);
require $root . '/includes/site-config.php';
require $root . '/includes/seo.php';

if (!extension_loaded('gd')) {
    throw new RuntimeException('The GD extension is required to rebuild image assets.');
}

// GD discards the original PNG's invalid embedded colour profile; pixels and alpha are retained.
$logo = @imagecreatefrompng($root . '/assets/images/logo.png');
if ($logo === false) {
    throw new RuntimeException('Unable to read the existing logo.');
}

function square_logo(GdImage $source, int $size): GdImage
{
    $image = imagecreatetruecolor($size, $size);
    imagealphablending($image, false);
    imagesavealpha($image, true);
    imagefill($image, 0, 0, imagecolorallocatealpha($image, 255, 255, 255, 127));
    $scale = min($size / imagesx($source), $size / imagesy($source));
    $width = (int) round(imagesx($source) * $scale);
    $height = (int) round(imagesy($source) * $scale);
    imagecopyresampled($image, $source, (int) (($size - $width) / 2), (int) (($size - $height) / 2), 0, 0, $width, $height, imagesx($source), imagesy($source));
    return $image;
}

imagepng(square_logo($logo, 96), $root . '/favicon-96x96.png', 9);
imagepng(square_logo($logo, 180), $root . '/apple-touch-icon.png', 9);
imagewebp(square_logo($logo, 192), $root . '/assets/images/brand-logo.webp', 88);

$sizes = [16, 32, 48, 64, 128, 256];
$offset = 6 + 16 * count($sizes);
$directory = '';
$payload = '';
foreach ($sizes as $size) {
    ob_start();
    imagepng(square_logo($logo, $size), null, 9);
    $png = (string) ob_get_clean();
    $dimension = $size === 256 ? 0 : $size;
    $directory .= pack('CCCCvvVV', $dimension, $dimension, 0, 0, 1, 32, strlen($png), $offset);
    $payload .= $png;
    $offset += strlen($png);
}
file_put_contents($root . '/favicon.ico', pack('vvv', 0, 1, count($sizes)) . $directory . $payload);

function available_font(array $candidates): string
{
    foreach ($candidates as $font) {
        if (is_readable($font)) {
            return $font;
        }
    }
    throw new RuntimeException('A TrueType font is required to rebuild the share card.');
}

$headingFont = available_font(['C:/Windows/Fonts/georgiab.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSerif-Bold.ttf']);
$bodyFont = available_font(['C:/Windows/Fonts/segoeui.ttf', '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf']);
$card = imagecreatetruecolor(1200, 630);
$forest = imagecolorallocate($card, 13, 56, 37);
$deepForest = imagecolorallocate($card, 10, 33, 23);
$gold = imagecolorallocate($card, 244, 213, 141);
$cream = imagecolorallocate($card, 255, 250, 240);
$muted = imagecolorallocate($card, 214, 229, 211);
imagefill($card, 0, 0, $deepForest);
imagefilledrectangle($card, 0, 0, 14, 630, $gold);
imagefilledellipse($card, 210, 315, 640, 800, $forest);
imagefilledellipse($card, 220, 315, 334, 334, $gold);
imagefilledellipse($card, 220, 315, 328, 328, $cream);
$shareLogo = square_logo($logo, 300);
imagealphablending($card, true);
imagecopy($card, $shareLogo, 70, 165, 0, 0, 300, 300);
imagettftext($card, 39, 0, 430, 237, $cream, $headingFont, 'DEVYA CEYLON');
imagettftext($card, 31, 0, 432, 300, $cream, $bodyFont, 'Ayurveda Centre');
imagettftext($card, 23, 0, 432, 352, $muted, $bodyFont, 'Kandy, Sri Lanka');
imagefilledrectangle($card, 432, 392, 1090, 393, $gold);
imagettftext($card, 23, 0, 432, 440, $gold, $bodyFont, 'Empowering Well-being');
imagettftext($card, 19, 0, 432, 486, $muted, $bodyFont, 'Consultations by appointment');
imagejpeg($card, $root . '/assets/images/social-preview.jpg', 88);

$xml = new XMLWriter();
$xml->openMemory();
$xml->setIndent(true);
$xml->startDocument('1.0', 'UTF-8');
$xml->startElement('urlset');
$xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
foreach (seo_pages() as $page => $details) {
    foreach (['en', 'si'] as $locale) {
        $xml->startElement('url');
        $xml->writeElement('loc', seo_page_url($page, $locale));
        foreach (['en' => 'en', 'si' => 'si', 'x-default' => 'en'] as $hreflang => $targetLocale) {
            $xml->startElement('xhtml:link');
            $xml->writeAttribute('rel', 'alternate');
            $xml->writeAttribute('hreflang', $hreflang);
            $xml->writeAttribute('href', seo_page_url($page, $targetLocale));
            $xml->endElement();
        }
        $xml->endElement();
    }
}
$xml->endElement();
$xml->endDocument();
file_put_contents($root . '/sitemap.xml', $xml->outputMemory());

$robots = "User-agent: *\nAllow: /\nDisallow: /devya/\nDisallow: /includes/\nDisallow: /languages/\nDisallow: /tools/\nDisallow: /seo.json\nDisallow: /SEO-DEPLOY.md\nDisallow: /*.zip$\n\nSitemap: " . seo_absolute_url('sitemap.xml') . "\n";
file_put_contents($root . '/robots.txt', $robots);

echo json_encode([
    'favicon_sizes' => $sizes,
    'png_favicon' => '96x96',
    'apple_touch_icon' => '180x180',
    'brand_logo' => '192x192',
    'share_card' => '1200x630',
    'sitemap_urls' => count(seo_pages()) * 2,
    'original_logo_bytes' => filesize($root . '/assets/images/logo.png'),
    'header_logo_bytes' => filesize($root . '/assets/images/brand-logo.webp'),
], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . PHP_EOL;
