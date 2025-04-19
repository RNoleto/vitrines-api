<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\FirebaseAuthenticate;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong from API']);
});

Route::post('/login', [FirebaseAuthController::class, 'login']);

// Rotas de Cadastro de Lojas
Route::middleware(FirebaseAuthenticate::class)->group(function(){
    Route::get('/stores', [StoreController::class, 'index']);
    Route::get('/stores/{id}', [StoreController::class, 'show']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::put('/stores/{id}', [StoreController::class, 'update']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);
});


Route::get('/lojas', [StoreController::class, 'publicList']);


// Rota para o banco de dados
Route::get('/db-check', function () {
    try {
        \DB::connection()->getPdo();
        return response()->json(['db' => 'conectado com sucesso']);
    } catch (\Exception $e) {
        return response()->json(['erro' => $e->getMessage()], 500);
    }
});
