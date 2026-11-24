<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ApiController;

// ==================== WEB ROUTES ====================

// Main SPA page (all tabs)
Route::get('/', [TransaksiController::class, 'index'])->name('kasir.index');

// Legacy single-product checkout (keep for compatibility)
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('kasir.store');

// Struk view
Route::get('/transaksi/{transaksi}/struk', [TransaksiController::class, 'struk'])->name('kasir.struk');

// Pelanggan store (traditional form submit)
Route::post('/pelanggan', [TransaksiController::class, 'storePelanggan'])->name('pelanggan.store');

// Produk CRUD (traditional forms)
Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

// Kategori store
Route::post('/kategori', [ProdukController::class, 'storeKategori'])->name('kategori.store');

// Keep produk.index for backward compat
Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');

// ==================== API ROUTES ====================
// These are used by the SPA JavaScript (AJAX)

Route::prefix('api')->group(function () {

    // GET lists
    Route::get('/produks',    [ApiController::class, 'produks']);
    Route::get('/pelanggans', [ApiController::class, 'pelanggans']);
    Route::get('/transaksis', [ApiController::class, 'transaksis']);

    // GET single transaksi for reprint
    Route::get('/transaksis/{id}', [ApiController::class, 'transaksiShow']);

    // POST multi-item checkout
    Route::post('/transaksi', [ApiController::class, 'checkout']);

    // POST pelanggan (quick add from modal)
    Route::post('/pelanggan', [ApiController::class, 'storePelanggan']);

    // DELETE pelanggan
    Route::delete('/pelanggan/{id}', [ApiController::class, 'deletePelanggan']);
});
