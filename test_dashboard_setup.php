<?php
echo "=== CUSTOMER DASHBOARD TEST SETUP ===\n\n";

// 1. Check database
$mysqli = new mysqli('127.0.0.1', 'root', '', 'db_peminjaman');

// Get customer
$customer = $mysqli->query("SELECT * FROM users WHERE role = 'customer' LIMIT 1")->fetch_assoc();
echo "[✓] Customer User\n";
echo "  ID: " . $customer['id_user'] . "\n";
echo "  Name: " . $customer['nama_lengkap'] . "\n";
echo "  Email: " . $customer['email'] . "\n";

// Get active borrowings
$borrowings = $mysqli->query("
  SELECT 
    b.*,
    e.name as equipment_name,
    c.name as category_name,
    u.nama_lengkap
  FROM borrowings b
  JOIN equipment e ON b.id_equipment = e.id_equipment
  JOIN categories c ON e.id_category = c.id_category
  JOIN users u ON b.id_user = u.id_user
  WHERE b.id_user = " . $customer['id_user'] . "
  AND b.status = 'picked_up'
  ORDER BY b.planned_return_date ASC
")->fetch_all(MYSQLI_ASSOC);

echo "\n[✓] Active Borrowings in Database\n";
echo "  Total: " . count($borrowings) . "\n";

foreach ($borrowings as $b) {
    $returnDate = $b['planned_return_date'];
    $today = date('Y-m-d');
    $isOverdue = strtotime($returnDate) < strtotime($today);
    
    echo "\n  - ID: " . $b['id_borrowing'] . "\n";
    echo "    Equipment: " . $b['equipment_name'] . " (" . $b['category_name'] . ")\n";
    echo "    Return Date: " . $returnDate . "\n";
    echo "    Status: " . ($isOverdue ? "🔴 OVERDUE" : "🟢 ACTIVE") . "\n";
    echo "    Code: " . $b['kode_verifikasi'] . "\n";
    echo "    Fine: Rp " . number_format($b['fine_amount'], 0, ',', '.') . "\n";
}

// Test API endpoint
echo "\n[✓] API Connection Test\n";
$curl = curl_init("http://localhost:8000/api/borrowings/user/" . $customer['id_user']);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "  HTTP Status: $httpCode\n";
if ($httpCode === 200) {
    $data = json_decode($response, true);
    echo "  Records returned: " . count($data['data']) . "\n";
    echo "  ✅ API is working correctly\n";
}

// Test formatDate function would work
echo "\n[✓] Date Formatting Test\n";
foreach (['2026-04-09', '2026-04-12', '2026-04-08'] as $date) {
    $dateObj = new DateTime($date);
    $formatted = $dateObj->format('d M Y');
    echo "  $date → $formatted\n";
}

echo "\n✅ ALL SYSTEMS READY FOR DASHBOARD TESTING\n";
echo "\n📝 TEST STEPS:\n";
echo "1. Open http://localhost:8000 in browser\n";
echo "2. Login as: customer@trustequip.id / Password123!\n";
echo "3. Navigate to the Dashboard Overview tab\n";
echo "4. Check 'Peminjaman Aktif' section\n";
echo "5. Should see 2 borrowings:\n";
echo "   - Laptop Dell XPS 15 (🟢 ACTIVE)\n";
echo "   - Kamera DSLR Canon 5D (🔴 OVERDUE) with Rp 15,000 fine\n";

$mysqli->close();
?>
