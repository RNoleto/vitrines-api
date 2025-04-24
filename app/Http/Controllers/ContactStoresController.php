<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use App\Models\ContactStore;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use PhpParser\Node\Stmt\TryCatch;

class ContactStoresController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        $contacts = ContactStore::where('user_id', $user->id)
            ->where('ativo', 1)
            ->get();
    
        return response()->json($contacts);
    }


    public function show($id)
    {
        $user = auth()->user();

        $contact = ContactStore::where('id', $id)
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
    
        $contacts = ContactStore::where('store_id', $storeId)->get();
    
        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:255',
            'photo' => 'nullable|mimes:jpg,jpeg,png,svg,webp',
        ]);

        $user = auth()->user();

        $photoUrl = null;
        if($request->hasFile('photo')){
            try{
                $uploaded = Cloudinary::uploadApi()->upload($request->file('photo')->getRealPath());
                $photoUrl = $uploaded['secure_url'];
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erro ao enviar imagem para o Cloudinary.'], 500);
            }
        }

        $contact = $user->contacts()->create([
            'user_id' => $user->id,
            'store_id' => $request->store_id,
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'photo' => $photoUrl,
        ]);

        return response()->json($contact, 201);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $contact = ContactStore::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if(!$contact) {
            return response()->json(['error' => 'Contato não encontrado.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'whatsapp' => 'sometimes|string|max:255',
            'photo' => 'nullable|mimes:jpg,jpeg,png,svg,webp',
        ]);

        if($request->hasFile('photo')){
            try{
                $uploaded = Cloudinary::uploadApi()->upload($request->file('photo')->getRealPath());
                $contact->photo = $uploaded['secure_url'];
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erro ao enviar imagem para o Cloudinary.'], 500);
            }
        }

        if($request->has('name')){
            $contact->name = $request->name;
        }

        if($request->has('whatsapp')){
            $contact->whatsapp = $request->whatsapp;
        }

        $contact->save();

        return response()->json($contact);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $contact = ContactStore::where('id', $id)
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
        $store = Store::where('id', $storeId)->where('ativo', 1)->first();

        if (!$store) {
            return response()->json(['message' => 'Loja não encontrada.'], 404);
        }

        $contacts = ContactStore::where('store_id', $storeId)
            ->where('ativo', 1)
            ->get();

        return response()->json($contacts);
    }

}
