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
            // Remove a coluna ativo se existir
            if (Schema::hasColumn('contact_store', 'ativo')) {
                $table->dropColumn('ativo');
            }
            
            // Adiciona deleted_at se não existir
            if (!Schema::hasColumn('contact_store', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_store', function (Blueprint $table) {
            $table->integer('ativo')->default(1);
            $table->dropColumn('deleted_at');
        });
    }
};
