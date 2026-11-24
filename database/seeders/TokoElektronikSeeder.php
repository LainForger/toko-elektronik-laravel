<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\TransaksiItem;

class TokoElektronikSeeder extends Seeder
{
    public function run(): void
    {
        // --- Kategoris ---
        $katTv    = Kategori::create(['nama_kategori' => 'Televisi & Audio']);
        $katRt    = Kategori::create(['nama_kategori' => 'Elektronik Rumah Tangga']);
        $katAc    = Kategori::create(['nama_kategori' => 'Pendingin Ruangan']);
        $katKomp  = Kategori::create(['nama_kategori' => 'Komputer & Gadget']);

        // --- Produks ---
        $p1 = Produk::create([
            'kode_produk' => 'ELK-001',
            'nama_produk' => 'Smart TV LED 43"',
            'kategori_id' => $katTv->id,
            'harga'       => 3850000,
            'stok'        => 12,
        ]);

        $p2 = Produk::create([
            'kode_produk' => 'ELK-002',
            'nama_produk' => 'Kulkas 2 Pintu Inverter',
            'kategori_id' => $katRt->id,
            'harga'       => 4200000,
            'stok'        => 8,
        ]);

        $p3 = Produk::create([
            'kode_produk' => 'ELK-003',
            'nama_produk' => 'AC Split 1 PK',
            'kategori_id' => $katAc->id,
            'harga'       => 3150000,
            'stok'        => 4,
        ]);

        $p4 = Produk::create([
            'kode_produk' => 'ELK-004',
            'nama_produk' => 'Mesin Cuci',
            'kategori_id' => $katRt->id,
            'harga'       => 2750000,
            'stok'        => 6,
        ]);

        $p5 = Produk::create([
            'kode_produk' => 'ELK-005',
            'nama_produk' => 'Home Theater',
            'kategori_id' => $katTv->id,
            'harga'       => 2499000,
            'stok'        => 5,
        ]);

        $p6 = Produk::create([
            'kode_produk' => 'ELK-006',
            'nama_produk' => 'Laptop Ultrabook',
            'kategori_id' => $katKomp->id,
            'harga'       => 8500000,
            'stok'        => 3,
        ]);

        // --- Pelanggans ---
        $budi = Pelanggan::create([
            'nama_pelanggan' => 'Budi Santoso',
            'no_hp'          => '081234567890',
            'alamat'         => 'Jl. Pemuda No. 12 Jepara',
        ]);

        $siti = Pelanggan::create([
            'nama_pelanggan' => 'Siti Rahma',
            'no_hp'          => '085711223344',
            'alamat'         => 'Jl. Kartini No. 8 Kudus',
        ]);

        // --- Sample Transaction ---
        $transaksi = Transaksi::create([
            'no_nota'      => 'TRX-' . date('Ymd') . '-0001',
            'pelanggan_id' => $budi->id,
            'total_harga'  => 2499000,
            'bayar'        => 3000000,
            'kembalian'    => 501000,
        ]);

        TransaksiItem::create([
            'transaksi_id' => $transaksi->id,
            'produk_id'    => $p5->id,
            'nama_produk'  => $p5->nama_produk,
            'harga'        => $p5->harga,
            'qty'          => 1,
            'subtotal'     => $p5->harga * 1,
        ]);

        // Decrement stock for the sample transaction
        $p5->decrement('stok', 1);
    }
}
