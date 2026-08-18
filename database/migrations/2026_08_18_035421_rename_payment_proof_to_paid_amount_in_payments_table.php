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
        Schema::table('payments', function (Blueprint $table) {
            // Ganti payment_proof (string/path file) menjadi paid_amount (nominal yang dibayarkan)
            // paid_amount digunakan untuk semua metode: cash, VA BTN, VA BCA
            $table->renameColumn('payment_proof', 'paid_amount');
        });

        // Ubah tipe kolom dari string ke decimal setelah rename
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('paid_amount', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('paid_amount')->nullable()->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('paid_amount', 'payment_proof');
        });
    }
};
