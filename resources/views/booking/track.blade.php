<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theo dõi chuyến đi - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
</script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Get station data from backend
        const mapStations = window.mapStations || [];
        console.log('Map stations data:', mapStations);

        if (mapStations.length < 2) {
            console.warn('Not enough stations with coordinates');
            return;
        }

        // Filter valid stations with coordinates
        const validStations = mapStations.filter(station => {
            const lat = parseFloat(station.lat);
            const lng = parseFloat(station.lng);
            const isValid = !isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
            if (!isValid) {
                console.warn('Invalid coordinates for station:', station);
            }
            return isValid;
        });

        console.log('Valid stations:', validStations);

        if (validStations.length < 2) {
            console.error('Not enough valid coordinates for map');
            return;
        }

        // Initialize map centered on first station
        const firstStation = validStations[0];
        const map = L.map('map').setView([parseFloat(firstStation.lat), parseFloat(firstStation.lng)], 12);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Create markers for each station with color coding
        const markers = [];
        validStations.forEach(function(station, index) {
            // Get marker icon based on station type
            const icon = getMarkerIcon(station.type);
            const marker = L.marker([parseFloat(station.lat), parseFloat(station.lng)], { icon: icon }).addTo(map);

            // Create detailed popup content
            let popupContent = `<div style="font-family: Poppins, sans-serif; max-width: 200px;">`;
            popupContent += `<h6 style="color: #007bff; margin-bottom: 8px;"><strong>${station.name}</strong></h6>`;
            if (station.address) popupContent += `<p style="margin: 4px 0;"><strong>Địa chỉ:</strong> ${station.address}</p>`;
            if (station.province) popupContent += `<p style="margin: 4px 0;"><strong>Tỉnh:</strong> ${station.province}</p>`;
            popupContent += `<p style="margin: 4px 0;"><strong>Loại:</strong> ${getStationTypeText(station.type)}</p>`;
            popupContent += `</div>`;

            marker.bindPopup(popupContent);
            markers.push(marker);
        });

        // Draw route line connecting all stations
        const routeCoords = validStations.map(station => [parseFloat(station.lat), parseFloat(station.lng)]);
        console.log('Route coordinates:', routeCoords);

        const polyline = L.polyline(routeCoords, {
            color: '#007bff',
            weight: 4,
            opacity: 0.8
        }).addTo(map);

        // Fit map to show entire route with padding
        try {
            map.fitBounds(polyline.getBounds(), { padding: [20, 20] });
            console.log('Map bounds fitted successfully');
        } catch (error) {
            console.error('Error fitting bounds:', error);
            // Fallback: fit to markers
            const markerGroup = L.featureGroup(markers);
            map.fitBounds(markerGroup.getBounds(), { padding: [20, 20] });
        }

        console.log('Map initialized successfully with', markers.length, 'markers and route');
    });

    // Function to get marker icon based on station type
    function getMarkerIcon(type) {
        let iconUrl;
        switch(type) {
            case 'departure':
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png';
                break;
            case 'arrival':
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png';
                break;
            default:
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-yellow.png';
                break;
        }
        return L.icon({
            iconUrl: iconUrl,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    }

    // Function to get station type text
    function getStationTypeText(type) {
        switch(type) {
            case 'departure':
                return 'Điểm khởi hành';
            case 'arrival':
                return 'Điểm đến';
            default:
                return 'Trạm trung chuyển';
        }
    }
</script>
@endif
</body>

</html>
