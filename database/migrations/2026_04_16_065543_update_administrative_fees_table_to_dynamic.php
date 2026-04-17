<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('administrative_fees', function (Blueprint $table) {
            // Bersihkan data lama karena struktur berubah total
            DB::table('administrative_fees')->truncate();

            // Hapus kolom lama jika ada
            if (Schema::hasColumn('administrative_fees', 'unit')) {
                $table->dropColumn(['unit', 'type', 'is_active']);
            }
            
            // Tambahkan kolom baru (cek dulu kalau-kalau sudah ada dari percobaan sebelumnya yang gagal)
            if (!Schema::hasColumn('administrative_fees', 'educational_level_id')) {
                $table->foreignId('educational_level_id')->after('id')->constrained('educational_levels')->cascadeOnDelete();
            }
            
            if (!Schema::hasColumn('administrative_fees', 'name')) {
                $table->string('name')->after('educational_level_id'); // Nama Administrasi
            }

            if (!Schema::hasColumn('administrative_fees', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('administrative_fees', function (Blueprint $table) {
            $table->dropForeign(['educational_level_id']);
            $table->dropColumn(['educational_level_id', 'name', 'sort_order']);
            $table->string('unit');
            $table->string('type');
            $table->boolean('is_active')->default(true);
        });
    }
};
