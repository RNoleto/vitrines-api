<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;

class ContactStoresController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $storeId = $user->store_id;

        $contacts = \App\Models\ContactStore::where('store_id', $storeId)->get();

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

        $path = null;

        if($request->hasFile('photo')){
            $path = $request->file('photo')->store('contacts_photo', 'public');
        }

        $contact = \App\Models\ContactStores::create([
            'store_id' => $request->store_id,
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'photo' => $path,
        ]);

        return response()->json($contact, 201);
    }
}
