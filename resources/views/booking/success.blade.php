@extends('app')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/booking-success.css') }}">
@endpush
@section('content')

    <div class="success-container">
        <!-- Success Header -->
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Đặt vé thành công!</h1>
            <p class="success-message">Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi.</p>
        </div>

        <!-- Booking Details -->
        <div class="booking-details">
            <!-- Booking Code -->
            <div class="booking-code">
                <div class="code-label">Mã đặt vé</div>
                <div class="code-number">{{ $code }}</div>
            </div>

            @php
                $firstBooking = $bookings->first();
                $trip = $firstBooking->chuyenXe;
            @endphp

            <!-- Trip Information -->
            <div class="trip-info">
                <div class="info-item">
                    <span class="info-label">Tuyến xe:</span>
                    <span class="info-value">{{ $trip->tramDi->ten_tram }} → {{ $trip->tramDen->ten_tram }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày khởi hành:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Giờ khởi hành:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($trip->gio_di)->format('H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nhà xe:</span>
                    <span class="info-value">{{ $trip->nhaXe->ten_nha_xe }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Loại xe:</span>
                    <span class="info-value">{{ $trip->loai_xe }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Số lượng vé:</span>
                    <span class="info-value">{{ $bookings->count() }}</span>
                </div>
            </div>

            <!-- Seats Information -->
            <div class="seats-section">
                <div class="seats-title">
                    <i class="fas fa-chair"></i> Ghế đã đặt
                </div>
                <div class="seats-grid">
                    @foreach($bookings as $booking)
                        <div class="seat-item">{{ $booking->so_ghe }}</div>
                    @endforeach
                </div>
            </div>

            <!-- Price Summary -->
            <div class="price-summary">
                <div class="total-label">Tổng tiền</div>
                <div class="total-amount">
                    {{ number_format($bookings->count() * $trip->gia_ve, 0, ',', '.') }}đ
                </div>
            </div>
        </div>

        <!-- Notice -->
        <div class="notice">
            <div class="notice-title">
                <i class="fas fa-info-circle"></i> Lưu ý quan trọng
            </div>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Vui lòng đến bến xe trước giờ khởi hành 30 phút để làm thủ tục.</li>
                <li>Mang theo giấy tờ tùy thân và mã đặt vé để nhận vé.</li>
                <li>Không hoàn tiền sau khi đặt vé thành công.</li>
                <li>Liên hệ hotline 1900 XXX XXX để được hỗ trợ.</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Về trang chủ
            </a>
            <a href="{{ url('/datve') }}" class="btn btn-secondary">
                <i class="fas fa-search"></i> Đặt vé khác
            </a>
        </div>
    </div>

@endsection
