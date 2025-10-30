<?php

// Direct database query to check bus types
try {
    // Database configuration
    $host = 'localhost';
    $dbname = 'tmdt_bc'; // Update this to your actual database name
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== ACTUAL BUS TYPE VALUES IN DATABASE ===\n\n";

    // Query distinct bus types
    $stmt = $pdo->query("SELECT DISTINCT loai_xe FROM chuyen_xe WHERE loai_xe IS NOT NULL AND loai_xe != '' ORDER BY loai_xe");
    $busTypes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Found " . count($busTypes) . " unique bus types:\n";
    foreach ($busTypes as $i => $type) {
        echo ($i + 1) . ". '" . $type . "' (length: " . strlen($type) . ", bytes: " . strlen(bin2hex($type))/2 . ")\n";
    }

    // Test specific values we're looking for
    echo "\n=== TESTING SPECIFIC VALUES ===\n";
    $testValues = ['Giường nằm', 'Ghế ngồi', 'Limousine'];

    foreach ($testValues as $testValue) {
        echo "Testing '$testValue':\n";
        foreach ($busTypes as $dbType) {
            $match = (strcasecmp(trim($testValue), trim($dbType)) === 0);
            echo "  vs '$dbType': " . ($match ? "MATCH" : "NO MATCH") . "\n";
        }
        echo "\n";
    }

} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage() . "\n";
    echo "Please check your database configuration.\n";
}

?>
