<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ChuyenXe;

echo "Checking and fixing route names...\n";

try {
    $trips = ChuyenXe::all();

    foreach ($trips as $trip) {
        if (empty($trip->route_name) && $trip->tramDi && $trip->tramDen) {
            $routeName = ($trip->tramDi->ten_tram ?? 'N/A') . ' → ' . ($trip->tramDen->ten_tram ?? 'N/A');
            $trip->update(['route_name' => $routeName]);
            echo "✅ Updated trip #{$trip->id}: {$routeName}\n";
        } elseif (!empty($trip->route_name)) {
            echo "✅ Trip #{$trip->id} already has route name: {$trip->route_name}\n";
        } else {
            echo "⚠️ Trip #{$trip->id} missing location data\n";
        }
    }

    echo "\n=== CHECKING BOOKING #101 ===\n";
    $booking = \App\Models\DatVe::with(['chuyenXe.tramDi', 'chuyenXe.tramDen'])->find(101);
    if ($booking && $booking->chuyenXe) {
        echo "Updated route name: {$booking->chuyenXe->route_name}\n";
        echo "Departure: " . ($booking->chuyenXe->tramDi->ten_tram ?? 'N/A') . "\n";
        echo "Destination: " . ($booking->chuyenXe->tramDen->ten_tram ?? 'N/A') . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
