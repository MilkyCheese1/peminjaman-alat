<?php
// Clean database - delete all borrowings & returns for fresh start
$mysqli = new mysqli('127.0.0.1', 'root', '', 'db_peminjaman');

echo "=== CLEANING DATABASE ===\n\n";

// Disable foreign key checks temporarily
$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

// Delete borrowing_returns
$result = $mysqli->query("DELETE FROM borrowing_returns");
$affected = $mysqli->affected_rows;
echo "[1] Deleted borrowing_returns: $affected records\n";

// Delete borrowings
$result = $mysqli->query("DELETE FROM borrowings");
$affected = $mysqli->affected_rows;
echo "[2] Deleted borrowings: $affected records\n";

// Re-enable foreign key checks
$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

// Verify cleanup
echo "\n[VERIFICATION]\n";
$borrowings = $mysqli->query("SELECT COUNT(*) as count FROM borrowings")->fetch_assoc();
$returns = $mysqli->query("SELECT COUNT(*) as count FROM borrowing_returns")->fetch_assoc();

printf("  Borrowings: %d\n", $borrowings['count']);
printf("  Returns: %d\n", $returns['count']);

if ($borrowings['count'] == 0 && $returns['count'] == 0) {
    echo "\n✓ DATABASE CLEANED - Ready for fresh testing\n";
} else {
    echo "\n✗ CLEANUP INCOMPLETE\n";
}

$mysqli->close();
?>
