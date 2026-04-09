<?php
// Test all dashboard-critical API endpoints
echo "=== TESTING ALL DASHBOARD ENDPOINTS ===\n\n";

$endpoints = [
    '/api/users' => 'Users List',
    '/api/equipment' => 'Equipment List',
    '/api/categories' => 'Categories List',
    '/api/borrowings' => 'Borrowings List',
    '/api/borrowings/status/overdue' => 'Overdue Borrowings',
    '/api/borrowings/user/3' => 'Customer Borrowings'
];

$baseUrl = 'http://localhost:8000';
$results = [];

foreach ($endpoints as $endpoint => $description) {
    $ch = curl_init($baseUrl . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $dataCount = 0;
    if ($httpCode == 200) {
        $json = json_decode($response, true);
        $dataCount = count($json['data'] ?? []);
    }
    
    $status = $httpCode == 200 ? '✓' : '✗';
    $color = $httpCode == 200 ? 'OK' : "ERROR($httpCode)";
    
    printf("[%s] %-40s %s - Records: %d\n", $status, $description, $color, $dataCount);
    
    $results[$endpoint] = [
        'status' => $httpCode,
        'count' => $dataCount
    ];
}

echo "\n=== SUMMARY ===\n";
$success = count(array_filter($results, fn($r) => $r['status'] == 200));
echo "Success: $success/" . count($results) . " endpoints\n";

if ($success == count($results)) {
    echo "\n✅ ALL ENDPOINTS ARE WORKING!\n";
    echo "Dashboard should now load successfully.\n";
} else {
    echo "\n⚠️ Some endpoints still failing.\n";
}
?>
