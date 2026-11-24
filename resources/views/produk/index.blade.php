@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Form Tambah Kategori & Produk (4 Kolom) -->
    <div class="lg:col-span-4 space-y-6">
        <!-- Card Tambah Kategori -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h2 class="font-bold text-slate-900 text-base mb-1 flex items-center gap-2">
                <i class="fa-solid fa-tags text-teal-600"></i> Tambah Kategori
            </h2>
            <p class="text-xs text-slate-500 mb-4">Kelompokkan jenis barang elektronik toko</p>
            <form action="{{ route('kategori.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="nama_kategori" required placeholder="Contoh: Televisi, AC, Audio"
                       class="flex-1 px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                    + Simpan
                </button>
            </form>
        </div>

        <!-- Card Tambah Produk Baru -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h2 class="font-bold text-slate-900 text-base mb-1 flex items-center gap-2">
                <i class="fa-solid fa-box-open text-teal-600"></i> Tambah Produk Elektronik
            </h2>
            <p class="text-xs text-slate-500 mb-4">Masukkan data barang dan stok gudang</p>

            <form action="{{ route('produk.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kode Barang (SKU)</label>
                    <input type="text" name="kode_produk" placeholder="ELK-007" required
                           class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Produk Elektronik</label>
                    <input type="text" name="nama_produk" placeholder="Contoh: Smart TV QLED 50 Inch" required
                           class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kategori</label>
                    <select name="kategori_id" required
                            class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="harga" placeholder="3850000" required
                               class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Stok Awal</label>
                        <input type="number" name="stok" placeholder="10" required
                               class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-plus mr-1"></i> Simpan Produk ke Gudang
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Inventori Produk (8 Kolom) -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-900 text-base">Daftar Inventori Produk Elektronik</h2>
                    <p class="text-xs text-slate-500">Total {{ $produks->count() }} item terdaftar di gudang</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Kode SKU</th>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="px-4 py-3 text-center">Stok</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($produks as $p)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-mono text-xs font-bold text-slate-700">{{ $p->kode_produk }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $p->nama_produk }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $p->kategori->nama_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-teal-700">Rp{{ number_format($p->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $p->stok > 5 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $p->stok }} unit
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada data produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection