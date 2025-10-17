<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ChuyenXe;

echo "Checking trip #1 details...\n";

try {
    $trip = ChuyenXe::with(['tramDi', 'tramDen', 'nhaXe'])->find(1);

    if (!$trip) {
        echo "❌ Trip #1 not found\n";
        return;
    }

    echo "=== TRIP #1 INFO ===\n";
    echo "ID: {$trip->id}\n";
    echo "Route name: '{$trip->route_name}'\n";
    echo "Departure date: {$trip->ngay_di}\n";
    echo "Departure time: {$trip->gio_di}\n";
    echo "Price: {$trip->gia_ve}\n";

    if ($trip->tramDi) {
        echo "Departure location: {$trip->tramDi->ten_tram} (ID: {$trip->tramDi->id})\n";
    } else {
        echo "❌ Departure location not found\n";
    }

    if ($trip->tramDen) {
        echo "Destination location: {$trip->tramDen->ten_tram} (ID: {$trip->tramDen->id})\n";
    } else {
        echo "❌ Destination location not found\n";
    }

    if ($trip->nhaXe) {
        echo "Bus company: {$trip->nhaXe->ten_nha_xe} (ID: {$trip->nhaXe->id})\n";
    } else {
        echo "❌ Bus company not found\n";
    }

    // Force update the route name if it's empty
    if (empty($trip->route_name) && $trip->tramDi && $trip->tramDen) {
        $routeName = $trip->tramDi->ten_tram . ' → ' . $trip->tramDen->ten_tram;
        $trip->update(['route_name' => $routeName]);
        echo "\n✅ Updated route name to: {$routeName}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
