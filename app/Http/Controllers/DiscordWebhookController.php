<?php

namespace App\Http\Controllers;

use App\Models\DiscordWebhook;
use App\Services\DiscordNotifier;
use Illuminate\Http\Request;

class DiscordWebhookController extends Controller
{
    /**
     * List all Discord webhooks.
     */
    public function index()
    {
        $webhooks = DiscordWebhook::orderBy('id', 'desc')->get();
        return response()->json($webhooks);
    }

    /**
     * Store a new Discord webhook.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'webhook_url' => 'required|url',
            'channel_type' => 'nullable|string|max:100',
            'events' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['channel_type'])) {
            $validated['channel_type'] = 'general';
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $webhook = DiscordWebhook::create($validated);

        return response()->json([
            'message' => 'Bot/Webhook do Discord cadastrado com sucesso!',
            'webhook' => $webhook,
        ], 201);
    }

    /**
     * Update an existing Discord webhook.
     */
    public function update(Request $request, $id)
    {
        $webhook = DiscordWebhook::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'webhook_url' => 'sometimes|required|url',
            'channel_type' => 'nullable|string|max:100',
            'events' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $webhook->update($validated);

        return response()->json([
            'message' => 'Bot/Webhook atualizado com sucesso!',
            'webhook' => $webhook,
        ]);
    }

    /**
     * Remove a Discord webhook.
     */
    public function destroy($id)
    {
        $webhook = DiscordWebhook::findOrFail($id);
        $webhook->delete();

        return response()->json([
            'message' => 'Bot/Webhook removido com sucesso!',
        ]);
    }

    /**
     * Send a test notification to a Discord webhook.
     */
    public function sendTest($id)
    {
        $webhook = DiscordWebhook::findOrFail($id);
        $result = DiscordNotifier::sendTest($webhook);

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 400);
    }
}
