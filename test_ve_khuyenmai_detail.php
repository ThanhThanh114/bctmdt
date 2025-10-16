<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DETAIL CHECK VE_KHUYENMAI ===\n\n";

$veKMs = App\Models\VeKhuyenMai::where('ma_km', 13)
    ->with('datVe')
    ->get();

echo "Tổng số records trong ve_khuyenmai cho KM_Tet12: {$veKMs->count()}\n\n";

foreach ($veKMs as $vkm) {
    echo "VeKhuyenMai ID: {$vkm->id}\n";
    echo "  - dat_ve_id: {$vkm->dat_ve_id}\n";
    echo "  - ma_km: {$vkm->ma_km}\n";

    if ($vkm->datVe) {
        $dv = $vkm->datVe;
        echo "  - DatVe Info:\n";
        echo "    * id: {$dv->id}\n";
        echo "    * ma_ve: {$dv->ma_ve}\n";
        echo "    * so_ghe: {$dv->so_ghe}\n";
        echo "    * user_id: {$dv->user_id}\n";
    }
    echo "\n";
}

echo "\n=== CHECK DAT_VE DETAILS ===\n\n";
$datVe47 = App\Models\DatVe::find(47);
if ($datVe47) {
    echo "DatVe #47:\n";
    echo "  - ma_ve: {$datVe47->ma_ve}\n";
    echo "  - so_ghe: {$datVe47->so_ghe}\n";

    echo "\nTất cả records với ma_ve = {$datVe47->ma_ve}:\n";
    $allSameBooking = App\Models\DatVe::where('ma_ve', $datVe47->ma_ve)->get();
    foreach ($allSameBooking as $dv) {
        echo "  - ID: {$dv->id}, Ghế: {$dv->so_ghe}\n";
    }
}
