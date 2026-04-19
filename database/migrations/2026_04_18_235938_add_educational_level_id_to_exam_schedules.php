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
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->foreignId('educational_level_id')->nullable()->after('academic_year_id')->constrained()->nullOnDelete();
        });

        // Migrate data
        $schedules = DB::table('exam_schedules')->get();
        foreach ($schedules as $schedule) {
            $levelId = DB::table('educational_levels')->where('name', $schedule->unit)->value('id');
            if ($levelId) {
                DB::table('exam_schedules')->where('id', $schedule->id)->update(['educational_level_id' => $levelId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropForeign(['educational_level_id']);
            $table->dropColumn('educational_level_id');
        });
    }
};
