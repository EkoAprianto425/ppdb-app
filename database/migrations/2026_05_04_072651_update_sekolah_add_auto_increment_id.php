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
        Schema::table('sekolah', function (Blueprint $table) {
            // Ubah nama kolom id yang lama (berisi string UUID) menjadi sekolah_id
            $table->renameColumn('id', 'sekolah_id');
        });

        Schema::table('sekolah', function (Blueprint $table) {
            // Tambahkan kolom id baru sebagai primary key auto increment
            $table->id()->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('sekolah', function (Blueprint $table) {
            $table->renameColumn('sekolah_id', 'id');
        });
    }
};
