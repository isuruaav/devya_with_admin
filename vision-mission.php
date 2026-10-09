<?php
declare(strict_types=1);
require __DIR__ . '/includes/site-config.php';
header('Location: ' . site_url('about-us.php'), true, 301);
exit;
