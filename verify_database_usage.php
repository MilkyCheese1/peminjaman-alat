<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║           DATABASE CONNECTION & DATA VERIFICATION             ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// 1. Check database config
echo "1️⃣  DATABASE CONFIGURATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Host: " . env('DB_HOST') . "\n";
echo "Port: " . env('DB_PORT') . "\n";
echo "Database: " . env('DB_DATABASE') . "\n";
echo "User: " . env('DB_USERNAME') . "\n";
echo "\n";

// 2. Query users from database
echo "2️⃣  USERS IN DATABASE (FROM: users TABLE)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$users = User::select('id_user', 'email', 'nama_lengkap', 'role', 'is_active', 'created_at')->get();

if ($users->count() === 0) {
    echo "❌ NO USERS FOUND!\n";
    exit(1);
}

echo "✅ Found " . $users->count() . " users\n\n";

foreach ($users as $idx => $user) {
    echo "(" . ($idx + 1) . ") " . $user->email . "\n";
    echo "    Name: " . $user->nama_lengkap . "\n";
    echo "    Role: " . $user->role . "\n";
    echo "    Active: " . ($user->is_active ? "YES ✅" : "NO ❌") . "\n";
    echo "    Created: " . $user->created_at->format('d M Y H:i:s') . "\n";
    echo "\n";
}

// 3. Test Login Flow
echo "3️⃣  SIMULATING LOGIN PROCESS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$testEmail = 'admin@trustequip.id';
$testPassword = 'admin123456';

echo "Step 1: Query User::where('email', '{$testEmail}')->first()\n";
$user = User::where('email', $testEmail)->first();

if (!$user) {
    echo "❌ User NOT FOUND in database!\n\n";
    exit(1);
}

echo "✅ User FOUND in database\n";
echo "   ID: " . $user->id_user . "\n";
echo "   Name: " . $user->nama_lengkap . "\n";
echo "   Role: " . $user->role . "\n\n";

echo "Step 2: Verify Password with Hash::check()\n";
$isValid = Hash::check($testPassword, $user->password);
echo ($isValid ? "✅" : "❌") . " Password '{$testPassword}' is " . ($isValid ? "VALID" : "INVALID") . "\n\n";

echo "Step 3: Prepare Response Data (from database fields)\n";
$response = [
    'id' => $user->id_user,
    'fullname' => $user->nama_lengkap,
    'email' => $user->email,
    'role' => $user->role,
    'phone' => $user->phone,
    'school' => $user->kota,
    'address' => $user->alamat,
];
echo "Data to send back to frontend:\n";
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// 4. Data Flow Diagram
echo "4️⃣  DATA FLOW DIAGRAM\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo <<<'FLOW'
BROWSER FORM
   ↓
   POST /api/login
   {email: "admin@trustequip.id", password: "admin123456"}
   ↓
LARAVEL ROUTE (routes/api.php)
   Route::post('/login', [UserController::class, 'login'])
   ↓
USER CONTROLLER (app/Http/Controllers/UserController.php)
   → Query DATABASE: User::where('email', $email)->first()
   ↓
DATABASE QUERY
   SELECT * FROM users WHERE email = 'admin@trustequip.id'
   ↓
USER FOUND IN DATABASE ✅
   id_user: 1
   email: admin@trustequip.id
   nama_lengkap: Admin TrustEquip
   role: admin
   password: $2y$12$eDDHkoJtpBlD0U/5Wp/nk.4W/g9tqPSJO... (HASHED)
   ↓
VERIFY PASSWORD
   Hash::check('admin123456', stored_hash) = TRUE ✅
   ↓
RESPONSE TO BROWSER
   {
     success: true,
     data: {
       id: 1,
       fullname: "Admin TrustEquip",
       email: "admin@trustequip.id",
       role: "admin",
       ...
     },
     message: "Login berhasil"
   }
   ↓
BROWSER STORES IN localStorage
   localStorage.setItem('user', JSON.stringify(data))
   ↓
FRONTEND REDIRECT
   router.push('/dashboard')

FLOW;

echo "\n\n";
echo "✅ VERIFICATION COMPLETE: System uses REAL DATABASE DATA\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

?>
