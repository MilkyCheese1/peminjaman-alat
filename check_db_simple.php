<?php
// Quick database check
$host = '127.0.0.1';
$db = 'db_peminjaman';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass);
    
    // Check users table columns
    $result = $pdo->query("SHOW COLUMNS FROM users");
    echo "=== Users Table Columns ===\n";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " (" . $row['Type'] . ", Null: " . $row['Null'] . ")\n";
    }
    
    // Check sample user
    echo "\n=== Sample User Data ===\n";
    $result = $pdo->query("SELECT * FROM users LIMIT 1");
    $user = $result->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        foreach ($user as $k => $v) {
            echo "$k: " . ($v ?? 'NULL') . "\n";
        }
    }
    
    // Check migrations
    echo "\n=== Recent Migrations ===\n";
    $result = $pdo->query("SELECT migration, batch FROM migrations ORDER BY batch DESC LIMIT 10");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['migration'] . " (batch: " . $row['batch'] . ")\n";
    }
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}
?>
