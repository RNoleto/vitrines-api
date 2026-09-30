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
        Schema::create('discord_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('webhook_url');
            $table->string('channel_type')->default('general');
            $table->json('events')->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discord_webhooks');
    }
};
