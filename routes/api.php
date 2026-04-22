<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PostController;

Route::get('/home', [HomeController::class, 'index']);

Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

Route::get('/posts/search', [PostController::class, 'search']);

Route::get('/posts/trash', [PostController::class, 'trash']);
Route::post('/posts/{id}/restore', [PostController::class, 'restore']);

Route::post('/posts/{id}/favorite', [PostController::class, 'favorite']);
Route::get('/favorites', [PostController::class, 'favorites']);