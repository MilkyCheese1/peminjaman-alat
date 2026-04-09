<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== DEBUG PASSWORD VERIFICATION ===\n\n";

$user = User::where('email', 'admin@trustequip.id')->first();

if ($user) {
    echo "✅ User found: " . $user->email . "\n";
    echo "📝 Name: " . $user->nama_lengkap . "\n";
    echo "🔑 Role: " . $user->role . "\n";
    echo "🟢 Active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
    echo "🔐 Password hash (first 30 chars): " . substr($user->password, 0, 30) . "...\n";
    echo "\n--- Password Verification Test ---\n";
    
    $testPasswords = [
        'admin123456',
        'admin123456 ',
        ' admin123456',
        'admin123456\n',
    ];
    
    foreach ($testPasswords as $pass) {
        $check = Hash::check($pass, $user->password);
        echo "✓ Hash::check('" . addslashes($pass) . "'): " . ($check ? '✅ TRUE' : '❌ FALSE') . "\n";
    }
} else {
    echo "❌ User not found\n";
}

echo "\n=== All Users ===\n";
$all_users = User::select('id_user', 'email', 'role', 'is_active')->get();
foreach ($all_users as $u) {
    echo $u->id_user . " | " . $u->email . " | " . $u->role . " | " . ($u->is_active ? 'Active' : 'Inactive') . "\n";
}
?>
