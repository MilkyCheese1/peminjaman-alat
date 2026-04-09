<?php
// Test API endpoint that's failing
echo "=== TESTING /api/users ENDPOINT ===\n\n";

$ch = curl_init('http://localhost:8000/api/users');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);

$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);

echo "[HTTP Status: $httpCode]\n\n";

if ($httpCode == 500) {
    echo "[ERROR RESPONSE]\n";
    echo $body;
    echo "\n\n";
    
    // Try to parse JSON error
    $json = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "[PLAIN TEXT ERROR]\n";
        echo $body;
    } else {
        echo "[JSON ERROR]\n";
        echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
} else {
    echo "[SUCCESS]\n";
    $json = json_decode($body, true);
    if ($json) {
        echo "Users found: " . count($json['data'] ?? []) . "\n";
    }
}
?>
