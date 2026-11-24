<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * GET /api/produks
     * Returns all products with their kategori relation.
     */
    public function produks(): JsonResponse
    {
        $produks = Produk::with('kategori')->orderBy('nama_produk')->get();
        return response()->json($produks);
    }

    /**
     * GET /api/pelanggans
     * Returns all customers.
     */
    public function pelanggans(): JsonResponse
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        return response()->json($pelanggans);
    }

    /**
     * GET /api/transaksis
     * Returns all transactions with pelanggan and multi-item list.
     */
    public function transaksis(): JsonResponse
    {
        $transaksis = Transaksi::with(['pelanggan', 'items.produk'])
            ->latest()
            ->get()
            ->map(function ($t) {
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
                    }),
                ];
            });

        return response()->json($transaksis);
    }

    /**
     * GET /api/transaksis/{id}
     * Returns a single transaction (for reprint struk).
     */
    public function transaksiShow(int $id): JsonResponse
    {
        $t = Transaksi::with(['pelanggan', 'items.produk'])->findOrFail($id);

        return response()->json([
            'id'             => $t->id,
            'no_nota'        => $t->no_nota,
            'created_at'     => $t->created_at->format('d/m/Y H:i:s'),
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
            }),
        ]);
    }

    /**
     * POST /api/transaksi
     * Multi-item checkout endpoint.
     *
     * Body: { pelanggan_id, bayar, items: [{ produk_id, qty }] }
     */
    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pelanggan_id'        => 'nullable|exists:pelanggans,id',
            'bayar'               => 'required|numeric|min:0',
            'items'               => 'required|array|min:1',
            'items.*.produk_id'   => 'required|exists:produks,id',
            'items.*.qty'         => 'required|integer|min:1',
        ]);

        $items       = $validated['items'];
        $pelangganId = $validated['pelanggan_id'] ?? null;
        $bayar       = (float) $validated['bayar'];

        // Calculate total & validate stock
        $totalHarga = 0;
        $produks    = [];
        foreach ($items as $item) {
            $produk = Produk::findOrFail($item['produk_id']);
            if ($produk->stok < $item['qty']) {
                return response()->json([
                    'message' => "Stok {$produk->nama_produk} tidak mencukupi! (tersisa {$produk->stok} unit)"
                ], 422);
            }
            $produks[] = ['model' => $produk, 'qty' => $item['qty']];
            $totalHarga += $produk->harga * $item['qty'];
        }

        if ($bayar < $totalHarga) {
            return response()->json([
                'message' => 'Uang pembayaran kurang dari total belanja!'
            ], 422);
        }

        $kembalian = $bayar - $totalHarga;
        $noNota    = 'TRX-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        $trx          = null;
        $receiptItems = [];

        DB::transaction(function () use ($produks, $pelangganId, $bayar, $totalHarga, $kembalian, $noNota, &$trx, &$receiptItems) {
            // 1. Create main transaction record
            $trx = Transaksi::create([
                'no_nota'      => $noNota,
                'pelanggan_id' => $pelangganId,
                'total_harga'  => $totalHarga,
                'bayar'        => $bayar,
                'kembalian'    => $kembalian,
            ]);

            // 2. Decrement stock and insert transaction items
            foreach ($produks as $entry) {
                $produk   = $entry['model'];
                $qty      = $entry['qty'];
                $subtotal = $produk->harga * $qty;

                $produk->decrement('stok', $qty);

                $trx->items()->create([
                    'produk_id'   => $produk->id,
                    'nama_produk' => $produk->nama_produk,
                    'harga'       => $produk->harga,
                    'qty'         => $qty,
                    'subtotal'    => $subtotal,
                ]);

                $receiptItems[] = [
                    'nama'     => $produk->nama_produk,
                    'harga'    => $produk->harga,
                    'qty'      => $qty,
                    'subtotal' => $subtotal,
                ];
            }
        });

        $trx->load(['pelanggan', 'items']);

        return response()->json([
            'id'             => $trx->id,
            'no_nota'        => $noNota,
            'created_at'     => $trx->created_at->format('d/m/Y H:i'),
            'total_harga'    => $totalHarga,
            'bayar'          => $bayar,
            'kembalian'      => $kembalian,
            'pelanggan_nama' => $trx->pelanggan ? $trx->pelanggan->nama_pelanggan : 'Pelanggan Umum',
            'pelanggan'      => $trx->pelanggan,
            'items'          => $receiptItems,
        ], 201);
    }

    /**
     * POST /api/pelanggan
     * Quick-add customer from the cart modal.
     */
    public function storePelanggan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ]);

        $pelanggan = Pelanggan::create($validated);
        return response()->json($pelanggan, 201);
    }

    /**
     * DELETE /api/pelanggan/{id}
     */
    public function deletePelanggan(int $id): JsonResponse
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return response()->json(['message' => 'Pelanggan berhasil dihapus.']);
    }
}
