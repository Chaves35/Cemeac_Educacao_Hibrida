<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AuthController;

// Rotas de autenticação
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rota do usuário autenticado (/me)
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

// Rota alternativa (/user)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas administrativas
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin/dashboard-stats', [AdminController::class, 'getDashboardStats']);
});
