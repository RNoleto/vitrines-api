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
        Schema::create('themes', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('label');
            $table->integer('is_custom')->default(0);
            $table->integer('is_premium')->default(0);
            $table->string('category')->default('standard');
            $table->string('font_family')->default('sans');
            $table->string('icon_family')->nullable()->default('fontawesome-6');
            $table->string('layout_style')->default('standard');
            $table->string('card_style')->default('flat');
            $table->string('bg_type')->default('solid');
            $table->text('bg_image_url')->nullable();
            $table->string('bg_attachment')->nullable()->default('scroll');
            $table->string('bg_size')->nullable()->default('cover');
            $table->string('bg_position')->nullable()->default('center');
            $table->string('bg_animation_type')->nullable();
            $table->json('bg_overlay')->nullable();
            $table->json('colors')->nullable();
            $table->integer('backdrop_blur')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
