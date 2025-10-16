<?php

// Test script to check DatVe data
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DatVe;

echo "=== Kiểm tra dữ liệu Đặt vé ===\n\n";

// Lấy 5 booking đầu tiên
$bookings = DatVe::with(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'chuyenXe.nhaXe'])
    ->orderBy('ngay_dat', 'desc')
    ->take(5)
    ->get();

echo "Tổng số booking: " . DatVe::count() . "\n\n";

foreach ($bookings as $booking) {
    echo "Mã vé: {$booking->ma_ve}\n";
    echo "Khách hàng: " . ($booking->user ? $booking->user->fullname : 'N/A') . "\n";
    echo "Email: " . ($booking->user ? $booking->user->email : 'N/A') . "\n";
    echo "Số ghế: {$booking->so_ghe}\n";
    echo "Trạng thái: {$booking->trang_thai}\n";

    if ($booking->chuyenXe) {
        echo "Chuyến xe:\n";
        echo "  - Từ: " . ($booking->chuyenXe->tramDi->ten_tram ?? 'N/A') . "\n";
        echo "  - Đến: " . ($booking->chuyenXe->tramDen->ten_tram ?? 'N/A') . "\n";
        echo "  - Ngày: {$booking->chuyenXe->ngay_di} - {$booking->chuyenXe->gio_di}\n";
        echo "  - Giá vé: " . number_format($booking->chuyenXe->gia_ve, 0, ',', '.') . " VNĐ\n";
    }

    echo "\n" . str_repeat('-', 50) . "\n\n";
}

echo "✅ Test hoàn tất!\n";
