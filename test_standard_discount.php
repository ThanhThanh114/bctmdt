<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST LOGIC CHUẨN (ĐÃ REVERT) ===\n\n";

$km = App\Models\KhuyenMai::find(13);

echo "Khuyến mãi: {$km->ten_km}\n";
echo "Giảm giá trong DB: {$km->giam_gia}%\n\n";

echo "--- LOGIC CHUẨN THÔNG THƯỜNG ---\n";
$tongTien = 200000;
$giamGia = $tongTien * ($km->giam_gia / 100);
$phaiTra = $tongTien - $giamGia;

echo "Tổng tiền: " . number_format($tongTien, 0, ',', '.') . "đ\n";
echo "Giảm giá: {$km->giam_gia}%\n";
echo "Được giảm: " . number_format($giamGia, 0, ',', '.') . "đ\n";
echo "Phải trả: " . number_format($phaiTra, 0, ',', '.') . "đ\n\n";

echo "=== SO SÁNH CÁC TRƯỜNG HỢP ===\n\n";

$testCases = [
    ['giam_gia' => 10, 'mota' => 'Giảm 10%'],
    ['giam_gia' => 50, 'mota' => 'Giảm 50%'],
    ['giam_gia' => 90, 'mota' => 'Giảm 90%'],
    ['giam_gia' => 99, 'mota' => 'Giảm 99%'],
];

foreach ($testCases as $case) {
    $giamGia = $tongTien * ($case['giam_gia'] / 100);
    $phaiTra = $tongTien - $giamGia;

    echo "{$case['mota']}:\n";
    echo "  Được giảm: " . number_format($giamGia, 0, ',', '.') . "đ\n";
    echo "  Phải trả: " . number_format($phaiTra, 0, ',', '.') . "đ\n\n";
}

echo "✅ Đây là logic CHUẨN mà mọi người thường hiểu!\n";
