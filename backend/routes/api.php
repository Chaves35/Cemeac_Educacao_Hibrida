<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\ForumPostController;
use App\Http\Controllers\Api\StudentActivityController;

// Rotas de autenticação
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// Rotas de recursos protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('schools', SchoolController::class);
    Route::apiResource('activities', ActivityController::class);
    Route::apiResource('contents', ContentController::class);
    Route::apiResource('forum-posts', ForumPostController::class);
    Route::apiResource('student-activities', StudentActivityController::class);
    
    // Rotas personalizadas do fórum
    Route::get('forum-posts/{forumPost}/replies', [ForumPostController::class, 'replies']);
    Route::patch('forum-posts/{forumPost}/pin', [ForumPostController::class, 'pin']);
    
    // Rotas personalizadas de student activities
    Route::post('student-activities/{studentActivity}/submit', [StudentActivityController::class, 'submit']);
    Route::patch('student-activities/{studentActivity}/reset-attempts', [StudentActivityController::class, 'resetAttempts']);
});