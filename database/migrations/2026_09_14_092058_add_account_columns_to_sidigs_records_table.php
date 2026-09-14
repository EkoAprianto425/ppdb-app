<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sidigs_records', function (Blueprint $table) {
            $table->string('student_username')->nullable()->after('status');
            $table->string('student_password')->nullable()->after('student_username');
            $table->string('wali_username')->nullable()->after('student_password');
            $table->string('wali_password')->nullable()->after('wali_username');
        });
    }

    public function down(): void
    {
        Schema::table('sidigs_records', function (Blueprint $table) {
            $table->dropColumn(['student_username', 'student_password', 'wali_username', 'wali_password']);
        });
    }
};
