<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::all();

echo "\n=== DAFTAR USER DALAM SISTEM ===\n\n";
foreach($users as $user) {
    echo "ID: {$user->id} | Username: {$user->username} | Role: {$user->role}\n";
}

echo "\n";
?>
