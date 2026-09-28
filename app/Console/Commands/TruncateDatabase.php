<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all application database tables (except migrations) and restart identities.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->environment('production') && !$this->option('force')) {
            $this->error('Running this in production requires the --force option.');
            return 1;
        }

        if (!$this->confirm('Are you sure you want to truncate the database? This will delete all users, stores, links and contacts!')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->info('Truncating tables...');

        $tables = [
            'store_links',
            'contact_store',
            'stores',
            'contacts',
            'users',
            'sessions',
            'jobs',
            'failed_jobs',
            'job_batches',
            'cache',
            'cache_locks',
            'password_reset_tokens'
        ];

        DB::beginTransaction();
        try {
            $driver = DB::connection()->getDriverName();

            if ($driver === 'pgsql') {
                // PostgreSQL: Truncate tables with quotes, cascade and restart sequences
                $quotedTables = array_map(fn($t) => '"' . $t . '"', $tables);
                $tablesStr = implode(', ', $quotedTables);
                DB::statement('TRUNCATE TABLE ' . $tablesStr . ' RESTART IDENTITY CASCADE;');
            } elseif ($driver === 'sqlite') {
                // SQLite: Disable foreign keys, truncate tables, reset sequences, re-enable
                DB::statement('PRAGMA foreign_keys = OFF;');
                foreach ($tables as $table) {
                    DB::table($table)->truncate();
                }
                DB::statement('PRAGMA foreign_keys = ON;');
            } else {
                // MySQL/other: Disable foreign key checks, truncate, re-enable
                Schema::disableForeignKeyConstraints();
                foreach ($tables as $table) {
                    DB::table($table)->truncate();
                }
                Schema::enableForeignKeyConstraints();
            }

            DB::commit();
            $this->info('Database cleaned successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to clean database: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
