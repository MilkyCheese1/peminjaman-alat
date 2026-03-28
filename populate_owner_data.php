<?php
require 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = env('DB_HOST');
$user = env('DB_USERNAME');
$pass = env('DB_PASSWORD');
$db = env('DB_DATABASE');

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die('Connection error: ' . $mysqli->connect_error);
}

// Update owner data
echo "Updating owner profile data...\n";

$query = "UPDATE users SET 
    nama_lengkap = ?,
    phone = ?,
    alamat = ?,
    kota = ?,
    provinsi = ?,
    kode_pos = ?
WHERE username = 'owner'";

$stmt = $mysqli->prepare($query);
$nama_lengkap = 'Pemilik Sistem';
$phone = '08555123456';
$alamat = 'Jl. Sudirman No. 999, Kantor Pusat';
$kota = 'Jakarta';
$provinsi = 'DKI Jakarta';
$kode_pos = '12999';

$stmt->bind_param("ssssss", $nama_lengkap, $phone, $alamat, $kota, $provinsi, $kode_pos);

if ($stmt->execute()) {
    echo "✓ Owner profile updated\n\n";
} else {
    echo "✗ Failed to update owner profile: " . $stmt->error . "\n";
}
$stmt->close();

// Create sample activity logs for owner
echo "Creating activity logs...\n";

$activities = [
    ['username' => 'admin', 'action' => 'CREATE', 'model_type' => 'Alat', 'changes' => json_encode(['nama' => 'Proyektor'])],
    ['username' => 'admin', 'action' => 'UPDATE', 'model_type' => 'Alat', 'changes' => json_encode(['stok' => 30])],
    ['username' => 'petugas', 'action' => 'APPROVE', 'model_type' => 'Peminjaman', 'changes' => json_encode(['status' => 'approved'])],
    ['username' => 'customer', 'action' => 'BORROW', 'model_type' => 'Peminjaman', 'changes' => json_encode(['action' => 'borrow'])],
    ['username' => 'petugas', 'action' => 'RETURN', 'model_type' => 'Peminjaman', 'changes' => json_encode(['status' => 'returned'])],
    ['username' => 'admin', 'action' => 'DELETE', 'model_type' => 'Kategori', 'changes' => json_encode(['deleted' => true])],
    ['username' => 'owner', 'action' => 'LOGIN', 'model_type' => 'User', 'changes' => json_encode(['ip' => $_SERVER['REMOTE_ADDR'] ?? 'localhost'])]
];

$count = 0;
foreach ($activities as $activity) {
    // Get user ID
    $userResult = $mysqli->query("SELECT id_user FROM users WHERE username = '{$activity['username']}'");
    $userRow = $userResult->fetch_assoc();
    $userId = $userRow['id_user'];
    
    // Insert activity log
    $insertQuery = "INSERT INTO activity_logs (id_user, action, model_type, model_id, changes, ip_address) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insertQuery);
    $modelId = 1;
    $ipAddress = 'localhost';
    $stmt->bind_param("ississ", $userId, $activity['action'], $activity['model_type'], $modelId, $activity['changes'], $ipAddress);
    
    if ($stmt->execute()) {
        echo "✓ Activity logged: {$activity['action']} by {$activity['username']}\n";
        $count++;
    } else {
        echo "⚠ Failed to log activity: " . $stmt->error . "\n";
    }
    $stmt->close();
}

echo "\n✓ Setup complete!\n";
echo "\n=== Owner Account Info ===\n";
$result = $mysqli->query("SELECT id_user, username, email, nama_lengkap, kota, provinsi, kode_pos FROM users WHERE username = 'owner'");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "✓ Owner Account:\n";
    echo "  ID: {$row['id_user']}\n";
    echo "  Username: {$row['username']}\n";
    echo "  Email: {$row['email']}\n";
    echo "  Nama: {$row['nama_lengkap']}\n";
    echo "  Kota: {$row['kota']}\n";
    echo "  Provinsi: {$row['provinsi']}\n";
    echo "  Kode Pos: {$row['kode_pos']}\n";
}

$mysqli->close();
?>
