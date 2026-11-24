<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks   = Produk::with('kategori')->latest()->get();
        $kategoris = Kategori::all();
        return view('produk.index', compact('produks', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produks,kode_produk',
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
        ]);

        Produk::create($request->only(['kode_produk', 'nama_produk', 'kategori_id', 'harga', 'stok']));
        return redirect()->route('kasir.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produks,kode_produk,' . $produk->id,
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
        ]);

        $produk->update($request->only(['kode_produk', 'nama_produk', 'kategori_id', 'harga', 'stok']));
        return redirect()->route('kasir.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function storeKategori(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        Kategori::create($request->only('nama_kategori'));
        return redirect()->route('kasir.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('kasir.index')->with('success', 'Produk berhasil dihapus.');
    }
}
