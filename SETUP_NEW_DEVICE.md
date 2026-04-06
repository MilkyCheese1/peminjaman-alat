# 📋 SETUP WEBSITE DI DEVICE BARU

## 🚀 Quick Start (3 Langkah)

### 1️⃣ **Download & Install Prerequisites**
Jalankan file ini:
```
install-prerequisites.bat
```

File ini akan install:
- ✅ Node.js (npm)
- ✅ PHP + Composer
- ✅ Git

---

### 2️⃣ **Setup Project**
```bash
# Posisi di folder project
cd c:\laragon\www\peminjaman-alat

# Jalankan setup
setup-project.bat
```

File ini akan:
- ✅ Install npm packages (Vue.js + dependencies)
- ✅ Install PHP packages (Laravel + dependencies)
- ✅ Setup database connection
- ✅ Generate APP_KEY

---

### 3️⃣ **Run Website**
```bash
run-website.bat
```

File ini akan jalankan:
- ✅ Vite Dev Server (port 5175) → Vue.js frontend
- ✅ Laravel Dev Server (port 8000) → Backend API

Setelah itu:
- 🌐 Buka: http://localhost:5175
- 📧 Login dengan: `customer@trustequip.id` / `password`

---

## 📦 Manual Install (Jika Perlu)

### Prerequisites Global (Install Sekali)

```bash
# 1. Node.js + npm
# Download: https://nodejs.org/
# Verify: node --version && npm --version

# 2. PHP + Composer
# Download: https://getcomposer.org/
# Verify: php --version && composer --version

# 3. Git
# Download: https://git-scm.com/
# Verify: git --version
```

### Setup Project (Per Folder)

```bash
# 1. Navigate ke project folder
cd c:\laragon\www\peminjaman-alat

# 2. Install Node packages
npm install

# 3. Install PHP packages
composer install

# 4. Copy environment file
copy .env.example .env

# 5. Generate APP_KEY
php artisan key:generate

# 6. Database setup (jika fresh install)
php artisan migrate --seed
```

### Run Development Servers

```bash
# Terminal 1: Laravel Backend (port 8000)
php artisan serve

# Terminal 2: Vite Frontend (port 5175)
npm run dev
```

---

## 🔧 Troubleshooting

### Port 5175 Sudah Dipakai?
```bash
# Vite akan auto-switch ke port 5176, 5177, dst
# Atau kill process di port 5175:
netstat -ano | findstr :5175
taskkill /PID <PID> /F
```

### Port 8000 Sudah Dipakai?
```bash
# Run Laravel di port lain:
php artisan serve --port=9000
```

### Composer/npm Error?
```bash
# Clear cache
npm cache clean --force
composer clear-cache

# Reinstall
npm install --force
composer install --no-cache
```

### Database Connection Error?
Edit `.env`:
```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=db_peminjaman
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📁 Project Structure

```
peminjaman-alat/
├── app/               → Laravel backend code
├── resources/
│   └── js/           → Vue.js frontend code
├── database/
│   └── migrations/   → Database migrations
├── public/           → Static assets
├── routes/
│   ├── api.php       → API endpoints
│   └── web.php       → Web routes
├── .env              → Configuration
├── composer.json     → PHP dependencies
├── package.json      → Node dependencies
└── vite.config.js    → Vite configuration
```

---

## ⌨️ Useful Commands

```bash
# Frontend (Vue.js + Vite)
npm run dev           # Start dev server
npm run build         # Build for production
npm run preview       # Preview production build

# Backend (Laravel)
php artisan serve     # Start Laravel server
php artisan migrate   # Run migrations
php artisan seed      # Seed database
php artisan tinker    # Interactive shell
php artisan make:model User  # Generate model

# Database
php artisan db:seed   # Seed database
php artisan migrate:fresh --seed  # Fresh migration + seed
```

---

## 🌐 URLs After Running

| Service | URL | Purpose |
|---------|-----|---------|
| Frontend | http://localhost:5175 | Vue.js App |
| Backend API | http://localhost:8000/api | Laravel REST API |
| Docs API | http://localhost:8000/api | See available endpoints |

---

## 👤 Test Logins

| Role | Email | Password |
|------|-------|----------|
| Customer | customer@trustequip.id | password |
| Staff | staff@trustequip.id | password |
| Admin | admin@trustequip.id | password |

---

**Siap? Jalankan `install-prerequisites.bat` sekarang!** 🚀
