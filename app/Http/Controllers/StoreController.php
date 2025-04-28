<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreLink;
use App\Models\User;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class StoreController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stores = Store::where('user_id', $user->id)
            ->with('links')
            ->get()
            ->append('logo_url');

        return response()->json($stores);
    }

    public function minhasLojas(Request $request)
    {
        $user = $request->user();

        $lojas = Store::where('user_id', $user->id)
                      ->where('ativo', 1)
                      ->get()
                      ->append('logo_url');

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
            'ativo' => 'integer',
            'links' => 'array',
            'links.*.icone' => 'required_with:links|string',
            'links.*.texto' => 'required_with:links|string',
            'links.*.url'   => 'required_with:links|url',
            'theme' => 'nullable|string',
        ]);

        $user = User::where('firebase_uid', $request->firebase_uid)->firstOrFail();

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            try {
                $uploaded = Cloudinary::uploadApi()->upload($request->file('logo')->getRealPath());
                $logoUrl = $uploaded['secure_url'];
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erro ao enviar imagem para o Cloudinary.'], 500);
            }
        }

        $store = Store::create([
            'user_id' => $user->id,
            'name'    => $request->name,
            'logo'    => $logoUrl,
            'ativo'   => $request->ativo ?? 1,
            'theme' => $request->theme ?? 'default',
        ]);

        foreach ($request->links ?? [] as $link) {
            $store->links()->create([
                'icone' => $link['icone'],
                'texto' => $link['texto'],
                'url'   => $link['url'],
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
            'ativo' => 'integer',
            'links' => 'array',
            'links.*.icone' => 'required_with:links|string',
            'links.*.texto' => 'required_with:links|string',
            'links.*.url'   => 'required_with:links|url',
            'theme' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            try {
                $uploaded = Cloudinary::uploadApi()->upload($request->file('logo')->getRealPath());
                $store->logo = $uploaded['secure_url'];
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erro ao enviar nova logo para o Cloudinary.'], 500);
            }
        }

        $store->update([
            'name'  => $request->name ?? $store->name,
            'ativo' => $request->ativo ?? $store->ativo,
            'theme' => $request->theme ?? $store->theme
        ]);

        if ($request->has('links')) {
            $store->links()->delete();
            foreach ($request->links as $link) {
                $store->links()->create([
                    'icone' => $link['icone'],
                    'texto' => $link['texto'],
                    'url'   => $link['url'],
                ]);
            }
        }

        return response()->json($store->load('links'));
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->ativo = 0;
        $store->save();

        return response()->json(['message' => 'Loja desativada com sucesso']);
    }

    // Rotas Publicas para usar nas páginas externas sem autenticação
    public function publicList()
    {
        $stores = Store::with('links')->get()->append('logo_url');
        return response()->json($stores);
    }

    public function publicShow($id)
    {
        $store = Store::with('links')
            ->where('id', $id)
            ->where('ativo', 1)
            ->firstOrFail()
            ->append('logo_url');

        return response()->json([
            ...$store->toArray(),
            'theme' => $store->theme // Garantir que o theme está sendo enviado
        ]);
    }

}
