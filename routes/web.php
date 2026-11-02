<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController; // Panggil controllernya di sini

Route::get('/', function () {
    return view('welcome');
});

// Alamat web untuk melihat daftar produk
Route::get('/produk', [ProdukController::class, 'index']);