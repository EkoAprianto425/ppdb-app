<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum to include SMA Reguler & SMA Tahfidz, replacing plain SMA
        DB::statement("ALTER TABLE users MODIFY COLUMN tujuan_masuk ENUM('SMP', 'SMA Reguler', 'SMA Tahfidz', 'SMK TKJ', 'SMK PBS', 'SMK Kuliner') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN tujuan_masuk ENUM('SMP', 'SMA', 'SMK TKJ', 'SMK PBS', 'SMK Kuliner') NULL");
    }
};
