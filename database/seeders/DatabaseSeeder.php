<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Produk;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat data Kategori
        $kategori1 = Kategori::create(['nama' => 'Televisi']);
        $kategori2 = Kategori::create(['nama' => 'Laptop']);

        // 2. Buat data Produk (menyambung ke ID Kategori)
        Produk::create([
            'kategori_id' => $kategori1->id, // Mengambil ID dari Televisi
            'nama_produk' => 'Samsung Smart TV 32 Inch',
            'harga' => 2500000,
            'stok' => 10
        ]);

        Produk::create([
            'kategori_id' => $kategori2->id, // Mengambil ID dari Laptop
            'nama_produk' => 'Asus ROG Zephyrus',
            'harga' => 25000000,
            'stok' => 5
        ]);
    }
}