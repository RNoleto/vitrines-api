<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FirebaseAuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function login(Request $request)
    {
        $request->validate([
            'idToken' => 'required|string',
        ]);

        try {
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($request->idToken);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');

            $userRecord = $this->firebaseAuth->getUser($firebaseUid);

            // Cria ou atualiza o usuário
            $user = User::updateOrCreate(
                ['firebase_uid' => $userRecord->uid],
                [
                    'name' => $userRecord->displayName ?? 'Usuário Firebase',
                    'email' => $userRecord->email,
                ]
            );

            return response()->json([
                'message' => 'Usuário autenticado com sucesso.',
                'user' => $user,
            ]);
        } catch (\Throwable $e) {
            // Log::error('Erro no login Firebase: ' . $e->getMessage());
            Log::error('Erro no login Firebase: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'error' => 'Token inválido ou expirado.',
                'details' => $e->getMessage(),
            ], 401);
        }
    }
}
