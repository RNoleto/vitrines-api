<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\User;
use App\Models\Contact;
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
            $idToken = $request->idToken;
            $firebaseUid = null;
            $email = null;
            $name = $request->name;
            $phoneNumber = null;

            if (app()->environment('local')) {
                // Decodifica JWT offline no ambiente local para evitar bloqueio de rede
                $parts = explode('.', $idToken);
                if (count($parts) === 3) {
                    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
                    $firebaseUid = $payload['sub'] ?? null;
                    $email = $payload['email'] ?? null;
                    $name = $name ?? $payload['name'] ?? null;
                    $phoneNumber = $payload['phone_number'] ?? null;
                } else {
                    $firebaseUid = $idToken;
                    $email = 'local-user@example.com';
                }
            } else {
                $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idToken);
                $firebaseUid = $verifiedIdToken->claims()->get('sub');
                $userRecord = $this->firebaseAuth->getUser($firebaseUid);
                $email = $userRecord->email;
                $name = $name ?? $userRecord->displayName;
                $phoneNumber = $userRecord->phoneNumber;
            }

            if (!$firebaseUid) {
                throw new \Exception('UID do Firebase inválido no token.');
            }

            $user = User::where('firebase_uid', $firebaseUid)->first();
        
            if (!$user) {
                $user = User::create([
                    'firebase_uid' => $firebaseUid,
                    'name' => $name ?? $this->extractNameFromEmail($email ?? 'user@example.com'),
                    'email' => $email ?? 'user@example.com',
                    'role' => $request->input('role', 'user'), // Padrão 'user'
                ]);
            }

            // Cria/atualiza o contato associado ao usuário se houver telefone/whatsapp
            $whatsappNumber = $phoneNumber ?? $request->whatsapp;
            $contact = null;
            if ($whatsappNumber) {
                $contact = Contact::updateOrCreate(
                    ['whatsapp' => $whatsappNumber],
                    [
                        'user_id' => $user->id,
                        'name' => $name ?? $user->name,
                        'photo' => $request->photo_path
                    ]
                );

                // Sincroniza as lojas
                if ($request->has('store_ids')) {
                    $contact->stores()->sync($request->store_ids);
                }
            }

            return response()->json([
                'message' => 'Operação realizada com sucesso',
                'user' => $user->fresh()->toArray(),
                'contact' => $contact ? $contact->load('stores') : null
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
