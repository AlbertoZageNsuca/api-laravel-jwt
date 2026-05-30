<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

// Rotas publicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

// Rotas protegidas
Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);

    // Soft delete routes
    Route::get('products/trashed',        [ProductController::class, 'trashed']);
    Route::patch('products/{id}/restore', [ProductController::class, 'restore']);
    Route::delete('products/{id}/force',  [ProductController::class, 'forceDelete']);

    // Produtos ativos
    Route::apiResource('products', ProductController::class);

    // Perfil
    Route::put('profile', [UserController::class, 'update']);

    // Admin
    Route::middleware('admin')->group(function () {
        Route::get('users',               [UserController::class, 'index']);
        Route::delete('users/{id}',       [UserController::class, 'destroy']);
        Route::get('users/{id}/products', [UserController::class, 'products']);
        Route::put('users/{id}/role',     [UserController::class, 'updateRole']);
    });
});
