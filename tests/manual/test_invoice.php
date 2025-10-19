<?php
/**
 * Script test trang hóa đơn với nhiều vé
 * Chạy: php scripts/test_invoice.php
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🧪 TEST TRANG HÓA ĐƠN\n";
echo str_repeat("=", 70) . "\n\n";

// Tìm các booking có nhiều vé
echo "📋 TÌM BOOKING CÓ NHIỀU VÉ:\n";
echo str_repeat("-", 70) . "\n";

$multiTicketBookings = DB::table('dat_ve')
    ->select('ma_ve', DB::raw('COUNT(*) as so_ghe'), DB::raw('GROUP_CONCAT(so_ghe ORDER BY so_ghe) as danh_sach_ghe'))
    ->where('trang_thai', '!=', 'Đã hủy')
    ->groupBy('ma_ve')
    ->having(DB::raw('COUNT(*)'), '>', 1)
    ->orderBy('ngay_dat', 'desc')
    ->limit(5)
    ->get();

if ($multiTicketBookings->isEmpty()) {
    echo "❌ Không tìm thấy booking nào có nhiều vé!\n";
    echo "💡 Hãy tạo booking mới với nhiều ghế để test.\n\n";
} else {
    echo "✅ Tìm thấy " . $multiTicketBookings->count() . " booking có nhiều vé:\n\n";
    
    foreach ($multiTicketBookings as $booking) {
        echo "   Mã vé: {$booking->ma_ve}\n";
        echo "   Số ghế: {$booking->so_ghe}\n";
        echo "   Danh sách: {$booking->danh_sach_ghe}\n";
        echo "   URL test: http://127.0.0.1:8000/hoadon\n";
        echo "   (Nhập mã: {$booking->ma_ve})\n";
        echo str_repeat("-", 70) . "\n";
    }
}

// Kiểm tra booking có giảm giá
echo "\n💰 TÌM BOOKING CÓ GIẢM GIÁ:\n";
echo str_repeat("-", 70) . "\n";

$discountBookings = DB::table('dat_ve as dv')
    ->join('ve_khuyenmai as vkm', 'dv.id', '=', 'vkm.dat_ve_id')
    ->join('khuyen_mai as km', 'vkm.ma_km', '=', 'km.ma_km')
    ->select('dv.ma_ve', 'dv.so_ghe', 'km.ten_km', 'km.giam_gia')
    ->where('dv.trang_thai', '!=', 'Đã hủy')
    ->orderBy('dv.ngay_dat', 'desc')
    ->limit(5)
    ->get();

if ($discountBookings->isEmpty()) {
    echo "❌ Không có booking nào sử dụng mã giảm giá!\n\n";
} else {
    echo "✅ Tìm thấy " . $discountBookings->count() . " vé có giảm giá:\n\n";
    
    $groupedByCode = $discountBookings->groupBy('ma_ve');
    foreach ($groupedByCode as $ma_ve => $tickets) {
        $firstTicket = $tickets->first();
        echo "   Mã vé: {$ma_ve}\n";
        echo "   Mã KM: {$firstTicket->ten_km} (Giảm {$firstTicket->giam_gia}%)\n";
        echo "   Số ghế: " . $tickets->pluck('so_ghe')->implode(', ') . "\n";
        echo str_repeat("-", 70) . "\n";
    }
}

// Test case cụ thể
echo "\n🎯 CÁC TEST CASE KHUYẾN NGHỊ:\n";
echo str_repeat("-", 70) . "\n";

$testCases = [];

// Test 1: Booking có nhiều vé nhất
$maxTickets = DB::table('dat_ve')
    ->select('ma_ve', DB::raw('COUNT(*) as total'))
    ->where('trang_thai', '!=', 'Đã hủy')
    ->groupBy('ma_ve')
    ->orderBy('total', 'desc')
    ->first();

if ($maxTickets) {
    $testCases[] = [
        'name' => '1 vé có nhiều ghế nhất',
        'code' => $maxTickets->ma_ve,
        'seats' => $maxTickets->total
    ];
}

// Test 2: Booking đã thanh toán
$paidBooking = DB::table('dat_ve')
    ->where('trang_thai', 'Đã thanh toán')
    ->orderBy('ngay_dat', 'desc')
    ->first();

if ($paidBooking) {
    $testCases[] = [
        'name' => '2 vé đã thanh toán',
        'code' => $paidBooking->ma_ve,
        'status' => 'Đã thanh toán'
    ];
}

// Test 3: Booking có giảm giá
if ($discountBookings->isNotEmpty()) {
    $discountCode = $discountBookings->first()->ma_ve;
    $testCases[] = [
        'name' => '3 vé có giảm giá',
        'code' => $discountCode,
        'discount' => $discountBookings->first()->giam_gia . '%'
    ];
}

echo "\n";
foreach ($testCases as $index => $test) {
    $caseNum = $index + 1;
    echo "✓ Test case {$caseNum}: {$test['name']}\n";
    echo "  Mã vé: {$test['code']}\n";
    if (isset($test['seats'])) echo "  Số ghế: {$test['seats']}\n";
    if (isset($test['status'])) echo "  Trạng thái: {$test['status']}\n";
    if (isset($test['discount'])) echo "  Giảm giá: {$test['discount']}\n";
    echo "\n";
}

echo str_repeat("=", 70) . "\n";
echo "✨ HƯỚNG DẪN TEST:\n";
echo "1. Mở: http://127.0.0.1:8000/hoadon\n";
echo "2. Nhập một trong các mã vé trên\n";
echo "3. Kiểm tra:\n";
echo "   - Hiển thị đầy đủ TẤT CẢ ghế\n";
echo "   - Tổng tiền tính đúng\n";
echo "   - Giảm giá (nếu có) hiển thị chính xác\n";
echo "   - Không có VAT (vé xe không chịu VAT)\n";
echo str_repeat("=", 70) . "\n";