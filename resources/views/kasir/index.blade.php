@extends('layouts.app')

@section('content')

{{-- ======================================================
     TAB 1: KASIR & TRANSAKSI
     ====================================================== --}}
<div id="tab-kasir" class="tab-content">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- LEFT: Product Catalog (7 cols) --}}
        <div class="lg:col-span-7 space-y-4">
            {{-- Search & Filter Bar --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400"></i>
                    <input type="text" id="searchPos" oninput="renderPosCatalog()"
                        placeholder="Cari nama atau kode barang..."
                        class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                </div>
                <div class="sm:w-48">
                    <select id="filterPosCategory" onchange="renderPosCatalog()"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none bg-white">
                        <option value="">Semua Kategori</option>
                    </select>
                </div>
            </div>

            {{-- Products Grid --}}
            <div id="posCatalogGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-4 max-h-[620px] overflow-y-auto pr-1">
                {{-- Dynamic Product Cards via JS --}}
            </div>
        </div>

        {{-- RIGHT: Cart & Payment (5 cols) --}}
        <div class="lg:col-span-5">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full sticky top-20">
                {{-- Cart Header --}}
                <div class="bg-slate-50 p-4 border-b border-slate-200 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-brand-600"></i>
                        <h2 class="font-bold text-slate-800">Keranjang Belanja</h2>
                    </div>
                    <button onclick="clearCart()" class="text-xs text-rose-600 hover:text-rose-800 hover:underline">
                        <i class="fa-solid fa-trash me-1"></i> Kosongkan
                    </button>
                </div>

                {{-- Customer Selection --}}
                <div class="p-4 border-b border-slate-200 bg-white">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pelanggan</label>
                    <div class="flex gap-2">
                        <select id="cartCustomer"
                            class="flex-grow px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="">Pelanggan Umum (Cash/Walk-in)</option>
                        </select>
                        <button onclick="openModal('modalPelanggan')"
                            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium border border-slate-300 transition"
                            title="Tambah Pelanggan Baru">
                            <i class="fa-solid fa-user-plus"></i>
                        </button>
                    </div>
                </div>

                {{-- Cart Items --}}
                <div id="cartItemsContainer" class="p-4 overflow-y-auto max-h-[260px] divide-y divide-slate-100 flex-grow">
                    {{-- Populated by JS --}}
                </div>

                {{-- Payment Section --}}
                <div class="p-4 bg-slate-50 border-t border-slate-200 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Subtotal Item:</span>
                        <span id="cartSubtotal" class="font-medium text-slate-800">Rp 0</span>
                    </div>

                    <div class="p-3 bg-brand-50 border border-brand-100 rounded-lg flex justify-between items-center">
                        <span class="font-bold text-brand-900 text-sm">TOTAL BAYAR:</span>
                        <span id="cartTotal" class="font-extrabold text-xl text-brand-700">Rp 0</span>
                    </div>

                    {{-- Payment Input --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-slate-600">Uang Bayar (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs text-slate-500 font-bold">Rp</span>
                            <input type="number" id="payAmountInput" oninput="calculateChange()"
                                placeholder="0"
                                class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg font-bold text-slate-900 text-base focus:ring-2 focus:ring-brand-500 outline-none">
                        </div>
                    </div>

                    {{-- Quick Pay Buttons --}}
                    <div class="grid grid-cols-4 gap-1.5 pt-1">
                        <button onclick="setQuickPay('pas')"
                            class="text-xs bg-white hover:bg-slate-100 border border-slate-300 py-1.5 rounded font-medium text-slate-700 transition">Uang Pas</button>
                        <button onclick="setQuickPay(1000000)"
                            class="text-xs bg-white hover:bg-slate-100 border border-slate-300 py-1.5 rounded font-medium text-slate-700 transition">1 Juta</button>
                        <button onclick="setQuickPay(5000000)"
                            class="text-xs bg-white hover:bg-slate-100 border border-slate-300 py-1.5 rounded font-medium text-slate-700 transition">5 Juta</button>
                        <button onclick="setQuickPay(10000000)"
                            class="text-xs bg-white hover:bg-slate-100 border border-slate-300 py-1.5 rounded font-medium text-slate-700 transition">10 Juta</button>
                    </div>

                    {{-- Change --}}
                    <div class="flex justify-between items-center text-sm pt-1">
                        <span class="text-slate-600">Kembalian:</span>
                        <span id="changeDisplay" class="font-bold text-slate-500">Rp 0</span>
                    </div>

                    {{-- Checkout Button --}}
                    <button onclick="processCheckout()"
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-print"></i> Proses &amp; Cetak Struk
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ======================================================
     TAB 2: DATA PRODUK & KATEGORI
     ====================================================== --}}
<div id="tab-produk" class="tab-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left: Forms (4 cols) --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Form Tambah Kategori --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-folder-plus text-brand-600"></i> Tambah Kategori
                </h3>
                <form action="{{ route('kategori.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required placeholder="Contoh: Pendingin Ruangan"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                    <button type="submit"
                        class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-medium text-sm rounded-lg transition">
                        Simpan Kategori
                    </button>
                </form>
            </div>

            {{-- Form Tambah / Edit Produk --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                <h3 id="formProdukTitle" class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-brand-600"></i> Tambah Produk Elektronik
                </h3>
                <form id="formProduk" action="{{ route('produk.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="_method" id="prodMethodInput" value="POST">
                    <input type="hidden" id="prodEditId" value="">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kode Barang</label>
                        <input type="text" name="kode_produk" id="prodKode" required placeholder="ELK-007"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Produk Elektronik</label>
                        <input type="text" name="nama_produk" id="prodNama" required placeholder="Kipas Angin Dinding 16 Inch"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                        <select name="kategori_id" id="prodKategori" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Harga (Rp)</label>
                            <input type="number" name="harga" id="prodHarga" required placeholder="350000" min="0"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Stok Awal</label>
                            <input type="number" name="stok" id="prodStok" required placeholder="10" min="0"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" id="btnCancelEditProd" onclick="resetFormProduk()"
                            class="hidden w-1/3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium text-sm rounded-lg transition">
                            Batal
                        </button>
                        <button type="submit" id="btnSaveProd"
                            class="flex-grow py-2 bg-brand-600 hover:bg-brand-700 text-white font-medium text-sm rounded-lg transition">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Product Table (8 cols) --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-3">
                    <h3 class="font-bold text-slate-800">Daftar Stok Produk Elektronik</h3>
                    <div class="relative w-full sm:w-64">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                        <input type="text" id="searchTableProduk" oninput="renderProdukTable()"
                            placeholder="Filter tabel produk..."
                            class="w-full pl-8 pr-3 py-1.5 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="p-3 font-semibold">Kode</th>
                                <th class="p-3 font-semibold">Nama Produk</th>
                                <th class="p-3 font-semibold">Kategori</th>
                                <th class="p-3 font-semibold">Harga</th>
                                <th class="p-3 font-semibold">Stok</th>
                                <th class="p-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="produkTableBody" class="divide-y divide-slate-200">
                            {{-- Dynamic via JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ======================================================
     TAB 3: DATA PELANGGAN
     ====================================================== --}}
<div id="tab-pelanggan" class="tab-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Form Tambah Pelanggan (4 cols) --}}
        <div class="lg:col-span-4">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-brand-600"></i> Registration Pelanggan
                </h3>
                <form id="formPelanggan" action="{{ route('pelanggan.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_pelanggan" id="pelNama" required placeholder="Budi Santoso"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" id="pelHp" placeholder="081234567890"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Alamat Singkat</label>
                        <textarea name="alamat" id="pelAlamat" rows="3" placeholder="Jl. Pemuda No. 45, Jepara"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-medium text-sm rounded-lg transition">
                        Simpan Pelanggan
                    </button>
                </form>
            </div>
        </div>

        {{-- List Pelanggan (8 cols) --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Daftar Pelanggan Terdaftar</h3>
                    <span id="pelangganCount" class="text-xs bg-slate-200 text-slate-700 px-2 py-1 rounded-full font-medium">0 orang</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="p-3 font-semibold">Nama</th>
                                <th class="p-3 font-semibold">No. HP</th>
                                <th class="p-3 font-semibold">Alamat</th>
                                <th class="p-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pelangganTableBody" class="divide-y divide-slate-200">
                            {{-- Dynamic via JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ======================================================
     TAB 4: RIWAYAT & LAPORAN
     ====================================================== --}}
<div id="tab-riwayat" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Riwayat Transaksi Toko</h3>
                <p class="text-xs text-slate-500">Semua catatan penjualan barang elektronik yang berhasil diproses</p>
            </div>
            <div class="flex gap-2">
                <input type="text" id="searchRiwayat" oninput="renderRiwayatTable()"
                    placeholder="Cari Nota / Pelanggan..."
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none w-full sm:w-64">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="p-3 font-semibold">No. Nota</th>
                        <th class="p-3 font-semibold">Waktu</th>
                        <th class="p-3 font-semibold">Pelanggan</th>
                        <th class="p-3 font-semibold">Rincian Item</th>
                        <th class="p-3 font-semibold text-right">Total Tagihan</th>
                        <th class="p-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="riwayatTableBody" class="divide-y divide-slate-200">
                    {{-- Dynamic via JS --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================================================
     MODAL: QUICK ADD PELANGGAN
     ====================================================== --}}
<div id="modalPelanggan" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
        <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
            <h3 class="font-bold text-sm">Tambah Pelanggan Baru</h3>
            <button onclick="closeModal('modalPelanggan')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-5 space-y-3">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Pelanggan</label>
                <input type="text" id="modalPelNama" required
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">No HP</label>
                <input type="text" id="modalPelHp"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Alamat</label>
                <textarea id="modalPelAlamat" rows="2"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modalPelanggan')"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg">Batal</button>
                <button type="button" onclick="savePelangganModal()"
                    class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium rounded-lg">Simpan &amp; Pilih</button>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================
     MODAL: STRUK PEMBAYARAN
     ====================================================== --}}
<div id="receiptModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full overflow-hidden receipt-box my-auto">
        <div class="p-4 bg-slate-900 text-white flex justify-between items-center no-print">
            <h3 class="font-bold text-sm">Struk Pembayaran</h3>
            <button onclick="closeModal('receiptModal')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-6 font-mono text-xs text-slate-800 bg-white" id="receiptContent">
            {{-- Populated by JS --}}
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 flex gap-2 no-print">
            <button onclick="window.print()"
                class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition flex items-center justify-center gap-1">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </button>
            <button onclick="closeModal('receiptModal')"
                class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg text-xs transition">
                Transaksi Baru
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Server-side embedded data
const initialKategoris   = @json($kategoris);
const initialProduks     = @json($produks);
const initialPelanggans  = @json($pelanggans);
const initialTransaksis  = @json($transaksisFormatted);

// ==================== APP STATE ====================
let kategoris   = [];
let produks     = [];
let pelanggans  = [];
let transaksis  = [];
let cart        = [];

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ==================== INIT ====================
document.addEventListener('DOMContentLoaded', () => {
    initApp();
    // Show active tab from session storage (preserves tab on page reload)
    const activeTab = sessionStorage.getItem('activeTab') || 'kasir';
    switchTab(activeTab);

    @if(session('success'))
        showToast('{{ session("success") }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session("error") }}', 'error');
    @endif
});

async function initApp() {
    await fetchData();
    renderPosCatalogOptions();
    renderPosCatalog();
    renderCart();
    renderPelangganDropdown();
    renderProdukTable();
    renderPelangganTable();
    renderRiwayatTable();
    updateDashboardStats();
}

async function fetchData() {
    try {
        const [produks_res, pelanggans_res, transaksis_res] = await Promise.all([
            fetch('/api/produks').then(r => r.json()),
            fetch('/api/pelanggans').then(r => r.json()),
            fetch('/api/transaksis').then(r => r.json()),
        ]);
        kategoris   = extractKategoris(produks_res);
        produks     = produks_res;
        pelanggans  = pelanggans_res;
        transaksis  = transaksis_res;
    } catch (err) {
        console.warn('API not available, loading from embedded data.', err);
        kategoris   = initialKategoris;
        produks     = initialProduks;
        pelanggans  = initialPelanggans;
        transaksis  = initialTransaksis;
    }
}

// Extract unique kategoris from produks array
function extractKategoris(produks_arr) {
    if (!produks_arr || produks_arr.length === 0) return initialKategoris;
    const map = {};
    produks_arr.forEach(p => {
        if (p.kategori) {
            map[p.kategori.id] = p.kategori;
        }
    });
    const result = Object.values(map);
    return result.length > 0 ? result : initialKategoris;
}

// ==================== TAB NAVIGATION ====================
function switchTab(tabName) {
    sessionStorage.setItem('activeTab', tabName);
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    const target = document.getElementById(`tab-${tabName}`);
    if (target) target.classList.remove('hidden');

    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('bg-brand-600', 'text-white', 'shadow');
        btn.classList.add('text-slate-300', 'hover:bg-slate-800');
    });
    const navBtn = document.getElementById(`nav-${tabName}`);
    if (navBtn) {
        navBtn.classList.add('bg-brand-600', 'text-white', 'shadow');
        navBtn.classList.remove('text-slate-300', 'hover:bg-slate-800');
    }

    document.querySelectorAll('.mobile-nav-btn').forEach(btn => {
        btn.classList.remove('bg-brand-600', 'text-white');
        btn.classList.add('text-slate-300');
    });
    const mobileBtn = document.getElementById(`mobile-nav-${tabName}`);
    if (mobileBtn) {
        mobileBtn.classList.add('bg-brand-600', 'text-white');
        mobileBtn.classList.remove('text-slate-300');
    }
}

// ==================== DASHBOARD STATS ====================
function updateDashboardStats() {
    const allTrx      = transaksis.length > 0 ? transaksis : initialTransaksis;
    const allProds    = produks.length > 0 ? produks : initialProduks;

    const totalOmzet  = allTrx.reduce((sum, t) => sum + (parseFloat(t.total_harga) || 0), 0);
    const totalTrx    = allTrx.length;
    const totalProd   = allProds.length;
    const stokMenipis = allProds.filter(p => parseInt(p.stok) <= 5).length;

    const elOmzet = document.getElementById('statOmzet');
    const elTrx   = document.getElementById('statTransaksi');
    const elProd  = document.getElementById('statProduk');
    const elStok  = document.getElementById('statStokAlerta');

    if (elOmzet) elOmzet.innerText = formatRupiah(totalOmzet);
    if (elTrx)   elTrx.innerText   = totalTrx;
    if (elProd)  elProd.innerText  = totalProd;
    if (elStok)  elStok.innerText  = stokMenipis;
}

// ==================== FORMAT HELPERS ====================
function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
}

// ==================== POS CATALOG ====================
function renderPosCatalogOptions() {
    const select = document.getElementById('filterPosCategory');
    if (!select) return;
    select.innerHTML = '<option value="">Semua Kategori</option>';

    const allKats = kategoris.length > 0 ? kategoris : initialKategoris;
    allKats.forEach(k => {
        select.innerHTML += `<option value="${k.id}">${k.nama_kategori}</option>`;
    });
}

function renderPosCatalog() {
    const searchInput = document.getElementById('searchPos');
    const search      = (searchInput ? searchInput.value : '').toLowerCase();
    const categoryId  = document.getElementById('filterPosCategory')?.value || '';
    const container   = document.getElementById('posCatalogGrid');
    if (!container) return;

    const allProduks = produks.length > 0 ? produks : initialProduks;

    const filtered = allProduks.filter(p => {
        const matchSearch = p.nama_produk.toLowerCase().includes(search) || p.kode_produk.toLowerCase().includes(search);
        const catId = p.kategori ? p.kategori.id : p.kategori_id;
        const matchCat = categoryId === '' || String(catId) === String(categoryId);
        const inStock  = parseInt(p.stok) > 0;
        return matchSearch && matchCat && inStock;
    });

    container.innerHTML = '';
    if (filtered.length === 0) {
        container.innerHTML = `<div class="col-span-full bg-white p-8 rounded-xl text-center text-slate-400 border border-slate-200">
            <i class="fa-solid fa-box-open text-3xl mb-2"></i>
            <p class="text-sm">Tidak ada produk ditemukan</p>
        </div>`;
        return;
    }

    filtered.forEach(p => {
        const katName = p.kategori ? p.kategori.nama_kategori : '-';
        const stok    = parseInt(p.stok);
        const harga   = parseFloat(p.harga);

        const stokBadge = stok > 5
            ? 'bg-emerald-100 text-emerald-700'
            : stok > 0 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700';

        container.innerHTML += `
            <div onclick="addToCart(${p.id})"
                class="bg-white rounded-xl p-3 shadow-sm border border-slate-200 transition-all duration-150 flex flex-col justify-between hover:shadow-md hover:border-brand-300 cursor-pointer">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">${p.kode_produk}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold ${stokBadge}">
                            Stok: ${stok}
                        </span>
                    </div>
                    <h4 class="font-bold text-xs text-slate-800 line-clamp-2 mb-1">${p.nama_produk}</h4>
                    <p class="text-[11px] text-slate-400 mb-2">${katName}</p>
                </div>
                <div class="pt-2 border-t border-slate-100 flex justify-between items-center">
                    <span class="font-bold text-xs text-brand-700">${formatRupiah(harga)}</span>
                    <button class="w-7 h-7 rounded-lg bg-brand-50 hover:bg-brand-600 text-brand-600 hover:text-white flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
        `;
    });
}

// ==================== CART LOGIC ====================
function addToCart(productId) {
    const allProduks = produks.length > 0 ? produks : initialProduks;
    const product = allProduks.find(p => p.id === productId || p.id == productId);
    if (!product) return;

    const stok = parseInt(product.stok);
    const existing = cart.find(item => String(item.id) === String(productId));

    if (existing) {
        if (existing.qty + 1 > stok) {
            showToast(`Stok ${product.nama_produk} terbatas (${stok} unit)!`, 'warning');
            return;
        }
        existing.qty++;
    } else {
        if (stok < 1) {
            showToast('Stok barang ini habis!', 'error');
            return;
        }
        cart.push({
            id:    product.id,
            kode:  product.kode_produk,
            nama:  product.nama_produk,
            harga: parseFloat(product.harga),
            qty:   1,
            stok:  stok,
        });
    }
    renderCart();
    showToast(`${product.nama_produk} ditambahkan ke keranjang`, 'info');
}

function updateCartQty(productId, delta) {
    const item = cart.find(i => String(i.id) === String(productId));
    if (!item) return;

    const newQty = item.qty + delta;
    if (newQty <= 0) { removeFromCart(productId); return; }
    if (newQty > item.stok) {
        showToast(`Stok hanya tersisa ${item.stok} unit!`, 'warning');
        return;
    }
    item.qty = newQty;
    renderCart();
}

function removeFromCart(productId) {
    cart = cart.filter(i => String(i.id) !== String(productId));
    renderCart();
}

function clearCart() {
    if (cart.length === 0) return;
    cart = [];
    renderCart();
    showToast('Keranjang dikosongkan', 'info');
}

function renderCart() {
    const container = document.getElementById('cartItemsContainer');
    if (!container) return;
    container.innerHTML = '';

    if (cart.length === 0) {
        container.innerHTML = `<div class="py-12 text-center text-slate-400">
            <i class="fa-solid fa-cart-arrow-down text-3xl mb-2"></i>
            <p class="text-xs">Keranjang masih kosong.<br>Klik produk di sebelah kiri untuk memilih.</p>
        </div>`;
        document.getElementById('cartSubtotal').innerText = formatRupiah(0);
        document.getElementById('cartTotal').innerText    = formatRupiah(0);
        calculateChange();
        return;
    }

    let total = 0;
    cart.forEach(item => {
        const subtotal = item.harga * item.qty;
        total += subtotal;

        container.innerHTML += `
            <div class="py-2.5 flex items-center justify-between gap-2">
                <div class="flex-grow min-w-0">
                    <h5 class="text-xs font-bold text-slate-800 truncate">${item.nama}</h5>
                    <span class="text-[11px] text-slate-500">${formatRupiah(item.harga)}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <button onclick="updateCartQty(${item.id}, -1)" class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center justify-center">-</button>
                    <span class="text-xs font-bold w-6 text-center">${item.qty}</span>
                    <button onclick="updateCartQty(${item.id}, 1)" class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center justify-center">+</button>
                </div>
                <div class="text-right min-w-[70px]">
                    <div class="text-xs font-bold text-slate-900">${formatRupiah(subtotal)}</div>
                    <button onclick="removeFromCart(${item.id})" class="text-[10px] text-rose-500 hover:text-rose-700">Hapus</button>
                </div>
            </div>
        `;
    });

    document.getElementById('cartSubtotal').innerText = formatRupiah(total);
    document.getElementById('cartTotal').innerText    = formatRupiah(total);
    calculateChange();
}

function calculateChange() {
    const total      = cart.reduce((sum, i) => sum + (i.harga * i.qty), 0);
    const payInput   = parseFloat(document.getElementById('payAmountInput')?.value) || 0;
    const changeDisp = document.getElementById('changeDisplay');
    if (!changeDisp) return;
    const kembalian  = payInput - total;

    if (payInput === 0) {
        changeDisp.innerText   = formatRupiah(0);
        changeDisp.className   = 'font-bold text-slate-500';
    } else if (kembalian < 0) {
        changeDisp.innerText   = `Kurang ${formatRupiah(Math.abs(kembalian))}`;
        changeDisp.className   = 'font-bold text-rose-600';
    } else {
        changeDisp.innerText   = formatRupiah(kembalian);
        changeDisp.className   = 'font-bold text-emerald-600';
    }
}

function setQuickPay(value) {
    const total = cart.reduce((sum, i) => sum + (i.harga * i.qty), 0);
    const input = document.getElementById('payAmountInput');
    if (input) {
        input.value = (value === 'pas') ? total : value;
    }
    calculateChange();
}

async function processCheckout() {
    if (cart.length === 0) {
        showToast('Keranjang belanja masih kosong!', 'error');
        return;
    }

    const total    = cart.reduce((sum, i) => sum + (i.harga * i.qty), 0);
    const payInput = parseFloat(document.getElementById('payAmountInput')?.value) || 0;

    if (payInput < total) {
        showToast('Uang pembayaran masih kurang!', 'error');
        return;
    }

    const pelangganId = document.getElementById('cartCustomer')?.value || null;

    const payload = {
        pelanggan_id: pelangganId || null,
        bayar:        payInput,
        items:        cart.map(i => ({ produk_id: i.id, qty: i.qty })),
    };

    try {
        const response = await fetch('/api/transaksi', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept':       'application/json',
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const err = await response.json().catch(() => ({}));
            showToast(err.message || 'Transaksi gagal diproses!', 'error');
            return;
        }

        const data = await response.json();
        showToast('Transaksi berhasil diproses!', 'success');

        const allPelanggans = pelanggans.length > 0 ? pelanggans : initialPelanggans;
        const pelangganObj  = allPelanggans.find(p => String(p.id) === String(pelangganId));
        const namaPelanggan = pelangganObj ? pelangganObj.nama_pelanggan : 'Pelanggan Umum';

        const trx = {
            no_nota:        data.no_nota || `TRX-${Date.now()}`,
            created_at:     data.created_at || new Date().toLocaleString('id-ID'),
            pelanggan_nama: data.pelanggan ? data.pelanggan.nama_pelanggan : namaPelanggan,
            items:          data.items || cart.map(i => ({ nama: i.nama, harga: i.harga, qty: i.qty })),
            total_harga:    data.total_harga || total,
            bayar:          data.bayar || payInput,
            kembalian:      data.kembalian !== undefined ? data.kembalian : (payInput - total),
        };

        showReceipt(trx);

        // Reset cart
        cart = [];
        const payEl = document.getElementById('payAmountInput');
        if (payEl) payEl.value = '';
        renderCart();

        // Refresh data from API
        await fetchData();
        renderPosCatalog();
        renderProdukTable();
        renderRiwayatTable();
        updateDashboardStats();

    } catch (err) {
        console.error('Checkout error:', err);
        showToast('Gagal memproses transaksi!', 'error');
    }
}

// ==================== RECEIPT MODAL ====================
function showReceipt(trx) {
    const container = document.getElementById('receiptContent');
    if (!container) return;

    let itemsHtml = '';
    (trx.items || []).forEach(item => {
        const nama     = item.nama || item.nama_produk || '-';
        const harga    = parseFloat(item.harga || 0);
        const qty      = parseInt(item.qty || item.jumlah || 1);
        const subtotal = item.subtotal ? parseFloat(item.subtotal) : (harga * qty);
        itemsHtml += `
            <div class="mb-1">
                <div class="font-bold">${nama}</div>
                <div class="flex justify-between text-[11px]">
                    <span>${qty} x ${formatRupiah(harga)}</span>
                    <span>${formatRupiah(subtotal)}</span>
                </div>
            </div>
        `;
    });

    container.innerHTML = `
        <div class="text-center mb-3">
            <div class="font-bold text-sm">TOKO ELEKTRONIK MAJU JAYA</div>
            <div class="text-[10px]">Jl. Pemuda No. 88, Jepara</div>
            <div class="text-[10px]">Telp/WA: 0812-3456-7890</div>
        </div>
        <div class="border-b border-dashed border-slate-400 my-2"></div>
        <div class="space-y-0.5 text-[11px]">
            <div class="flex justify-between"><span>No. Nota:</span><span>${trx.no_nota}</span></div>
            <div class="flex justify-between"><span>Tanggal:</span><span>${trx.created_at}</span></div>
            <div class="flex justify-between"><span>Pelanggan:</span><span>${trx.pelanggan_nama || trx.pelanggan?.nama_pelanggan || 'Pelanggan Umum'}</span></div>
        </div>
        <div class="border-b border-dashed border-slate-400 my-2"></div>
        <div class="py-1">${itemsHtml}</div>
        <div class="border-b border-dashed border-slate-400 my-2"></div>
        <div class="space-y-0.5 text-[11px]">
            <div class="flex justify-between font-bold text-xs"><span>TOTAL:</span><span>${formatRupiah(trx.total_harga)}</span></div>
            <div class="flex justify-between"><span>BAYAR:</span><span>${formatRupiah(trx.bayar)}</span></div>
            <div class="flex justify-between"><span>KEMBALI:</span><span>${formatRupiah(trx.kembalian)}</span></div>
        </div>
        <div class="border-b border-dashed border-slate-400 my-2"></div>
        <div class="text-center text-[10px] space-y-1 mt-3">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p class="text-[9px] text-slate-500">Garansi resmi toko 7 hari sejak tanggal pembelian dengan membawa nota ini.</p>
        </div>
    `;

    openModal('receiptModal');
}

// ==================== PRODUK TABLE (Tab 2) ====================
function renderProdukTable() {
    const search = (document.getElementById('searchTableProduk')?.value || '').toLowerCase();
    const tbody  = document.getElementById('produkTableBody');
    if (!tbody) return;

    const allProduks = produks.length > 0 ? produks : initialProduks;

    const filtered = allProduks.filter(p =>
        p.nama_produk.toLowerCase().includes(search) ||
        p.kode_produk.toLowerCase().includes(search)
    );

    tbody.innerHTML = '';
    if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400">Tidak ada data produk.</td></tr>`;
        return;
    }

    filtered.forEach(p => {
        const katName    = p.kategori ? p.kategori.nama_kategori : '-';
        const stok       = parseInt(p.stok);
        const badgeClass = stok > 5 ? 'bg-emerald-100 text-emerald-800'
                         : stok > 0 ? 'bg-amber-100 text-amber-800'
                         : 'bg-rose-100 text-rose-800';

        tbody.innerHTML += `
            <tr class="hover:bg-slate-50 transition">
                <td class="p-3 font-mono text-xs font-bold">${p.kode_produk}</td>
                <td class="p-3 font-semibold text-slate-900">${p.nama_produk}</td>
                <td class="p-3 text-slate-600">${katName}</td>
                <td class="p-3 font-semibold text-slate-900">${formatRupiah(parseFloat(p.harga))}</td>
                <td class="p-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ${badgeClass}">${stok} unit</span>
                </td>
                <td class="p-3 text-center">
                    <div class="flex justify-center gap-1">
                        <button onclick="editProduk(${p.id})"
                            class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="deleteProduk(${p.id})"
                            class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded transition" title="Hapus">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

function editProduk(id) {
    const allProduks = produks.length > 0 ? produks : initialProduks;
    const p = allProduks.find(item => item.id == id || String(item.id) === String(id));
    if (!p) return;

    switchTab('produk');

    document.getElementById('formProdukTitle').innerHTML =
        '<i class="fa-solid fa-pen-to-square text-brand-600"></i> Edit Produk Elektronik';
    document.getElementById('prodEditId').value      = p.id;
    document.getElementById('prodKode').value        = p.kode_produk;
    document.getElementById('prodNama').value        = p.nama_produk;
    document.getElementById('prodHarga').value       = p.harga;
    document.getElementById('prodStok').value        = p.stok;

    const katSelect = document.getElementById('prodKategori');
    const katId     = p.kategori ? p.kategori.id : p.kategori_id;
    if (katSelect) katSelect.value = katId;

    const form = document.getElementById('formProduk');
    if (form) form.action = `/produk/${p.id}`;
    const methodInput = document.getElementById('prodMethodInput');
    if (methodInput) methodInput.value = 'PUT';

    document.getElementById('btnCancelEditProd')?.classList.remove('hidden');
    const btnSave = document.getElementById('btnSaveProd');
    if (btnSave) btnSave.innerText = 'Update Produk';

    form?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function resetFormProduk() {
    document.getElementById('formProdukTitle').innerHTML =
        '<i class="fa-solid fa-plus-circle text-brand-600"></i> Tambah Produk Elektronik';
    document.getElementById('prodEditId').value   = '';
    document.getElementById('prodKode').value     = '';
    document.getElementById('prodNama').value     = '';
    document.getElementById('prodKategori').value = '';
    document.getElementById('prodHarga').value    = '';
    document.getElementById('prodStok').value     = '';

    const form = document.getElementById('formProduk');
    if (form) form.action = '{{ route("produk.store") }}';
    const methodInput = document.getElementById('prodMethodInput');
    if (methodInput) methodInput.value = 'POST';

    document.getElementById('btnCancelEditProd')?.classList.add('hidden');
    const btnSave = document.getElementById('btnSaveProd');
    if (btnSave) btnSave.innerText = 'Simpan Produk';
}

function deleteProduk(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/produk/${id}`;
    form.innerHTML = `
        <input type="hidden" name="_token" value="${CSRF_TOKEN}">
        <input type="hidden" name="_method" value="DELETE">
    `;
    document.body.appendChild(form);
    form.submit();
}

// ==================== PELANGGAN MODULE ====================
function renderPelangganDropdown() {
    const select = document.getElementById('cartCustomer');
    if (!select) return;
    select.innerHTML = '<option value="">Pelanggan Umum (Cash/Walk-in)</option>';

    const allPelanggans = pelanggans.length > 0 ? pelanggans : initialPelanggans;
    allPelanggans.forEach(p => {
        select.innerHTML += `<option value="${p.id}">${p.nama_pelanggan} (${p.no_hp || '-'})</option>`;
    });
}

function renderPelangganTable() {
    const tbody = document.getElementById('pelangganTableBody');
    if (!tbody) return;

    const allPelanggans = pelanggans.length > 0 ? pelanggans : initialPelanggans;

    const countEl = document.getElementById('pelangganCount');
    if (countEl) countEl.innerText = `${allPelanggans.length} orang`;

    tbody.innerHTML = '';
    if (allPelanggans.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">Belum ada pelanggan terdaftar.</td></tr>`;
        return;
    }

    allPelanggans.forEach(p => {
        tbody.innerHTML += `
            <tr class="hover:bg-slate-50 transition">
                <td class="p-3 font-semibold text-slate-900">${p.nama_pelanggan}</td>
                <td class="p-3 text-slate-600">${p.no_hp || '-'}</td>
                <td class="p-3 text-slate-600">${p.alamat || '-'}</td>
                <td class="p-3 text-center">
                    <button onclick="deletePelanggan(${p.id})"
                        class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded transition">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

async function savePelangganModal() {
    const nama   = document.getElementById('modalPelNama').value.trim();
    const hp     = document.getElementById('modalPelHp').value.trim();
    const alamat = document.getElementById('modalPelAlamat').value.trim();

    if (!nama) { showToast('Nama pelanggan wajib diisi!', 'error'); return; }

    try {
        const response = await fetch('/api/pelanggan', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ nama_pelanggan: nama, no_hp: hp, alamat: alamat }),
        });

        if (!response.ok) {
            const err = await response.json().catch(() => ({}));
            showToast(err.message || 'Gagal menambah pelanggan!', 'error');
            return;
        }

        const newP = await response.json();
        pelanggans.push(newP);
        renderPelangganDropdown();
        renderPelangganTable();

        const select = document.getElementById('cartCustomer');
        if (select) select.value = newP.id;

        closeModal('modalPelanggan');
        document.getElementById('modalPelNama').value   = '';
        document.getElementById('modalPelHp').value     = '';
        document.getElementById('modalPelAlamat').value = '';
        showToast('Pelanggan ditambahkan dan dipilih!', 'success');

    } catch (err) {
        showToast('Gagal menghubungi server!', 'error');
    }
}

function deletePelanggan(id) {
    if (!confirm('Hapus data pelanggan ini?')) return;

    fetch(`/api/pelanggan/${id}`, {
        method:  'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
    }).then(async r => {
        if (r.ok) {
            pelanggans = pelanggans.filter(p => String(p.id) !== String(id));
            renderPelangganDropdown();
            renderPelangganTable();
            showToast('Pelanggan dihapus', 'info');
        } else {
            showToast('Gagal menghapus pelanggan', 'error');
        }
    }).catch(() => {
        showToast('Gagal terhubung ke server', 'error');
    });
}

// ==================== RIWAYAT TABLE (Tab 4) ====================
function renderRiwayatTable() {
    const search = (document.getElementById('searchRiwayat')?.value || '').toLowerCase();
    const tbody  = document.getElementById('riwayatTableBody');
    if (!tbody) return;

    const allTrx = transaksis.length > 0 ? transaksis : initialTransaksis;

    const filtered = allTrx.filter(t => {
        const nota      = (t.no_nota || '').toLowerCase();
        const pelanggan = (t.pelanggan_nama || (t.pelanggan && t.pelanggan.nama_pelanggan) || '').toLowerCase();
        return nota.includes(search) || pelanggan.includes(search);
    });

    tbody.innerHTML = '';
    if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-400">Belum ada riwayat transaksi.</td></tr>`;
        return;
    }

    filtered.forEach(t => {
        const pelangganNama = t.pelanggan_nama || (t.pelanggan && t.pelanggan.nama_pelanggan) || 'Pelanggan Umum';
        const items         = t.items || [];
        const itemsSummary  = items.map(i => {
            const nama = i.nama || i.nama_produk || (i.produk && i.produk.nama_produk) || '-';
            const qty  = i.qty || i.jumlah || 1;
            return `${nama} (${qty}x)`;
        }).join(', ');

        const createdAt = t.created_at || '-';

        tbody.innerHTML += `
            <tr class="hover:bg-slate-50 transition">
                <td class="p-3 font-mono font-bold text-brand-700">${t.no_nota}</td>
                <td class="p-3 text-xs text-slate-500">${createdAt}</td>
                <td class="p-3 font-medium text-slate-800">${pelangganNama}</td>
                <td class="p-3 text-xs text-slate-600 max-w-xs truncate">${itemsSummary}</td>
                <td class="p-3 font-bold text-right text-slate-900">${formatRupiah(parseFloat(t.total_harga))}</td>
                <td class="p-3 text-center">
                    <button onclick="reprintStruk(${t.id})"
                        class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-medium transition flex items-center gap-1 mx-auto">
                        <i class="fa-solid fa-print"></i> Struk
                    </button>
                </td>
            </tr>
        `;
    });
}

async function reprintStruk(trxId) {
    // Try API first
    try {
        const response = await fetch(`/api/transaksis/${trxId}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            const trx = await response.json();
            const pelangganNama = trx.pelanggan_nama || (trx.pelanggan && trx.pelanggan.nama_pelanggan) || 'Pelanggan Umum';
            const items = trx.items || [];

            showReceipt({
                no_nota:        trx.no_nota,
                created_at:     trx.created_at,
                pelanggan_nama: pelangganNama,
                items:          items,
                total_harga:    parseFloat(trx.total_harga),
                bayar:          parseFloat(trx.bayar),
                kembalian:      parseFloat(trx.kembalian),
            });
            return;
        }
    } catch (err) { /* fall through */ }

    // Fallback: use local data
    const allTrx = transaksis.length > 0 ? transaksis : initialTransaksis;
    const trx = allTrx.find(t => String(t.id) === String(trxId));
    if (trx) {
        showReceipt({
            no_nota:        trx.no_nota,
            created_at:     trx.created_at,
            pelanggan_nama: trx.pelanggan_nama || (trx.pelanggan && trx.pelanggan.nama_pelanggan) || 'Pelanggan Umum',
            items:          trx.items || [],
            total_harga:    parseFloat(trx.total_harga),
            bayar:          parseFloat(trx.bayar),
            kembalian:      parseFloat(trx.kembalian),
        });
    } else {
        window.open(`/transaksi/${trxId}/struk`, '_blank');
    }
}
</script>
@endpush
