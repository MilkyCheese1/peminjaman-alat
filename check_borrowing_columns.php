<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'db_peminjaman');
$result = $mysqli->query('DESCRIBE borrowings');
echo "=== BORROWINGS TABLE COLUMNS ===\n\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
$mysqli->close();
?>
