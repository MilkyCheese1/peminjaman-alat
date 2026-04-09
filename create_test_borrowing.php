<?php
// Create test borrowing data to test active borrowings display
echo "=== CREATING TEST BORROWING DATA ===\n\n";

$mysqli = new mysqli('127.0.0.1', 'root', '', 'db_peminjaman');

// Get customer user ID
$customer = $mysqli->query("SELECT id_user FROM users WHERE role = 'customer'")->fetch_assoc();
$customerId = $customer['id_user'];

// Get first equipment
$equip = $mysqli->query("SELECT id_equipment FROM equipment LIMIT 1")->fetch_assoc();
$equipId = $equip['id_equipment'];

// Create a borrowing with status 'picked_up' (active)
$today = date('Y-m-d');
$returnDate = date('Y-m-d', strtotime($today . ' +3 days'));
$verificationCode = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

$sql = "INSERT INTO borrowings (
  id_user, 
  id_equipment, 
  borrow_date, 
  planned_return_date, 
  durasi_jam,
  kode_verifikasi,
  status,
  quantity,
  fine_amount,
  created_at,
  updated_at
) VALUES (
  $customerId,
  $equipId,
  '$today 08:00:00',
  '$returnDate 17:00:00',
  24,
  '$verificationCode',
  'picked_up',
  1,
  0.00,
  NOW(),
  NOW()
)";

if ($mysqli->query($sql)) {
    $borrowingId = $mysqli->insert_id;
    echo "[✓] Created test borrowing record\n";
    echo "  ID: $borrowingId\n";
    echo "  Customer: $customerId\n";
    echo "  Equipment: $equipId\n";
    echo "  Status: picked_up (ACTIVE)\n";
    echo "  Date: $today to $returnDate\n";
    echo "  Verification Code: $verificationCode\n";
} else {
    echo "[✗] Error creating borrowing: " . $mysqli->error . "\n";
}

// Create another borrowing that's OVERDUE
$borrowDate = date('Y-m-d', strtotime($today . ' -10 days'));
$overdueReturnDate = date('Y-m-d', strtotime($today . ' -1 days')); // Yesterday
$verCode2 = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

// Get different equipment
$equip2 = $mysqli->query("SELECT id_equipment FROM equipment WHERE id_equipment != $equipId LIMIT 1")->fetch_assoc();
$equipId2 = $equip2['id_equipment'];

$sql2 = "INSERT INTO borrowings (
  id_user,
  id_equipment,
  borrow_date,
  planned_return_date,
  durasi_jam,
  kode_verifikasi,
  status,
  quantity,
  fine_amount,
  created_at,
  updated_at
) VALUES (
  $customerId,
  $equipId2,
  '$borrowDate 08:00:00',
  '$overdueReturnDate 17:00:00',
  72,
  '$verCode2',
  'picked_up',
  1,
  15000.00,
  NOW(),
  NOW()
)";

if ($mysqli->query($sql2)) {
    $borrowingId2 = $mysqli->insert_id;
    echo "\n[✓] Created OVERDUE borrowing record\n";
    echo "  ID: $borrowingId2\n";
    echo "  Date: $borrowDate to $overdueReturnDate (PASSED!)\n";
    echo "  Status: picked_up (But should show as OVERDUE)\n";
}

// Verify active borrowings
echo "\n[✓] VERIFICATION\n";
$active = $mysqli->query("SELECT COUNT(*) as count FROM borrowings WHERE id_user = $customerId AND status = 'picked_up'")->fetch_assoc();
echo "  Active borrowings for customer: " . $active['count'] . "\n";

$allStats = $mysqli->query("
  SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'picked_up' THEN 1 ELSE 0 END) as active,
    SUM(CASE WHEN status = 'applied' THEN 1 ELSE 0 END) as pending
  FROM borrowings
")->fetch_assoc();

echo "\n[✓] SYSTEM STATS\n";
printf("  Total borrowings: %d\n", $allStats['total']);
printf("  Active (picked_up): %d\n", $allStats['active']);
printf("  Pending (applied): %d\n", $allStats['pending']);

echo "\n✅ TEST DATA CREATED!\n";
echo "Customer dashboard should now show active borrowings.\n";

$mysqli->close();
?>
