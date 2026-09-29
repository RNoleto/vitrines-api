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

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|in:admin,user',
        ]);

        $user = User::findOrFail($id);

        // Previne que o administrador logado altere seu próprio papel se for o único admin
        $loggedUser = $request->user();
        if ($loggedUser && $loggedUser->id === $user->id && $request->role !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'error' => 'Você é o único administrador do sistema e não pode remover seu próprio papel de admin.'
                ], 422);
            }
        }

        $user->role = $request->role;
        $user->save();

        return response()->json([
            'message' => 'Função do usuário atualizada com sucesso.',
            'user' => $user
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $loggedUser = $request->user();

        if ($loggedUser && $loggedUser->id === $user->id) {
            return response()->json([
                'error' => 'Você não pode excluir sua própria conta de administrador.'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuário excluído com sucesso.'
        ]);
    }
}

