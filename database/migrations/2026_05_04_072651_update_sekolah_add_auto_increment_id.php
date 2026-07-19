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
        if (!Schema::hasTable('sekolah')) {
            Schema::create('sekolah', function (Blueprint $table) {
                $table->id(); // auto-incrementing ID
                $table->string('sekolah_id')->nullable();
                $table->string('kode_prop')->nullable();
                $table->string('propinsi')->nullable();
                $table->string('kode_kab_kota')->nullable();
                $table->string('kabupaten_kota')->nullable();
                $table->string('kode_kec')->nullable();
                $table->string('kecamatan')->nullable();
                $table->string('npsn')->nullable();
                $table->string('sekolah')->nullable();
                $table->string('bentuk')->nullable();
                $table->string('status')->nullable();
                $table->string('alamat_jalan')->nullable();
                $table->string('lintang')->nullable();
                $table->string('bujur')->nullable();
                $table->timestamps();
            });
        } else if (DB::getDriverName() !== 'sqlite') {
            Schema::table('sekolah', function (Blueprint $table) {
                // Ubah nama kolom id yang lama (berisi string UUID) menjadi sekolah_id
                $table->renameColumn('id', 'sekolah_id');
            });

            Schema::table('sekolah', function (Blueprint $table) {
                // Tambahkan kolom id baru sebagai primary key auto increment
                $table->id()->first();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sekolah')) {
            Schema::table('sekolah', function (Blueprint $table) {
                $table->dropColumn('id');
            });

            Schema::table('sekolah', function (Blueprint $table) {
                $table->renameColumn('sekolah_id', 'id');
            });
        }
    }
};
