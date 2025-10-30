<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theo dõi chuyến đi - {{ $booking->ma_ve }}</title>
    <!-- Modern font stack with fallbacks -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Leaflet CSS for map functionality -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/btrack.css') }}">
</head>

<body>
    <div class="container">

        <div class="header">
            <h1><i class="fas fa-map-marked-alt"></i> Theo dõi chuyến đi</h1>
            <p>Mã vé: <strong>{{ $booking->ma_ve }}</strong></p>
        </div>

        <div class="grid">
            <!-- Trip Information -->
            <div>
                <!-- Trip Status -->
                <div class="card">
                    <div class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Trạng thái chuyến
                    </div>
                    <div class="status-badge status-{{ $tripStatus }}">
                        @if($tripStatus == 'upcoming')
                            <i class="fas fa-clock"></i> Sắp khởi hành
                        @elseif($tripStatus == 'in_progress')
                            <i class="fas fa-bus"></i> Đang di chuyển
                        @else
                            <i class="fas fa-check-circle"></i> Đã hoàn thành
                        @endif
                    </div>
                </div>

                <!-- Route Information -->
                <div class="card">
                    <div class="card-title">
                        <i class="fas fa-route"></i>
                        Tuyến đường
                    </div>
                    <div class="route-list">
                        <div class="route-item start">
                            <i class="fas fa-play-circle text-success"></i>
                            <div>
                                <strong>{{ $booking->chuyenXe->tramDi->ten_tram }}</strong>
                                <div class="text-muted small">{{ $booking->chuyenXe->tramDi->tinh_thanh }}</div>
                            </div>
                        </div>

                        @if($intermediateStops->count() > 0)
                            @foreach($intermediateStops as $stop)
                            <div class="route-item middle">
                                <i class="fas fa-dot-circle text-warning"></i>
                                <div>
                                    <strong>{{ $stop->ten_tram }}</strong>
                                    <div class="text-muted small">{{ $stop->tinh_thanh }}</div>
                                </div>
                            </div>
                            @endforeach
                        @endif

                        <div class="route-item end">
                            <i class="fas fa-flag-checkered text-danger"></i>
                            <div>
                                <strong>{{ $booking->chuyenXe->tramDen->ten_tram }}</strong>
                                <div class="text-muted small">{{ $booking->chuyenXe->tramDen->tinh_thanh }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trip Details -->
                <div class="card">
                    <div class="card-title">
                        <i class="fas fa-calendar-alt"></i>
                        Chi tiết chuyến
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Ngày đi</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Giờ khởi hành</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Ghế</div>
                            <div class="info-value primary">{{ $booking->so_ghe }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Giá vé</div>
                            <div class="info-value">{{ number_format($booking->chuyenXe->gia_ve) }}đ</div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card">
                    <div class="card-title">
                        <i class="fas fa-address-book"></i>
                        Thông tin liên hệ
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Tài xế</div>
                            <div class="info-value">{{ $booking->chuyenXe->ten_tai_xe }}</div>
                            <a href="tel:{{ $booking->chuyenXe->sdt_tai_xe }}" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-phone"></i> Gọi
                            </a>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nhà xe</div>
                            <div class="info-value">{{ $booking->chuyenXe->nhaXe->ten_nha_xe }}</div>
                            @if($booking->chuyenXe->nhaXe->so_dien_thoai)
                            <a href="tel:{{ $booking->chuyenXe->nhaXe->so_dien_thoai }}" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-phone"></i> Gọi
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map and Progress -->
            <div>
                <!-- Map -->
                <div class="card">
                    <div class="card-title">
                        <i class="fas fa-map-marked-alt"></i>
                        Bản đồ hành trình
                    </div>
                    @if($mapStations->count() >= 2)
                    <div id="map"></div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Không đủ thông tin tọa độ để hiển thị bản đồ. Vui lòng liên hệ nhà xe để cập nhật thông tin trạm.
                    </div>
                    @endif
                </div>

                <!-- Trip Progress -->
                <div class="card">
                    <div class="card-title">
                        <i class="fas fa-road"></i>
                        Tiến trình hành trình
                    </div>
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
                                        // Assuming $booking->chuyenXe->gio_di is already in a valid format
                                        echo \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i');
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

        <!-- Action Buttons -->
        <div class="actions">
            <a href="{{ route('booking.history') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay lại lịch sử
            </a>
        </div>
    </div>

    <!-- OpenStreetMap with Leaflet and OSRM Routing -->
@if($mapStations->count() >= 2)
    <script>
        // Prepare station data with proper type assignment for marker colors
        // First station = departure (green), last station = arrival (red), others = intermediate (yellow)
        window.mapStations = {!! json_encode(
            $mapStations->map(function($station, $index) use ($mapStations) {
                $type = 'intermediate';
                if ($index === 0) {
                    $type = 'departure';
                } elseif ($index === $mapStations->count() - 1) {
                    $type = 'arrival';
                }

                // Ensure coordinates are properly converted to float
                $lat = $station->latitude ? floatval($station->latitude) : null;
                $lng = $station->longitude ? floatval($station->longitude) : null;

                return [
                    'name' => $station->ten_tram ?? 'Không rõ',
                    'lat' => $lat,
                    'lng' => $lng,
                    'address' => $station->dia_chi ?? '',
                    'province' => $station->tinh_thanh ?? '',
                    'type' => $type
                ];
            })->filter(function($station) {
                // Filter stations that have valid numeric coordinates
                return is_numeric($station['lat']) && is_numeric($station['lng']) &&
                    $station['lat'] >= -90 && $station['lat'] <= 90 &&
                    $station['lng'] >= -180 && $station['lng'] <= 180;
            })->values()->all()
        ) !!};

        // Trip timing data for arrival time calculations
        window.tripData = {
            departureTime: {!! json_encode($booking->chuyenXe->ngay_di . ' ' . $booking->chuyenXe->gio_di) !!},
            averageSpeed: 50 // km/h - typical bus speed
        };
    </script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('assets/js/map.js') }}"></script>
@endif
</body>

</html>
