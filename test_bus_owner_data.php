<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\NhaXe;
use App\Models\ChuyenXe;
use App\Models\DatVe;
use Illuminate\Support\Facades\DB;

echo "=== TEST BUS OWNER DATA ===\n\n";

// Test 1: Check bus_owner users
echo "1. Checking bus_owner users:\n";
$busOwners = User::where('role', 'bus_owner')->get();
echo "   Found " . $busOwners->count() . " bus owner(s)\n\n";

foreach ($busOwners as $owner) {
    echo "   - Username: {$owner->username}\n";
    echo "     Email: {$owner->email}\n";
    echo "     ma_nha_xe: {$owner->ma_nha_xe}\n";
    
    if ($owner->ma_nha_xe) {
        $nhaXe = NhaXe::where('ma_nha_xe', $owner->ma_nha_xe)->first();
        if ($nhaXe) {
            echo "     Nhà xe: {$nhaXe->ten_nha_xe}\n";
            
            // Count trips
            $tripCount = ChuyenXe::where('ma_nha_xe', $owner->ma_nha_xe)->count();
            echo "     Tổng chuyến xe: {$tripCount}\n";
            
            // Count bookings
            $bookingCount = DatVe::whereHas('chuyenXe', function($q) use ($owner) {
                $q->where('ma_nha_xe', $owner->ma_nha_xe);
            })->count();
            echo "     Tổng đặt vé: {$bookingCount}\n";
            
            // Revenue
            $revenue = DatVe::join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
                ->where('chuyen_xe.ma_nha_xe', $owner->ma_nha_xe)
                ->sum('chuyen_xe.gia_ve');
            echo "     Tổng doanh thu: " . number_format($revenue) . " VNĐ\n";
        } else {
            echo "     ⚠️ Nhà xe không tồn tại!\n";
        }
    } else {
        echo "     ⚠️ Chưa có ma_nha_xe!\n";
    }
    echo "\n";
}

// Test 2: Check NhaXe table
echo "\n2. Checking NhaXe table:\n";
$nhaXes = NhaXe::all();
echo "   Found " . $nhaXes->count() . " nhà xe\n\n";

foreach ($nhaXes as $nhaXe) {
    echo "   - ma_nha_xe: {$nhaXe->ma_nha_xe}\n";
    echo "     ten_nha_xe: {$nhaXe->ten_nha_xe}\n";
    
    $tripCount = ChuyenXe::where('ma_nha_xe', $nhaXe->ma_nha_xe)->count();
    echo "     Số chuyến xe: {$tripCount}\n\n";
}

// Test 3: Sample trips
echo "\n3. Sample trips (first 5):\n";
$trips = ChuyenXe::with(['tramDi', 'tramDen'])->limit(5)->get();
foreach ($trips as $trip) {
    echo "   - ID: {$trip->id}, ma_nha_xe: {$trip->ma_nha_xe}\n";
    echo "     Tên xe: {$trip->ten_xe}\n";
    echo "     Ngày đi: {$trip->ngay_di}, Giờ: {$trip->gio_di}\n";
    echo "     Điểm đi: {$trip->diem_di}, Điểm đến: {$trip->diem_den}\n";
    echo "     Giá vé: " . number_format($trip->gia_ve ?? 0) . " VNĐ\n\n";
}

// Test 4: Sample bookings
echo "\n4. Sample bookings (first 5):\n";
$bookings = DatVe::with(['chuyenXe', 'user'])->limit(5)->get();
foreach ($bookings as $booking) {
    echo "   - ID: {$booking->id}\n";
    echo "     User: " . ($booking->user ? $booking->user->fullname : 'N/A') . "\n";
    echo "     Chuyến xe ID: {$booking->chuyen_xe_id}\n";
    if ($booking->chuyenXe) {
        echo "     ma_nha_xe: {$booking->chuyenXe->ma_nha_xe}\n";
        echo "     Giá vé: " . number_format($booking->chuyenXe->gia_ve ?? 0) . " VNĐ\n";
    }
    echo "     Trạng thái: {$booking->trang_thai}\n";
    echo "     Ngày đặt: {$booking->ngay_dat}\n\n";
}

echo "\n=== TEST COMPLETED ===\n";
