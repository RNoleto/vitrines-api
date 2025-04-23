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
}
