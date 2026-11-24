<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran — {{ $transaksi->no_nota }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: JetBrains Mono for receipt look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

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
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .receipt-wrapper {
                width: 80mm !important;
                max-width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans min-h-screen flex flex-col items-center justify-start py-8 px-4">

    <!-- Action Buttons (no-print) -->
    <div class="no-print mb-6 flex gap-3 w-full max-w-sm">
        <a href="{{ url('/') }}"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm rounded-xl transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Kasir
        </a>
        <button onclick="window.print()"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition">
            <i class="fa-solid fa-print"></i> Cetak Struk
        </button>
    </div>

    <!-- Receipt Card -->
    <div class="receipt-wrapper bg-white shadow-xl rounded-2xl overflow-hidden w-full max-w-sm">

        <!-- Receipt Header (Store Info) -->
        <div class="bg-slate-900 text-white p-5 text-center no-print">
            <div class="flex items-center justify-center gap-2 mb-1">
                <div class="bg-brand-600 p-1.5 rounded-lg">
                    <i class="fa-solid fa-bolt text-sm"></i>
                </div>
                <span class="font-bold text-base">Maju Jaya POS</span>
            </div>
            <p class="text-xs text-slate-400">Struk Pembayaran — Bukti Transaksi Resmi</p>
        </div>

        <!-- Printable Receipt Content -->
        <div class="p-6 font-mono text-xs text-slate-800 bg-white">

            <!-- Store Header -->
            <div class="text-center mb-4">
                <div class="font-bold text-sm tracking-wide">TOKO ELEKTRONIK MAJU JAYA</div>
                <div class="text-[11px] text-slate-600">Jl. Pemuda No. 88, Jepara</div>
                <div class="text-[11px] text-slate-600">Telp/WA: 0812-3456-7890</div>
            </div>

            <div class="border-b-2 border-dashed border-slate-300 my-3"></div>

            <!-- Transaction Info -->
            <div class="space-y-1 text-[11px]">
                <div class="flex justify-between">
                    <span class="text-slate-500">No. Nota</span>
                    <span class="font-bold text-right">{{ $transaksi->no_nota }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tanggal</span>
                    <span>{{ $transaksi->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Pelanggan</span>
                    <span class="font-semibold">
                        {{ $transaksi->pelanggan ? $transaksi->pelanggan->nama_pelanggan : 'Pelanggan Umum' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Kasir</span>
                    <span>Kasir Maju Jaya</span>
                </div>
            </div>

            <div class="border-b-2 border-dashed border-slate-300 my-3"></div>

            <!-- Item(s) Table -->
            <div class="space-y-2">
                @php
                    // Support both single-product (old schema) and multi-item (new schema via items relationship)
                    $hasItems = $transaksi->relationLoaded('items') && $transaksi->items->count() > 0;
                @endphp

                @if($hasItems)
                    {{-- Multi-item (new schema) --}}
                    @foreach($transaksi->items as $item)
                        <div class="mb-2">
                            <div class="font-bold text-[11px]">{{ $item->produk ? $item->produk->nama_produk : ($item->nama_produk ?? '-') }}</div>
                            <div class="flex justify-between text-[10px] text-slate-600 mt-0.5">
                                <span>{{ $item->qty ?? $item->jumlah ?? 1 }} x {{ 'Rp' . number_format($item->harga ?? ($item->produk ? $item->produk->harga : 0), 0, ',', '.') }}</span>
                                <span class="font-bold text-slate-900">
                                    @php
                                        $qty   = $item->qty ?? $item->jumlah ?? 1;
                                        $harga = $item->harga ?? ($item->produk ? $item->produk->harga : 0);
                                    @endphp
                                    Rp{{ number_format($qty * $harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Single-product (existing schema) --}}
                    <div class="mb-2">
                        <div class="font-bold text-[11px]">{{ $transaksi->produk ? $transaksi->produk->nama_produk : '-' }}</div>
                        <div class="flex justify-between text-[10px] text-slate-600 mt-0.5">
                            <span>{{ $transaksi->jumlah }} x Rp{{ number_format($transaksi->produk ? $transaksi->produk->harga : 0, 0, ',', '.') }}</span>
                            <span class="font-bold text-slate-900">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="border-b-2 border-dashed border-slate-300 my-3"></div>

            <!-- Totals -->
            <div class="space-y-1 text-[11px]">
                <div class="flex justify-between font-bold text-xs">
                    <span>TOTAL TAGIHAN</span>
                    <span>Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Dibayar</span>
                    <span>Rp{{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold">
                    <span>Kembalian</span>
                    <span class="text-emerald-700">Rp{{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-b-2 border-dashed border-slate-300 my-3"></div>

            <!-- Footer Message -->
            <div class="text-center text-[10px] text-slate-500 space-y-1">
                <p class="font-semibold text-slate-700">Terima kasih atas kunjungan Anda!</p>
                <p>Garansi resmi toko 7 hari sejak tanggal pembelian</p>
                <p>dengan membawa nota ini sebagai bukti.</p>
                <p class="mt-2 text-[9px]">★ Maju Jaya — Terpercaya Sejak 2010 ★</p>
            </div>
        </div>

        <!-- Bottom Action Bar (no-print) -->
        <div class="no-print bg-slate-50 border-t border-slate-200 p-4 flex gap-2">
            <a href="{{ url('/') }}"
                class="flex-1 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold rounded-lg text-xs text-center transition">
                ← Kasir
            </a>
            <button onclick="window.print()"
                class="flex-2 flex-grow py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition flex items-center justify-center gap-1">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </button>
        </div>
    </div>

    <p class="no-print mt-6 text-xs text-slate-400">
        Halaman ini dapat dicetak. Tekan <kbd class="bg-slate-200 px-1 rounded">Ctrl+P</kbd> atau klik tombol <strong>Cetak Struk</strong>.
    </p>

</body>
</html>
