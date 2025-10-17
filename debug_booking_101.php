<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DatVe;

echo "=== DEBUGGING MULTIPLE BOOKINGS ===\n\n";

try {
    // Check recent bookings
    $recentBookings = DatVe::latest('id')->limit(5)->get();

    foreach ($recentBookings as $booking) {
        echo "=== BOOKING #{$booking->id} ===\n";
        echo "ID: {$booking->id}\n";
        echo "Ma ve: {$booking->ma_ve}\n";
        echo "User ID: {$booking->user_id}\n";
        echo "Chuyen xe ID: {$booking->chuyen_xe_id}\n";
        echo "Status: {$booking->status}\n";
        echo "Trang thai: {$booking->trang_thai}\n";
        echo "Ngay dat: {$booking->ngay_dat}\n";
        echo "So ghe: '{$booking->so_ghe}'\n";
        echo "So ghe length: " . strlen($booking->so_ghe ?? '') . "\n";
        echo "So ghe is empty: " . (empty($booking->so_ghe) ? 'YES' : 'NO') . "\n";
        echo "So ghe is null: " . (is_null($booking->so_ghe) ? 'YES' : 'NO') . "\n";
        echo "\n";

        // Load relationships
        $booking->load(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'chuyenXe.nhaXe']);

        echo "USER INFO:\n";
        if ($booking->user) {
            echo "✅ User: {$booking->user->username} ({$booking->user->fullname})\n";
        } else {
            echo "❌ User not found\n";
        }

        echo "\nTRIP INFO:\n";
        if ($booking->chuyenXe) {
            echo "✅ Trip: {$booking->chuyenXe->route_name}\n";
            echo "Departure: " . ($booking->chuyenXe->tramDi->ten_tram ?? 'N/A') . "\n";
            echo "Destination: " . ($booking->chuyenXe->tramDen->ten_tram ?? 'N/A') . "\n";
            echo "Bus company: " . ($booking->chuyenXe->nhaXe->ten_nha_xe ?? 'N/A') . "\n";
        } else {
            echo "❌ Trip not found\n";
        }

        echo "\n" . str_repeat("=", 50) . "\n\n";
    }

    // Check if so_ghe column exists and has data
    echo "=== CHECKING SO_GHE COLUMN ===\n";
    $bookingsWithSeat = DatVe::whereNotNull('so_ghe')->where('so_ghe', '!=', '')->count();
    $bookingsWithoutSeat = DatVe::whereNull('so_ghe')->orWhere('so_ghe', '')->count();
    $totalBookings = DatVe::count();

    echo "Total bookings: {$totalBookings}\n";
    echo "Bookings with seat numbers: {$bookingsWithSeat}\n";
    echo "Bookings without seat numbers: {$bookingsWithoutSeat}\n";

    // Show some examples of bookings with seat numbers
    if ($bookingsWithSeat > 0) {
        echo "\n=== SAMPLE BOOKINGS WITH SEAT NUMBERS ===\n";
        $sampleBookings = DatVe::whereNotNull('so_ghe')->where('so_ghe', '!=', '')->limit(3)->get();
        foreach ($sampleBookings as $booking) {
            echo "Booking #{$booking->id}: '{$booking->so_ghe}'\n";
        }
    }

    // Check for bookings without seat numbers
    $problemBookings = DatVe::whereNull('so_ghe')->orWhere('so_ghe', '')->get();
    if ($problemBookings->count() > 0) {
        echo "\n=== BOOKINGS WITHOUT SEAT NUMBERS ===\n";
        foreach ($problemBookings as $booking) {
            echo "Booking #{$booking->id}: so_ghe = '{$booking->so_ghe}'\n";
        }
    } else {
        echo "\n✅ All bookings have seat numbers assigned!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
