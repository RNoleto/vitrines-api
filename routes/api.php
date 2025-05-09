<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\FirebaseAuthenticate;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;

Route::get('/users', [UserController::class, 'users']);

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
    Route::get('/contacts/{id}', [ContactController::class, 'show']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::put('/contacts/{id}', [ContactController::class, 'update']); 
    Route::put('/contacts/{id}/stores', [ContactController::class, 'updateStores']);
    Route::get('/contacts/by-store', [ContactController::class, 'contactByStore']);
    Route::post('/contacts/link', [ContactController::class, 'linkToStore']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
});

// Rotas públicas para páginas externas

//Lojas
Route::get('/lojas/{loja}', [StoreController::class, 'showBySlug'])->where('loja', '.*');
Route::get('/public/stores', [StoreController::class, 'publicList']);
Route::get('/public/stores/{store:slug}', [StoreController::class, 'publicShow']);
Route::post('/public/stores/{slug}/visit', [StoreController::class, 'registerVisit']);
Route::post('/public/stores/links/{id}/click', [StoreController::class, 'registerLinkClick']);
Route::post('/stores/{store}/contacts/{contact}/click', [StoreController::class, 'registerContactClick']);

//Contatos
Route::get('/public/stores/{store}/contacts', [ContactController::class, 'publicByStore']);
// Route::get('/admin/contacts', [ContactController::class, 'adminIndex']);

//Rotas Administrativas
Route::middleware([FirebaseAuthenticate::class, 'role:admin'])->group(function () {
    Route::get('/admin/contacts', [ContactController::class, 'adminIndex']);
});



// Rotas de autenticação
Route::get('/usuarios/firebase/{firebase_uid}', [UserController::class, 'buscarPorFirebase']);


Route::middleware([FirebaseAuthenticate::class, 'role:admin'])->group(function(){
    Route::get('/admin/dashboard', function() {
        return response()->json(['message' => 'Bem-vindo, administrador']);
    });
});