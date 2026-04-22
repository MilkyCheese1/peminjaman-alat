# TrustEquip.id

TrustEquip.id adalah aplikasi peminjaman alat berbasis Laravel + Vue untuk mengelola inventaris, transaksi peminjaman, pengembalian, laporan, notifikasi, dan hak akses user.

## Fitur utama

- Landing page publik
- Login dan registrasi
- Manajemen user
- Manajemen alat dan kategori
- Manajemen peminjaman
- Konfirmasi pengembalian dan perhitungan denda
- Laporan admin, owner, dan staff
- Notifikasi
- Log aktivitas
- Profil dan akun user

## Struktur penting

- Frontend utama: `resources/js`
- Halaman utama: `resources/js/pages`
- Komponen bersama: `resources/js/components`
- Router Vue: `resources/js/router/index.js`
- API Laravel: `routes/api.php`
- Controller transaksi: `app/Http/Controllers/Api/BorrowingController.php`
- Dokumentasi aplikasi: `dokumentasi-trustequip.html`
- Dump database: `db_peminjaman.sql`

## Setup cepat

### 1. Siapkan file environment

Jalankan PowerShell:

```powershell
.\setup.ps1
```

Script ini akan:

- membuat `.env` dari `.env.example` jika belum ada
- menjalankan `composer install` jika tersedia
- menjalankan `npm install` jika tersedia
- membuat `APP_KEY`

### 2. Atur database

File `db_peminjaman.sql` sudah disiapkan untuk import manual ke MySQL.

Contoh:

```bash
mysql -u root -p nama_database < db_peminjaman.sql
```

Pastikan isi `.env` disesuaikan dengan database lokal kamu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Jalankan aplikasi

Gunakan salah satu:

```bat
start-dev.bat
```

atau:

```bash
npm run start
```

## Catatan upload file

Project ini memakai helper `resources/js/lib/api.js` untuk request API. Jika form mengirim `FormData` dengan metode `PUT` atau `PATCH`, helper akan otomatis mengubahnya menjadi `POST` + `_method` supaya upload file tetap kompatibel dengan Laravel.

## Catatan dokumentasi

File `dokumentasi-trustequip.html` berisi:

- penjelasan aplikasi
- tabel database aktif
- relasi antar tabel
- alasan tipe data per kolom
- lokasi file fitur
- penjelasan fitur
- pembagian role dan hak akses

