@extends('app')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Index.css') }}">
    <style>
        .success-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .success-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .success-icon {
            font-size: 4rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .success-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }

        .success-message {
            font-size: 1.1rem;
            color: #666;
        }

        .booking-details {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .booking-code {
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .code-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .code-number {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .trip-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .info-label {
            font-weight: 600;
            color: #333;
        }

        .info-value {
            color: #666;
        }

        .seats-section {
            margin-top: 20px;
        }

        .seats-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .seats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 10px;
        }

        .seat-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            color: #FF5722;
        }

        .price-summary {
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
        }

        .total-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .notice-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .trip-info {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                text-align: center;
                justify-content: center;
            }
        }
    </style>
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