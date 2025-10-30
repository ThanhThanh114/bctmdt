<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TESTING AVAILABLE SEATS FILTER ===\n\n";

// Test available seats condition
echo "Testing available seats query:\n";
$query = DB::table('chuyen_xe as cx')
    ->leftJoin(
        DB::raw("(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai = 'Đã thanh toán' GROUP BY chuyen_xe_id) dv"),
        'cx.id',
        '=',
        'dv.chuyen_xe_id'
    )
    ->select('cx.id', 'cx.ma_xe', 'cx.so_cho', 'cx.gia_ve', DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats'));

$results = $query->get();
echo "   Total trips with seats info: " . count($results) . "\n";

$tripsWithAvailableSeats = 0;
$tripsWithoutAvailableSeats = 0;

foreach ($results as $trip) {
    if (($trip->available_seats ?? 0) > 0) {
        $tripsWithAvailableSeats++;
        echo "   - ID: {$trip->id}, Seats: {$trip->so_cho}, Available: {$trip->available_seats}, Price: {$trip->gia_ve}\n";
    } else {
        $tripsWithoutAvailableSeats++;
    }
}

echo "\n   Trips with available seats: {$tripsWithAvailableSeats}\n";
echo "   Trips without available seats: {$tripsWithoutAvailableSeats}\n";

echo "\nTesting full query with bus_type AND available_seats:\n";
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
    ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe', DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats'));

// Apply bus type filter
$busTypeLower = mb_strtolower('Giường nằm', 'UTF-8');
$query2->where(function($q) use ($busTypeLower) {
    $q->whereRaw('LOWER(cx.loai_xe) = ?', [$busTypeLower])
      ->orWhereRaw('cx.loai_xe = ?', ['Giường nằm']);
});

// Apply the date cutoff
$yesterday = date('Y-m-d', strtotime('-1 day'));
$query2->whereDate('cx.ngay_di', '>=', $yesterday);

// Apply available seats filter
$query2->whereRaw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) > 0');

$results2 = $query2->get();
echo "   Found " . count($results2) . " results\n";

echo "\n=== END TEST ===\n";
