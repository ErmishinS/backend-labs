<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RecordController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/user', [UserController::class, 'store']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);

Route::get('/category', [CategoryController::class, 'index']);
Route::post('/category', [CategoryController::class, 'store']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

Route::get('/record/{id}', [RecordController::class, 'show']);
Route::post('/record', [RecordController::class, 'store']);
Route::delete('/record/{id}', [RecordController::class, 'destroy']);
Route::get('/record', [RecordController::class, 'index']);