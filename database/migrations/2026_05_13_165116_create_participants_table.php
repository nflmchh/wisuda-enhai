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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nrp')->unique();
            $table->string('email');
            $table->string('faculty')->nullable();
            $table->string('program_study')->nullable();
            $table->enum('category', ['Wisudawan', 'Orang Tua', 'Tamu Lainnya'])->default('Wisudawan');
            $table->string('qr_token', 64)->unique();
            $table->string('qr_code_path')->nullable();
            $table->string('barcode_path')->nullable();
            $table->enum('attendance_status', ['belum_hadir', 'hadir'])->default('belum_hadir');
            $table->timestamp('attended_at')->nullable();
            $table->string('scanner_gate')->nullable();
            $table->timestamps();

            $table->index('qr_token');
            $table->index('attendance_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
