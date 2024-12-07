# Jawaban Query Database MySQL

## 1. Identifikasi dan Perbaiki Kesalahan
### a. Logika Bisnis yang Salah

- **Masalah**: `order_date` tidak sesuai dengan input pengguna.
- **Solusi**:  `order_date` diambil dari input pengguna, bukan menggunakan tanggal saat ini.

### b. Perhitungan Subtotal

- **Masalah**: Tidak ada validasi subtotal (harga per unit * jumlah).
- **Solusi**: Tambahkan perhitungan subtotal untuk setiap item, dihitung berdasarkan `quantity * price`.

### c. Penanganan Rollback

- **Masalah**: Tidak ada rollback jika salah satu item gagal disimpan.
- **Solusi**: Gunakan transaksi database untuk melakukan rollback jika ada kesalahan. Contoh:

    ```php
    DB::beginTransaction();
    if ($gagal) {
        DB::rollBack();
    } else {
        DB::commit();
    }
    ```


### d. Penanganan Stok Produk

- **Masalah**: Jika stok tidak mencukupi untuk salah satu item, stok yang sudah dikurangi pada produk sebelumnya tidak dikembalikan.
- **Solusi**: Gunakan `DB::rollBack()` untuk membatalkan semua perubahan jika stok tidak mencukupi.

### e. Struktur Kode

- **Masalah**: Tidak ada transaksi database untuk memastikan konsistensi data jika terjadi kegagalan.
- **Solusi**: Gunakan `DB::beginTransaction()`, `DB::commit()`, dan `DB::rollBack()` untuk transaksi, sehingga data tetap konsisten.

## 2. Tambahkan Validasi dan Fitur

### a. Validasi untuk Items Tidak Boleh Kosong

- **Solusi**: Ditambahkan aturan validasi `min:1` untuk memastikan `items` tidak kosong.

### b. Perhitungan Subtotal untuk Setiap Item

- **Solusi**: Hitung subtotal sebagai `quantity * price` dan simpan pada setiap entitas `OrderItem`.

### c. Perhitungan Total Harga Pesanan

- **Solusi**: DiHitung toal harga pesanan dari subtotal semua item dan simpan pada entitas `Order`.

### d. Penanganan Kesalahan dengan Rollback

- **Solusi**: Menggunakan transaksi database (misalnya `DB::beginTransaction()`, `DB::commit()`, `DB::rollBack()`) untuk memastikan bahwa semua perubahan dibatalkan jika terjadi kegagalan.

## 3. Tambahkan Middleware untuk Autentikasi JWT

### a. Penggunaan Middleware untuk Keamanan

- **Solusi**: Menggunakan middleware `auth:api` untuk memastikan endpoint hanya bisa diakses oleh pengguna yang sudah login.

    ```php
    Route::middleware('auth:api')->group(function () {
        // Daftar route yang dilindungi
    });
    ```
