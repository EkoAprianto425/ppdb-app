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
            $table->string('payment_proof')->nullable()->after('penghasilan_ibu');
            $table->string('payment_status')->default('pending')->after('payment_proof');
            $table->foreignId('exam_schedule_id')->nullable()->after('payment_status')->constrained('exam_schedules')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['exam_schedule_id']);
            $table->dropColumn(['payment_proof', 'payment_status', 'exam_schedule_id']);
        });
    }
};
