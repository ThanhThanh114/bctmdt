<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING TRIP DATES ===\n\n";

echo "Current date: " . date('Y-m-d') . "\n\n";

echo "Trip dates in database:\n";
$dates = DB::table('chuyen_xe')
    ->select('ngay_di', DB::raw('COUNT(*) as count'))
    ->groupBy('ngay_di')
    ->orderBy('ngay_di')
    ->get();

foreach ($dates as $date) {
    $isPast = $date->ngay_di < date('Y-m-d') ? ' (PAST)' : '';
    echo "   - {$date->ngay_di} ({$date->count} trips){$isPast}\n";
}

echo "\n=== END DATE CHECK ===\n";
