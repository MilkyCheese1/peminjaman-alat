<?php
require 'vendor/autoload.php';

// Setup Laravel
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== User Management ===\n\n";

// Show all users and their password hashes
echo "📋 Current Users:\n";
$users = User::all(['id_user', 'username', 'email', 'role', 'password']);
foreach ($users as $user) {
    echo "ID: {$user->id_user}, Username: {$user->username}, Email: {$user->email}, Role: {$user->role}\n";
    echo "   Password Hash: " . substr($user->password, 0, 20) . "...\n";
}

echo "\n";

// Reset passwords to known value
$newPassword = 'password123';
$hash = Hash::make($newPassword);

echo "🔄 Resetting all passwords to: '$newPassword'\n";
User::query()->update(['password' => $hash]);

echo "✅ All passwords reset successfully!\n";
echo "You can now login with any user and password: $newPassword\n\n";

echo "Test Credentials:\n";
echo "  1. admin / $newPassword (Admin role)\n";
echo "  2. petugas / $newPassword (Staff role)\n";
echo "  3. peminjam / $newPassword (User role)\n";
