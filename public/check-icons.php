<?php
/**
 * Icon Debug Script
 * Upload to production and access via https://jbshop237.com/check-icons.php
 */

header('Content-Type: text/plain');

echo "=== PWA Icon Debug ===\n\n";

$publicPath = __DIR__;
$iconsPath = $publicPath . '/icons';

echo "1. Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "2. Current Script: " . __FILE__ . "\n";
echo "3. Public Path: " . $publicPath . "\n";
echo "4. Icons Path: " . $iconsPath . "\n\n";

echo "=== Directory Check ===\n";
echo "Icons directory exists: " . (is_dir($iconsPath) ? 'YES' : 'NO') . "\n";
echo "Icons directory readable: " . (is_readable($iconsPath) ? 'YES' : 'NO') . "\n";

if (is_dir($iconsPath)) {
    echo "\n=== Icon Files ===\n";
    $files = scandir($iconsPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $fullPath = $iconsPath . '/' . $file;
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        $readable = is_readable($fullPath) ? 'readable' : 'NOT readable';
        $size = filesize($fullPath);
        echo "  - $file (perms: $perms, size: $size bytes, $readable)\n";
    }
    
    // Check specific files
    echo "\n=== Specific Icon Check ===\n";
    $checkFiles = [
        'icon-192x192.png',
        'icon-512x512.png',
        'favicon-32x32.png'
    ];
    
    foreach ($checkFiles as $file) {
        $fullPath = $iconsPath . '/' . $file;
        echo "\n$file:\n";
        echo "  Full path: $fullPath\n";
        echo "  Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
        echo "  Is file: " . (is_file($fullPath) ? 'YES' : 'NO') . "\n";
        echo "  Readable: " . (is_readable($fullPath) ? 'YES' : 'NO') . "\n";
        if (file_exists($fullPath)) {
            echo "  Size: " . filesize($fullPath) . " bytes\n";
            echo "  Mime: " . mime_content_type($fullPath) . "\n";
        }
    }
} else {
    echo "\n!!! Icons directory does not exist !!!\n";
    
    // Check what's in public directory
    echo "\n=== Public Directory Contents ===\n";
    $files = scandir($publicPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $type = is_dir($publicPath . '/' . $file) ? '[DIR]' : '[FILE]';
        echo "  $type $file\n";
    }
}

// Check Apache permissions
echo "\n=== Server Info ===\n";
echo "PHP User: " . get_current_user() . "\n";
echo "PHP Process User: " . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : 'N/A') . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "\n";

// Ownership check
if (function_exists('posix_getpwuid') && is_dir($iconsPath)) {
    $stat = stat($iconsPath);
    $owner = posix_getpwuid($stat['uid']);
    $group = posix_getgrgid($stat['gid']);
    echo "Icons dir owner: " . ($owner['name'] ?? $stat['uid']) . "\n";
    echo "Icons dir group: " . ($group['name'] ?? $stat['gid']) . "\n";
}

echo "\n=== Manifest Check ===\n";
$manifestPath = $publicPath . '/manifest.json';
echo "Manifest exists: " . (file_exists($manifestPath) ? 'YES' : 'NO') . "\n";
echo "Manifest readable: " . (is_readable($manifestPath) ? 'YES' : 'NO') . "\n";

echo "\n=== Done ===\n";
