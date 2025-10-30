<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TESTING WITHOUT DATE CUTOFF ===\n\n";

// Test without any date restriction first
echo "Testing ALL trips without date cutoff:\n";
$query = DB::table('chuyen_xe as cx')
    ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
    ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
    ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
    ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe');

$results = $query->get();
echo "   Found " . count($results) . " total trips\n";

echo "\nTesting with bus_type='Giường nằm' filter (NO date cutoff):\n";
$query2 = DB::table('chuyen_xe as cx')
    ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
    ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
    ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
    ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe');

// Apply bus type filter
$busTypeLower = mb_strtolower('Giường nằm', 'UTF-8');
$query2->where(function($q) use ($busTypeLower) {
    $q->whereRaw('LOWER(cx.loai_xe) = ?', [$busTypeLower])
      ->orWhereRaw('cx.loai_xe = ?', ['Giường nằm']);
});

$results2 = $query2->get();
echo "   Found " . count($results2) . " results\n";

foreach ($results2 as $trip) {
    echo "   - {$trip->diem_di} → {$trip->diem_den} | {$trip->loai_xe} | {$trip->ngay_di} | {$trip->gia_ve}VND\n";
}

echo "\nTesting with date cutoff from 2025-10-27:\n";
$query3 = DB::table('chuyen_xe as cx')
    ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
    ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
    ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
    ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe');

// Apply bus type filter
$busTypeLower = mb_strtolower('Giường nằm', 'UTF-8');
$query3->where(function($q) use ($busTypeLower) {
    $q->whereRaw('LOWER(cx.loai_xe) = ?', [$busTypeLower])
      ->orWhereRaw('cx.loai_xe = ?', ['Giường nằm']);
});

// Apply cutoff from 2025-10-27 (the oldest date in DB)
$query3->whereDate('cx.ngay_di', '>=', '2025-10-27');

$results3 = $query3->get();
echo "   Found " . count($results3) . " results\n";

foreach ($results3 as $trip) {
    echo "   - {$trip->diem_di} → {$trip->diem_den} | {$trip->loai_xe} | {$trip->ngay_di} | {$trip->gia_ve}VND\n";
}

echo "\n=== END TEST ===\n";
