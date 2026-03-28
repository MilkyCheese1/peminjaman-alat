<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\User;
use Illuminate\Database\Migrations\Migrator;

echo "=== DATABASE PROFILE FIELDS CHECK ===\n\n";

// Get database connection
$connection = \DB::connection();
$tables = $connection->getDoctrineSchemaManager()->listTableNames();

echo "Tables in database:\n";
foreach ($tables as $table) {
    echo "  - $table\n";
}

echo "\n=== Users Table Columns ===\n";

try {
    $columns = $connection->getDoctrineSchemaManager()->listTableColumns('users');
    foreach ($columns as $column) {
        echo "  - {$column->getName()}: {$column->getType()}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Sample User Data ===\n";

try {
    $users = User::limit(1)->get();
    if ($users->count() > 0) {
        $user = $users->first();
        echo "User ID: {$user->id_user}\n";
        echo "Username: {$user->username}\n";
        echo "Email: {$user->email}\n";
        echo "Phone: {$user->phone}\n";
        echo "Role: {$user->role}\n";
        echo "Alamat: {$user->alamat}\n";
        echo "Nama Lengkap: " . ($user->nama_lengkap ?? 'NULL') . "\n";
        echo "Foto: " . ($user->foto ?? 'NULL') . "\n";
        echo "Kota: " . ($user->kota ?? 'NULL') . "\n";
        echo "Provinsi: " . ($user->provinsi ?? 'NULL') . "\n";
        echo "Kode Pos: " . ($user->kode_pos ?? 'NULL') . "\n";
    } else {
        echo "No users found\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Migration Status ===\n";

try {
    $migrations = \DB::table('migrations')->orderBy('batch', 'desc')->limit(10)->get();
    foreach ($migrations as $migration) {
        echo "  - {$migration->migration} (batch: {$migration->batch})\n";
    }
} catch (\Exception $e) {
    echo "Migrations table error: " . $e->getMessage() . "\n";
}
