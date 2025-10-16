<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\ChuyenXe;

echo "=== Test Create Form Data ===\n\n";

$users = User::orderBy('fullname')->get();
echo "Users available: " . $users->count() . "\n";
if ($users->count() > 0) {
    echo "Sample users:\n";
    foreach ($users->take(3) as $user) {
        echo "  - {$user->fullname} ({$user->email})\n";
    }
}

echo "\n";

$chuyenXes = ChuyenXe::with(['tramDi', 'tramDen'])
    ->where('ngay_di', '>=', now())
    ->orderBy('ngay_di')
    ->get();

echo "ChuyenXe available (upcoming): " . $chuyenXes->count() . "\n";
if ($chuyenXes->count() > 0) {
    echo "Sample trips:\n";
    foreach ($chuyenXes->take(3) as $cx) {
        $from = $cx->tramDi->ten_tram ?? 'N/A';
        $to = $cx->tramDen->ten_tram ?? 'N/A';
        $date = $cx->ngay_di ? \Carbon\Carbon::parse($cx->ngay_di)->format('d/m/Y') : 'N/A';
        echo "  - {$from} → {$to} ({$date})\n";
    }
} else {
    echo "⚠️ WARNING: No upcoming trips found!\n";
    echo "Showing all trips instead:\n";
    $allTrips = ChuyenXe::with(['tramDi', 'tramDen'])->orderBy('ngay_di', 'desc')->take(3)->get();
    foreach ($allTrips as $cx) {
        $from = $cx->tramDi->ten_tram ?? 'N/A';
        $to = $cx->tramDen->ten_tram ?? 'N/A';
        $date = $cx->ngay_di ? \Carbon\Carbon::parse($cx->ngay_di)->format('d/m/Y') : 'N/A';
        echo "  - {$from} → {$to} ({$date})\n";
    }
}

echo "\n✅ Test complete!\n";
