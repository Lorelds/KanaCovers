<?php
/**
 * Forward Vercel requests to normal Laravel public/index.php
 * and override storage paths to /tmp since Vercel is read-only
 */

$compiled = '/tmp/storage/framework/views';
if (!is_dir($compiled)) {
    mkdir($compiled, 0755, true);
}
putenv("VIEW_COMPILED_PATH={$compiled}");

putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');

require __DIR__ . '/../public/index.php';
