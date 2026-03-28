<?php
$mysqli = new mysqli('localhost', 'root', '', 'db_peminjaman');
$result = $mysqli->query('DESC activity_logs');
if ($result && $result->num_rows > 0) {
    echo "Columns in activity_logs table:\n";
    while($row = $result->fetch_assoc()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
} else {
    echo "Table activity_logs not found or empty\n";
}
?>
