<?php

declare(strict_types=1);

$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/devya/index.php'));
$basePath = in_array($scriptDirectory, ['/', '.'], true) ? '' : '/'.trim($scriptDirectory, '/');
if (
    preg_match('~\A(?:www\.)?devyaceylon\.lk(?::[0-9]+)?\z~i', $_SERVER['HTTP_HOST'] ?? '') === 1
    && (($_SERVER['HTTPS'] ?? 'off') !== 'on' || str_starts_with(strtolower($_SERVER['HTTP_HOST']), 'www.'))
) {
    header('Location: https://devyaceylon.lk'.$basePath.'/', true, 302);
    exit;
}

// Render the existing login here while retaining its public asset and Livewire URLs.
$_SERVER['SCRIPT_NAME'] = $basePath.'/public/index.php';
$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
$_SERVER['REQUEST_URI'] = $basePath.'/public/admin/login';

if (($_SERVER['QUERY_STRING'] ?? '') !== '') {
    $_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
}

require __DIR__.'/public/index.php';
