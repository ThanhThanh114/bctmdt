<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST LOGIC MỚI - ĐẢO NGƯỢC ===\n\n";

$km = App\Models\KhuyenMai::find(13);

echo "Khuyến mãi: {$km->ten_km}\n";
echo "Giá trị trong DB: {$km->giam_gia}%\n\n";

echo "--- LOGIC CŨ (SAI theo yêu cầu) ---\n";
$tongTien = 200000;
$giamGiaCu = $tongTien * ($km->giam_gia / 100);
echo "Tổng tiền: " . number_format($tongTien, 0, ',', '.') . "đ\n";
echo "Giảm: " . number_format($giamGiaCu, 0, ',', '.') . "đ ({$km->giam_gia}%)\n";
echo "Phải trả: " . number_format($tongTien - $giamGiaCu, 0, ',', '.') . "đ\n\n";

echo "--- LOGIC MỚI (ĐÚNG theo yêu cầu) ---\n";
$phanTramKhachTra = $km->giam_gia;
$phanTramGiam = 100 - $phanTramKhachTra;
$giamGiaMoi = $tongTien * ($phanTramGiam / 100);
$phaiTra = $tongTien - $giamGiaMoi;

echo "Tổng tiền: " . number_format($tongTien, 0, ',', '.') . "đ\n";
echo "Mã giảm: {$phanTramKhachTra}% (khách trả {$phanTramKhachTra}%)\n";
echo "Được giảm: " . number_format($giamGiaMoi, 0, ',', '.') . "đ ({$phanTramGiam}%)\n";
echo "Phải trả: " . number_format($phaiTra, 0, ',', '.') . "đ\n\n";

echo "=== SO SÁNH CÁC TRƯỜNG HỢP ===\n\n";

$testCases = [
    ['giam_gia' => 10, 'mota' => 'Khách trả 10%'],
    ['giam_gia' => 50, 'mota' => 'Khách trả 50%'],
    ['giam_gia' => 90, 'mota' => 'Khách trả 90%'],
    ['giam_gia' => 99, 'mota' => 'Khách trả 99%'],
];

foreach ($testCases as $case) {
    $phanTramKhachTra = $case['giam_gia'];
    $phanTramGiam = 100 - $phanTramKhachTra;
    $giamGia = $tongTien * ($phanTramGiam / 100);
    $phaiTra = $tongTien - $giamGia;

    echo "{$case['mota']}:\n";
    echo "  Giảm: {$phanTramGiam}% = " . number_format($giamGia, 0, ',', '.') . "đ\n";
    echo "  Trả: {$phanTramKhachTra}% = " . number_format($phaiTra, 0, ',', '.') . "đ\n\n";
}
