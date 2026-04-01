# Installation & Setup Guide - Equipment Rental System

## Architecture
- **Frontend**: Vue.js 3 + Vite (Port 5173)
- **Backend**: Laravel 10 API (Port 8000)
- **Node.js**: v22 (from Laragon)
- **PHP**: 8.3.26
- **Database**: MySQL 8.0.30

## Project Structure

```
peminjaman-alat/
├── app/                          # Laravel application logic
│   ├── Http/
│   │   └── Controllers/
│   ├── Models/
│   └── ...
├── resources/
│   ├── js/                       # Vue.js Frontend
│   │   ├── main.js              # Entry point
│   │   ├── App.vue              # Root component
│   │   ├── components/          # Vue components
│   │   └── pages/               # Page components
│   ├── css/                      # Stylesheets
│   └── views/
│       └── welcome.blade.php    # Blade template (serves Vue app)
├── routes/
│   ├── web.php                  # Web routes
│   └── api.php                  # API routes
├── public/                       # Public assets
│   └── dist/                    # Built Vue.js files (vite build output)
├── database/                     # Database
│   ├── migrations/
│   └── seeders/
├── package.json                  # Node.js dependencies
├── vite.config.js               # Vite configuration
├── run-dev.bat                  # Start development (Windows)
├── run-dev.sh                   # Start development (Linux/Mac)
└── ...
```

## Installation & Setup

### 1. Already Installed ✅
- [x] Node.js v22 (Laragon)
- [x] PHP 8.3.26
- [x] Laravel 10
- [x] MySQL 8.0.30
- [x] npm dependencies (54 packages)

### 2. First Time Setup
If this is a fresh clone:

```bash
# Install npm dependencies
npm install

# If you need to install PHP dependencies too
composer install
```

### 3. Configure Environment

Edit `.env` file:
```properties
APP_DEBUG=true
DB_DATABASE=db_peminjaman
DB_USERNAME=root
DB_PASSWORD=
```

Edit `.env.vite` (Vite frontend):
```properties
VITE_API_URL=http://localhost:8000/api
```

### 4. Database Setup

```bash
# Create migrations (if needed)
php artisan migrate

# Seed database (if seeders exist)
php artisan db:seed
```

## Development Workflow

### Start Development Environment

**Windows:**
```bash
run-dev.bat
```

This will open two terminals:
- One running Laravel backend (port 8000)
- One running Vue.js + Vite (port 5173)

**Linux/Mac:**
```bash
chmod +x run-dev.sh
./run-dev.sh
```

### Manual Start (if script doesn't work)

**Terminal 1 - Backend:**
```bash
php artisan serve --host=localhost --port=8000
```

**Terminal 2 - Frontend:**
```bash
npm run dev
```

Then visit: **http://localhost:5173**

## Available npm Scripts

```json
{
  "dev": "vite",              // Start dev server
  "build": "vite build",      // Build for production
  "preview": "vite preview",  // Preview production build
  "serve": "php artisan serve" // Start Laravel backend
}
```

## API Communication

Vue.js Frontend communicates with Laravel Backend via HTTP API:

```javascript
// Example API call from Vue.js
import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api'
})

// Health check
api.get('/health').then(response => {
  console.log(response.data)
})
```

## Available API Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/api/health` | API health check |
| GET | `/api/user` | Get current user (requires auth) |

## Building for Production

```bash
# Build Vue.js frontend
npm run build

# This generates optimized files in public/dist/

# Serve production build
php artisan serve
```

## Troubleshooting

### npm command not found
```powershell
# Add Node.js to PATH (Windows PowerShell)
$env:PATH = "C:\laragon\bin\nodejs\node-v22;$env:PATH"
npm --version
```

### Port already in use
- Laravel showing "Port 8000 already in use": Change port in `php artisan serve --port=8001`
- Vite showing "Port 5173 already in use": Vite will automatically use 5174, 5175, etc.

### Database connection error
- Check `.env` file has correct DB credentials
- Ensure MySQL is running
- Run `php artisan migrate` to create tables

### Vue.js not rendering
1. Check browser console for errors (F12)
2. Ensure `npm run dev` is running
3. Ensure vite.config.js is correct
4. Check that `resources/views/welcome.blade.php` has `<div id="app"></div>`

## Project Features Ready

- ✅ Vue.js 3 Composition API setup
- ✅ Vite development server with hot module replacement (HMR)
- ✅ Proxy to Laravel API (/api routes)
- ✅ Laravel Sanctum authentication ready
- ✅ Axios for API calls (pre-installed)
- ✅ CSRF token support
- ✅ Production build optimization
- ✅ SQLite/MySQL database ready

## Next Steps

1. **Create Vue Components** in `resources/js/components/`
2. **Create API Routes** in `routes/api.php`
3. **Create Controllers** in `app/Http/Controllers/`
4. **Setup Models** in `app/Models/`
5. **Create Migrations** in `database/migrations/`
6. **Build your app!** 🚀

## Node.js & npm Info

- **Node.js Path**: `C:\laragon\bin\nodejs\node-v22`
- **Node.js Version**: v22.x
- **npm Version**: 10.9.0
- **Node.js Modules**: 54 packages installed

---

**Development Environment**: Laravel 10 + Vue.js 3 + Vite
**Last Updated**: April 1, 2026
