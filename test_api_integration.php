<?php
/**
 * API Integration Test
 * Test semua endpoint untuk memastikan database integration berjalan
 */

$baseUrl = 'http://localhost:8000/api';
$csrfToken = null;
$sessionCookie = '';

function makeRequest($method, $endpoint, $data = null, $isAuth = false) {
    global $baseUrl, $sessionCookie;
    
    $ch = curl_init($baseUrl . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_COOKIE, $sessionCookie);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    
    // Get session cookie if not already set
    if (empty($sessionCookie)) {
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        if (preg_match('/Set-Cookie: ([^;]+)/i', $headers, $matches)) {
            $sessionCookie = $matches[1];
        }
    }
    
    curl_close($ch);
    return json_decode($response, true);
}

echo "=== API Integration Test ===\n\n";

// Test 1: Login dengan existing user 
echo "1️⃣ Testing Login Endpoint...\n";
$loginResult = makeRequest('POST', '/login', [
    'username_or_email' => 'admin@example.com',
    'password' => 'password123' // Testing standard password
]);
print_r($loginResult);
echo "\n";

// If login fails, try with username
if (!isset($loginResult['success']) || !$loginResult['success']) {
    echo "Trying with username...\n";
    $loginResult = makeRequest('POST', '/login', [
        'username_or_email' => 'admin',
        'password' => 'password123'
    ]);
    print_r($loginResult);
    echo "\n";
}

// Test 2: Get Dashboard Stats
echo "2️⃣ Testing Dashboard Stats (Admin)...\n";
$statsResult = makeRequest('GET', '/dashboard/stats', null, true);
print_r($statsResult);
echo "\n";

// Test 3: Get Alat List
echo "3️⃣ Testing Get Alat List...\n";
$alatResult = makeRequest('GET', '/alat', null, true);
if (isset($alatResult['data'])) {
    echo "Total alat: " . count($alatResult['data']) . "\n";
    if (count($alatResult['data']) > 0) {
        echo "Sample: " . json_encode($alatResult['data'][0]) . "\n";
    }
} else {
    print_r($alatResult);
}
echo "\n";

// Test 4: Get Users (Admin only)
echo "4️⃣ Testing Get Users (Admin)...\n";
$usersResult = makeRequest('GET', '/users', null, true);
if (isset($usersResult['data'])) {
    echo "Total users: " . count($usersResult['data']) . "\n";
    foreach ($usersResult['data'] as $user) {
        echo "  - {$user['username']} ({$user['role']})\n";
    }
} else {
    print_r($usersResult);
}
echo "\n";

// Test 5: Get Peminjaman
echo "5️⃣ Testing Get Peminjaman...\n";
$peminjamanResult = makeRequest('GET', '/peminjaman', null, true);
if (isset($peminjamanResult['data'])) {
    echo "Total peminjaman: " . count($peminjamanResult['data']) . "\n";
} else {
    print_r($peminjamanResult);
}
echo "\n";

// Test 6: Get Profile
echo "6️⃣ Testing Get Profile...\n";
$profileResult = makeRequest('GET', '/profile', null, true);
print_r($profileResult);
echo "\n";

echo "=== Test Complete ===\n";
