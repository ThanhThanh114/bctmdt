<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TESTING 7-DAY CUTOFF FIX ===\n\n";

// Simulate the 7-day cutoff
$sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
echo "7 days ago cutoff: {$sevenDaysAgo}\n\n";

// Test the full query with all filters applied using 7-day cutoff
echo "Testing with bus_type='Giường nằm' filter and 7-day cutoff:\n";
$query = DB::table('chuyen_xe as cx')
    ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
    ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
    ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
    ->leftJoin(
        DB::raw("(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai = 'Đã thanh toán' GROUP BY chuyen_xe_id) dv"),
        'cx.id',
        '=',
        'dv.chuyen_xe_id'
    )
    ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe', DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats'));

// Apply bus type filter
$busTypeLower = mb_strtolower('Giường nằm', 'UTF-8');
$query->where(function($q) use ($busTypeLower) {
    $q->whereRaw('LOWER(cx.loai_xe) = ?', [$busTypeLower])
      ->orWhereRaw('cx.loai_xe = ?', ['Giường nằm']);
});

// Apply the 7-day cutoff
$query->whereDate('cx.ngay_di', '>=', $sevenDaysAgo);

// Apply available seats filter
$query->whereRaw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) > 0');

$results = $query->get();
echo "   Found " . count($results) . " results\n";

foreach ($results as $trip) {
    echo "   - {$trip->diem_di} → {$trip->diem_den} | {$trip->loai_xe} | {$trip->ngay_di} | {$trip->gia_ve}VND | {$trip->available_seats} seats\n";
}

echo "\nTesting with bus_company filter (ID: 1) and 7-day cutoff:\n";
$query2 = DB::table('chuyen_xe as cx')
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
    ->where('nx.ma_nha_xe', '=', 1)
    ->whereDate('cx.ngay_di', '>=', $sevenDaysAgo)
    ->whereRaw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) > 0');

$results2 = $query2->get();
echo "   Found " . count($results2) . " results\n";

foreach ($results2 as $trip) {
    echo "   - {$trip->diem_di} → {$trip->diem_den} | {$trip->ten_nha_xe} | {$trip->ngay_di} | {$trip->gia_ve}VND\n";
}

echo "\nTesting with price range filter (200000-400000) and 7-day cutoff:\n";
$query3 = DB::table('chuyen_xe as cx')
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
    ->whereBetween('cx.gia_ve', [200000, 400000])
    ->whereDate('cx.ngay_di', '>=', $sevenDaysAgo)
    ->whereRaw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) > 0');

$results3 = $query3->get();
echo "   Found " . count($results3) . " results\n";

foreach ($results3 as $trip) {
    echo "   - {$trip->diem_di} → {$trip->diem_den} | {$trip->gia_ve}VND | {$trip->ngay_di}\n";
}

echo "\n=== END TEST ===\n";
