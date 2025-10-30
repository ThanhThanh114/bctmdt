<?php

// Test script to verify filter functionality
$baseUrl = 'http://127.0.0.1:8000/lichtrinh';

function testFilter($url, $filterName, $expectedBehavior = 'should return trips') {
    echo "Testing $filterName...\n";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo "✓ $filterName: HTTP $httpCode - Working\n";

        // Check if response contains expected content
        if (strpos($response, 'trips-content') !== false) {
            echo "  ✓ Contains trips content\n";
        } else {
            echo "  ⚠ Missing trips content\n";
        }

        // Check for JSON response vs HTML
        if (strpos($response, 'X-Requested-With: XMLHttpRequest') !== false) {
            echo "  ℹ AJAX request detected\n";
        }
    } else {
        echo "✗ $filterName: HTTP $httpCode - Failed\n";
    }

    echo "\n";
}

// Test 1: Basic page load
echo "=== FILTER TESTING SCRIPT ===\n\n";
testFilter($baseUrl, 'Basic page load');

// Test 2: Bus type filter - Giường nằm
testFilter($baseUrl . '?bus_type=Giường nằm', 'Bus Type Filter - Giường nằm');

// Test 3: Bus type filter - Limousine
testFilter($baseUrl . '?bus_type=Limousine', 'Bus Type Filter - Limousine');

// Test 4: Bus company filter (using a numeric ID - assuming 1 exists)
testFilter($baseUrl . '?bus_company=1', 'Bus Company Filter - ID 1');

// Test 5: Driver filter (using a driver name - assuming some drivers exist)
testFilter($baseUrl . '?driver=An', 'Driver Filter - Name containing "An"');

// Test 6: Price range filter
testFilter($baseUrl . '?price_range=200000-400000', 'Price Range Filter - 200k-400k');

// Test 7: Sort by price ascending
testFilter($baseUrl . '?sort=price_asc', 'Sort Filter - Price Ascending');

// Test 8: Combined filters
testFilter($baseUrl . '?bus_type=Giường nằm&price_range=200000-400000&sort=price_asc', 'Combined Filters');

echo "=== TESTING COMPLETE ===\n";
echo "\nNote: Test the following URL in a browser to see the full interface:\n";
echo "$baseUrl\n";
echo "\nTest specific filters by modifying the URL parameters.\n";

?>
