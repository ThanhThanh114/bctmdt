<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Testing Tram Search ===\n\n";

// Test 1: Tìm trạm theo tên
echo "1. Tìm trạm có tên chứa 'An Giang':\n";
$tramDi = DB::table('tram_xe')->where('ten_tram', 'like', '%An Giang%')->first();
if ($tramDi) {
    echo "   ✅ Tìm thấy: {$tramDi->ten_tram}\n";
} else {
    echo "   ❌ KHÔNG tìm thấy!\n";
}

// Test 2: Tìm trạm theo tỉnh
echo "\n2. Tìm trạm ở tỉnh An Giang:\n";
$trams = DB::table('tram_xe')->where('tinh_thanh', 'like', '%An Giang%')->get();
echo "   Tìm thấy " . count($trams) . " trạm:\n";
foreach ($trams as $t) {
    echo "   - {$t->ten_tram} (tỉnh: {$t->tinh_thanh})\n";
}

// Test 3: Tìm chuyến xe
echo "\n3. Tìm chuyến xe từ An Giang đến Bắc Ninh:\n";
echo "   Cách 1: Tìm theo TÊN TRẠM 'An Giang':\n";
$trips1 = DB::table('chuyen_xe as cx')
    ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
    ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
    ->where('di.ten_tram', 'like', '%An Giang%')
    ->where('den.ten_tram', 'like', '%Bắc Ninh%')
    ->select('di.ten_tram as di', 'den.ten_tram as den', 'cx.gio_di')
    ->get();
echo "   Kết quả: " . count($trips1) . " chuyến\n";

echo "\n   Cách 2: Tìm theo TỈNH 'An Giang':\n";
$trips2 = DB::table('chuyen_xe as cx')
    ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
    ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
    ->where('di.tinh_thanh', 'like', '%An Giang%')
    ->where('den.tinh_thanh', 'like', '%Bắc Ninh%')
    ->select('di.ten_tram as di', 'di.tinh_thanh as tinh_di', 'den.ten_tram as den', 'den.tinh_thanh as tinh_den', 'cx.gio_di')
    ->get();
echo "   Kết quả: " . count($trips2) . " chuyến\n";
if (count($trips2) > 0) {
    foreach ($trips2 as $t) {
        echo "     - {$t->di} ({$t->tinh_di}) → {$t->den} ({$t->tinh_den}) | {$t->gio_di}\n";
    }
}

echo "\n=== Done ===\n";
