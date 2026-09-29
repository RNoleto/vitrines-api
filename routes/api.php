<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\FirebaseAuthenticate;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\FontController;
use App\Http\Controllers\IconFamilyController;

Route::post('/login', [FirebaseAuthController::class, 'login']);

// Rotas de Temas Visuais (Público / Autenticado)
Route::get('/themes', [ThemeController::class, 'index']);
Route::get('/themes/{id}', [ThemeController::class, 'show']);

// Rotas de Fontes Tipográficas (Público / Autenticado)
Route::get('/fonts', [FontController::class, 'index']);

// Rotas de Famílias de Ícones (Público / Autenticado)
Route::get('/icon-families', [IconFamilyController::class, 'index']);

// Rotas de Cadastro de Lojas
Route::middleware(FirebaseAuthenticate::class)->group(function(){
    Route::get('/stores', [StoreController::class, 'index']);
    Route::get('/stores/{id}', [StoreController::class, 'show']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::put('/stores/{id}', [StoreController::class, 'update']);
    Route::patch('/stores/{store}/theme', [StoreController::class, 'updateTheme']);
    Route::patch('/stores/{store}/custom-content', [StoreController::class, 'updateCustomContent']);
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
// Route::get('/public/stores', [StoreController::class, 'publicList']);
Route::get('/public/stores/{store:slug}', [StoreController::class, 'publicShow']);
Route::post('/public/stores/{slug}/visit', [StoreController::class, 'registerVisit']);
Route::post('/public/stores/links/{id}/click', [StoreController::class, 'registerLinkClick']);
Route::post('/stores/{store}/contacts/{contact}/click', [StoreController::class, 'registerContactClick']);

//Contatos
// Route::get('/public/stores/{store}/contacts', [ContactController::class, 'publicByStore']);

// Rotas de autenticação
Route::get('/usuarios/firebase/{firebase_uid}', [UserController::class, 'buscarPorFirebase']);


// Rotas Administrativas (Dashboard Admin)
Route::middleware([
    FirebaseAuthenticate::class,
    \App\Http\Middleware\CheckRole::class.':admin'
])->group(function(){
    Route::get('/admin/dashboard', function() {
        return response()->json(['message' => 'Bem-vindo, administrador']);
    });
    Route::get('/users', [UserController::class, 'users']);
    Route::put('/admin/users/{id}/role', [UserController::class, 'updateRole']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
    Route::get('/public/stores', [StoreController::class, 'publicList']);
    Route::get('/admin/contacts', [ContactController::class, 'adminIndex']);

    // CRUD de Temas Visuais do Sistema & Customizados
    Route::post('/themes', [ThemeController::class, 'store']);
    Route::put('/themes/{id}', [ThemeController::class, 'update']);
    Route::delete('/themes/{id}', [ThemeController::class, 'destroy']);

    // Gestão de Fontes Tipográficas
    Route::post('/fonts', [FontController::class, 'store']);
    Route::delete('/fonts/{id}', [FontController::class, 'destroy']);

    // Gestão de Famílias de Ícones
    Route::post('/icon-families', [IconFamilyController::class, 'store']);
    Route::delete('/icon-families/{id}', [IconFamilyController::class, 'destroy']);
});



