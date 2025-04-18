<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function index()
    {
        return Store::with('links')->where('ativo', true)->get();
    }

    public function show($id)
    {
        return Store::with('links')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'logo' => 'nullable|image',
            'ativo' => 'integer',
            'links' => 'array',
            'links.*' => 'url',
        ]);

        $user = auth()->user(); // ou qualquer forma que você recupere o usuário

        if (Store::where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Usuário já possui uma loja.'], 422);
        }

        $logoPath = $request->file('logo')?->store('logos', 'public');

        $store = Store::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'logo' => $logoPath,
        ]);

        foreach ($request->links ?? [] as $link) {
            $store->links()->create(['url' => $link]);
        }

        return response()->json($store->load('links'), 201);
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        $request->validate([
            'name' => 'string',
            'logo' => 'nullable|image',
            'ativo' => 'boolean',
            'links' => 'array',
            'links.*' => 'url',
        ]);

        if ($request->hasFile('logo')) {
            // Remove antiga
            if ($store->logo) Storage::disk('public')->delete($store->logo);
            $store->logo = $request->file('logo')->store('logos', 'public');
        }

        $store->update([
            'name' => $request->name ?? $store->name,
            'ativo' => $request->ativo ?? $store->ativo,
        ]);

        if ($request->has('links')) {
            $store->links()->delete();
            foreach ($request->links as $link) {
                $store->links()->create(['url' => $link]);
            }
        }

        return response()->json($store->load('links'));
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return response()->json(['message' => 'Loja excluída com sucesso (soft delete).']);
    }
}
