<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach ($tables as $table) {
    if (is_object($table)) {
        $table = array_values((array)$table)[0];
    }
    $cols = \Illuminate\Support\Facades\Schema::getColumnListing($table);
    echo $table . ': ' . implode(', ', $cols) . PHP_EOL;
}
