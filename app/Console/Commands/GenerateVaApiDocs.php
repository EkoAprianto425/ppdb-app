<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateVaApiDocs extends Command
{
    protected $signature   = 'api:generate-docs {--output=}';
    protected $description = 'Generate PDF dokumentasi API Virtual Account';

    public function handle(): int
    {
        $this->info('Generating VA API Documentation PDF...');

        // Render HTML dari Blade view
        $html = view('api-docs.va-api-docs')->render();

        // Konfigurasi dompdf
        $pdf = Pdf::loadHTML($html)
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'DejaVu Sans',
                'dpi'                  => 150,
                'debugKeepTemp'        => false,
                'chroot'               => base_path(),
            ]);

        // Tentukan output path
        $outputDir  = storage_path('app/api-docs');
        $outputFile = $this->option('output')
            ?: $outputDir . '/VA-API-Documentation-v1.0.0-' . date('Ymd') . '.pdf';

        // Pastikan direktori tersedia
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Simpan PDF
        $pdf->save($outputFile);

        $this->newLine();
        $this->info('✅ PDF berhasil dibuat!');
        $this->line('   <fg=cyan>Path:</> ' . $outputFile);
        $this->line('   <fg=cyan>Size:</> ' . number_format(filesize($outputFile) / 1024, 1) . ' KB');
        $this->newLine();

        return self::SUCCESS;
    }
}
