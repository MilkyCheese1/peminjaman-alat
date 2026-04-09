<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'db_peminjaman');

echo "=== CURRENT USER DATABASE STATE ===\n\n";

$result = $mysqli->query('SELECT id_user, nama_lengkap, username, email, role FROM users ORDER BY role, id_user');

echo sprintf("%-5s %-25s %-15s %-15s %-15s", 'ID', 'NAMA', 'USERNAME', 'EMAIL', 'ROLE') . "\n";
echo str_repeat('-', 85) . "\n";

$role_count = [];
while ($row = $result->fetch_assoc()) {
    echo sprintf("%-5s %-25s %-15s %-15s %-15s", 
        $row['id_user'], 
        substr($row['nama_lengkap'] ?? 'NULL', 0, 25), 
        substr($row['username'] ?? 'NULL', 0, 15),
        substr($row['email'] ?? 'NULL', 0, 15),
        $row['role'] ?? 'NULL'
    ) . "\n";
    
    $role = $row['role'] ?? 'unknown';
    $role_count[$role] = ($role_count[$role] ?? 0) + 1;
}

echo "\n[ROLE SUMMARY]\n";
foreach (['owner', 'admin', 'staff', 'customer'] as $role) {
    $count = $role_count[$role] ?? 0;
    $status = $count == 1 ? '✓ OK' : '✗ EXTRA';
    echo sprintf("  %s %s: %d user(s)\n", $status, $role, $count);
}

echo "\n[DATA SUMMARY]\n";
$users = $mysqli->query('SELECT COUNT(*) as count FROM users')->fetch_assoc();
$borrowings = $mysqli->query('SELECT COUNT(*) as count FROM borrowings')->fetch_assoc();
$returns = $mysqli->query('SELECT COUNT(*) as count FROM borrowing_returns')->fetch_assoc();

printf("  Users: %d\n", $users['count']);
printf("  Borrowings: %d\n", $borrowings['count']);
printf("  Returns: %d\n", $returns['count']);

echo "\n[PLAN]\n";
echo "  → Delete test users (keep only 4)\n";
echo "  → Delete all borrowing records\n";
echo "  → Delete all return records\n";
echo "  → Create role-based middleware\n";
echo "  → Update dashboards for each role\n";
?>
