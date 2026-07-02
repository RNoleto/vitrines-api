<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FirebaseAuthenticate
{
    private FirebaseAuth $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        $idToken = $request->bearerToken();
        if (! $idToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            if (app()->environment('local')) {
                // Decodifica o JWT offline para evitar chamadas de rede no ambiente corporativo bloqueado
                $parts = explode('.', $idToken);
                if (count($parts) === 3) {
                    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
                    $uid = $payload['sub'] ?? null;
                } else {
                    $uid = $idToken; // Aceita o UID direto em testes simplificados
                }
            } else {
                $verifiedToken = $this->auth->verifyIdToken($idToken);
                $uid = $verifiedToken->claims()->get('sub');
            }

            if (!$uid) {
                throw new \Exception('UID do Firebase não encontrado no token.');
            }

            $user = User::where('firebase_uid', $uid)->firstOrFail();
            Auth::login($user);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Invalid token',
                'details' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
