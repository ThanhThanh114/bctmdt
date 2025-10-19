<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Testing Nearby Routes Search ===\n\n";

// Test 1: Tìm chuyến từ An Giang đến Hà Nội (không có trực tiếp)
echo "1. Search: An Giang -> Hà Nội (không có trực tiếp)\n";
echo "   Tìm chuyến đến các tỉnh gần Hà Nội...\n\n";

$from = 'An Giang';
$to = 'Hà Nội';

// Các tỉnh lân cận Hà Nội
$nearbyProvinces = ['Hà Nội', 'Hưng Yên', 'Bắc Ninh', 'Hải Phòng', 'Hải Dương', 'Vĩnh Phúc', 'Hà Nam', 'Nam Định', 'Thái Bình'];

$routes = DB::table('chuyen_xe as cx')
    ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
    ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
    ->where('di.tinh_thanh', 'LIKE', "%$from%")
    ->where(function ($q) use ($nearbyProvinces) {
        foreach ($nearbyProvinces as $province) {
            $q->orWhere('den.tinh_thanh', 'LIKE', "%$province%");
        }
    })
    ->select(
        'di.tinh_thanh as diem_di',
        'den.tinh_thanh as diem_den',
        'cx.gio_di',
        'cx.gia_ve',
        'cx.loai_xe',
        'cx.so_ve as con_trong'
    )
    ->limit(5)
    ->get();

if (count($routes) > 0) {
    echo "   Tìm thấy " . count($routes) . " chuyến GẦN Hà Nội:\n\n";
    foreach ($routes as $index => $route) {
        $num = $index + 1;
        echo "   CHUYẾN {$num}:\n";
        echo "   - Tuyến: {$route->diem_di} → {$route->diem_den}\n";
        echo "   - Giờ đi: {$route->gio_di}\n";
        echo "   - Giá vé: " . number_format($route->gia_ve, 0, ',', '.') . "đ\n";
        echo "   - Loại xe: {$route->loai_xe}\n";
        echo "   - Còn trống: {$route->con_trong} vé\n\n";
    }
} else {
    echo "   Không tìm thấy chuyến nào!\n\n";
}

// Test 2: Tìm chuyến từ An Giang đến Bắc Ninh (có trực tiếp)
echo "2. Search: An Giang -> Bắc Ninh (có trực tiếp)\n";
$directRoutes = DB::table('chuyen_xe as cx')
    ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
    ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
    ->where('di.tinh_thanh', 'LIKE', "%An Giang%")
    ->where('den.tinh_thanh', 'LIKE', "%Bắc Ninh%")
    ->select(
        'di.tinh_thanh as diem_di',
        'den.tinh_thanh as diem_den',
        'cx.gio_di',
        'cx.gia_ve'
    )
    ->get();

echo "   Tìm thấy " . count($directRoutes) . " chuyến TRỰC TIẾP\n\n";

echo "=== Done ===\n";
