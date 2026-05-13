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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('qr_token', 64);
            $table->string('scanner_gate');
            $table->enum('scan_status', ['success', 'duplicate', 'invalid'])->default('invalid');
            $table->string('message')->nullable();
            $table->timestamp('scanned_at');
            $table->timestamps();

            $table->index('qr_token');
            $table->index('scanner_gate');
            $table->index('scan_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
