<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║           DEBUG: PASSWORD CHANGE & HASH VERIFICATION          ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// 1. Check what's currently in database
echo "1️⃣  CHECKING DATABASE FOR ADMIN USER\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$admin = User::where('email', 'admin@trustequip.id')->first();

if (!$admin) {
    echo "❌ Admin user NOT found!\n";
    exit(1);
}

echo "✅ Admin user found:\n";
echo "  ID: {$admin->id_user}\n";
echo "  Email: {$admin->email}\n";
echo "  Name: {$admin->nama_lengkap}\n";
echo "  Password field (first 50 chars): " . substr($admin->password, 0, 50) . "...\n";
echo "  Password length: " . strlen($admin->password) . "\n";
echo "\n";

// 2. Test with old password
echo "2️⃣  TEST OLD PASSWORD: 'admin123456'\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$oldPass = "admin123456";
$oldCheck = Hash::check($oldPass, $admin->password);

echo "  Plaintext: '$oldPass'\n";
echo "  Length: " . strlen($oldPass) . "\n";
echo "  Hash::check() result: " . ($oldCheck ? "✅ TRUE (MATCHES)" : "❌ FALSE (NO MATCH)") . "\n";
echo "\n";

// 3. Test with new password
echo "3️⃣  TEST NEW PASSWORD: '12345678'\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$newPass = "12345678";
$newCheck = Hash::check($newPass, $admin->password);

echo "  Plaintext: '$newPass'\n";
echo "  Length: " . strlen($newPass) . "\n";
echo "  Hash::check() result: " . ($newCheck ? "✅ TRUE (MATCHES)" : "❌ FALSE (NO MATCH)") . "\n";
echo "\n";

// 4. Test hashing the new password
echo "4️⃣  CREATE NEW HASH FOR '12345678'\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$newHash = Hash::make($newPass);
echo "  Hash::make('12345678') = \n";
echo "  " . $newHash . "\n";
echo "  Length: " . strlen($newHash) . "\n";
echo "\n";

// 5. Test if new hash matches
echo "5️⃣  TEST IF NEW HASH IS CORRECT\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$testNewHash = Hash::check($newPass, $newHash);
echo "  Hash::check('12345678', newHash) = " . ($testNewHash ? "✅ TRUE" : "❌ FALSE") . "\n";
echo "\n";

// 6. Compare hashes
echo "6️⃣  COMPARING HASHES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "  Database hash:   " . substr($admin->password, 0, 50) . "...\n";
echo "  New hash (fresh): " . substr($newHash, 0, 50) . "...\n";
echo "  Are they the same? " . ($admin->password === $newHash ? "YES" : "NO") . "\n";
echo "\n";

// 7. Diagnosis
echo "7️⃣  DIAGNOSIS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if ($oldCheck) {
    echo "❌ Database still has OLD password hash (admin123456)\n";
    echo "   USER DID NOT UPDATE DATABASE CORRECTLY\n";
} elseif ($newCheck) {
    echo "✅ Database has correct NEW password hash (12345678)\n";
    echo "   Everything is OK!\n";
} else {
    echo "⚠️  Database hash is INVALID or CORRUPT\n";
    echo "   Hash doesn't match neither old nor new password\n";
}

echo "\n";

// 8. Solution
echo "8️⃣  SOLUTION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if (!$newCheck) {
    echo "✅ UPDATE DATABASE WITH CORRECT HASH:\n\n";
    echo "Run in terminal:\n";
    echo "php artisan tinker\n";
    echo ">>> \$u = App\\Models\\User::find(1)\n";
    echo ">>> \$u->password = Hash::make('12345678')\n";
    echo ">>> \$u->save()\n";
    echo ">>> exit\n\n";
    
    echo "Or directly via SQL:\n";
    echo "UPDATE users SET password = '" . $newHash . "' WHERE id_user = 1;\n";
}

?>
