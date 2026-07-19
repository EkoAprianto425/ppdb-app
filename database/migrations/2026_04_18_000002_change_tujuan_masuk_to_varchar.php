<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Change tujuan_masuk from ENUM to VARCHAR so values are driven
     * by the educational_levels table instead of being hardcoded.
     */
    public function up(): void
    {
        // Convert ENUM to VARCHAR(255) - preserves existing data
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN tujuan_masuk VARCHAR(255) NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN tujuan_masuk ENUM('SMP', 'SMA Reguler', 'SMA Tahfidz', 'SMK TKJ', 'SMK PBS', 'SMK Kuliner') NULL");
        }
    }
};
