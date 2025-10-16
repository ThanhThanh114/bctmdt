<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST KHUYẾN MÃI DATA ===\n\n";

// Test booking #47
echo "1. Kiểm tra Booking #47:\n";
$booking = App\Models\DatVe::with('user', 'chuyenXe')->find(47);

if ($booking) {
    echo "   - ID: {$booking->id}\n";
    echo "   - Ma Ve: {$booking->ma_ve}\n";
    echo "   - User ID: {$booking->user_id}\n";
    echo "   - User Name: " . ($booking->user ? $booking->user->name : 'NULL') . "\n";
    echo "   - User Email: " . ($booking->user ? $booking->user->email : 'NULL') . "\n";
    echo "   - Chuyen Xe ID: {$booking->chuyen_xe_id}\n";
    echo "   - So Ghe: {$booking->so_ghe}\n";
} else {
    echo "   Booking #47 không tồn tại!\n";
}

echo "\n2. Kiểm tra các booking cùng ma_ve với #47:\n";
$maVe = $booking->ma_ve ?? 'VE1001';
$allBookings = App\Models\DatVe::where('ma_ve', $maVe)->get();
echo "   Tổng số ghế: {$allBookings->count()}\n";
foreach ($allBookings as $b) {
    echo "   - ID: {$b->id}, Ghế: {$b->so_ghe}\n";
}

echo "\n3. Kiểm tra VeKhuyenMai cho booking #47:\n";
$veKM = App\Models\VeKhuyenMai::where('dat_ve_id', 47)->with('khuyenMai')->get();
echo "   Số lượng: {$veKM->count()}\n";
foreach ($veKM as $vkm) {
    echo "   - VeKhuyenMai ID: {$vkm->id}, Ma KM: {$vkm->ma_km}\n";
    if ($vkm->khuyenMai) {
        echo "     Khuyến mãi: {$vkm->khuyenMai->ten_km} (Giảm {$vkm->khuyenMai->giam_gia}%)\n";
    }
}

echo "\n4. Kiểm tra User ID 15:\n";
$user = App\Models\User::find(15);
if ($user) {
    echo "   - ID: {$user->id}\n";
    echo "   - Name: {$user->name}\n";
    echo "   - Email: {$user->email}\n";
    echo "   - Phone: " . ($user->phone ?? 'NULL') . "\n";
} else {
    echo "   User #15 không tồn tại!\n";
}

echo "\n5. Test logic controller:\n";
$khuyenmai = App\Models\KhuyenMai::where('ma_km', 13)->first();
if ($khuyenmai) {
    echo "   Khuyến mãi: {$khuyenmai->ten_km}\n";
    echo "   Giảm giá: {$khuyenmai->giam_gia}%\n";

    $veKhuyenMais = $khuyenmai->veKhuyenMai()
        ->with([
            'datVe.user',
            'datVe.chuyenXe.tramDi',
            'datVe.chuyenXe.tramDen'
        ])
        ->orderBy('id', 'desc')
        ->get();

    echo "   Tổng số vé khuyến mãi: {$veKhuyenMais->count()}\n";

    $recentBookings = $veKhuyenMais->groupBy(function ($item) {
        return $item->datVe ? $item->datVe->ma_ve : null;
    })->filter(function ($group, $key) {
        return $key !== null;
    })->take(10)->map(function ($group) use ($khuyenmai) {
        $firstBooking = $group->first()->datVe;
        $soLuongGhe = $group->count();
        $giaVe = $firstBooking->chuyenXe->gia_ve ?? 0;
        $tongTien = $soLuongGhe * $giaVe;
        $giamGia = $tongTien * ($khuyenmai->giam_gia / 100);

        return (object)[
            'id' => $firstBooking->id,
            'ma_ve' => $firstBooking->ma_ve,
            'user' => $firstBooking->user,
            'user_name' => $firstBooking->user ? $firstBooking->user->name : 'NULL',
            'chuyenXe' => $firstBooking->chuyenXe,
            'so_luong_ghe' => $soLuongGhe,
            'tong_tien' => $tongTien,
            'giam_gia' => $giamGia,
        ];
    })->values();

    echo "   Số booking unique: {$recentBookings->count()}\n";

    foreach ($recentBookings as $idx => $bk) {
        echo "\n   Booking #" . ($idx + 1) . ":\n";
        echo "     - ID: {$bk->id}\n";
        echo "     - Ma Ve: {$bk->ma_ve}\n";
        echo "     - User Name: {$bk->user_name}\n";
        echo "     - Số ghế: {$bk->so_luong_ghe}\n";
        echo "     - Tổng tiền: " . number_format($bk->tong_tien, 0, ',', '.') . "đ\n";
        echo "     - Giảm giá: " . number_format($bk->giam_gia, 0, ',', '.') . "đ\n";
    }
}

echo "\n=== END TEST ===\n";
