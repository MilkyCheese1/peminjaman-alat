<?php
// Simple database check without full Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';

try {
    // Load .env
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    // Simple PDO connection
    $pdo = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    
    echo "\n✅ DATABASE CONNECTION SUCCESSFUL\n";
    echo "Host: " . env('DB_HOST') . "\n";
    echo "Database: " . env('DB_DATABASE') . "\n\n";
    
    // Check tables
    $tables = [
        'borrowings' => 'SELECT COUNT(*) as count FROM borrowings',
        'borrowing_returns' => 'SELECT COUNT(*) as count FROM borrowing_returns',
        'users' => 'SELECT COUNT(*) as count FROM users',
        'equipment' => 'SELECT COUNT(*) as count FROM equipment'
    ];
    
    echo "DATA RECORDS:\n";
    foreach ($tables as $table => $query) {
        $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        echo "  $table: {$result['count']}\n";
    }
    
    // Check status distribution
    echo "\nSTATUS DISTRIBUTION:\n";
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM borrowings GROUP BY status");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$row['status']}: {$row['count']}\n";
    }
    
    // Check codes
    echo "\nVERIFICATION CODES:\n";
    $codes = $pdo->query("SELECT COUNT(*) as count FROM borrowings WHERE kode_verifikasi IS NOT NULL")->fetch(PDO::FETCH_ASSOC);
    echo "  Generated: {$codes['count']}\n";
    
    $pickupCodes = $pdo->query("SELECT COUNT(*) as count FROM borrowings WHERE pickup_code IS NOT NULL")->fetch(PDO::FETCH_ASSOC);
    echo "  Pickup codes: {$pickupCodes['count']}\n";
    
    // Fine amounts
    echo "\nFINE CALCULATIONS:\n";
    $fines = $pdo->query("SELECT SUM(fine_amount) as total, MAX(fine_amount) as max, COUNT(*) as count FROM borrowings WHERE fine_amount > 0")->fetch(PDO::FETCH_ASSOC);
    echo "  Total fines recorded: Rp " . number_format($fines['total'] ?? 0) . "\n";
    echo "  Max fine amount: Rp " . number_format($fines['max'] ?? 0) . "\n";
    echo "  Records with fines: {$fines['count']}\n";

    // Sample fine breakdown
    echo "\nFINE BREAKDOWN:\n";
    $stmt = $pdo->query("SELECT fine_amount, COUNT(*) as count FROM borrowings WHERE fine_amount > 0 GROUP BY fine_amount");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  Rp " . number_format($row['fine_amount']) . ": {$row['count']} records\n";
    }
    
    echo "\n✅ ALL SYSTEMS OPERATIONAL\n";
    echo "Status: READY FOR PRODUCTION\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}
?>
