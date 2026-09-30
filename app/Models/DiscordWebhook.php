<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordWebhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'webhook_url',
        'channel_type',
        'events',
        'is_active',
        'last_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'is_active' => 'boolean',
            'last_sent_at' => 'datetime',
        ];
    }
}
