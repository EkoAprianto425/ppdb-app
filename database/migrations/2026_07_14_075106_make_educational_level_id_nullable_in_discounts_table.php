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
        Schema::table('discounts', function (Blueprint $table) {
            // Anak pegawai berlaku lintas jenjang, sehingga tidak perlu educational_level_id
            $table->unsignedBigInteger('educational_level_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('educational_level_id')->nullable(false)->change();
        });
    }
};
