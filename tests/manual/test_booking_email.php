<?php
/**
 * Script test gửi email xác nhận đặt vé
 * Chạy: php scripts/test_booking_email.php
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DatVe;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;

echo "🧪 TEST GỬI EMAIL XÁC NHẬN ĐẶT VÉ\n";
echo str_repeat("=", 60) . "\n\n";

// Lấy một booking đã thanh toán gần đây
$booking = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'user'])
    ->where('trang_thai', 'Đã thanh toán')
    ->whereHas('user', function($q) {
        $q->whereNotNull('email');
    })
    ->orderBy('ngay_dat', 'desc')
    ->first();

if (!$booking) {
    echo "❌ Không tìm thấy booking nào đã thanh toán!\n";
    echo "💡 Hãy tạo một booking mới và thanh toán trước.\n";
    exit(1);
}

$bookingCode = $booking->ma_ve;
$userEmail = $booking->user->email;

// Lấy tất cả vé cùng mã booking
$bookings = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'user'])
    ->where('ma_ve', $bookingCode)
    ->get();

$trip = $bookings->first()->chuyenXe;
$totalSeats = $bookings->count();
$totalAmount = $trip->gia_ve * $totalSeats;

echo "📋 THÔNG TIN TEST:\n";
echo "   Mã đặt vé: {$bookingCode}\n";
echo "   Email người nhận: {$userEmail}\n";
echo "   Tên khách hàng: {$booking->user->fullname}\n";
echo "   Tuyến: {$trip->tramDi->ten_tram} → {$trip->tramDen->ten_tram}\n";
echo "   Số ghế: " . $bookings->pluck('so_ghe')->implode(', ') . "\n";
echo "   Tổng tiền: " . number_format($totalAmount, 0, ',', '.') . "đ\n\n";

echo "📧 Đang gửi email...\n";

try {
    Mail::to($userEmail)->send(new BookingConfirmation(
        $bookings,
        $bookingCode,
        $totalAmount,
        0 // Không có giảm giá trong test
    ));
    
    echo "✅ GỬI EMAIL THÀNH CÔNG!\n\n";
    echo "📬 Hãy kiểm tra hộp thư: {$userEmail}\n";
    echo "💡 Lưu ý: Nếu không thấy trong Inbox, kiểm tra thư mục Spam/Junk\n";
    
} catch (\Exception $e) {
    echo "❌ LỖI KHI GỬI EMAIL:\n";
    echo "   " . $e->getMessage() . "\n\n";
    echo "🔧 CÁCH KHẮC PHỤC:\n";
    echo "   1. Kiểm tra cấu hình MAIL_ trong file .env\n";
    echo "   2. Đảm bảo Gmail App Password đúng\n";
    echo "   3. Kiểm tra kết nối internet\n";
    echo "   4. Xem log chi tiết tại: storage/logs/laravel.log\n";
    exit(1);
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✨ HOÀN TẤT TEST!\n";