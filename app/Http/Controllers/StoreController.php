<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreLink;
use App\Models\User;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;

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
        ]);

        try {
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

            $baseSlug = Str::slug($request->name, '-', 'pr_BR');
            $slug = !empty($baseSlug) ? $baseSlug : 'loja-sem-nome';
            $slug = $this->makeUniqueSlug($slug);
    
            $store = Store::create([
                'user_id' => $user->id,
                'name'    => $request->name,
                'slug'    => $slug,
                'logo'    => $logoUrl,
                'ativo'   => $request->ativo ?? 1,
            ]);

            foreach ($request->links ?? [] as $link) {
                $store->links()->create([
                    'icone' => $link['icone'],
                    'texto' => $link['texto'],
                    'url'   => $link['url'],
                ]);
            }

            return response()->json($store->load('links')->append('logo_url'), 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro interno:' . $e->getMessage()
            ], 500);
        }

    }

    public function updateTheme(Request $request, $id)
    {
        $request->validate([
            'theme' => 'required|string|max:50'
        ]);
    
        try {
            $store = Store::findOrFail($id);
            $store->update([
                'theme' => $request->theme
            ]);
        
            return response()->json($store);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar tema: ' . $e->getMessage()
            ], 500);
        }
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
        $store = Store::with('links')->where('id', $id)->where('ativo', 1)->firstOrFail();
        return response()->json($store->append('logo_url'));
    }

    public function showBySlug($slug)
    {
        return Store::whereRaw('LOWER(slug) = LOWER(?)', [$slug])
            ->where('ativo', 1)
            ->with(['links', 'contacts'])
            ->firstOrFail();

        $store->increment('visits');
        $store->update(['last_visited_at' => now()]);

        return response()->json($store->append('logo_url'));
    }

    private function makeUniqueSlug($slug)
    {
        $original = $slug;
        $count = 1;

        while (Store::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public function registerVisit($slug)
    {
        $store = Store::where('slug', $slug)->first();

        if(!$store){
            return response()->json(['error' => 'loja não encontrada'], 404);
        }

        $store->increment('visits');
        $store->last_visited_at = now();
        $store->save();

        return response()->json(['message' => 'Visita registrada com sucesso']);
    }

    public function registerLinkClick($id)
    {
        $link = StoreLink::find($id);
    
        if (!$link) {
            return response()->json(['error' => 'Link não encontrado'], 404);
        }
    
        $link->increment('visits');
        $link->last_visited_at = now();
        $link->save();
    
        return response()->json(['message' => 'Clique registrado com sucesso']);
    }


}
