<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

echo "=== DEBUGGING FILTER ISSUE ===\n\n";

// Check bus types in database
echo "1. Checking bus types in chuyen_xe table:\n";
$busTypes = DB::table('chuyen_xe')
    ->select('loai_xe', DB::raw('COUNT(*) as count'))
    ->groupBy('loai_xe')
    ->get();

foreach ($busTypes as $type) {
    echo "   - '{$type->loai_xe}' ({$type->count} trips)\n";
}

echo "\n2. Checking bus companies:\n";
$companies = DB::table('nha_xe')
    ->select('ma_nha_xe', 'ten_nha_xe')
    ->get();

foreach ($companies as $company) {
    echo "   - ID: {$company->ma_nha_xe}, Name: {$company->ten_nha_xe}\n";
}

echo "\n3. Checking drivers:\n";
$drivers = DB::table('chuyen_xe')
    ->select('ten_tai_xe', DB::raw('COUNT(*) as count'))
    ->whereNotNull('ten_tai_xe')
    ->where('ten_tai_xe', '!=', '')
    ->groupBy('ten_tai_xe')
    ->limit(10)
    ->get();

foreach ($drivers as $driver) {
    echo "   - '{$driver->ten_tai_xe}' ({$driver->count} trips)\n";
}

echo "\n4. Checking price ranges:\n";
$prices = DB::table('chuyen_xe')
    ->select('gia_ve', DB::raw('COUNT(*) as count'))
    ->groupBy('gia_ve')
    ->orderBy('gia_ve')
    ->limit(10)
    ->get();

foreach ($prices as $price) {
    echo "   - {$price->gia_ve} VND ({$price->count} trips)\n";
}

// Test filtering logic
echo "\n5. Testing filter logic:\n";

// Test bus type filter
$query = DB::table('chuyen_xe as cx')
    ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
    ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
    ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
    ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe')
    ->limit(5);

echo "\n6. Testing 'Giường nằm' filter:\n";
$testQuery = clone $query;
$testQuery->whereRaw('LOWER(cx.loai_xe) = ?', [mb_strtolower('Giường nằm', 'UTF-8')]);
$results = $testQuery->get();
echo "   Found " . count($results) . " results\n";

echo "\n7. Testing 'Limousine' filter:\n";
$testQuery = clone $query;
$testQuery->whereRaw('LOWER(cx.loai_xe) = ?', [mb_strtolower('Limousine', 'UTF-8')]);
$results = $testQuery->get();
echo "   Found " . count($results) . " results\n";

echo "\n8. Testing price range filter (200000-400000):\n";
$testQuery = clone $query;
$testQuery->whereBetween('cx.gia_ve', [200000, 400000]);
$results = $testQuery->get();
echo "   Found " . count($results) . " results\n";

echo "\n9. Testing available seats filter:\n";
$testQuery = DB::table('chuyen_xe as cx')
    ->leftJoin(
        DB::raw("(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai = 'Đã thanh toán' GROUP BY chuyen_xe_id) dv"),
        'cx.id',
        '=',
        'dv.chuyen_xe_id'
    )
    ->select('cx.*', DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats'))
    ->whereRaw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) > 0')
    ->limit(10);

$results = $testQuery->get();
echo "   Found " . count($results) . " results with available seats\n";

echo "\n10. Full query test with date filter (today):\n";
$today = date('Y-m-d');
$testQuery = DB::table('chuyen_xe as cx')
    ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
    ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
    ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
    ->leftJoin(
        DB::raw("(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai = 'Đã thanh toán' GROUP BY chuyen_xe_id) dv"),
        'cx.id',
        '=',
        'dv.chuyen_xe_id'
    )
    ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe', DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats'))
    ->whereDate('cx.ngay_di', '>=', $today)
    ->whereRaw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) > 0');

$results = $testQuery->get();
echo "   Found " . count($results) . " results for today onwards\n";

echo "\n=== END DEBUG ===\n";
