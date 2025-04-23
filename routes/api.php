<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\FirebaseAuthenticate;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactStoresController;

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

// Rotas de lojas
Route::middleware(FirebaseAuthenticate::class)->group(function(){
    Route::get('/contacts', [ContactStoresController::class, 'index']);
    Route::post('/contacts', [ContactStoresController::class, 'store']);
    Route::get('/contacts/{id}', [ContactStoresController::class, 'show']);
    Route::put('/contacts/{id}', [ContactStoresController::class, 'update']);
    Route::delete('/contacts/{id}', [ContactStoresController::class, 'destroy']);
});

// routes/api.php
Route::middleware('auth:sanctum')->get('/minhas-lojas', [StoreController::class, 'minhasLojas']);

// Rotas públicas de teste
Route::get('/lojas', [StoreController::class, 'publicList']);
Route::get('/users', [UserController::class, 'users']);


Route::get('/usuarios/firebase/{firebase_uid}', [UserController::class, 'buscarPorFirebase']);



// Rota para o banco de dados
Route::get('/db-check', function () {
    try {
        \DB::connection()->getPdo();
        return response()->json(['db' => 'conectado com sucesso']);
    } catch (\Exception $e) {
        return response()->json(['erro' => $e->getMessage()], 500);
    }
});

// Rota teste
Route::get('/bcrypt-test', function () {
    return bcrypt('teste123');
});

