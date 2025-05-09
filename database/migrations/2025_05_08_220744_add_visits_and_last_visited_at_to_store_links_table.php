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
        Schema::table('store_links', function (Blueprint $table) {
            $table->unsignedBigInteger('visits')->default(0)->after('url');
            $table->timestamp('last_visited_at')->nullable()->after('visits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_links', function (Blueprint $table) {
            $table->droptable('visits');
            $table->droptable('last_visited_at');
        });
    }
};
