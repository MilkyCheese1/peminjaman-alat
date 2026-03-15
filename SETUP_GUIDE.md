# Panduan Setup Laravel 10 - Sistem Peminjaman Alat

## Prerequisites

Sebelum memulai, pastikan Anda telah menginstal:
- **PHP 8.2** atau lebih tinggi
- **Composer** (untuk mengelola dependencies Laravel)
- **MySQL 8.0** atau lebih tinggi
- **Git** (opsional, tapi direkomendasikan)

## Step-by-Step Installation

### 1. Navigasi ke Project Directory

```bash
cd c:\Users\Allic\OneDrive\Documents\ujikom\peminjaman-alat-laravel
```

### 2. Install Dependencies dengan Composer

```bash
composer install
```

Ini akan menginstal semua dependencies yang diperlukan Laravel 10.

### 3. Generate Application Key

```bash
php artisan key:generate
```

Ini akan membuat `APP_KEY` di file `.env` yang diperlukan untuk enkripsi.

### 4. Setup Database

#### a. Verifikasi Database Connection di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_peminjaman
DB_USERNAME=root
DB_PASSWORD=
```

#### b. Jalankan Migrations
```bash
php artisan migrate
```

#### c. (Opsional) Seed Database dengan Data Sample
Jika Anda ingin mengisi database dengan data awal, jalankan:
```bash
php artisan db:seed
```

### 5. Jalankan Development Server

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## Struktur Project

```
peminjaman-alat-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Logic aplikasi
│   │   └── Middleware/     # Middleware untuk request handling
│   ├── Models/             # Eloquent Models untuk database
│   └── Exceptions/         # Exception handling
├── database/
│   └── migrations/         # Database migrations
├── public/
│   ├── index.php           # Entry point Laravel
│   ├── css/                # CSS files
│   ├── js/                 # JavaScript files
│   └── images/             # Images
├── resources/
│   └── views/              # Blade template files
├── routes/
│   ├── web.php             # Web routes
│   └── console.php         # Artisan commands
├── .env                    # Environment configuration
├── artisan                 # Artisan CLI
└── composer.json           # Composer configuration
```

## API Endpoints

### Authentication
- **POST** `/api/register` - Register user baru
- **POST** `/api/login` - Login user
- **POST** `/api/logout` - Logout user

### Profile
- **GET** `/api/profile` - Dapatkan profil user (require auth)
- **POST** `/api/profile/update` - Update profil (require auth)
- **POST** `/api/profile/change-password` - Ubah password (require auth)

### Equipment (Alat)
- **GET** `/api/alat` - Dapatkan semua alat (require auth)
- **GET** `/api/alat/{id}` - Dapatkan alat by ID (require auth)
- **GET** `/api/kategoris` - Dapatkan semua kategori (require auth)
- **POST** `/api/alat` - Tambah alat baru (admin only)
- **PUT** `/api/alat/{id}` - Update alat (admin only)
- **DELETE** `/api/alat/{id}` - Hapus alat (admin only)

### Borrowing (Peminjaman)
- **GET** `/api/my-borrowings` - Dapatkan peminjaman saya (require auth)
- **GET** `/api/borrow-history` - Dapatkan riwayat peminjaman (require auth)
- **POST** `/api/peminjaman` - Buat permintaan peminjaman (require auth)
- **GET** `/api/peminjaman` - Dapatkan semua peminjaman (staff/admin only)
- **PUT** `/api/peminjaman/{id}/status` - Update status peminjaman (staff/admin only)

### Dashboard
- **GET** `/api/dashboard/stats` - Dapatkan statistik dashboard (require auth)
- **GET** `/api/users` - Dapatkan semua users (admin only)

## Konfigurasi Penting

### 1. `.env` File
Pastikan konfigurasi database sudah benar:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_peminjaman
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Session Configuration
Session menggunakan file storage (default). Untuk production, gunakan database atau Redis.

### 3. Authentication
Aplikasi menggunakan Laravel's built-in session-based authentication.

## Commands Berguna

```bash
# Jalankan server
php artisan serve

# Buat migration baru
php artisan make:migration create_table_name

# Jalankan migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Lihat routes yang tersedia
php artisan route:list

# Jalankan tinker (interactive shell)
php artisan tinker

# Clear cache
php artisan cache:clear
php artisan config:cache
```

## Troubleshooting

### 1. Composer Install Error
Jika terjadi error saat `composer install`, coba:
```bash
composer install --no-dev
composer dump-autoload
```

### 2. Database Connection Error
Pastikan MySQL server sudah running dan konfigurasi `.env` benar.
```bash
# Test database connection
php artisan tinker
# Di tinker console: DB::connection()->getPdo();
```

### 3. Permission Error (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### 4. Port 8000 Sudah Digunakan
```bash
php artisan serve --port=8001
```

## Migrasi dari Go ke Laravel

Perubahan utama:
1. **Framework**: Go + http package → Laravel 10
2. **Database**: Driver sama (MySQL), schema dipertahankan
3. **Authentication**: Custom Go → Laravel's built-in auth
4. **Routes**: Go HTTP handlers → Laravel Routes + Controllers
5. **Models**: Go structs → Eloquent Models
6. **Middleware**: Middleware baru untuk role-based access

## Langkah Selanjutnya

1. Buat database seeders untuk data inisial
2. Buat view files (Blade templates) untuk frontend
3. Setup frontend assets (CSS, JS)
4. Configure CORS jika digunakan untuk API eksternal
5. Setup email notification (opsional)

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel API Documentation](https://laravel.com/api/10.x)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

Semoga proses migrasi berjalan lancar! 🚀
