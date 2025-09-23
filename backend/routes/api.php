// backend/routes/api.php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SchoolController;

// Rotas de autenticação
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// Rotas de recursos de usuário
Route::apiResource('users', UserController::class)
    ->middleware('auth:sanctum');

// Rotas de recursos de schools
Route::apiResource('schools', SchoolController::class)
    ->middleware('auth:sanctum');