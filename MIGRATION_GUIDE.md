# Panduan Migrasi Go → Laravel 10

Dokumen ini menjelaskan secara detail bagaimana aplikasi Sistem Peminjaman Alat telah dimigrasikan dari Go ke PHP dengan Laravel 10.

## 📊 Perbandingan Arsitektur

### Go (Sebelumnya)
```
Go Application
├── main.go (Entry point & Routes)
├── config/database.go (Database setup)
├── controllers/ (Business logic)
├── models/ (Data structures)
├── public/ (Static files)
└── views/ (HTML templates)
```

### Laravel 10 (Sekarang)
```
Laravel Application
├── artisan (CLI)
├── public/index.php (Entry point)
├── app/
│   ├── Http/Controllers/
│   ├── Http/Middleware/
│   ├── Models/
│   └── Exceptions/
├── routes/web.php (Route definitions)
├── database/
│   ├── migrations/ (Schema changes)
│   └── seeders/ (Initial data)
└── resources/views/ (Blade templates)
```

## 🔄 Mapping Kode Go → Laravel

### 1. Main Entry Point

**Go:**
```go
func main() {
    defer config.CloseDatabase()
    http.HandleFunc("/", homeHandler)
    http.HandleFunc("/api/login", controllers.Login)
    http.ListenAndServe(":8080", nil)
}
```

**Laravel:**
```php
// routes/web.php
Route::get('/', function () {
    return view('index');
});
Route::post('/api/login', [AuthController::class, 'login']);

// Jalankan dengan: php artisan serve
```

### 2. Controllers

**Go:**
```go
func Login(w http.ResponseWriter, r *http.Request) {
    var payload models.LoginPayload
    json.NewDecoder(r.Body).Decode(&payload)
    
    user := db.QueryRow("SELECT ... FROM users WHERE username = ?", payload.UsernameOrEmail)
    // Validasi & response
}
```

**Laravel:**
```php
// app/Http/Controllers/AuthController.php
public function login(Request $request)
{
    $validated = $request->validate([
        'username_or_email' => 'required',
        'password' => 'required',
    ]);
    
    $user = User::where('username', $validated['username_or_email'])
        ->orWhere('email', $validated['username_or_email'])
        ->first();
    
    // ...
}
```

### 3. Database Models

**Go Struct:**
```go
type User struct {
    IDUser         int       `json:"id_user"`
    Username       string    `json:"username"`
    Email          string    `json:"email"`
    HashedPassword string    `json:"-"`
    Role           string    `json:"role"`
}
```

**Laravel Model:**
```php
// app/Models/User.php
class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $fillable = ['username', 'email', 'password', 'role'];
    protected $hidden = ['password'];
}
```

### 4. Database Operations

**Go:**
```go
rows, err := db.Query("SELECT id_user, username, email FROM users WHERE role = ?", "admin")
if err != nil {
    // Error handling
}
defer rows.Close()

for rows.Next() {
    var user User
    rows.Scan(&user.IDUser, &user.Username, &user.Email)
    // Process user
}
```

**Laravel (Eloquent):**
```php
$users = User::where('role', 'admin')->get();

foreach ($users as $user) {
    // Process user
}
```

### 5. Request Validation

**Go:**
```go
if payload.Username == "" || len(payload.Username) < 3 {
    http.Error(w, "Invalid username", http.StatusBadRequest)
    return
}
```

**Laravel:**
```php
$request->validate([
    'username' => 'required|min:3|unique:users',
    'email' => 'required|email|unique:users',
]);
```

### 6. Password Hashing

**Go:**
```go
import "golang.org/x/crypto/bcrypt"

hash, err := bcrypt.GenerateFromPassword([]byte(password), bcrypt.DefaultCost)
err = bcrypt.CompareHashAndPassword([]byte(hashedPassword), []byte(password))
```

**Laravel:**
```php
use Illuminate\Support\Facades\Hash;

$hash = Hash::make($password);
Hash::check($password, $hashedPassword);
```

### 7. Authentication & Sessions

**Go:**
```go
// Manual session handling
session := sessions.Default(c)
session.Set("user_id", user.IDUser)
session.Save()
```

**Laravel:**
```php
Auth::login($user);  // Automatic session handling
Auth::logout();      // Logout
$user = Auth::user(); // Get current user
```

### 8. Middleware

**Go:**
```go
func authMiddleware(next http.HandlerFunc) http.HandlerFunc {
    return func(w http.ResponseWriter, r *http.Request) {
        session := sessions.Default(r)
        if session.Get("user_id") == nil {
            http.Error(w, "Unauthorized", http.StatusUnauthorized)
            return
        }
        next(w, r)
    }
}
```

**Laravel:**
```php
// app/Http/Middleware/Authenticate.php
public function handle(Request $request, Closure $next)
{
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    return $next($request);
}

// Use in routes:
Route::middleware('auth')->group(function () {
    // Protected routes
});
```

