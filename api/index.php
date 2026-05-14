<?php
/**
 * Vercel Serverless Entry Point for Laravel
 * 
 * Vercel has a read-only filesystem, so we must redirect all
 * writable paths to /tmp BEFORE Laravel boots.
 */

// 1. Create required /tmp directories
$storagePath = '/tmp/storage';
$dirs = [
    "$storagePath/app/public",
    "$storagePath/framework/cache/data",
    "$storagePath/framework/sessions",
    "$storagePath/framework/testing",
    "$storagePath/framework/views",
    "$storagePath/logs",
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Set environment variables BEFORE autoload/bootstrap
// Using $_ENV and $_SERVER ensures Laravel picks them up
$envOverrides = [
    'APP_SERVICES_CACHE'  => "$storagePath/framework/cache/services.php",
    'APP_PACKAGES_CACHE'  => "$storagePath/framework/cache/packages.php",
    'APP_CONFIG_CACHE'    => "$storagePath/framework/cache/config.php",
    'APP_ROUTES_CACHE'    => "$storagePath/framework/cache/routes.php",
    'APP_EVENTS_CACHE'    => "$storagePath/framework/cache/events.php",
    'VIEW_COMPILED_PATH'  => "$storagePath/framework/views",
    'SESSION_DRIVER'      => 'cookie',
    'LOG_CHANNEL'         => 'stderr',
    'CACHE_STORE'         => 'array',
];

foreach ($envOverrides as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("$key=$value");
}

// 3. Boot Laravel
require __DIR__ . '/../public/index.php';
