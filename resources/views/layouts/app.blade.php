<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Maju Jaya POS — Sistem Kasir Toko Elektronik</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Print styles for receipts */
        @media print {
            body * { visibility: hidden; }
            #receiptModal, #receiptModal * { visibility: visible; }
            #receiptModal {
                position: absolute;
                left: 0; top: 0;
                width: 100%;
                background: white;
                display: block !important;
            }
            .no-print { display: none !important; }
            .receipt-box {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 80mm !important;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 min-h-screen flex flex-col">

    <!-- HEADER / NAVIGATION BAR -->
    <header class="bg-slate-900 text-white shadow-lg sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Title -->
                <div class="flex items-center space-x-3">
                    <div class="bg-brand-600 p-2 rounded-lg flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-bolt text-xl"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-tight">Maju Jaya POS</h1>
                        <p class="text-xs text-slate-400">Sistem Kasir Toko Elektronik</p>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <nav class="hidden md:flex space-x-1">
                    <button onclick="switchTab('kasir')" id="nav-kasir"
                        class="nav-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 flex items-center gap-2 bg-brand-600 text-white shadow">
                        <i class="fa-solid fa-cash-register"></i> Kasir &amp; Transaksi
                    </button>
                    <button onclick="switchTab('produk')" id="nav-produk"
                        class="nav-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 flex items-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800">
                        <i class="fa-solid fa-tv"></i> Produk &amp; Kategori
                    </button>
                    <button onclick="switchTab('pelanggan')" id="nav-pelanggan"
                        class="nav-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 flex items-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800">
                        <i class="fa-solid fa-users"></i> Pelanggan
                    </button>
                    <button onclick="switchTab('riwayat')" id="nav-riwayat"
                        class="nav-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 flex items-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800">
                        <i class="fa-solid fa-receipt"></i> Riwayat &amp; Laporan
                    </button>
                </nav>

                <!-- Clock & Reset -->
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div id="liveClock" class="font-mono text-sm font-medium text-slate-200">00:00:00</div>
                        <div id="liveDate" class="text-xs text-slate-400">1 Jan 2026</div>
                    </div>
                    <a href="{{ url('/') }}" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-md border border-slate-700 transition" title="Reload halaman">
                        <i class="fa-solid fa-rotate-right me-1"></i> Reset Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden bg-slate-800 px-2 pt-2 pb-3 flex space-x-1 overflow-x-auto border-t border-slate-700">
            <button onclick="switchTab('kasir')" id="mobile-nav-kasir"
                class="mobile-nav-btn whitespace-nowrap px-3 py-1.5 rounded-md text-xs font-medium text-white bg-brand-600">Kasir</button>
            <button onclick="switchTab('produk')" id="mobile-nav-produk"
                class="mobile-nav-btn whitespace-nowrap px-3 py-1.5 rounded-md text-xs font-medium text-slate-300 hover:bg-slate-700">Produk</button>
            <button onclick="switchTab('pelanggan')" id="mobile-nav-pelanggan"
                class="mobile-nav-btn whitespace-nowrap px-3 py-1.5 rounded-md text-xs font-medium text-slate-300 hover:bg-slate-700">Pelanggan</button>
            <button onclick="switchTab('riwayat')" id="mobile-nav-riwayat"
                class="mobile-nav-btn whitespace-nowrap px-3 py-1.5 rounded-md text-xs font-medium text-slate-300 hover:bg-slate-700">Riwayat</button>
        </div>
    </header>

    <!-- DASHBOARD STATS SUMMARY BAR -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 w-full">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Total Omzet -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200/80 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Omzet</p>
                    <h3 id="statOmzet" class="text-lg sm:text-xl font-bold text-slate-900 mt-1">Rp 0</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-lg"></i>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200/80 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Transaksi</p>
                    <h3 id="statTransaksi" class="text-lg sm:text-xl font-bold text-slate-900 mt-1">0</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-shopping-bag text-lg"></i>
                </div>
            </div>

            <!-- Jumlah Produk -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200/80 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Jumlah Produk</p>
                    <h3 id="statProduk" class="text-lg sm:text-xl font-bold text-slate-900 mt-1">0</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-boxes-stacked text-lg"></i>
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200/80 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Stok Menipis (≤5)</p>
                    <h3 id="statStokAlerta" class="text-lg sm:text-xl font-bold text-amber-600 mt-1">0</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; 2026 Toko Elektronik Maju Jaya — System Kasir POS v2.0
        </div>
    </footer>

    <!-- TOAST NOTIFICATION CONTAINER -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50 space-y-2 pointer-events-none"></div>

    <script>
        // Live Clock
        function startLiveClock() {
            function update() {
                const now = new Date();
                document.getElementById('liveClock').innerText = now.toLocaleTimeString('id-ID');
                document.getElementById('liveDate').innerText = now.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            }
            update();
            setInterval(update, 1000);
        }

        // Toast Helper
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            let bg = 'bg-slate-800 text-white';
            let icon = 'fa-info-circle';
            if (type === 'success') { bg = 'bg-emerald-600 text-white'; icon = 'fa-check-circle'; }
            if (type === 'error')   { bg = 'bg-rose-600 text-white';    icon = 'fa-exclamation-circle'; }
            if (type === 'warning') { bg = 'bg-amber-500 text-white';   icon = 'fa-triangle-exclamation'; }

            toast.className = `flex items-center gap-2 px-4 py-2.5 rounded-lg shadow-lg ${bg} text-xs font-medium transition-all transform translate-y-2 opacity-0 duration-200`;
            toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${message}</span>`;
            container.appendChild(toast);

            setTimeout(() => toast.classList.remove('translate-y-2', 'opacity-0'), 10);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 200);
            }, 3000);
        }

        // Modal helpers
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        document.addEventListener('DOMContentLoaded', () => { startLiveClock(); });
    </script>

    @stack('scripts')
</body>
</html>