### 9. Role-Based Access Control

**Go:**
```go
if user.Role != "admin" {
    http.Error(w, "Forbidden", http.StatusForbidden)
    return
}
```

**Laravel:**
```php
// Custom middleware
Route::middleware('role:admin')->group(function () {
    Route::post('/users/delete', [UserController::class, 'destroy']);
});
```

## 📁 File Structure Mapping

| Go | Laravel | Tujuan |
|----|----|--------|
| `main.go` | `routes/web.php` + `artisan` | Routing & CLI |
| `config/database.go` | `.env` + migrations | Database config |
| `controllers/auth.go` | `app/Http/Controllers/AuthController.php` | Auth logic |
| `models/user.go` | `app/Models/User.php` | User model |
| `views/index.html` | `resources/views/index.blade.php` | Blade template |
| `public/js/auth.js` | `public/js/auth.js` | Frontend JS |
| `db_peminjaman.sql` | `database/migrations/` | Database schema |

## 🔧 API Endpoints (Tetap Sama)

Semua API endpoints tetap sama:

```
POST   /api/register
POST   /api/login
POST   /api/logout
GET    /api/profile
POST   /api/profile/update
GET    /api/alat
POST   /api/peminjaman
... dll
```

## ⚙️ Database Schema (Tetap Sama)

**Users table tetap menggunakan:**
- `id_user` (Primary Key)
- `username`, `email`, `phone`, `password`
- `role` (enum: admin, petugas, peminjam)
- `created_at`, `updated_at`

**Alat, Kategori, Peminjaman tables:**
- Struktur tetap sama
- Laravel menggunakan migrations untuk manajemen schema

## 🚀 Migrasi Data

Jika Anda ingin mengmigrasi data dari database lama:

1. **Export dari database lama:**
```bash
mysqldump -u root db_peminjaman > backup.sql
```

2. **Import ke database baru:**
```bash
mysql -u root db_peminjaman < backup.sql
```

3. **Jalankan migrations Laravel:**
```bash
php artisan migrate
```

## 📝 Perubahan Fitur

| Fitur | Go | Laravel | Status |
|-------|----|----|--------|
| Authentication | Custom JWT | Eloquent Auth | ✅ Upgraded |
| Validation | Manual | Laravel Validator | ✅ Better |
| ORM | Custom queries | Eloquent | ✅ Better |
| Middleware | Custom | Built-in | ✅ Easier |
| Sessions | Manual | Automatic | ✅ Better |
| Error Handling | Manual try-catch | Exception Handler | ✅ Better |
| Database Migrations | Manual SQL | Laravel Migrations | ✅ Better |
| Testing | Manual | PHPUnit integrated | ✅ Better |

## 💡 Keuntungan Migrasi ke Laravel

1. **Produktivitas Lebih Tinggi**
   - Built-in features seperti auth, validation, ORM
   - Less boilerplate code

2. **Maintenance Lebih Mudah**
   - Large ecosystem & community
   - Better documentation
   - Established patterns

3. **Security Lebih Baik**
   - CSRF protection built-in
   - Password hashing automatic
   - SQL injection prevention

4. **Scalability**
   - Easy to add new features
   - Modularity dengan packages
   - Queue system untuk background jobs

5. **Development Experience**
   - Artisan CLI yang powerful
   - Tinker for testing
   - Better debugging tools

## 🔍 Troubleshooting Migrasi

### Q: API endpoints tidak bekerja?
**A:** Pastikan middleware `web` dikonfigurasi dengan benar di `app/Http/Kernel.php`

### Q: Database connection error?
**A:** Verify `.env` file memiliki database credentials yang benar

### Q: CSRF token mismatch?
**A:** Semua POST requests harus include CSRF token (automatic dengan Blade)

### Q: Routes tidak ditemukan?
**A:** Jalankan `php artisan route:cache` untuk cache routes

## 📚 Referensi

- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Eloquent ORM](https://laravel.com/docs/10.x/eloquent)
- [Blade Templates](https://laravel.com/docs/10.x/blade)
- [Artisan CLI](https://laravel.com/docs/10.x/artisan)

## ✅ Checklist Migrasi

- [x] Framework dimigrasi (Go → Laravel)
- [x] Database schema disiapkan
- [x] Models dibuat
- [x] Controllers dibuat
- [x] Routes dikonfigurasi
- [x] Authentication disetup
- [x] Views dibuat
- [x] Frontend JS diadaptasi
- [x] Seeders dibuat
- [ ] Testing disiapkan
- [ ] Production deployment

---

**Status**: ✅ Migration Complete
**Date**: March 15, 2026
**Version**: Laravel 10 LTS

Semoga dokumentasi ini membantu Anda memahami perubahan dari Go ke Laravel! 🚀
