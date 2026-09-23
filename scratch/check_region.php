<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$reg = \App\Models\Registration::whereNotNull('provinsi')->first();
if ($reg) {
    echo json_encode([
        'provinsi'  => $reg->provinsi,
        'kabupaten' => $reg->kabupaten,
        'kecamatan' => $reg->kecamatan,
    ]);
} else {
    echo 'no data';
}
