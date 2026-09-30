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
        if (Schema::hasColumn('stores', 'theme')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('theme');
            });
        }

        if (!Schema::hasColumn('stores', 'ref_cod_theme')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->unsignedBigInteger('ref_cod_theme')->nullable()->after('ativo');
            });
        }

        // Adiciona a foreign key com nullOnDelete na coluna ref_cod_theme
        Schema::table('stores', function (Blueprint $table) {
            $table->foreign('ref_cod_theme')
                  ->references('id')
                  ->on('themes')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['ref_cod_theme']);
        });
    }
};
