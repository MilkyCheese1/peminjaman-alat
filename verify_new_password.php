<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::find(1);
$check = Hash::check('12345678', $user->password);

echo "===============================================\n";
echo "     PASSWORD VERIFICATION TEST\n";
echo "===============================================\n\n";
echo "Admin Email: {$user->email}\n";
echo "Password: 12345678\n";
echo "Result: " . ($check ? "✅ VALID" : "❌ INVALID") . "\n\n";

if ($check) {
    echo "✅ Database hash is CORRECT!\n";
    echo "Password '12345678' will work for login.\n\n";
    echo "Try login in browser at:\n";
    echo "http://localhost:5173/#/login\n";
    echo "Email: admin@trustequip.id\n";
    echo "Password: 12345678\n";
} else {
    echo "❌ Password still doesn't match!\n";
}

echo "\n===============================================\n";
?>
