<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

// Rotas de Autenticação (Públicas)
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

// Rotas Protegidas (Autenticadas)
Route::middleware('auth:api')->group(function () {

    // Autenticação
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);

    // Gestão de Produtos
    Route::apiResource('products', ProductController::class);

    // Gestão de Perfil do Utilizador
    Route::put('profile', [UserController::class, 'update']);

    // Rotas exclusivas para Admin
    Route::middleware('admin')->group(function () {
        Route::get('users',                        [UserController::class, 'index']);
        Route::delete('users/{id}',                [UserController::class, 'destroy']);
        Route::get('users/{id}/products',          [UserController::class, 'products']);
        Route::put('users/{id}/role',              [UserController::class, 'updateRole']);
    });
});
