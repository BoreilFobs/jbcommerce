<?php
/**
 * Icon Server - Workaround for Apache routing issue
 * Usage: /serve-icon.php?file=icon-192x192.png
 */

$file = $_GET['file'] ?? '';

// Security: only allow specific icon files
$allowedFiles = [
    'icon-72x72.png',
    'icon-96x96.png',
    'icon-128x128.png',
    'icon-144x144.png',
    'icon-152x152.png',
    'icon-192x192.png',
    'icon-192x192-maskable.png',
    'icon-384x384.png',
    'icon-512x512.png',
    'icon-512x512-maskable.png',
    'apple-touch-icon.png',
    'apple-touch-icon-120x120.png',
    'apple-touch-icon-152x152.png',
    'apple-touch-icon-167x167.png',
    'apple-touch-icon-180x180.png',
    'favicon-16x16.png',
    'favicon-32x32.png',
    'favicon-48x48.png',
    'og-image.png',
    'splash-640x640.png',
    'splash-750x750.png',
    'splash-828x828.png',
    'splash-1242x1242.png',
];

if (!in_array($file, $allowedFiles)) {
    http_response_code(404);
    exit('Not found');
}

$path = __DIR__ . '/icons/' . $file;

if (!file_exists($path)) {
    http_response_code(404);
    exit('File not found');
}

// Serve the file
header('Content-Type: image/png');
header('Content-Length: ' . filesize($path));
header('Cache-Control: public, max-age=31536000');
readfile($path);
