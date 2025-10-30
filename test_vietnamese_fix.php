<?php

// Test the Vietnamese character encoding fix
echo "=== TESTING VIETNAMESE CHARACTER ENCODING FIX ===\n\n";

$testUrls = [
    'Giường nằm' => 'http://127.0.0.1:8000/lichtrinh?bus_type=Gi%C6%B0%E1%BB%9Dng+n%E1%BA%B1m&page=1',
    'Ghế ngồi' => 'http://127.0.0.1:8000/lichtrinh?bus_type=Gh%E1%BA%BF+ng%E1%BB%93i&page=1',
    'Limousine' => 'http://127.0.0.1:8000/lichtrinh?bus_type=Limousine&page=1',
];

foreach ($testUrls as $name => $url) {
    echo "Testing $name filter...\n";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        // Check if response contains trips or "không tìm thấy" message
        if (strpos($response, 'không tìm thấy') !== false || strpos($response, 'Không tìm thấy') !== false) {
            echo "  ✗ Still showing 'no trips found' message\n";
        } elseif (strpos($response, 'chuyến xe') !== false || strpos($response, 'trip') !== false) {
            echo "  ✓ Filter working - showing trips\n";
        } else {
            echo "  ⚠ Unclear response content\n";
        }
    } else {
        echo "  ✗ HTTP Error: $httpCode\n";
    }

    echo "\n";
}

echo "=== TEST COMPLETE ===\n";

?>
