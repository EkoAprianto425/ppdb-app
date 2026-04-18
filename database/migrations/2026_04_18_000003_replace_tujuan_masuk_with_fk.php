<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Replace tujuan_masuk (string) with educational_level_id (FK)
     * so the data is properly joined with the educational_levels table.
     * If the level name changes, all users automatically reflect the update.
     */
    public function up(): void
    {
        // 1. Add FK column
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('educational_level_id')
                  ->nullable()
                  ->after('asal_sekolah')
                  ->constrained('educational_levels')
                  ->nullOnDelete();
        });

        // 2. Populate from existing tujuan_masuk data
        DB::statement("
            UPDATE users u
            INNER JOIN educational_levels el ON el.name = u.tujuan_masuk
            SET u.educational_level_id = el.id
            WHERE u.tujuan_masuk IS NOT NULL
        ");

        // 3. Drop old column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tujuan_masuk');
        });
    }

    public function down(): void
    {
        // 1. Re-add tujuan_masuk column
        Schema::table('users', function (Blueprint $table) {
            $table->string('tujuan_masuk')->nullable()->after('asal_sekolah');
        });

        // 2. Populate back from FK
        DB::statement("
            UPDATE users u
            INNER JOIN educational_levels el ON el.id = u.educational_level_id
            SET u.tujuan_masuk = el.name
        ");

        // 3. Drop FK column
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['educational_level_id']);
            $table->dropColumn('educational_level_id');
        });
    }
};
