<?php
// Test API endpoint for customer borrowings
echo "=== TESTING API RESPONSE ===\n\n";

// Get customer ID
$mysqli = new mysqli('127.0.0.1', 'root', '', 'db_peminjaman');
$customer = $mysqli->query("SELECT id_user FROM users WHERE role = 'customer' LIMIT 1")->fetch_assoc();
$customerId = $customer['id_user'];
$mysqli->close();

echo "Customer ID: $customerId\n";
echo "Endpoint: GET /api/borrowings/user/$customerId\n\n";

// Call the API
$curl = curl_init("http://localhost:8000/api/borrowings/user/$customerId");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "[HTTP $httpCode]\n\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    echo $response;
}
?>
