<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=db_peminjaman', 'root', '');
$users = $pdo->query('SELECT COUNT(*) FROM users')->fetch()[0];
$kategori = $pdo->query('SELECT COUNT(*) FROM kategori')->fetch()[0];
$alat = $pdo->query('SELECT COUNT(*) FROM alat')->fetch()[0];
$peminjaman = $pdo->query('SELECT COUNT(*) FROM peminjaman')->fetch()[0];

echo "Users: $users\n";
echo "Kategori: $kategori\n";
echo "Alat: $alat\n";
echo "Peminjaman: $peminjaman\n";

echo "\n=== Users Data ===\n";
$stmt = $pdo->query('SELECT id_user, username, email, role FROM users');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id_user']}, Username: {$row['username']}, Email: {$row['email']}, Role: {$row['role']}\n";
}
