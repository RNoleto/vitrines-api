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
        $store = $user->store;

        if ($store) {
            $contacts = ContactStore::where('store_id', $store->id)->get();
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
    
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('contacts_photo', 'public');
        }
    
        $contact = ContactStore::create([
            'user_id' => auth()->id(), // aqui
            'store_id' => $request->store_id,
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'photo' => $path,
        ]);
    
        return response()->json($contact, 201);
    }
}
