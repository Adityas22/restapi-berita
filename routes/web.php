<?php

use Illuminate\Support\Facades\Route;

// Beranda
Route::get('/', function () {
    return view('home');
});
Route::get('/posts-view/{id}', function ($id) {
    return view('show', ['id' => $id]);
});


// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Register
Route::get('/register', function () {
    return view('auth.register');
});

// // Halaman CRUD Post untuk FE
// Route::get('/posts-view', function () {
//     return view('posts.index');
// });