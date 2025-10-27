<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ChuyenXe;
use App\Models\DatVe;
use Illuminate\Support\Facades\Auth;

echo "=== TEST DASHBOARD CONTROLLER DATA ===\n\n";

// Test với bus owner có dữ liệu
$busOwner = User::where('username', 'busowner')->first();

if (!$busOwner) {
    echo "❌ Bus owner 'busowner' not found!\n";
    exit;
}

echo "✅ Found bus owner: {$busOwner->username}\n";
echo "   Email: {$busOwner->email}\n";
echo "   ma_nha_xe: {$busOwner->ma_nha_xe}\n\n";

// Simulate authentication
Auth::login($busOwner);

echo "🔐 Authenticated as: " . Auth::user()->username . "\n\n";

// Test relationship
$bus_company = $busOwner->nhaXe;

if ($bus_company) {
    echo "✅ Bus company found: {$bus_company->ten_nha_xe}\n";
    echo "   ma_nha_xe: {$bus_company->ma_nha_xe}\n\n";
} else {
    echo "❌ No bus company relationship!\n\n";
    exit;
}

// Test statistics
$today = date('Y-m-d');
$currentMonth = date('m');
$currentYear = date('Y');

echo "📊 STATISTICS:\n\n";

// Total trips
$totalTrips = ChuyenXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->count();
echo "   Tổng chuyến xe: {$totalTrips}\n";

// Today trips
$todayTrips = ChuyenXe::where('ma_nha_xe', $bus_company->ma_nha_xe)
    ->where('ngay_di', $today)->count();
echo "   Chuyến xe hôm nay: {$todayTrips}\n";

// Total bookings
$totalBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
    $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
})->count();
echo "   Tổng đặt vé: {$totalBookings}\n";

// Today bookings
$todayBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
    $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
})->whereDate('ngay_dat', $today)->count();
echo "   Đặt vé hôm nay: {$todayBookings}\n";

// Monthly revenue
$monthlyRevenue = DatVe::with('chuyenXe')
    ->whereHas('chuyenXe', function ($q) use ($bus_company) {
        $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
    })
    ->whereMonth('ngay_dat', $currentMonth)
    ->whereYear('ngay_dat', $currentYear)
    ->get()
    ->sum(function ($booking) {
        return ($booking->chuyenXe && $booking->chuyenXe->gia_ve) ? $booking->chuyenXe->gia_ve : 0;
    });
echo "   Doanh thu tháng này: " . number_format($monthlyRevenue) . " VNĐ\n";

// Pending bookings
$pendingBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
    $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
})->where('trang_thai', 'Đã đặt')->count();
echo "   Vé đang chờ: {$pendingBookings}\n";

// Confirmed bookings
$confirmedBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
    $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
})->where('trang_thai', 'Đã xác nhận')->count();
echo "   Vé đã xác nhận: {$confirmedBookings}\n";

// Test trip performance query
echo "\n📈 TRIP PERFORMANCE:\n\n";
$trip_performance = ChuyenXe::select('chuyen_xe.id', 'chuyen_xe.ten_xe', 'chuyen_xe.so_cho')
    ->withCount(['datVe as bookings_count' => function ($q) {
        $q->where('trang_thai', '!=', 'Đã hủy');
    }])
    ->where('ma_nha_xe', $bus_company->ma_nha_xe)
    ->orderBy('bookings_count', 'desc')
    ->limit(5)
    ->get()
    ->map(function ($trip) {
        // Count booked seats from so_ghe (comma-separated seat numbers)
        $bookedSeatsData = DatVe::where('chuyen_xe_id', $trip->id)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->pluck('so_ghe');
        
        $totalSeatsBooked = $bookedSeatsData->reduce(function ($carry, $seats) {
            return $carry + (empty($seats) ? 0 : substr_count($seats, ',') + 1);
        }, 0);
        
        $trip->total_seats_booked = $totalSeatsBooked;
        $trip->occupancy_rate = $trip->so_cho > 0 ?
            round(($totalSeatsBooked / $trip->so_cho) * 100, 1) : 0;
        return $trip;
    });

foreach ($trip_performance as $trip) {
    echo "   - {$trip->ten_xe}\n";
    echo "     Số đặt: {$trip->bookings_count}\n";
    echo "     Ghế đã đặt: {$trip->total_seats_booked}/{$trip->so_cho}\n";
    echo "     Tỷ lệ lấp đầy: {$trip->occupancy_rate}%\n\n";
}

echo "\n✅ TEST COMPLETED SUCCESSFULLY!\n";
echo "\nℹ️  Bây giờ hãy refresh trang dashboard: http://127.0.0.1:8000/bus-owner/dashboard\n";
