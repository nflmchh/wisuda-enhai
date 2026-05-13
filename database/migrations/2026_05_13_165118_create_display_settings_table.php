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
        Schema::create('display_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gate')->default('1');
            $table->string('background_image')->nullable();
            $table->integer('name_x_position')->default(50);
            $table->integer('name_y_position')->default(45);
            $table->integer('nrp_x_position')->default(50);
            $table->integer('nrp_y_position')->default(55);
            $table->integer('name_font_size')->default(72);
            $table->integer('nrp_font_size')->default(48);
            $table->string('font_color')->default('#FFFFFF');
            $table->boolean('show_category')->default(true);
            $table->boolean('show_time')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('display_settings');
    }
};
