<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theo dõi chuyến đi - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/BookingHistory.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/track.css') }}">
</head>

<body>
    <div class="tracking-container">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('booking.history') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Quay lại lịch sử đặt vé
            </a>
        </div>

        <!-- Header -->
        <div class="tracking-header">
            <h1>
                <i class="fas fa-map-marked-alt mr-3"></i>
                Theo dõi chuyến đi
            </h1>
            <p>Mã vé: <strong>{{ $booking->ma_ve }}</strong></p>
        </div>

        <div class="row">
            <!-- Trip Information -->
            <div class="col-lg-4">
                <!-- Trip Status -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-info-circle mr-2"></i>Trạng thái chuyến</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="text-center mb-3">
                            <span class="status-badge status-{{ $tripStatus }}">
                                @if($tripStatus == 'upcoming')
                                    <i class="fas fa-clock mr-1"></i> Sắp khởi hành
                                @elseif($tripStatus == 'in_progress')
                                    <i class="fas fa-bus mr-1"></i> Đang di chuyển
                                @else
                                    <i class="fas fa-check-circle mr-1"></i> Đã hoàn thành
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Route Information -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-route mr-2"></i>Tuyến đường</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="route-info">
                            <div class="route-point">
                                <i class="fas fa-play-circle text-success"></i>
                                <div>
                                    <strong>{{ $booking->chuyenXe->tramDi->ten_tram }}</strong><br>
                                    <small class="text-muted">{{ $booking->chuyenXe->tramDi->tinh_thanh }}</small>
                                </div>
                            </div>

                            @if($intermediateStops->count() > 0)
                                @foreach($intermediateStops as $stop)
                                <div class="route-point">
                                    <i class="fas fa-dot-circle text-warning"></i>
                                    <div>
                                        <strong>{{ $stop->ten_tram }}</strong><br>
                                        <small class="text-muted">{{ $stop->tinh_thanh }}</small>
                                    </div>
                                </div>
                                @endforeach
                            @endif

                            <div class="route-point">
                                <i class="fas fa-flag-checkered text-danger"></i>
                                <div>
                                    <strong>{{ $booking->chuyenXe->tramDen->ten_tram }}</strong><br>
                                    <small class="text-muted">{{ $booking->chuyenXe->tramDen->tinh_thanh }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driver Information -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-user-tie mr-2"></i>Thông tin tài xế</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="driver-info">
                            <p><strong>Tên:</strong> {{ $booking->chuyenXe->ten_tai_xe }}</p>
                            <p><strong>SĐT:</strong>
                                <a href="tel:{{ $booking->chuyenXe->sdt_tai_xe }}" class="text-primary">
                                    {{ $booking->chuyenXe->sdt_tai_xe }}
                                </a>
                            </p>
                            <p><strong>Xe:</strong> {{ $booking->chuyenXe->ten_xe }} ({{ $booking->chuyenXe->loai_xe }})</p>
                        </div>
                    </div>
                </div>

                <!-- Trip Details -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-calendar-alt mr-2"></i>Chi tiết chuyến</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="trip-details">
                            <p><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }}</p>
                            <p><strong>Giờ đi:</strong> {{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</p>
                            <p><strong>Ghế:</strong> <span class="badge badge-primary">{{ $booking->so_ghe }}</span></p>
                            <p><strong>Giá vé:</strong> <span class="text-success font-weight-bold">{{ number_format($booking->chuyenXe->gia_ve) }}đ</span></p>
                        </div>
                    </div>
                </div>

                <!-- Bus Company -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-building mr-2"></i>Nhà xe</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="company-info">
                            <p><strong>{{ $booking->chuyenXe->nhaXe->ten_nha_xe }}</strong></p>
                            @if($booking->chuyenXe->nhaXe->email)
                            <p><strong>Email:</strong> {{ $booking->chuyenXe->nhaXe->email }}</p>
                            @endif
                            @if($booking->chuyenXe->nhaXe->so_dien_thoai)
                            <p><strong>SĐT:</strong> {{ $booking->chuyenXe->nhaXe->so_dien_thoai }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Google Map -->
            <div class="col-lg-8">
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-map-marked-alt mr-2"></i>Bản đồ hành trình</h3>
                    </div>
                    <div class="info-card-body">
                        @if($mapStations->count() >= 2)
                        <div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Không đủ thông tin tọa độ để hiển thị bản đồ. Vui lòng liên hệ nhà xe để cập nhật thông tin trạm.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Trip Progress -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-road mr-2"></i>Tiến trình hành trình</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="progress-timeline">
                            <!-- Departure -->
                            <div class="progress-item {{ $tripStatus != 'upcoming' ? 'completed' : 'current' }}">
                                <div class="progress-marker">
                                    <i class="fas fa-play-circle"></i>
                                </div>
                                <div class="progress-content">
                                    <h6>Khởi hành</h6>
                                    <p>{{ $booking->chuyenXe->tramDi->ten_tram }}</p>
                                    <small class="text-muted">
                                        @php
                                            try {
                                                $gio_di = trim($booking->chuyenXe->gio_di);
                                                if (strlen($gio_di) === 5) {
                                                    $gio_di .= ':00';
                                                }
                                                echo \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->chuyenXe->ngay_di . ' ' . $gio_di)->format('d/m/Y H:i');
                                            } catch (Exception $e) {
                                                echo \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') . ' ' . $booking->chuyenXe->gio_di;
                                            }
                                        @endphp
                                    </small>
                                </div>
                            </div>

                            <!-- Intermediate Stops -->
                            @foreach($intermediateStops as $index => $stop)
                            <div class="progress-item {{ $tripStatus == 'completed' ? 'completed' : ($tripStatus == 'in_progress' && $index == 0 ? 'current' : '') }}">
                                <div class="progress-marker">
                                    <i class="fas fa-dot-circle"></i>
                                </div>
                                <div class="progress-content">
                                    <h6>Dừng chân</h6>
                                    <p>{{ $stop->ten_tram }}</p>
                                    <small class="text-muted">Trạm trung chuyển</small>
                                </div>
                            </div>
                            @endforeach

                            <!-- Arrival -->
                            <div class="progress-item {{ $tripStatus == 'completed' ? 'completed' : '' }}">
                                <div class="progress-marker">
                                    <i class="fas fa-flag-checkered"></i>
                                </div>
                                <div class="progress-content">
                                    <h6>Đến nơi</h6>
                                    <p>{{ $booking->chuyenXe->tramDen->ten_tram }}</p>
                                    <small class="text-muted">
                                        @php
                                            try {
                                                echo \Carbon\Carbon::parse($booking->chuyenXe->gio_den)->format('H:i');
                                            } catch (Exception $e) {
                                                echo $booking->chuyenXe->gio_den ?? 'Chưa cập nhật';
                                            }
                                        @endphp
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="actions-section">
            <a href="{{ route('booking.history') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Quay lại lịch sử
            </a>
            <a href="tel:{{ $booking->chuyenXe->sdt_tai_xe }}" class="btn-call">
                <i class="fas fa-phone"></i>
                Gọi tài xế
            </a>
            @if($booking->chuyenXe->nhaXe->so_dien_thoai)
            <a href="tel:{{ $booking->chuyenXe->nhaXe->so_dien_thoai }}" class="btn-call">
                <i class="fas fa-building"></i>
                Gọi nhà xe
            </a>
            @endif
        </div>
    </div>

    <!-- Google Maps Script -->
    @if($mapStations->count() >= 2)
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=geometry&callback=initMap" async defer></script>
    <script src="{{ asset('assets/js/user-trip-tracking.js') }}"></script>
    @endif
</body>

</html>
