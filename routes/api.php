<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong from API']);
});

Route::post('/login', [FirebaseAuthController::class, 'login']);