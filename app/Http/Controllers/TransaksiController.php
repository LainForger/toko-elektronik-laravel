<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Main SPA page — passes all data needed by all 4 tabs.
     */
    public function index()
    {
        $produks    = Produk::with('kategori')->orderBy('nama_produk')->get();
        $kategoris  = Kategori::orderBy('nama_kategori')->get();
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $transaksis = Transaksi::with(['items.produk', 'pelanggan'])->latest()->get();

        $transaksisFormatted = $transaksis->map(function ($t) {
            return [
                'id'             => $t->id,
                'no_nota'        => $t->no_nota,
                'created_at'     => $t->created_at->format('d/m/Y H:i'),
                'total_harga'    => $t->total_harga,
                'bayar'          => $t->bayar,
                'kembalian'      => $t->kembalian,
                'pelanggan_nama' => $t->pelanggan ? $t->pelanggan->nama_pelanggan : 'Pelanggan Umum',
                'pelanggan'      => $t->pelanggan,
                'items'          => $t->items->map(function ($item) {
                    return [
                        'nama'     => $item->nama_produk,
                        'harga'    => $item->harga,
                        'qty'      => $item->qty,
                        'subtotal' => $item->subtotal,
                    ];
                })->values(),
            ];
        })->values();

        return view('kasir.index', compact('produks', 'kategoris', 'pelanggans', 'transaksis', 'transaksisFormatted'));
    }

    /**
     * Single-product checkout fallback (traditional form submit).
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id'    => 'required|exists:produks,id',
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
            'jumlah'       => 'required|integer|min:1',
            'bayar'        => 'required|numeric|min:0',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi!');
        }

        $total_harga = $produk->harga * $request->jumlah;

        if ($request->bayar < $total_harga) {
            return redirect()->back()->with('error', 'Uang pembayaran kurang dari total belanja!');
        }

        $kembalian = $request->bayar - $total_harga;

        $transaksi = DB::transaction(function () use ($request, $produk, $total_harga, $kembalian) {
            $produk->decrement('stok', $request->jumlah);

            $trx = Transaksi::create([
                'no_nota'      => 'TRX-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
                'pelanggan_id' => $request->pelanggan_id,
                'total_harga'  => $total_harga,
                'bayar'        => $request->bayar,
                'kembalian'    => $kembalian,
            ]);

            $trx->items()->create([
                'produk_id'   => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'harga'       => $produk->harga,
                'qty'         => $request->jumlah,
                'subtotal'    => $total_harga,
            ]);

            return $trx;
        });

        return redirect()->route('kasir.struk', $transaksi->id)
            ->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Show printable struk (receipt page).
     */
    public function struk(Transaksi $transaksi)
    {
        $transaksi->load(['items.produk', 'pelanggan']);
        return view('kasir.struk', compact('transaksi'));
    }

    /**
     * Store customer via traditional form submit.
     */
    public function storePelanggan(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ]);

        Pelanggan::create($request->only(['nama_pelanggan', 'no_hp', 'alamat']));
        return redirect()->route('kasir.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }
}
