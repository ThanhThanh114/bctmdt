<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đặt vé mới</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin-booking-notification.css') }}">
</head>

<body>
    <div class="header">
        <h1>🔔 THÔNG BÁO ĐẶT VÉ MỚI</h1>
        <p style="margin: 10px 0 0 0;">FUTA Bus Lines - Admin System</p>
    </div>

    <div class="content">
        <div class="alert">
            <strong>⚠️ CÓ ĐẶT VÉ MỚI CẦN XỬ LÝ!</strong>
        </div>

        <p><strong>Thời gian đặt:</strong> {{ date('d/m/Y H:i:s', strtotime($bookings->first()->ngay_dat)) }}</p>

        <div class="booking-code">
            <strong>Mã đặt vé: {{ $bookingCode }}</strong>
        </div>

        @php
        $firstBooking = $bookings->first();
        $trip = $firstBooking->chuyenXe;
        $user = $firstBooking->user;
        @endphp

        <div class="customer-info">
            <h3>👤 Thông tin khách hàng</h3>

            <div class="info-row">
                <span><strong>Họ tên:</strong></span>
                <span>{{ $user->fullname ?? 'N/A' }}</span>
            </div>

            <div class="info-row">
                <span><strong>Email:</strong></span>
                <span>{{ $user->email ?? 'N/A' }}</span>
            </div>

            <div class="info-row">
                <span><strong>SĐT:</strong></span>
                <span>{{ $user->phone ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="trip-info">
            <h3>🚌 Thông tin chuyến xe</h3>

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
            <p style="font-size: 18px; color: #dc3545; margin: 10px 0;">
                {{ $bookings->pluck('so_ghe')->implode(', ') }}
            </p>
            <small>(Tổng: {{ $bookings->count() }} ghế)</small>
        </div>

        <div class="price-summary">
            <h3 style="margin-top: 0; color: #dc3545;">💰 Chi tiết thanh toán</h3>

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

        <div class="action-buttons">
            <a href="{{ url('/admin/bookings') }}" class="action-button">👁️ Xem chi tiết</a>
            <a href="{{ url('/admin/bookings?ma_ve=' . $bookingCode) }}" class="action-button">🔍 Tìm kiếm vé</a>
        </div>

        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            Email này được gửi tự động khi có đặt vé mới trong hệ thống.
        </p>
    </div>

    <div class="footer">
        <p style="margin: 0; font-size: 12px; color: #666;">
            © {{ date('Y') }} FUTA Bus Lines - Admin System<br>
            Email này được gửi tự động, vui lòng không trả lời.
        </p>
    </div>
</body>

</html>
