<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== KIỂM TRA KHUYẾN MÃI ===\n\n";

$km = App\Models\KhuyenMai::find(13);

if ($km) {
    echo "Mã KM: {$km->ma_km}\n";
    echo "Tên KM: {$km->ten_km}\n";
    echo "Mã Code: {$km->ma_code}\n";
    echo "Giảm giá: {$km->giam_gia}\n";
    echo "Kiểu: " . gettype($km->giam_gia) . "\n";
    echo "Ngày bắt đầu: {$km->ngay_bat_dau}\n";
    echo "Ngày kết thúc: {$km->ngay_ket_thuc}\n\n";

    echo "=== TEST TÍNH TOÁN ===\n";
    $tongTien = 200000;
    echo "Tổng tiền: " . number_format($tongTien, 0, ',', '.') . "đ\n";
    echo "% giảm: {$km->giam_gia}%\n";

    $giamGia = $tongTien * ($km->giam_gia / 100);
    echo "Số tiền giảm: " . number_format($giamGia, 0, ',', '.') . "đ\n";
    echo "Còn lại: " . number_format($tongTien - $giamGia, 0, ',', '.') . "đ\n";
}
