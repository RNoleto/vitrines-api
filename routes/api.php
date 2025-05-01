<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\FirebaseAuthenticate;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;

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
    Route::patch('/stores/{store}/theme', [StoreController::class, 'updateTheme']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);
});

// Rotas de contatos
Route::middleware(FirebaseAuthenticate::class)->group(function(){
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/contacts/by-store', [ContactController::class, 'contactByStore']);
    Route::post('/contacts/link', [ContactController::class, 'linkToStore']);
});

// Rotas públicas para páginas externas
Route::get('/lojas/{loja}', [StoreController::class, 'showBySlug'])->where('loja', '.*');
Route::get('/public/stores', [StoreController::class, 'publicList']); // já existe
Route::get('/public/stores/{store:slug}', [StoreController::class, 'publicShow']);
Route::get('/public/stores/{id}/contacts', [ContactController::class, 'publicByStore']);


// routes/api.php
Route::middleware('auth:sanctum')->get('/minhas-lojas', [StoreController::class, 'minhasLojas']);


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

