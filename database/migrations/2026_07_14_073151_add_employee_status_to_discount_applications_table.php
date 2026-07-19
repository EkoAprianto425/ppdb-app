<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('discount_applications', function (Blueprint $table) {
            // Status kepegawaian orang tua/wali, khusus untuk kategori anak_pegawai
            // Contoh: Guru Tetap, Guru Tidak Tetap, Staff Tetap, Staff Tidak Tetap, dll.
            $table->string('employee_status')->nullable()->after('discount_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discount_applications', function (Blueprint $table) {
            $table->dropColumn('employee_status');
        });
    }
};
