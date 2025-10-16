<?php
// Script chạy định kỳ để hủy booking hết hạn (chạy mỗi phút)
// CHỈ hủy booking "Đã đặt" chưa thanh toán quá 15 phút

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DatVe;

echo "[" . date('Y-m-d H:i:s') . "] Kiểm tra booking hết hạn...\n";

// Lấy booking "Đã đặt" (chưa thanh toán) quá 15 phút
$expiredTime = now()->subMinutes(15);

$expiredBookings = DatVe::where('trang_thai', 'Đã đặt')
    ->where('ngay_dat', '<', $expiredTime)
    ->get();

$count = $expiredBookings->count();

if ($count > 0) {
    // Group theo ma_ve
    $groupedBookings = $expiredBookings->groupBy('ma_ve');

    echo "Tìm thấy " . $groupedBookings->count() . " booking hết hạn (chưa thanh toán):\n";

    foreach ($groupedBookings as $code => $bookings) {
        $seats = $bookings->pluck('so_ghe')->join(', ');
        $time = $bookings->first()->ngay_dat;
        echo "  • $code: Ghế $seats (đặt lúc $time)\n";
    }

    // Cập nhật trạng thái thành "Đã hủy"
    DatVe::where('trang_thai', 'Đã đặt')
        ->where('ngay_dat', '<', $expiredTime)
        ->update(['trang_thai' => 'Đã hủy']);

    echo "✓ Đã hủy $count ghế\n";
} else {
    echo "Không có booking nào cần hủy\n";
}

echo "\n📊 THỐNG KÊ:\n";
$stats = DatVe::selectRaw('trang_thai, COUNT(*) as count')
    ->groupBy('trang_thai')
    ->get();

foreach ($stats as $stat) {
    $icon = match ($stat->trang_thai) {
        'Đã đặt' => '⏳',
        'Đã thanh toán' => '✅',
        'Đã hủy' => '❌',
        default => '❓'
    };
    echo "$icon {$stat->trang_thai}: {$stat->count} ghế\n";
}
