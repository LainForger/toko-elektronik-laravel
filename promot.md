# PANDUAN TUGAS & PROMPT PENGEMBANGAN TIM (3 ORANG)
## Sistem Kasir Toko Elektronik (Maju Jaya POS)

Dokumen ini berisi panduan pembagian tugas dan **prompt siap pakai** untuk 3 anggota tim pengembang proyek kasir toko elektronik berbasis **Laravel 11**, **MySQL (database: `ulil_capstone`)**, dan **Tailwind CSS**.

---

## ⚠️ ATURAN EMAS ANTI-TABRAKAN (GIT CONFLICT PREVENTION)
Setiap anggota tim **WAJIB** mematuhi 3 aturan berikut agar kodingan tidak pernah mengalami bentrok/tabrakan (*merge conflict*):
1. **Wajib Bekerja di Branch Sendiri:** Jangan pernah koding atau push langsung di branch `main`.
2. **Jangan Mengubah File Migration Lama:** Selalu buat file migration baru dengan perintah `php artisan make:migration add_...`.
3. **Pemisahan File Kerja:** Buat Controller dan View tersendiri sesuai pembagian tugas masing-masing.

---

## 👤 PROMPT UNTUK ANGGOTA 1: Keamanan (Login Multi-Role) & Multi-Metode Pembayaran

Salin teks prompt di bawah ini ke asisten AI / AI coding:

```text
Halo, saya sedang mengembangkan proyek sistem kasir toko elektronik (Maju Jaya POS) berbasis Laravel 11, MySQL (database: ulil_capstone), dan Tailwind CSS di folder C:\laravel\toko-elektronik-laravel\toko-elektronik.

Saya adalah ANGGOTA 1 dalam tim yang beranggotakan 3 orang. Agar kodingan saya TIDAK TABRAKAN (no merge conflict) dengan anggota lain, ikuti aturan ketat berikut:
1. ATURAN GIT: Jalankan git checkout -b feature-auth-payment sebelum mulai koding.
2. ATURAN DATABASE: JANGAN MENGUBAH file migration yang sudah ada. Buat file migration BARU (contoh: php artisan make:migration add_metode_bayar_to_transaksis_table).
3. ATURAN FILE: Buat Controller sendiri (AuthController.php) dan halaman view sendiri (resources/views/auth/login.blade.php). Jangan mengedit Tab Riwayat atau tabel produk yang merupakan jatah anggota lain.

TUGAS SAYA (Implementasikan fitur berikut):
1. Sistem Login & Multi-Role User (Role Based Access Control):
   - Buat sistem autentikasi login (menggunakan Session / Auth Laravel).
   - Buat 2 role pengguna pada tabel users: 'admin' dan 'kasir'.
   - Role 'kasir': Hanya bisa mengakses Tab Kasir dan mencetak struk. Form tambah/edit/hapus produk disembunyikan/diproteksi (read-only).
   - Role 'admin': Akses penuh ke semua tab.
   - Buat halaman login yang bersih dan modern menggunakan Tailwind CSS.
   - Tambahkan tombol Logout dan info nama user login di navbar atas (layouts/app.blade.php).
   - Buat Database Seeder akun default: admin (admin@majujaya.com / password) dan kasir (kasir@majujaya.com / password).

2. Multi-Metode Pembayaran Kasir:
   - Buat migration baru untuk menambahkan kolom 'metode_bayar' (enum: cash, qris, transfer, debit) dan 'no_referensi' (nullable) pada tabel 'transaksis'.
   - Pada panel pembayaran kasir (Tab 1), tambahkan pilihan metode bayar:
     * Tunai (Cash): Alur normal input uang bayar dan kembalian.
     * QRIS: Tampilkan pop-up modal gambar QR Code QRIS dinamis/mockup untuk discan pembeli.
     * Transfer Bank: Pilihan bank (BCA, Mandiri, BRI) dan input nomor referensi bukti transfer.
     * Kartu Debit: Input nomor kartu/approval code EDC.
   - Simpan data metode bayar saat checkout dan tampilkan nama metode pembayaran pada struk nota belanja.

Di akhir pengerjaan, berikan perintah git add, git commit, dan git push origin feature-auth-payment agar saya bisa langsung simpan ke GitHub.
```

