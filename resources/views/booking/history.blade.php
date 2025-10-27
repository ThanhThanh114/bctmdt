<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đặt vé - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/BookingHistory.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-trip-tracking.css') }}">
</head>

<body>
    <div class="profile-container">
        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại trang chủ
        </a>

        <div class="profile-header">
            <h1 class="profile-title">
                <i class="fas fa-history"></i> Lịch sử đặt vé
            </h1>
        </div>

        <div class="profile-wrapper">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-name">{{ Auth::user()->fullname }}</div>
                    <div class="profile-role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>

                <ul class="profile-menu">
                    <li class="profile-menu-item">
                        <a href="{{ route('profile.edit') }}" class="profile-menu-link">
                            <i class="fas fa-user-edit"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="{{ route('password.edit') }}" class="profile-menu-link">
                            <i class="fas fa-key"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="{{ route('booking.history') }}" class="profile-menu-link active">
                            <i class="fas fa-history"></i>
                            <span>Lịch sử đặt vé</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="{{ route('home') }}" class="profile-menu-link">
                            <i class="fas fa-home"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="profile-content">
                <div class="content-header">
                    <h2 class="content-title">
                        <i class="fas fa-ticket-alt"></i>
                        Danh sách vé đã đặt
                    </h2>
                </div>

                @if($bookings->count() > 0)
                    @foreach($bookings as $booking)
                        <div class="booking-card">
                            <div class="booking-header-bar">
                                <div class="booking-code">
                                    <i class="fas fa-ticket-alt"></i>
                                    {{ $booking->ma_ve }}
                                </div>
                                <div class="booking-header-right">
                                    <div class="booking-date">
                                        <i class="fas fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="booking-status-wrapper">
                                        @php
                                            $statusClass = 'pending';
                                            if (str_contains($booking->trang_thai, 'thanh toán')) {
                                                $statusClass = 'paid';
                                            } elseif (str_contains($booking->trang_thai, 'hủy')) {
                                                $statusClass = 'cancelled';
                                            }
                                        @endphp
                                        <span class="booking-status status-{{ $statusClass }}">
                                            {{ $booking->trang_thai }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="booking-body">
                                <div class="booking-info-row">

                                    <!-- Tuyến đường -->
                                    <div class="booking-route">
                                        <div class="route-point">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <div class="route-label">Điểm đi</div>
                                                <div class="route-value">{{ $booking->chuyenXe->tramDi->ten_tram ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="route-arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                        <div class="route-point">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <div class="route-label">Điểm đến</div>
                                                <div class="route-value">{{ $booking->chuyenXe->tramDen->ten_tram ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chi tiết chuyến xe -->
                                    <div class="booking-details">
                                        <div class="detail-item">
                                            <i class="fas fa-bus"></i>
                                            <span>{{ $booking->chuyenXe->nhaXe->ten_nha_xe ?? 'N/A' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }}
                                                {{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-chair"></i>
                                            <span>
                                                @if(isset($booking->so_ghe_list) && count($booking->so_ghe_list) > 1)
                                                    Ghế {{ implode(', ', $booking->so_ghe_list) }}
                                                @else
                                                    Ghế {{ $booking->so_ghe }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span>
                                                @if(isset($booking->tong_tien))
                                                    {{ number_format($booking->tong_tien, 0, ',', '.') }} VNĐ
                                                @else
                                                    {{ number_format($booking->chuyenXe->gia_ve ?? 0, 0, ',', '.') }} VNĐ
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="booking-actions">
                                    @if(str_contains($booking->trang_thai, 'thanh toán'))
                                    <a href="{{ route('bookings.track', $booking->ma_ve) }}" class="btn-track">
                                        <i class="fas fa-map-marked-alt"></i>
                                        Theo dõi chuyến đi
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    @if($bookings->hasPages())
                        <div class="pagination-wrapper">
                            {{ $bookings->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        <h3>Chưa có lịch sử đặt vé</h3>
                        <p>Bạn chưa đặt vé nào. Hãy đặt vé đầu tiên của mình!</p>
                        <a href="{{ route('home') }}" class="btn-primary">
                            <i class="fas fa-bus"></i> Đặt vé ngay
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
