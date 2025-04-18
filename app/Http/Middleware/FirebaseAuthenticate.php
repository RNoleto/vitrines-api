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
            
            $verifiedToken = $this->auth->verifyIdToken($idToken);
            $uid = $verifiedToken->claims()->get('sub');

            
            $user = User::where('firebase_uid', $uid)->firstOrFail();

            
            Auth::login($user);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
