<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Kiểm tra ngày của chuyến xe ===\n\n";

echo "Các chuyến An Giang → Bắc Ninh:\n";
$trips = DB::table('chuyen_xe as cx')
    ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
    ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
    ->where('di.tinh_thanh', 'like', '%An Giang%')
    ->where('den.tinh_thanh', 'like', '%Bắc Ninh%')
    ->select('cx.id', 'cx.ngay_di', 'cx.gio_di', 'cx.gia_ve')
    ->orderBy('ngay_di')
    ->get();

foreach ($trips as $t) {
    echo "  ID: {$t->id} | Ngày: {$t->ngay_di} | Giờ: {$t->gio_di} | Giá: " . number_format($t->gia_ve, 0) . "đ\n";
}

echo "\nNgày hôm nay: " . date('Y-m-d') . "\n";
echo "So sánh với database:\n";

$today = date('Y-m-d');
$todayTrips = DB::table('chuyen_xe as cx')
    ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
    ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
    ->where('di.tinh_thanh', 'like', '%An Giang%')
    ->where('den.tinh_thanh', 'like', '%Bắc Ninh%')
    ->whereDate('ngay_di', $today)
    ->count();

echo "  - Chuyến ngày hôm nay ($today): $todayTrips chuyến\n";

// Lấy ngày gần nhất
$nextTrip = DB::table('chuyen_xe as cx')
    ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
    ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
    ->where('di.tinh_thanh', 'like', '%An Giang%')
    ->where('den.tinh_thanh', 'like', '%Bắc Ninh%')
    ->where('ngay_di', '>=', now())
    ->orderBy('ngay_di')
    ->select('cx.ngay_di')
    ->first();

if ($nextTrip) {
    echo "  - Ngày gần nhất có chuyến: {$nextTrip->ngay_di}\n";
}

echo "\n=== Done ===\n";
