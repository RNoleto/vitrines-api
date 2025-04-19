<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function users()
    {
        $users = User::all();
        return response()->json($users);
    }
    
    public function buscarPorFirebase($firebase_uid)
    {
        $user = User::where('firebase_uid', $firebase_uid)->first();
    
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    
        return response()->json($user);
    }

}
