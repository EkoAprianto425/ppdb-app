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
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('set null')->after('id');
            $table->foreignId('registration_wave_id')->nullable()->constrained()->onDelete('set null')->after('academic_year_id');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['registration_wave_id']);
            $table->dropColumn(['academic_year_id', 'registration_wave_id']);
        });
    }
};
