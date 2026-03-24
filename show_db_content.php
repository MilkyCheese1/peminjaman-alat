<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=db_peminjaman', 'root', '');

echo "=== Database Content Overview ===\n\n";

// Alat dengan Kategori
echo "📦 ALAT (Equipment):\n";
$stmt = $pdo->prepare('SELECT a.*, k.nama_kategori FROM alat a LEFT JOIN kategori k ON a.id_kategori = k.id_kategori');
$stmt->execute();
$alats = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Total: " . count($alats) . "\n";
foreach ($alats as $alat) {
    $tersedia = $alat['stok'] - $alat['dipinjam'];
    echo "  ✓ {$alat['nama_alat']} ({$alat['nama_kategori']}) - Stock: {$alat['stok']}, Dipinjam: {$alat['dipinjam']}, Tersedia: $tersedia\n";
}

echo "\n";

// Kategori
echo "📂 KATEGORI (Categories):\n";
$stmt = $pdo->prepare('SELECT * FROM kategori');
$stmt->execute();
$kategoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Total: " . count($kategoris) . "\n";
foreach ($kategoris as $k) {
    echo "  ✓ {$k['nama_kategori']}\n";
}

echo "\n";

// Users
echo "👥 USERS:\n";
$stmt = $pdo->prepare('SELECT * FROM users');
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Total: " . count($users) . "\n";
foreach ($users as $u) {
    echo "  ✓ {$u['username']} ({$u['role']}) - Email: {$u['email']}, Status: " . ($u['is_active'] ? 'Aktif' : 'Inactive') . "\n";
}

echo "\n";

// Peminjaman
echo "📋 PEMINJAMAN (Borrowings):\n";
$stmt = $pdo->prepare('SELECT * FROM peminjaman');
$stmt->execute();
$peminjamanCount = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Total: " . count($peminjamanCount) . "\n";
if (count($peminjamanCount) > 0) {
    foreach ($peminjamanCount as $p) {
        echo "  ✓ ID: {$p['id_peminjaman']}, User: {$p['id_user']}, Alat: {$p['id_alat']}, Status: {$p['status']}\n";
    }
} else {
    echo "  (Tidak ada peminjaman yet)\n";
}

echo "\n=== Database Ready for Testing ===\n";
