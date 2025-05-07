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

    private function extractNameFromEmail($email)
    {
        $namePart = explode('@', $email)[0];
        return ucfirst(str_replace(['.', '_'], ' ', $namePart)); // Ex: "joao.silva" vira "Joao Silva"
    }

    public function login(Request $request)
    {
        $request->validate([
            'idToken' => 'required|string',
            'name' => 'sometimes|string',
            'store_ids' => 'sometimes|array',
            'store_ids.*' => 'exists:stores,id'
        ]);
    
        try {
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($request->idToken);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
        
            $user = User::where('firebase_uid', $firebaseUid)->first();
        
            if (!$user) {
                // Criação de usuário apenas se não existir
                $userRecord = $this->firebaseAuth->getUser($firebaseUid);
                
                $user = User::create([
                    'firebase_uid' => $firebaseUid,
                    'name' => $request->name ?? $userRecord->displayName ?? $this->extractNameFromEmail($userRecord->email),
                    'email' => $userRecord->email,
                    'role' => $request->input('role', 'user'), // Padrão 'user'
                ]);
            }

            return response()->json([
                'message' => 'Operação realizada com sucesso',
                'user' => $user->fresh()->toArray()
            ]);

            // Cria/atualiza o contato
            $contact = Contact::updateOrCreate(
                ['whatsapp' => $userRecord->phoneNumber ?? $request->whatsapp],
                [
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'photo' => $request->photo_path
                ]
            );

            // Sincroniza as lojas
            if ($request->has('store_ids')) {
                $contact->stores()->sync($request->store_ids);
            }

            return response()->json([
                'message' => 'Contato criado/atualizado com sucesso',
                'contact' => $contact->load('stores')
            ]);     
        
        } catch (\Throwable $e) {
            Log::error('Erro na operação Firebase: ' . $e->getMessage());
            return response()->json([
                'error' => 'Falha na operação',
                'details' => $e->getMessage(),
            ], 401);
        }
    }
}
