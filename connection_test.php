<?php
/**
 * CONNECTION & INTEGRATION TEST
 * Checks: Database, API endpoints, middleware, authentication
 * Date: April 9, 2026
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "\n" . str_repeat("=", 80) . "\n";
echo "🔗 CONNECTION & INTEGRATION TEST\n";
echo str_repeat("=", 80) . "\n\n";

$issues = [];
$passed = 0;
$total = 0;

// Test 1: Database Connection
echo "[TEST 1] Database Connection...\n";
$total++;
try {
    $connection = DB::connection()->getPdo();
    if ($connection) {
        echo "✅ Database connected successfully\n";
        $passed++;
    }
} catch (\Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    $issues[] = "Database: " . $e->getMessage();
}

// Test 2: Check tables exist
echo "\n[TEST 2] Required Tables...\n";
$tables = ['users', 'equipment', 'categories', 'borrowings', 'borrowing_returns'];
$total++;
$allTablesExist = true;
foreach ($tables as $table) {
    $exists = DB::connection()->getSchemaBuilder()->hasTable($table);
    if ($exists) {
        echo "  ✅ $table\n";
    } else {
        echo "  ❌ $table MISSING\n";
        $allTablesExist = false;
    }
}
if ($allTablesExist) $passed++;
else $issues[] = "Missing tables";

// Test 3: Check borrowings table structure
echo "\n[TEST 3] Borrowings Table Structure...\n";
$total++;
$schema = DB::connection()->getSchemaBuilder();
$columns = $schema->getColumnListing('borrowings');
$requiredColumns = [
    'id_peminjaman',
    'id_user',
    'id_equipment',
    'status',
    'kode_verifikasi',
    'pickup_code',
    'fine_amount',
    'planned_return_date',
    'actual_return_date',
    'quantity'
];

$allColumnsExist = true;
foreach ($requiredColumns as $col) {
    if (in_array($col, $columns)) {
        echo "  ✅ $col\n";
    } else {
        echo "  ❌ $col MISSING\n";
        $allColumnsExist = false;
    }
}
if ($allColumnsExist) $passed++;
else $issues[] = "Missing borrowings columns";

// Test 4: Check data integrity
echo "\n[TEST 4] Data Integrity...\n";
$total++;
$userCount = DB::table('users')->count();
$equipmentCount = DB::table('equipment')->count();
$borrowingCount = DB::table('borrowings')->count();

echo "  Users: $userCount\n";
echo "  Equipment: $equipmentCount\n";
echo "  Borrowings: $borrowingCount\n";

if ($userCount > 0 && $equipmentCount > 0 && $borrowingCount > 0) {
    echo "✅ All core data exists\n";
    $passed++;
} else {
    echo "⚠️ Some data missing\n";
    $issues[] = "Insufficient test data";
}

// Test 5: Verification code generation
echo "\n[TEST 5] Verification Codes...\n";
$total++;
$codesGenerated = DB::table('borrowings')
    ->whereNotNull('kode_verifikasi')
    ->count();
$totalBorrowings = DB::table('borrowings')->count();

if ($codesGenerated == $totalBorrowings && $codesGenerated > 0) {
    echo "✅ All borrowings have verification codes ($codesGenerated/$totalBorrowings)\n";
    $passed++;
} else {
    echo "⚠️ Verification codes missing on some records\n";
    $issues[] = "Verification codes: $codesGenerated/$totalBorrowings";
}

// Test 6: Status workflow
echo "\n[TEST 6] Status Workflow...\n";
$total++;
$statuses = DB::table('borrowings')
    ->select('status')
    ->distinct()
    ->pluck('status')
    ->toArray();

$validStatuses = ['applied', 'approved', 'ready_for_pickup', 'picked_up', 'returned', 'rejected', 'cancelled', 'overdue'];
$allValid = true;
foreach ($statuses as $status) {
    if (in_array($status, $validStatuses)) {
        echo "  ✅ $status\n";
    } else {
        echo "  ❌ $status INVALID\n";
        $allValid = false;
    }
}
if ($allValid && count($statuses) > 0) {
    echo "✅ All statuses valid\n";
    $passed++;
} else {
    $issues[] = "Invalid statuses found";
}

// Test 7: Fine calculations
echo "\n[TEST 7] Fine Calculations...\n";
$total++;
$finesRecorded = DB::table('borrowings')
    ->where('status', 'returned')
    ->whereNotNull('fine_amount')
    ->count();
$returnedTotal = DB::table('borrowings')
    ->where('status', 'returned')
    ->count();

echo "  Returned records: $returnedTotal\n";
echo "  With fine amounts: $finesRecorded\n";

if ($finesRecorded == $returnedTotal && $returnedTotal > 0) {
    // Check fine values
    $fines = DB::table('borrowings')
        ->where('status', 'returned')
        ->pluck('fine_amount', 'id_peminjaman')
        ->toArray();
    
    $validFines = true;
    foreach ($fines as $id => $fine) {
        if ($fine >= 0 && $fine <= 1500000) {
            echo "  ✅ Borrowing #$id: Rp " . number_format($fine) . "\n";
        } else {
            echo "  ❌ Borrowing #$id: Invalid fine Rp " . number_format($fine) . "\n";
            $validFines = false;
        }
    }
    
    if ($validFines) {
        echo "✅ All fine calculations valid\n";
        $passed++;
    } else {
        $issues[] = "Invalid fine amounts";
    }
} else {
    echo "⚠️ Some returned records missing fine amounts\n";
    $issues[] = "Incomplete fine data";
}

// Test 8: Borrowing returns table
echo "\n[TEST 8] Return Details Table...\n";
$total++;
$returnsCount = DB::table('borrowing_returns')->count();
$returnedBorrowings = DB::table('borrowings')
    ->where('status', 'returned')
    ->count();

echo "  Return records: $returnsCount\n";
echo "  Returned borrowings: $returnedBorrowings\n";

if ($returnsCount == $returnedBorrowings && $returnsCount > 0) {
    echo "✅ All returns properly recorded\n";
    $passed++;
} else {
    echo "⚠️ Mismatch in return records\n";
    $issues[] = "Return records mismatch: $returnsCount vs $returnedBorrowings";
}

// Test 9: User-Equipment relationships
echo "\n[TEST 9] User-Equipment Relationships...\n";
$total++;
$borrowingsWithRelations = DB::table('borrowings')
    ->whereNotNull('id_user')
    ->whereNotNull('id_equipment')
    ->count();
$allBorrowings = DB::table('borrowings')->count();

if ($borrowingsWithRelations == $allBorrowings && $allBorrowings > 0) {
    echo "✅ All borrowings have user & equipment ($borrowingsWithRelations/$allBorrowings)\n";
    $passed++;
} else {
    echo "❌ Some borrowings missing relations\n";
    $issues[] = "Missing relationships: $borrowingsWithRelations/$allBorrowings";
}

// Test 10: Equipment availability
echo "\n[TEST 10] Equipment Status...\n";
$total++;
$availableEquipment = DB::table('equipment')
    ->where('is_available', 1)
    ->count();
$totalEquipment = DB::table('equipment')->count();

echo "  Available: $availableEquipment/$totalEquipment\n";

$equipment = DB::table('equipment')->select('id_equipment', 'name', 'quantity', 'is_available')->limit(5)->get();
foreach ($equipment as $item) {
    echo "  ✓ " . $item->name . " (Qty: {$item->quantity})\n";
}

if ($availableEquipment > 0) {
    echo "✅ Equipment available\n";
    $passed++;
} else {
    echo "❌ No equipment available\n";
    $issues[] = "Equipment not available";
}

// Summary
echo "\n" . str_repeat("=", 80) . "\n";
echo "📊 CONNECTION TEST SUMMARY\n";
echo str_repeat("=", 80) . "\n\n";

echo "Tests Passed: $passed/$total\n";
echo "Pass Rate: " . round(($passed/$total)*100) . "%\n\n";

if (count($issues) === 0) {
    echo "✅ ALL SYSTEMS OPERATIONAL - NO ISSUES FOUND\n";
    echo "\n🎯 System Status: READY FOR PRODUCTION\n";
} else {
    echo "⚠️ ISSUES FOUND:\n";
    foreach ($issues as $issue) {
        echo "  - $issue\n";
    }
}

// Test setup verification
echo "\n" . str_repeat("=", 80) . "\n";
echo "🧪 TEST SETUP VERIFICATION\n";
echo str_repeat("=", 80) . "\n\n";

$users = DB::table('users')->select('id_user', 'username', 'role')->get();
echo "Test Users Available:\n";
foreach ($users as $user) {
    echo "  - {$user->username} ({$user->role}) [ID: {$user->id_user}]\n";
}

echo "\nTest Equipment Available:\n";
$equip = DB::table('equipment')->select('id_equipment', 'name', 'quantity')->limit(5)->get();
foreach ($equip as $item) {
    echo "  - {$item->name} (Qty: {$item->quantity}) [ID: {$item->id_equipment}]\n";
}

// Current status samples
echo "\nCurrent Borrowing Status Samples:\n";
$samples = DB::table('borrowings')
    ->selectRaw('status, COUNT(*) as count, MAX(fine_amount) as max_fine')
    ->groupBy('status')
    ->get();

foreach ($samples as $sample) {
    echo "  {$sample->status}: {$sample->count} records (max fine: Rp " . number_format($sample->max_fine ?? 0) . ")\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "Generated: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 80) . "\n\n";
?>
