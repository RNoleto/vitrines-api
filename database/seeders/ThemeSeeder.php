<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela de temas do banco de dados
        Schema::disableForeignKeyConstraints();
        DB::table('themes')->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
