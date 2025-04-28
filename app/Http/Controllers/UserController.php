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
        try {
            $user = User::where('firebase_uid', $firebase_uid)
                ->firstOrFail();

            return response()->json($user);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Usuário não encontrado',
                'details' => $e->getMessage()
            ], 404);
        }
    }

}
