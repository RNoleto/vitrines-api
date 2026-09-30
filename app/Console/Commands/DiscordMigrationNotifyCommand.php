<?php

namespace App\Console\Commands;

use App\Services\DiscordNotifier;
use Illuminate\Console\Command;

class DiscordMigrationNotifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:notify-migration {status : started|success|failed} {--message= : Mensagem adicional ou log} {--details= : Detalhes adicionais}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia notificação sobre o status da execução de migrations para os Webhooks do Discord';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $status = $this->argument('status');
        $message = $this->option('message');
        $details = $this->option('details');

        DiscordNotifier::notifyMigration($status, $message, [
            'details' => $details,
            'environment' => config('app.env', 'production'),
        ]);

        $this->info("Notificação de migration [{$status}] enviada ao Discord!");
        return 0;
    }
}
