<?php

namespace Database\Seeders;

use App\Models\DiscordWebhook;
use Illuminate\Database\Seeder;

class DiscordWebhookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiscordWebhook::updateOrCreate(
            ['webhook_url' => 'https://discordapp.com/api/webhooks/1554847188792647781/zzwI4K8I5Cr7bf-pq-M2hMrbUMjIgNtZeOsP99Z-BOhOpuUpSmav-vQZce2w-pzgWeJv'],
            [
                'name' => 'Canal de erros-log',
                'channel_type' => 'errors-log',
                'events' => ['system.error', 'system.exception', 'system.test'],
                'is_active' => true,
            ]
        );
    }
}
