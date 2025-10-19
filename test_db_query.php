<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Testing Database Queries ===\n\n";

// Test 1: Get sample routes
echo "1. Sample routes:\n";
$routes = DB::table('chuyen_xe as cx')
    ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
    ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
    ->select(
        'di.tinh_thanh as diem_di',
        'den.tinh_thanh as diem_den',
        'cx.gio_di',
        'cx.gia_ve',
        'cx.loai_xe',
        'cx.so_cho'
    )
    ->limit(5)
    ->get();

foreach ($routes as $route) {
    echo "  {$route->diem_di} -> {$route->diem_den} | {$route->gio_di} | " .
        number_format($route->gia_ve, 0) . "đ | {$route->loai_xe}\n";
}

// Test 2: Get all provinces
echo "\n2. All provinces:\n";
$provinces = DB::table('tram_xe')
    ->select('tinh_thanh')
    ->distinct()
    ->orderBy('tinh_thanh')
    ->get();

foreach ($provinces as $p) {
    echo "  - {$p->tinh_thanh}\n";
}

// Test 3: Search specific route
echo "\n3. Search route (example: First province to second):\n";
if (count($provinces) >= 2) {
    $from = $provinces[0]->tinh_thanh;
    $to = $provinces[1]->tinh_thanh;

    $results = DB::table('chuyen_xe as cx')
        ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
        ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
        ->where('di.tinh_thanh', 'LIKE', "%$from%")
        ->where('den.tinh_thanh', 'LIKE', "%$to%")
        ->select(
            'di.tinh_thanh as diem_di',
            'den.tinh_thanh as diem_den',
            'cx.gio_di',
            'cx.gia_ve',
            'cx.loai_xe'
        )
        ->limit(3)
        ->get();

    echo "  From: $from -> To: $to\n";
    echo "  Found: " . count($results) . " routes\n";
    foreach ($results as $r) {
        echo "    {$r->diem_di} -> {$r->diem_den} at {$r->gio_di}\n";
    }
}

echo "\n=== Done ===\n";
