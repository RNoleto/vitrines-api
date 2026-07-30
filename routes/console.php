<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:truncate', function () {
    if (!app()->environment('local', 'testing')) {
        $this->error('⚠️ Este comando só pode ser executado em ambiente local ou de teste!');
        return 1;
    }

    $this->warn('🧹 Limpando dados de todas as tabelas...');

    Schema::disableForeignKeyConstraints();

    $connection = config('database.default');

    if ($connection === 'sqlite') {
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        foreach ($tables as $table) {
            $name = $table->name;
            if ($name === 'migrations') continue;
            DB::table($name)->truncate();
            $this->line("  ✓ Tabela '{$name}' limpa.");
        }
    } else {
        // PostgreSQL / MySQL
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE'");
        foreach ($tables as $table) {
            $name = $table->table_name;
            if ($name === 'migrations') continue;
            DB::table($name)->truncate();
            $this->line("  ✓ Tabela '{$name}' limpa.");
        }
    }

    Schema::enableForeignKeyConstraints();

    $this->info('✅ Base de dados limpa com sucesso!');
})->purpose('Limpa todas as tabelas da base de dados sem excluir a estrutura (exceto a tabela migrations)');
