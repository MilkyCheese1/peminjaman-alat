<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║           FIXING PASSWORD HASH IN DATABASE                    ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "Updating admin user password...\n";

$user = User::find(1);

if (!$user) {
    echo "❌ User not found!\n";
    exit(1);
}

echo "Current user: {$user->nama_lengkap} ({$user->email})\n";
echo "Old hash: " . substr($user->password, 0, 40) . "...\n";

// Update with correct hash for '12345678'
$newPassword = '12345678';
$user->password = Hash::make($newPassword);
$user->save();

echo "New hash: " . substr($user->password, 0, 40) . "...\n\n";

// Verify
$verify = Hash::check($newPassword, $user->password);

echo "✅ VERIFICATION RESULT:\n";
echo "   Plaintext: '$newPassword'\n";
echo "   Hash::check() result: " . ($verify ? "✅ TRUE - Password is CORRECT!" : "❌ FALSE - Still wrong") . "\n";

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
if ($verify) {
    echo "║           ✅ PASSWORD SUCCESSFULLY UPDATED!                  ║\n";
    echo "║                                                              ║\n";
    echo "║  Login with:                                                ║\n";
    echo "║    Email: admin@trustequip.id                              ║\n";
    echo "║    Password: 12345678                                       ║\n";
} else {
    echo "║           ❌ SOMETHING WENT WRONG                            ║\n";
}
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

?>
