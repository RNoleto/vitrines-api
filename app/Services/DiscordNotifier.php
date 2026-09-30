<?php

namespace App\Services;

use App\Models\DiscordWebhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordNotifier
{
    /**
     * Send a notification payload to a given webhook URL.
     */
    public static function sendPayload(string $webhookUrl, array $payload): bool
    {
        try {
            $response = Http::post($webhookUrl, $payload);
            return $response->successful() || $response->status() === 204;
        } catch (\Throwable $e) {
            Log::error("Discord Webhook dispatch error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a test message to a specific webhook.
     */
    public static function sendTest(DiscordWebhook $webhook): array
    {
        $payload = [
            'username' => 'Vitrines System Bot',
            'avatar_url' => 'https://raw.githubusercontent.com/fortawesome/Font-Awesome/6.x/svgs/solid/shop.svg',
            'embeds' => [
                [
                    'title' => '⚡ Teste de Integração Discord',
                    'description' => "O webhook **{$webhook->name}** foi configurado e testado com sucesso no ecossistema **Vitrines**!",
                    'color' => 0x3B82F6, // Hex #3B82F6 (Blue)
                    'fields' => [
                        [
                            'name' => '📌 Canal / Tipo',
                            'value' => "`{$webhook->channel_type}`",
                            'inline' => true,
                        ],
                        [
                            'name' => '🟢 Status',
                            'value' => $webhook->is_active ? 'Ativo' : 'Inativo',
                            'inline' => true,
                        ],
                        [
                            'name' => '⏱️ Horário do Teste',
                            'value' => now()->format('d/m/Y H:i:s'),
                            'inline' => false,
                        ],
                    ],
                    'footer' => [
                        'text' => 'Vitrines Platform • Discord Integration Module',
                    ],
                    'timestamp' => now()->toIso8601String(),
                ]
            ]
        ];

        $success = self::sendPayload($webhook->webhookUrl ?? $webhook->webhook_url, $payload);

        if ($success) {
            $webhook->update(['last_sent_at' => now()]);
            return ['success' => true, 'message' => 'Notificação de teste enviada com sucesso ao Discord!'];
        }

        return ['success' => false, 'message' => 'Falha ao enviar mensagem para a URL do Webhook do Discord. Verifique a URL inserida.'];
    }

    /**
     * Send system error to active error log webhooks.
     */
    public static function notifyError(\Throwable $exception, array $extra = []): void
    {
        $webhooks = DiscordWebhook::where('is_active', true)
            ->where(function($query) {
                $query->where('channel_type', 'errors-log')
                      ->orWhereJsonContains('events', 'system.error');
            })
            ->get();

        if ($webhooks->isEmpty()) {
            return;
        }

        $payload = [
            'username' => 'Vitrines Error Sentinel',
            'embeds' => [
                [
                    'title' => '🚨 Erro Detectado no Sistema',
                    'description' => '```php' . "\n" . substr($exception->getMessage(), 0, 1900) . "\n" . '```',
                    'color' => 0xEF4444, // Hex #EF4444 (Red)
                    'fields' => [
                        [
                            'name' => '📁 Arquivo',
                            'value' => '`' . $exception->getFile() . ':' . $exception->getLine() . '`',
                            'inline' => false,
                        ],
                        [
                            'name' => '🌐 URL / Rota',
                            'value' => request()->fullUrl() ?: 'N/A',
                            'inline' => true,
                        ],
                        [
                            'name' => '🛠️ Método',
                            'value' => request()->method() ?: 'N/A',
                            'inline' => true,
                        ],
                    ],
                    'footer' => [
                        'text' => 'Vitrines Error Sentinel • Laravel Exception Handler',
                    ],
                    'timestamp' => now()->toIso8601String(),
                ]
            ]
        ];

        foreach ($webhooks as $webhook) {
            self::sendPayload($webhook->webhook_url, $payload);
            $webhook->update(['last_sent_at' => now()]);
        }
    }

    /**
     * Send database migration status to active migration webhooks.
     */
    public static function notifyMigration(string $status, ?string $customMessage = null, array $extra = []): void
    {
        $webhooks = DiscordWebhook::where('is_active', true)
            ->where(function($query) use ($status) {
                $query->whereIn('channel_type', ['migrations-log', 'migrations', 'errors-log'])
                      ->orWhereJsonContains('events', 'migration.' . $status)
                      ->orWhereJsonContains('events', 'migration.execution');
            })
            ->get();

        if ($webhooks->isEmpty()) {
            return;
        }

        $config = match ($status) {
            'started', 'in_progress', 'executing' => [
                'title' => '⏳ Execução de Migrations Iniciada',
                'description' => $customMessage ?: 'As migrations do banco de dados estão sendo executadas em produção no GitHub Actions...',
                'color' => 0xF59E0B, // Yellow / Amber #F59E0B
                'badge' => '🟡 EM EXECUÇÃO',
            ],
            'success', 'completed' => [
                'title' => '✅ Migrations Executadas com Sucesso',
                'description' => $customMessage ?: 'Todas as migrations do banco de dados foram aplicadas com sucesso sem erros!',
                'color' => 0x10B981, // Emerald Green #10B981
                'badge' => '🟢 SUCESSO',
            ],
            'failed', 'error' => [
                'title' => '❌ Falha na Execução das Migrations',
                'description' => $customMessage ?: 'Ocorreu um erro ao aplicar as migrations no banco de dados em produção!',
                'color' => 0xEF4444, // Red #EF4444
                'badge' => '🔴 ERRO / FALHA',
            ],
            default => [
                'title' => '🗄️ Notificação de Migration',
                'description' => $customMessage ?: 'Atualização sobre o processo de migrations.',
                'color' => 0x3B82F6, // Blue #3B82F6
                'badge' => '🔵 INFO',
            ]
        };

        $fields = [
            [
                'name' => '📌 Status',
                'value' => $config['badge'],
                'inline' => true,
            ],
            [
                'name' => '🌐 Ambiente',
                'value' => '`' . strtoupper(config('app.env', 'production')) . '`',
                'inline' => true,
            ],
            [
                'name' => '⏱️ Horário',
                'value' => now()->format('d/m/Y H:i:s'),
                'inline' => true,
            ],
        ];

        if (!empty($extra['details'])) {
            $fields[] = [
                'name' => '📝 Detalhes / Log',
                'value' => '```' . "\n" . substr($extra['details'], 0, 1000) . "\n" . '```',
                'inline' => false,
            ];
        }

        $payload = [
            'username' => 'Vitrines Database Sentinel',
            'avatar_url' => 'https://raw.githubusercontent.com/fortawesome/Font-Awesome/6.x/svgs/solid/database.svg',
            'embeds' => [
                [
                    'title' => $config['title'],
                    'description' => $config['description'],
                    'color' => $config['color'],
                    'fields' => $fields,
                    'footer' => [
                        'text' => 'Vitrines Database Sentinel • GitHub Actions Deploy',
                    ],
                    'timestamp' => now()->toIso8601String(),
                ]
            ]
        ];

        foreach ($webhooks as $webhook) {
            self::sendPayload($webhook->webhook_url, $payload);
            $webhook->update(['last_sent_at' => now()]);
        }
    }
}
