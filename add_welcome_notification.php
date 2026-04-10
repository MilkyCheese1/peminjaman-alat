<?php
// Quick script to add welcome notification to database using Artisan

$basePath = __DIR__;

// Bootstrap Laravel
$app = require_once $basePath . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Notification;

try {
    $customerId = 3; // Customer user

    // Add welcome notification
    $notification = Notification::create([
        'id_user' => $customerId,
        'type' => 'announcement',
        'title' => '👋 Selamat Datang di TrustEquip!',
        'message' => 'Halo! Selamat datang di platform peminjaman alat TrustEquip. Sistem notifikasi kami sedang menguji dengan pesan sambutan ini. Jika Anda melihat pesan ini, berarti notifikasi bekerja dengan baik dan mengambil data dari database.',
        'icon' => '👋',
        'color' => 'success',
        'is_read' => false,
    ]);

    echo "✅ Welcome notification berhasil ditambahkan!\n";
    echo "Notification ID: {$notification->id_notification}\n";
    echo "User ID: {$customerId}\n";
    echo "Type: announcement\n";
    echo "\n🔔 Silakan refresh dashboard dan lihat notifikasi baru muncul\n";
    
} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    exit(1);
}
