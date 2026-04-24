<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InformationSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            'Teman / Keluarga',
            'Instagram',
            'Facebook',
            'TikTok',
            'YouTube',
            'Website Sekolah',
            'Brosur / Pamflet',
            'Lainnya'
        ];

        foreach ($sources as $source) {
            \App\Models\InformationSource::create(['name' => $source]);
        }
    }
}
