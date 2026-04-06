
# 🚀 QUICK START GUIDE - NEW DEVICE SETUP

## 📋 What You Need

Before starting, make sure you have:
- Windows 7, 8, 10, or 11
- Internet connection
- ~2GB free disk space

---

## ✅ 3-Step Installation

### **Step 1: Check Prerequisites** (5 min)
```bash
Double-click: install-prerequisites.bat
```
This checks if you have:
- ✅ Node.js (for Vue.js)
- ✅ PHP (for Laravel)
- ✅ Composer (PHP package manager)
- ✅ Git (version control)

If any missing, it will tell you where to download.

---

### **Step 2: Setup Project** (10-15 min)
```bash
Double-click: setup-project.bat
```
This will:
1. Download npm packages (Vue.js, Vite, etc)
2. Download PHP packages (Laravel, etc)
3. Setup `.env` configuration
4. Generate Laravel APP_KEY
5. Ask if you want to setup database

---

### **Step 3: Run Website** (Start)
```bash
Double-click: run-website.bat
```
This will:
1. Start Laravel Backend (port 8000)
2. Start Vite Frontend (port 5175)
3. Open browser automatically

---

## 🌐 Access Website

After Step 3, open:
```
http://localhost:5175
```

### Test Logins:
```
👤 CUSTOMER (Default)
   Email: customer@trustequip.id
   Password: password

👨‍💼 STAFF (Verification & Approval)
   Email: staff@trustequip.id
   Password: password

🔐 ADMIN (System Admin)
   Email: admin@trustequip.id
   Password: password
```

---

## 🎯 What Each Service Does

| Service | Port | URL | Purpose |
|---------|------|-----|---------|
| **Vite Dev** | 5175 | http://localhost:5175 | Vue.js Frontend |
| **Laravel API** | 8000 | http://localhost:8000/api | REST API Backend |
| **Database** | 3306 | localhost:3306 | MySQL Database |

---

## 🆘 Troubleshooting

### "Port already in use"
Both Vite and Laravel will auto-switch to next available ports (5176, 8001, etc).
The console will show which port they're using.

### "npm not found"
Install Node.js from: https://nodejs.org/

### "php not found"
Install PHP via Laragon: https://laragon.org/

### "composer not found"
Install Composer from: https://getcomposer.org/

### "Database connection error"
Check `.env` file has correct settings:
```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=db_peminjaman
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📁 Project Layout

```
peminjaman-alat/
│
├── resources/js/          ← Vue.js Frontend
│   ├── pages/             ← Page components
│   ├── components/        ← Reusable components
│   ├── config/            ← API configuration
│   └── main.js            ← App entry point
│
├── app/Http/Controllers/  ← Laravel Backend
│   ├── BorrowingController.php
│   ├── EquipmentController.php
│   └── UserController.php
│
├── database/
│   ├── migrations/        ← Schema definitions
│   └── seeders/           ← Sample data
│
├── routes/
│   ├── api.php            ← API routes
│   └── web.php            ← Web routes
│
├── .env                   ← Configuration (AUTO-GENERATED)
├── package.json           ← npm dependencies
├── composer.json          ← PHP dependencies
└── vite.config.js         ← Vite configuration
```

---

## ⌨️ Useful Commands

```bash
# Stop servers
# Just close the terminal windows

# Run without batch files
npm run dev                    # Start Vite frontend
php artisan serve            # Start Laravel backend

# Database commands
php artisan migrate          # Run migrations
php artisan seed             # Seed with data
php artisan migrate:fresh    # Reset & migrate

# Clear caches
npm cache clean --force
composer clear-cache

# Reinstall dependencies
rm node_modules package-lock.json
npm install

rm -r vendor composer.lock
composer install
```

---

## 🔧 Manual Setup (Alternative)

If the batch files don't work:

```bash
# 1. Navigate to project
cd c:\laragon\www\peminjaman-alat

# 2. Install npm packages
npm install

# 3. Install PHP packages
composer install

# 4. Setup environment
copy .env.example .env
php artisan key:generate

# 5. Setup database (if fresh)
php artisan migrate --seed

# 6. Terminal 1: Start Laravel
php artisan serve

# 7. Terminal 2: Start Vite
npm run dev

# 8. Open browser
http://localhost:5175
```

---

## 📞 Need Help?

Check:
- ✅ `SETUP_NEW_DEVICE.md` - Detailed setup guide
- ✅ `DUMMY_USERS_GUIDE.md` - User system documentation
- ✅ Browser console (F12) - Frontend errors
- ✅ Terminal output - Backend errors

---

**Ready? Start with Step 1: `install-prerequisites.bat` 🚀**
