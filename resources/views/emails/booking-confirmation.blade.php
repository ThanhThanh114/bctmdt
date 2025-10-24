<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt vé</title>
    <link rel="stylesheet" href="{{ asset('assets/css/booking-confirmation.css') }}">
</head>

<body>
    <div class="header">
        <h1>🎫 XÁC NHẬN ĐẶT VÉ THÀNH CÔNG</h1>
        <p style="margin: 10px 0 0 0;">FUTA Bus Lines</p>
    </div>

    <div class="content">
        <p>Kính chào <strong>{{ $bookings->first()->user->fullname ?? 'Quý khách' }}</strong>,</p>

        <p>Cảm ơn bạn đã sử dụng dịch vụ của FUTA Bus Lines. Vé của bạn đã được đặt và thanh toán thành công!</p>

        <div class="booking-code">
            <strong>Mã đặt vé: {{ $bookingCode }}</strong>
        </div>

        @php
            $firstBooking = $bookings->first();
            $trip = $firstBooking->chuyenXe;
        @endphp

        <div class="trip-info">
            <h3>📍 Thông tin chuyến xe</h3>

            <div class="info-row">
                <span><strong>Tuyến:</strong></span>
                <span>{{ $trip->tramDi->ten_tram }} → {{ $trip->tramDen->ten_tram }}</span>
            </div>

            <div class="info-row">
                <span><strong>Nhà xe:</strong></span>
                <span>{{ $trip->nhaXe->ten_nha_xe }}</span>
            </div>

            <div class="info-row">
                <span><strong>Loại xe:</strong></span>
                <span>{{ $trip->loai_xe }}</span>
            </div>

            <div class="info-row">
                <span><strong>Ngày đi:</strong></span>
                <span>{{ date('d/m/Y', strtotime($trip->ngay_di)) }}</span>
            </div>

            <div class="info-row">
                <span><strong>Giờ khởi hành:</strong></span>
                <span>{{ date('H:i', strtotime($trip->gio_di)) }}</span>
            </div>
        </div>

        <div class="seats">
            <strong>Số ghế đã đặt:</strong>
            <p style="font-size: 18px; color: #FF6F3C; margin: 10px 0;">
                {{ $bookings->pluck('so_ghe')->implode(', ') }}
            </p>
            <small>(Tổng: {{ $bookings->count() }} ghế)</small>
        </div>

        <div class="price-summary">
            <h3 style="margin-top: 0; color: #FF6F3C;">💰 Chi tiết thanh toán</h3>

            <div class="price-row">
                <span>Giá vé ({{ $bookings->count() }} x {{ number_format($trip->gia_ve, 0, ',', '.') }}đ):</span>
                <span><strong>{{ number_format($trip->gia_ve * $bookings->count(), 0, ',', '.') }}đ</strong></span>
            </div>

            @if($discountAmount > 0)
                <div class="price-row" style="color: #28a745;">
                    <span>Giảm giá:</span>
                    <span><strong>-{{ number_format($discountAmount, 0, ',', '.') }}đ</strong></span>
                </div>
            @endif

            <div class="price-row total">
                <span>TỔNG CỘNG:</span>
                <span>{{ number_format($totalAmount, 0, ',', '.') }}đ</span>
            </div>
        </div>

        <div class="note">
            <strong>⚠️ Lưu ý quan trọng:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Vui lòng có mặt tại bến xe trước giờ khởi hành <strong>15-30 phút</strong></li>
                <li>Mang theo <strong>CMND/CCCD</strong> để đối chiếu khi lên xe</li>
                <li>Xuất trình <strong>mã đặt vé</strong> này cho nhân viên</li>
                <li>Liên hệ hotline nếu cần hỗ trợ hoặc thay đổi lịch trình</li>
            </ul>
        </div>

        <div class="contact-info">
            <strong>📞 Liên hệ hỗ trợ:</strong><br>
            Hotline: <strong>1900 6067</strong><br>
            Email: <strong>support@futabus.vn</strong><br>
            Website: <strong>www.futabus.vn</strong>
        </div>

        <p style="margin-top: 30px;">Chúc bạn có một chuyến đi an toàn và vui vẻ! 🚌✨</p>

        <p style="margin-top: 20px; font-size: 14px; color: #666;">
            Trân trọng,<br>
            <strong style="color: #FF6F3C;">Đội ngũ FUTA Bus Lines</strong>
        </p>
    </div>

    <div class="footer">
        <p style="margin: 0; font-size: 12px; color: #666;">
            © {{ date('Y') }} FUTA Bus Lines. All rights reserved.<br>
            Email này được gửi tự động, vui lòng không trả lời.
        </p>
    </div>
</body>

</html>
