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

// Create owner user
$ownerData = [
    'username' => 'owner',
    'email' => 'owner@peminjaman.local',
    'password' => password_hash('owner', PASSWORD_BCRYPT),
    'phone' => '08555123456',
    'alamat' => 'Jl. Sudirman No. 999, Kantor Pusat',
    'kota' => 'Jakarta',
    'provinsi' => 'DKI Jakarta',
    'kode_pos' => '12999',
    'nama_lengkap' => 'Pemilik Sistem',
    'role' => 'owner',
    'email_verified' => 1,
    'is_active' => 1
];

echo "Creating owner user...\n";

// Check if owner already exists
$checkQuery = "SELECT id_user FROM users WHERE username = 'owner'";
$checkResult = $mysqli->query($checkQuery);

if ($checkResult->num_rows > 0) {
    echo "⚠ Owner user already exists\n";
    $ownerId = $checkResult->fetch_assoc()['id_user'];
} else {
    // Insert new owner
    $query = "INSERT INTO users (username, email, password, phone, alamat, kota, provinsi, kode_pos, nama_lengkap, role, email_verified, is_active, created_at, updated_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssssssssiii", 
        $ownerData['username'],
        $ownerData['email'],
        $ownerData['password'],
        $ownerData['phone'],
        $ownerData['alamat'],
        $ownerData['kota'],
        $ownerData['provinsi'],
        $ownerData['kode_pos'],
        $ownerData['nama_lengkap'],
        $ownerData['role'],
        $ownerData['email_verified'],
        $ownerData['is_active']
    );
    
    if ($stmt->execute()) {
        $ownerId = $stmt->insert_id;
        echo "✓ Owner user created successfully!\n";
        echo "  Username: owner\n";
        echo "  Password: owner\n";
        echo "  Email: owner@peminjaman.local\n";
    } else {
        echo "✗ Failed to create owner user: " . $stmt->error . "\n";
        exit(1);
    }
    $stmt->close();
}

// Verify owner user
echo "\n=== Verification ===\n";
$result = $mysqli->query("SELECT id_user, username, email, role, kota, provinsi FROM users WHERE username = 'owner'");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "✓ Owner user verified:\n";
    echo "  ID: {$row['id_user']}\n";
    echo "  Username: {$row['username']}\n";
    echo "  Email: {$row['email']}\n";
    echo "  Role: {$row['role']}\n";
    echo "  Kota: {$row['kota']}\n";
    echo "  Provinsi: {$row['provinsi']}\n";
}

echo "\n✓ Owner account setup complete!\n";
echo "\nLogin with:\n";
echo "  URL: http://localhost:8000/login\n";
echo "  Username: owner\n";
echo "  Password: owner\n";

$mysqli->close();
?>
