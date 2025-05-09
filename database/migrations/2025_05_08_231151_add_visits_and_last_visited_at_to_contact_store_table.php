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
        Schema::table('contact_store', function (Blueprint $table) {
            $table->unsignedInteger('visits')->default(0)->after('store_id');
            $table->timestamp('last_visited_at')->nullable()->after('visits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_store', function (Blueprint $table) {
            $table->dropColumn(['visits', 'last_visited_at']);
        });
    }
};
