<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING DATABASE USERS ===\n\n";

$users = User::select('id_user', 'username', 'email', 'role', 'password')->get();

if ($users->isEmpty()) {
    echo "❌ NO USERS FOUND IN DATABASE!\n";
} else {
    echo "✅ Found " . $users->count() . " users\n\n";
    
    foreach ($users as $user) {
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "ID: {$user->id_user}\n";
        echo "Username: {$user->username}\n";
        echo "Email: {$user->email}\n";
        echo "Role: {$user->role}\n";
        echo "Password Hash (first 40 chars):\n";
        echo "  " . substr($user->password, 0, 40) . "...\n";
        echo "  Length: " . strlen($user->password) . " chars\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }
}

// Now test password verification
echo "\n=== TESTING PASSWORD VERIFICATION ===\n\n";

$testCreds = [
    ['email' => 'admin@trustequip.id', 'password' => 'admin123456', 'expected_role' => 'admin'],
    ['email' => 'admin@trustequip.id', 'password' => 'admin', 'expected_role' => 'admin'],
    ['email' => 'admin@trustequip.id', 'password' => 'admin12345', 'expected_role' => 'admin'],
];

foreach ($testCreds as $test) {
    $admin = User::where('email', $test['email'])->first();
    
    if (!$admin) {
        echo "❌ User not found: {$test['email']}\n";
        continue;
    }
    
    $matches = \Illuminate\Support\Facades\Hash::check($test['password'], $admin->password);
    $status = $matches ? "✅ MATCH" : "❌ NO MATCH";
    
    echo "{$status} | Email: {$test['email']} | Pass: '{$test['password']}' (len: " . strlen($test['password']) . ")\n";
}

?>
