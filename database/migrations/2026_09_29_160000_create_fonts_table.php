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
        Schema::create('fonts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('family_name');
            $table->string('display_name');
            $table->string('provider')->default('google');
            $table->text('import_url')->nullable();
            $table->string('category')->default('sans-serif');
            $table->integer('is_system')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fonts');
    }
};
