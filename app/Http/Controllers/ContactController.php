<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use App\Models\Contact;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        $contacts = Contact::where('user_id', $user->id)
            ->where('ativo', 1)
            ->with(['stores' => function($query) {
                $query->whereNull('contact_store.deleted_at');
            }])
            ->get();
    
        return response()->json($contacts);
    }

    public function show($id)
    {
        $user = auth()->user();

        $contact = Contact::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if(!$contact){
            return response()->json(['error' => 'Contato não encontrado'], 404);
        }

        return response()->json($contact);
    }

    public function contactByStore(Request $request)
    {
        $user = auth()->user();
    
        // Valida o ID da loja que o usuário quer filtrar
        $request->validate([
            'store_id' => 'required|integer|exists:stores,id',
        ]);
    
        $storeId = $request->input('store_id');
    
        // Verifica se a loja pertence ao usuário autenticado
        $store = $user->stores()->where('id', $storeId)->first();
    
        if (!$store) {
            return response()->json(['message' => 'Loja não encontrada ou não pertence a este usuário.'], 403);
        }
    
        $contacts = Contact::where('store_id', $storeId)->get();
    
        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
        
            $request->validate([
                'store_ids' => 'required|array|min:1',
                'store_ids.*' => 'exists:stores,id',
                'name' => 'required|string|max:255',
                'whatsapp' => 'required|string|max:20',
                'photo' => 'nullable|image|max:2048',
            ]);
        
            $user = auth()->user();
        
            // Verificação de lojas pertencentes ao usuário
            $invalidStores = array_diff(
                $request->store_ids,
                $user->stores()->pluck('id')->toArray()
            );
        
            if (!empty($invalidStores)) {
                return response()->json([
                    'error' => 'Algumas lojas não pertencem a este usuário'
                ], 403);
            }
        
            // Upload da foto (igual ao StoreController)
            $photoUrl = null;
            if ($request->hasFile('photo')) {
                try {
                    $uploaded = Cloudinary::uploadApi()->upload(
                        $request->file('photo')->getRealPath(),
                        ['folder' => 'contacts']
                    );
                    $photoUrl = $uploaded['secure_url'];
                } catch (\Exception $e) {
                    \Log::error('Cloudinary error: ' . $e->getMessage());
                    return response()->json(['error' => 'Erro no upload da imagem'], 500);
                }
            }
        
            // Cria o contato
            $contact = $user->contacts()->create([
                'name' => $request->name,
                'whatsapp' => $request->whatsapp,
                'photo' => $photoUrl,
            ]);
        
            // Vincula as lojas
            $contact->stores()->sync($request->store_ids);
        
            DB::commit();
        
            return response()->json($contact->load(['stores' => function($query) {
                $query->whereNull('contact_store.deleted_at');
            }]), 201);
        
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Contact store error: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno no servidor'], 500);
        }
    }

    public function updateStores(Request $request, $id)
    {
        try {
            $user = auth()->user();
        
            $contact = Contact::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        
            $request->validate([
                'lojas' => 'required|array|min:1',
                'lojas.*' => 'exists:stores,id'
            ]);
        
            // Verificação de lojas
            $invalidStores = array_diff(
                $request->lojas,
                $user->stores()->pluck('id')->toArray()
            );
        
            if (!empty($invalidStores)) {
                return response()->json([
                    'error' => 'Algumas lojas não pertencem a este usuário'
                ], 403);
            }
        
            // Sincroniza as lojas, removendo as associações que não estão na lista
            $contact->stores()->sync($request->lojas);
        
            return response()->json($contact->load(['stores']));
        
        } catch (\Exception $e) {
            logger()->error('Update stores error: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar lojas'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $contact = Contact::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $request->validate([
                'name' => 'sometimes|string|max:255',
                'whatsapp' => 'sometimes|string|max:20',
                'photo' => 'nullable|image|max:2048',
            ]);

            // Atualiza foto
            if ($request->hasFile('photo')) {
                $uploaded = Cloudinary::uploadApi()->upload(
                    $request->file('photo')->getRealPath(),
                    ['folder' => 'contacts']
                );
                $contact->photo = $uploaded['secure_url'];
            }

            // Atualiza campos básicos
            $contact->fill($request->only(['name', 'whatsapp']));
            $contact->save();

            DB::commit();

            return response()->json($contact->load('stores'));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Contact update error: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar contato'], 500);
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $contact = Contact::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if(!$contact){
            return response()->json(['error' => 'Contato não encontrado.'], 404);
        }

        $contact->ativo = 0;
        $contact->save();

        return response()->json(['message' => 'Contato excluído com sucesso.'], 201);
    }
    
    // Rotas Publicas para usar nas páginas externas sem autenticação
    public function publicByStore($storeId)
    {
        $store = Store::where('id', $storeId)
            ->where('ativo', 1)
            ->with(['contacts' => function($query) {
                $query->where('contacts.ativo', 1)
                      ->whereNull('contact_store.deleted_at')
                      ->with('stores');
            }])
            ->first();
        
        if (!$store) {
            return response()->json(['message' => 'Loja não encontrada'], 404);
        }
    
        return response()->json($store->contacts);
    }

    public function linkToStore(Request $request)
    {
        $request->validate([
            'whatsapp' => 'required',
            'store_id' => 'required|exists:stores,id'
        ]);

        $user = auth()->user();
        $store = Store::findOrFail($request->store_id);

        // Verifica se o contato já existe para este usuário
        $contact = Contact::firstOrCreate(
            [
                'user_id' => $user->id,
                'whatsapp' => $request->whatsapp
            ],
            [
                'name' => $request->name,
                'photo' => $request->photo
            ]
        );

        // Associa à loja (evita duplicata na relação)
        $store->contacts()->syncWithoutDetaching([$contact->id]);

        return response()->json(['message' => 'Contato vinculado com sucesso!']);
    }

}
