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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // anak_pegawai, alumni, umum
            $table->string('name')->nullable(); // general name/criteria/jabatan
            $table->foreignId('educational_level_id')->constrained()->onDelete('cascade');
            $table->foreignId('registration_wave_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2)->default(0); // discount amount or biaya masuk
            $table->decimal('spp_amount', 15, 2)->nullable(); // specific for anak pegawai
            $table->integer('qty')->nullable();
            $table->text('description')->nullable();
            $table->string('apply_to')->nullable(); // alumni, umum
            $table->boolean('require_document')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
