<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use App\Models\ContactStore;

class ContactStoresController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Pegando a loja do usuário
        $store = $user->store;

        // Se a loja existir, buscar os contatos da loja
        if ($store) {
            $contacts = ContactStore::all();

            return response()->json($contacts);
        }

        return response()->json([]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:255',
            'photo' => 'nullable|mimes:jpg,jpeg,png,svg,webp',
        ]);

        $path = null;

        if($request->hasFile('photo')){
            $path = $request->file('photo')->store('contacts_photo', 'public');
        }

        $contact = ContactStore::create([
            'store_id' => $request->store_id,
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'photo' => $path,
        ]);

        return response()->json($contact, 201);
    }
}
