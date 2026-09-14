<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_bank_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source', 50);          // VA_INQUIRY | VA_NOTIFY | BTN_CALLBACK
            $table->string('level', 10);            // info | warning | error
            $table->string('event', 100);           // HIT | SUCCESS | BILL_NOT_FOUND | dst
            $table->string('va_number', 30)->nullable();
            $table->string('ip', 45)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('ref', 100)->nullable();
            $table->text('message')->nullable();
            $table->json('payload')->nullable();    // raw request / context
            $table->timestamps();

            $table->index(['source', 'created_at']);
            $table->index('va_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_bank_logs');
    }
};
