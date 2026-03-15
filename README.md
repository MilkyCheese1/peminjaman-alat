# Sistem Peminjaman Alat - Migrated to Laravel 10

Selamat! Aplikasi Sistem Peminjaman Alat Anda telah berhasil dimigrasikan dari Go ke PHP menggunakan Laravel 10 Framework.

## 📋 Apa yang Telah Dimigrasikan

### ✅ Backend
- **Framework**: Go HTTP → Laravel 10
- **Bahasa**: Go → PHP 8.2+
- **Architecture**: MVC Pattern (Laravel standard)

### ✅ Database
- Schema tetap sama (Users, Alat, Kategori, Peminjaman)
- Menggunakan MySQL 8.0+
- Eloquent ORM untuk database operations

### ✅ Features
- ✔️ Authentication (Register/Login/Logout)
- ✔️ Role-Based Access Control (Admin, Petugas, Peminjam)
- ✔️ User Profile Management
- ✔️ Equipment Management
- ✔️ Borrowing System
- ✔️ Dashboard dengan Statistics

### ✅ Frontend
- HTML Views (Blade Templates)
- JavaScript untuk interaksi
- CSS untuk styling
- API-driven architecture

## 🚀 Quick Start

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database
Edit file `.env`:
```env
DB_DATABASE=db_peminjaman
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Start Development Server
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## 📁 Project Structure

```
peminjaman-alat-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Business Logic
│   │   │   ├── AuthController.php
│   │   │   ├── AlatController.php
│   │   │   ├── PeminjamanController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/           # Request Middleware
│   └── Models/                   # Eloquent Models
│       ├── User.php
│       ├── Alat.php
│       ├── Kategori.php
│       └── Peminjaman.php
├── database/
│   └── migrations/               # Schema Migrations
├── public/
│   ├── index.php                 # Entry Point
│   ├── css/                      # Styling
│   ├── js/                       # JavaScript
│   └── images/                   # Static Assets
├── resources/
│   └── views/                    # Blade Templates
├── routes/
│   └── web.php                   # Route Definitions
├── .env                          # Environment Config
└── artisan                       # CLI Command
```

## 🔄 Database Schema

Semua tabel tetap sama dengan struktur asli:

### Users Table
```sql
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    role ENUM('admin', 'petugas', 'peminjam'),
    alamat TEXT,
    email_verified BOOLEAN,
    is_active BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Kategori, Alat, Peminjaman Tables
Milik dengan struktur yang sama dari database asli.

## 🔑 API Endpoints

### Authentication
- `POST /api/register` - Register user baru
- `POST /api/login` - Login
- `POST /api/logout` - Logout
- `GET /api/profile` - Get profile
- `POST /api/profile/update` - Update profile
- `POST /api/profile/change-password` - Change password

### Equipment
- `GET /api/alat` - Get all equipment
- `GET /api/alat/{id}` - Get equipment by ID
- `GET /api/kategoris` - Get all categories
- `POST /api/alat` - Create equipment (admin)
- `PUT /api/alat/{id}` - Update equipment (admin)
- `DELETE /api/alat/{id}` - Delete equipment (admin)

### Borrowing
- `GET /api/peminjaman` - Get all borrowings (staff)
- `GET /api/my-borrowings` - Get my borrowings
- `GET /api/borrow-history` - Get borrow history
- `POST /api/peminjaman` - Create borrowing request
- `PUT /api/peminjaman/{id}/status` - Update borrowing status (staff)

### Dashboard
- `GET /api/dashboard/stats` - Get dashboard statistics
- `GET /api/users` - Get all users (admin)

## 📝 Key Changes from Go to Laravel

| Aspek | Go | Laravel |
|-------|----|----|
| Framework | net/http | Laravel 10 |
| HTTP Handler | `http.HandleFunc` | Routes + Controllers |
| Authentication | Custom JWT/Session | Laravel Auth |
| Database | Database driver | Eloquent ORM |
| Models | Go structs | Eloquent Models |
| Middleware | Custom handlers | Laravel Middleware |
| Validation | Manual validation | Laravel Validator |
| Password Hashing | bcrypt package | Hash::make() |

## 🔐 Authentication Flow

1. User submits login form
2. API validates credentials dengan `Hash::check()`
3. Session dibuat menggunakan `Auth::login()`
4. User redirected ke dashboard sesuai role
5. Middleware `auth` melindungi protected routes
6. Middleware `role` memvalidasi role-based access

## 📚 Useful Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName

# List all routes
php artisan route:list

# Tinker shell
php artisan tinker

# Clear cache
php artisan cache:clear
```

## 🐛 Troubleshooting

### Port 8000 sudah digunakan
```bash
php artisan serve --port=8001
```

### Database connection error
Verify `.env` configuration dan pastikan MySQL running.

### Migration errors
```bash
php artisan migrate:reset
php artisan migrate
```

### Permission errors (Linux/Mac)
```bash
chmod -R 755 storage bootstrap/cache
```

## 📖 Documentation

- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Eloquent Documentation](https://laravel.com/docs/10.x/eloquent)
- [Laravel Routing](https://laravel.com/docs/10.x/routing)

## ✅ Next Steps

1. **Deploy ke Production**
   - Setup web server (Apache/Nginx)
   - Configure environment variables
   - Setup SSL certificate
   - Configure database backups

2. **Add More Features**
   - Email notifications
   - PDF reports
   - Advanced filtering
   - User dashboard improvements

3. **Security Improvements**
   - Add rate limiting
   - Implement 2FA
   - Add audit logging
   - CORS configuration

4. **Performance Optimization**
   - Database indexing
   - Query optimization
   - Caching strategy
   - Asset minification

## 📞 Support

Untuk bantuan lebih lanjut, silakan konsultasikan:
- Laravel Documentation
- This SETUP_GUIDE.md
- Project README.md

---

**Status**: ✅ Migration Complete
**Version**: Laravel 10 LTS
**PHP Version**: 8.2+
**Database**: MySQL 8.0+

Happy coding! 🎉
