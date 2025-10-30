<?php

// Debug bus types and encoding
require_once 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    // Create a test request
    $request = Illuminate\Http\Request::create('/lichtrinh?bus_type=Gi%C6%B0%E1%BB%9Dng+n%E1%BA%B1m', 'GET');

    echo "=== BUS TYPE ENCODING DEBUG ===\n\n";

    // Test URL decoded value
    $decoded = urldecode('Gi%C6%B0%E1%BB%9Dng+n%E1%BA%B1m');
    echo "URL Decoded value: '" . $decoded . "'\n";
    echo "URL Decoded (UTF-8): '" . utf8_decode($decoded) . "'\n\n";

    // Get actual bus types from database
    $pdo = new PDO("mysql:host=localhost;dbname=test", "root", "");
    $stmt = $pdo->query("SELECT DISTINCT loai_xe FROM chuyen_xe WHERE loai_xe IS NOT NULL AND loai_xe != '' ORDER BY loai_xe");
    $busTypes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Actual bus types in database:\n";
    foreach ($busTypes as $type) {
        echo "- '" . $type . "'\n";
    }

    echo "\n=== COMPARISON ===\n";
    foreach ($busTypes as $type) {
        $match = strcasecmp(trim($decoded), trim($type)) === 0;
        echo "'" . $decoded . "' vs '" . $type . "': " . ($match ? "MATCH" : "NO MATCH") . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>
