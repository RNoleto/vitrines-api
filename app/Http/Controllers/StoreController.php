<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        $stores = Store::where('user_id', $user->id)
            ->with('links')
            ->get()
            ->append('logo_url'); // <- adiciona a URL completa no retorno
    
        return response()->json($stores);
    }
    public function minhasLojas(Request $request)
    {
        $user = $request->user();  // Obtém o usuário autenticado
    
        $lojas = Store::where('user_id', $user->id)
                      ->where('ativo', 1)
                      ->get();
    
        return response()->json($lojas);
    }

    public function show($id)
    {
        return Store::with('links')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'firebase_uid' => 'required|string|exists:users,firebase_uid',
            'logo' => 'nullable|mimes:jpg,jpeg,png,svg,webp',
            // ou: 'logo' => 'nullable|mimetypes:image/jpeg,image/png,image/svg+xml,image/webp',
            'ativo' => 'integer',
            'links' => 'array',
            'links.*.icone'   => 'required_with:links|string',
            'links.*.texto'   => 'required_with:links|string',
            'links.*.url'     => 'required_with:links|url',
        ]);

        
        // Aplicar somente para usuários com plano free
        // if (Store::where('user_id', $user->id)->exists()) {
            //     return response()->json(['error' => 'Usuário já possui uma loja.'], 422);
            // }
            
        $user = User::where('firebase_uid', $request->firebase_uid)->firstOrFail();
        
        $logoPath = $request->file('logo')?->store('logos', 'public');

        $store = Store::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'logo' => $logoPath,
        ]);

        foreach ($request->links ?? [] as $link) {
            $store->links()->create([
                'icone' => $link['icone'],
                'texto' => $link['texto'],
                'url' => $link['url'],
            ]);
        }

        return response()->json($store->load('links')->append('logo_url'), 201);
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        $request->validate([
            'name' => 'string',
            'logo' => 'nullable|mimes:jpg,jpeg,png,svg,webp',
            // ou: 'logo' => 'nullable|mimetypes:image/jpeg,image/png,image/svg+xml,image/webp',
            'ativo' => 'boolean',
            'links' => 'array',
            'links.*.icone'   => 'required_with:links|string',
            'links.*.texto'   => 'required_with:links|string',
            'links.*.url'     => 'required_with:links|url',
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
            if ($request->has('links')) {
                $store->links()->delete();
                foreach ($request->links as $link) {
                    $store->links()->create([
                        'icone' => $link['icone'],
                        'texto' => $link['texto'],
                        'url' => $link['url'],
                    ]);
                }
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

    public function publicList()
    {
        $stores = Store::with('links')->get()->append('logo_url');
        return response()->json($stores);
    }

}
