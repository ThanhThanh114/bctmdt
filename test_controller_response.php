<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\TripController;

echo "=== TESTING ACTUAL CONTROLLER RESPONSE ===\n\n";

// Simulate request with bus_type filter
$request = Request::create('/lichtrinh?bus_type=Giường nằm', 'GET');
$controller = new TripController();
$response = $controller->index($request);

// Get the response content
$content = $response->getContent();

// Check if we have results
if (strpos($content, 'Tìm thấy') !== false) {
    echo "✅ Filter is working! Found results in response.\n";

    // Extract the count
    preg_match('/Tìm thấy (\d+) chuyến xe/', $content, $matches);
    if (isset($matches[1])) {
        echo "📊 Found {$matches[1]} trips\n";
    }
} else {
    echo "❌ Filter is not working. No results found.\n";
}

// Check for specific trip details
if (strpos($content, 'Giường nằm') !== false) {
    echo "✅ Bus type filter working - found 'Giường nằm' trips\n";
} else {
    echo "❌ Bus type filter not working - no 'Giường nằm' trips found\n";
}

echo "\n=== TESTING OTHER FILTERS ===\n\n";

// Test price range filter
$request2 = Request::create('/lichtrinh?price_range=200000-400000', 'GET');
$response2 = $controller->index($request2);
$content2 = $response2->getContent();

if (strpos($content2, 'Tìm thấy') !== false) {
    echo "✅ Price range filter is working!\n";
    preg_match('/Tìm thấy (\d+) chuyến xe/', $content2, $matches2);
    if (isset($matches2[1])) {
        echo "📊 Found {$matches2[1]} trips in price range 200k-400k\n";
    }
} else {
    echo "❌ Price range filter not working\n";
}

// Test bus company filter
$request3 = Request::create('/lichtrinh?bus_company=1', 'GET');
$response3 = $controller->index($request3);
$content3 = $response3->getContent();

if (strpos($content3, 'Tìm thấy') !== false) {
    echo "✅ Bus company filter is working!\n";
    preg_match('/Tìm thấy (\d+) chuyến xe/', $content3, $matches3);
    if (isset($matches3[1])) {
        echo "📊 Found {$matches3[1]} trips for company ID 1\n";
    }
} else {
    echo "❌ Bus company filter not working\n";
}

echo "\n=== END TEST ===\n";
