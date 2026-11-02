<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk; // Panggil model produk

class ProdukController extends Controller
{
    public function index()
    {
        // Si pelayan mengambil semua data produk beserta nama kategorinya
        $semuaProduk = Produk::with('kategori')->get();
        
        // Menampilkan datanya ke layar
        return response()->json($semuaProduk);
    }
}