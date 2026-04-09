<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'db_peminjaman');

// Check equipment columns
$result = $mysqli->query('DESCRIBE equipment');
echo "=== EQUIPMENT TABLE COLUMNS ===\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}

// Check categories columns
$result = $mysqli->query('DESCRIBE categories');
echo "\n=== CATEGORIES TABLE COLUMNS ===\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}

$mysqli->close();
?>
