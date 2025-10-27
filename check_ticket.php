<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Tìm vé: BK20251027014527583\n";
echo "=====================================\n";

$ve = \App\Models\DatVe::where('ma_ve', 'BK20251027014527583')->first();

if ($ve) {
    echo "✅ Tìm thấy vé!\n";
    echo "ID: " . $ve->id . "\n";
    echo "Mã vé: " . $ve->ma_ve . "\n";
    echo "Trạng thái: " . $ve->trang_thai . "\n";
    echo "Ngày đặt: " . $ve->ngay_dat . "\n";
    echo "Chuyến xe ID: " . $ve->chuyen_xe_id . "\n";
    
    if ($ve->chuyenXe) {
        echo "Tên chuyến: " . ($ve->chuyenXe->ten_xe ?? $ve->chuyenXe->ma_xe) . "\n";
    }
    
    if ($ve->user) {
        echo "Khách hàng: " . ($ve->user->fullname ?? $ve->user->username) . "\n";
    }
} else {
    echo "❌ KHÔNG tìm thấy vé với mã này!\n";
    echo "\nKiểm tra các vé gần đây:\n";
    
    $recentVe = \App\Models\DatVe::orderBy('id', 'desc')->take(5)->get();
    foreach ($recentVe as $v) {
        echo "  - " . $v->ma_ve . " (" . $v->trang_thai . ")\n";
    }
}

echo "\n";
