<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RecordController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:api');

Route::middleware('jwt.custom')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
    
    Route::get('/record', [RecordController::class, 'index']);
    Route::get('/record/{id}', [RecordController::class, 'show']);
    Route::post('/record', [RecordController::class, 'store']);
    Route::delete('/record/{id}', [RecordController::class, 'destroy']);
});