<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('name');
            $table->string('whatsapp_number')->nullable()->after('email');
            $table->string('asal_sekolah')->nullable();
            $table->enum('tujuan_masuk', ['SMP', 'SMA', 'SMK TKJ', 'SMK PBS', 'SMK Kuliner'])->nullable();
            $table->text('alasan_memilih')->nullable();
            $table->string('sumber_informasi')->nullable();
            $table->string('role')->default('siswa');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'whatsapp_number',
                'asal_sekolah',
                'tujuan_masuk',
                'alasan_memilih',
                'sumber_informasi',
                'role',
            ]);
        });
    }
};