---

## 👤 PROMPT UNTUK ANGGOTA 2: Fitur Toko Elektronik (Nomor Seri, Garansi, Barcode & Opsi Nota WA)

Salin teks prompt di bawah ini ke asisten AI / AI coding:

```text
Halo, saya sedang mengembangkan proyek sistem kasir toko elektronik (Maju Jaya POS) berbasis Laravel 11, MySQL (database: ulil_capstone), dan Tailwind CSS di folder C:\laravel\toko-elektronik-laravel\toko-elektronik.

Saya adalah ANGGOTA 2 dalam tim yang beranggotakan 3 orang. Agar kodingan saya TIDAK TABRAKAN (no merge conflict) dengan anggota lain, ikuti aturan ketat berikut:
1. ATURAN GIT: Jalankan git checkout -b feature-garansi-barcode sebelum mulai koding.
2. ATURAN DATABASE: JANGAN MENGUBAH file migration yang sudah ada. Buat file migration BARU (contoh: php artisan make:migration add_serial_number_and_garansi).
3. ATURAN FILE: Fokus pada input keranjang kasir, pencarian barcode, dan modal struk. Jangan menyentuh sistem login (milik Anggota 1) atau ekspor laporan keuangan (milik Anggota 3).

TUGAS SAYA (Implementasikan fitur berikut):
1. Nomor Seri (Serial Number / SN) & Masa Garansi Resmi:
   - Barang elektronik wajib memiliki garansi dan nomor seri.
   - Buat migration baru untuk menambahkan kolom 'garansi' pada tabel 'produks' (contoh: "Garansi Toko 7 Hari, Resmi 1 Tahun") dan kolom 'serial_number' pada tabel 'transaksi_items'.
   - Pada keranjang kasir (Tab 1), sediakan input opsi 'Nomor Seri / SN' saat barang elektronik dimasukkan ke keranjang.
   - Pada cetakan struk nota (thermal 80mm), tampilkan Nomor Seri dan masa garansi resmi di bawah nama barang secara rapi dan profesional.

2. Fitur Scan Barcode Cepat:
   - Di Tab Kasir, buat input khusus scan barcode yang auto-focus atau responsif terhadap alat Barcode Scanner USB / pengetikan kode cepat.
   - Jika barcode (misal 'ELK-001') discan/dienter, produk otomatis langsung bertambah ke keranjang belanja tanpa perlu klik mouse.

3. Fitur Potongan Diskon:
   - Tambahkan input diskon belanja di kasir (potongan nominal Rupiah atau persentase %). Total tagihan otomatis terpotong dan nilai diskon tercantum di struk.

4. Menu Pilihan Nota Digital (Kirim via WhatsApp - Opsional):
   - Struk cetak fisik thermal tetap menjadi cetakan utama.
   - Namun, pada modal struk pembayaran, tambahkan tombol pilihan opsional: "Kirim Nota Digital (WhatsApp)".
   - Jika diklik, sistem membuka link WhatsApp Web (wa.me/{no_hp_pelanggan}) dengan template pesan faktur belanja rapi yang berisi: No Nota, Tanggal, Rincian Barang + Nomor Seri, Total Tagihan, dan Info Garansi Toko.

Di akhir pengerjaan, berikan perintah git add, git commit, dan git push origin feature-garansi-barcode agar saya bisa langsung simpan ke GitHub.
```

---

## 👤 PROMPT UNTUK ANGGOTA 3: Laporan Keuangan, Laba Bersih, Filter Tanggal & Ekspor Excel/PDF

Salin teks prompt di bawah ini ke asisten AI / AI coding:

