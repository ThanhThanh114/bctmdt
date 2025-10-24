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
    <style>
        .tracking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .tracking-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .tracking-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }

        .tracking-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }

        .info-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .info-card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .info-card-header h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .info-card-body {
            padding: 2rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .status-upcoming {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }

        .status-in-progress {
            background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
            color: white;
        }

        .status-completed {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .route-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 1.5rem;
        }

        .route-point {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .route-point:last-child {
            margin-bottom: 0;
        }

        .route-point i {
            font-size: 1.2rem;
            margin-right: 1rem;
            width: 20px;
        }

        .driver-info {
            background: #e3f2fd;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #2196f3;
        }

        .trip-details {
            background: #fff3cd;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #ffc107;
        }

        .company-info {
            background: #f8d7da;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #dc3545;
        }

        .btn-back {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #5a6268 0%, #3e4449 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .btn-call {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-call:hover {
            background: linear-gradient(135deg, #218838 0%, #1ba085 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .progress-timeline {
            position: relative;
            padding-left: 3rem;
        }

        .progress-timeline::before {
            content: '';
            position: absolute;
            left: 1.5rem;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #dee2e6;
        }

        .progress-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 1rem;
        }

        .progress-item.completed .progress-marker {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-color: #28a745;
            color: white;
        }

        .progress-item.current .progress-marker {
            background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
            border-color: #007bff;
            color: white;
            animation: pulse 2s infinite;
        }

        .progress-item .progress-marker {
            position: absolute;
            left: -2.5rem;
            top: 0;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 3px solid #6c757d;
            color: #6c757d;
            font-size: 1.2rem;
        }

        .progress-content h6 {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .progress-content p {
            margin-bottom: 0.25rem;
            color: #666;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(0, 123, 255, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }

        .actions-section {
            text-align: center;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .tracking-container {
                padding: 1rem 0.5rem;
            }

            .tracking-header {
                padding: 1.5rem;
            }

            .tracking-header h1 {
                font-size: 1.5rem;
            }

            .info-card-body {
                padding: 1rem;
            }

            .btn-back, .btn-call {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
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
