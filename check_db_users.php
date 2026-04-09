<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'database' => env('DB_DATABASE', 'db_peminjaman'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->bootEloquent();

echo "=== Database Users Table ===\n\n";

$users = Capsule::table('users')
    ->select('id_user', 'username', 'email', 'role', 'is_active')
    ->orderBy('id_user')
    ->get();

foreach ($users as $user) {
    echo sprintf("[ID:%d] %s (%s) → Role: %s | Active: %d\n",
        $user->id_user,
        $user->username,
        $user->email,
        $user->role,
        $user->is_active
    );
}

echo "\n";
?>