```text
Halo, saya sedang mengembangkan proyek sistem kasir toko elektronik (Maju Jaya POS) berbasis Laravel 11, MySQL (database: ulil_capstone), dan Tailwind CSS di folder C:\laravel\toko-elektronik-laravel\toko-elektronik.

Saya adalah ANGGOTA 3 dalam tim yang beranggotakan 3 orang. Agar kodingan saya TIDAK TABRAKAN (no merge conflict) dengan anggota lain, ikuti aturan ketat berikut:
1. ATURAN GIT: Jalankan git checkout -b feature-laporan-profit sebelum mulai koding.
2. ATURAN DATABASE: JANGAN MENGUBAH file migration yang sudah ada. Buat file migration BARU (contoh: php artisan make:migration add_harga_modal_to_produks_table).
3. ATURAN FILE: Buat Controller sendiri khusus laporan (contoh: LaporanController.php) dan fokus mengedit Tab 4 (Riwayat & Laporan). Jangan menyentuh alur login (milik Anggota 1) atau keranjang belanja kasir (milik Anggota 2).

TUGAS SAYA (Implementasikan fitur berikut):
1. Pencatatan Harga Modal (HPP) & Kalkulasi Laba Bersih (Net Profit):
   - Buat migration baru untuk menambahkan kolom 'harga_modal' (bigint) pada tabel 'produks'.
   - Perbarui form tambah dan edit produk agar admin bisa mengisi harga modal/beli barang.
   - Pada kartu ringkasan KPI di dashboard atas, tambahkan indikator: 'Laba Bersih' (Total Penjualan dikurangi Total Harga Modal dari barang-barang yang terjual), sehingga pemilik toko mengetahui keuntungan bersih riil, bukan hanya omzet kotor.

2. Filter Riwayat Transaksi Berdasarkan Rentang Tanggal (Date Range):
   - Pada Tab 4 (Riwayat & Laporan), tambahkan kontrol filter tanggal:
     * Tombol cepat: 'Hari Ini', 'Minggu Ini', 'Bulan Ini', 'Semua'.
     * Input rentang tanggal kustom: Dari Tanggal (Start Date) sampai Sampai Tanggal (End Date).
   - Tabel riwayat transaksi dan total penjualan otomatis ter-update memfilter data sesuai tanggal yang dipilih tanpa reload halaman.

3. Fitur Ekspor Data Laporan (Excel & Cetak PDF):
   - Tambahkan tombol "Ekspor Excel (.csv / .xlsx)" pada tab riwayat untuk mengunduh rekap penjualan lengkap dengan rincian item, nomor nota, dan pelanggan.
   - Tambahkan tombol "Cetak Rekap Laporan" yang menghasilkan format halaman cetak bersih ramah printer A4 untuk laporan ke pemilik toko.

4. Grafik Tren Penjualan (Sales Chart):
   - Tambahkan grafik visual (menggunakan Chart.js dari CDN) pada Tab Riwayat/Dashboard untuk melihat grafik garis tren kenaikan omzet toko selama 7 hari atau 30 hari terakhir.

Di akhir pengerjaan, berikan perintah git add, git commit, dan git push origin feature-laporan-profit agar saya bisa langsung simpan ke GitHub.
```

---

## 🚀 ALUR PENGGABUNGAN GIT (WORKFLOW)

### 1. Saat Mulai Koding:
```bash
git checkout main
git pull origin main
git checkout -b <nama-branch-fitur-anda>
```

### 2. Saat Selesai Koding di Cabang Sendiri:
```bash
git add .
git commit -m "Selesaikan fitur <nama-fitur>"
git push origin <nama-branch-fitur-anda>
```
Lalu buka GitHub dan buat **Pull Request (PR)** untuk digabung (*merge*) ke `main`.

### 3. Sebelum Anggota Lain Melanjutkan Koding:
Tarik update terbaru dari `main` ke branch masing-masing:
```bash
git checkout main
git pull origin main
git checkout <nama-branch-fitur-anda>
git merge main
```
