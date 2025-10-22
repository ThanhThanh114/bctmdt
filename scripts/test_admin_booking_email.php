<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DatVe;
use App\Models\ChuyenXe;
use App\Models\User;
use App\Models\NhaXe;
use App\Models\TramXe;

echo "🧪 Testing Admin Booking Notification Email\n";
echo "==========================================\n\n";

// Create test data
try {
    // Get a sample booking
    $booking = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'user'])
        ->where('trang_thai', 'Đã thanh toán')
        ->first();

    if (!$booking) {
        echo "❌ No paid bookings found in database. Creating test booking...\n";

        // Create test user
        $user = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'fullname' => 'Nguyễn Văn Test',
            'password' => bcrypt('password'),
            'phone' => '0123456789'
        ]);

        // Get first trip
        $trip = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])->first();

        if (!$trip) {
            echo "❌ No trips found in database. Please seed the database first.\n";
            exit(1);
        }

        // Create test booking
        $booking = DatVe::create([
            'user_id' => $user->id,
            'chuyen_xe_id' => $trip->id,
            'ma_ve' => 'TEST' . date('YmdHis'),
            'so_ghe' => 'A01',
            'trang_thai' => 'Đã thanh toán',
            'ngay_dat' => now()
        ]);

        $booking->load(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'user']);
    }

    echo "✅ Test booking found/created:\n";
    echo "   - Booking Code: {$booking->ma_ve}\n";
    echo "   - Customer: {$booking->user->fullname} ({$booking->user->email})\n";
    echo "   - Trip: {$booking->chuyenXe->tramDi->ten_tram} → {$booking->chuyenXe->tramDen->ten_tram}\n";
    echo "   - Seat: {$booking->so_ghe}\n";
    echo "   - Status: {$booking->trang_thai}\n\n";

    // Prepare email data
    $bookings = collect([$booking]);
    $bookingCode = $booking->ma_ve;
    $totalAmount = $booking->chuyenXe->gia_ve;
    $discountAmount = 0;

    echo "📧 Sending admin notification email...\n";

    // Send email
    \Mail::to('lethanhem01011975@gmail.com')
        ->send(new \App\Mail\AdminBookingNotification(
            $bookings,
            $bookingCode,
            $totalAmount,
            $discountAmount
        ));

    echo "✅ Admin notification email sent successfully!\n";
    echo "📬 Check lethanhem01011975@gmail.com for the notification email.\n\n";

    echo "🎯 Test completed successfully!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "🔍 Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}