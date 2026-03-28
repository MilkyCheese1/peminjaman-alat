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

// Sample data for each user
$userData = [
    [
        'id_user' => 3,
        'nama_lengkap' => 'Admin Sistem',
        'phone' => '08123456789',
        'alamat' => 'Jl. Merdeka No. 1, Pusat Kota',
        'kota' => 'Jakarta',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '12345'
    ],
    [
        'id_user' => 4,
        'nama_lengkap' => 'Petugas Alat',
        'phone' => '08198765432',
        'alamat' => 'Jl. Ahmad Yani No. 25, Kompleks Perkantoran',
        'kota' => 'Bandung',
        'provinsi' => 'Jawa Barat',
        'kode_pos' => '40123'
    ],
    [
        'id_user' => 5,
        'nama_lengkap' => 'Budi Santoso',
        'phone' => '08567890123',
        'alamat' => 'Jl. Sudirman No. 42, Perumahan Bersih',
        'kota' => 'Surabaya',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '60123'
    ]
];

echo "Updating user data...\n\n";

foreach ($userData as $data) {
    $id_user = $data['id_user'];
    $nama_lengkap = $data['nama_lengkap'];
    $phone = $data['phone'];
    $alamat = $data['alamat'];
    $kota = $data['kota'];
    $provinsi = $data['provinsi'];
    $kode_pos = $data['kode_pos'];
    
    $query = "UPDATE users SET 
        nama_lengkap = ?, 
        phone = ?, 
        alamat = ?, 
        kota = ?, 
        provinsi = ?, 
        kode_pos = ? 
        WHERE id_user = ?";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssssi", $nama_lengkap, $phone, $alamat, $kota, $provinsi, $kode_pos, $id_user);
    
    if ($stmt->execute()) {
        echo "✓ Updated user ID $id_user ({$data['nama_lengkap']})\n";
        echo "  Kota: {$data['kota']}\n";
        echo "  Provinsi: {$data['provinsi']}\n";
        echo "  Kode Pos: {$data['kode_pos']}\n";
        echo "  Phone: {$data['phone']}\n\n";
    } else {
        echo "✗ Failed to update user ID $id_user: " . $stmt->error . "\n\n";
    }
    
    $stmt->close();
}

// Verify all users
echo "=== Verification ===\n";
$result = $mysqli->query("SELECT id_user, username, nama_lengkap, kota, provinsi, kode_pos, phone FROM users WHERE id_user IN (3, 4, 5)");

while ($row = $result->fetch_assoc()) {
    echo "\nUser: {$row['username']} (ID: {$row['id_user']})\n";
    echo "  Nama: " . ($row['nama_lengkap'] ? $row['nama_lengkap'] : 'NULL') . "\n";
    echo "  Kota: " . ($row['kota'] ? $row['kota'] : 'NULL') . "\n";
    echo "  Provinsi: " . ($row['provinsi'] ? $row['provinsi'] : 'NULL') . "\n";
    echo "  Kode Pos: " . ($row['kode_pos'] ? $row['kode_pos'] : 'NULL') . "\n";
    echo "  Phone: " . ($row['phone'] ? $row['phone'] : 'NULL') . "\n";
}

echo "\n✓ All user data updated!\n";

$mysqli->close();
?>
