<?php
/**
 * TEMPORARY - Delete this file after use!
 * Upload ke: public_html/ppdb-app/public/clear-cache.php
 * Akses via browser: https://dev.spmbalhasra.com/clear-cache.php?token=mySecretToken123
 */

// Security: simple token check
if (!isset($_GET['token']) || $_GET['token'] !== 'mySecretToken123') {
    http_response_code(403);
    die('Forbidden');
}

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$artisan = $app->make('Illuminate\Contracts\Console\Kernel');

$commands = [
    'route:clear',
    'config:clear',
    'view:clear',
    'cache:clear',
    'route:cache',
    'config:cache',
];

header('Content-Type: text/plain');
echo "=== Laravel Cache Clear & Rebuild ===\n\n";

foreach ($commands as $cmd) {
    ob_start();
    $exitCode = $artisan->call($cmd);
    $output = ob_get_clean();
    $status = $exitCode === 0 ? 'OK' : 'FAIL';
    echo "[$status] php artisan $cmd\n";
    if (trim($output)) echo "      " . trim($output) . "\n";
    echo "\n";
}

echo "Done! DELETE this file immediately after use.\n";
