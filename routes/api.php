<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// contoh route untuk test
Route::get('/posts', [PostController::class, 'index'] );
Route::get('/posts/{id}', [PostController::class, 'show'] );
Route::get('/posts2/{id}', [PostController::class, 'show2'] );