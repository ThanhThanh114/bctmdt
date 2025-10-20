<div class="results-info">
    @if ($trips->isNotEmpty())
        <span>Tìm thấy {{ $totalCount }} chuyến xe</span>
    @endif
</div>

@if (!empty($trips) && $trips->isNotEmpty())
    <div class="trip-list">
        @foreach ($trips as $trip)
        <div class="trip-card">
            <div class="trip-info">
                <div class="route">
                    <i class="fas fa-route"></i>
                    {{ $trip->diem_di }} → {{ $trip->diem_den }}
                </div>
                @if (!empty($trip->tram_trung_gian_names) && count($trip->tram_trung_gian_names) > 0)
                <div class="intermediate-stops">
                    <i class="fas fa-map-marked-alt"></i>
                    <span class="stops-label">Trạm trung gian:</span>
                    <div class="stops-list">
                        @foreach ($trip->tram_trung_gian_names as $index => $tramName)
                            <span class="stop-badge">
                                <i class="fas fa-map-pin"></i>
                                {{ $tramName }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="datetime">
                    <div><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') }}</div>
                    <div><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($trip->gio_di)->format('H:i') }}</div>
                </div>
            </div>

            <div class="trip-details">
                <div class="bus-info">
                    <span class="bus-type">{{ $trip->loai_xe }}</span>
                    <span class="company">{{ $trip->ten_nha_xe }}</span>
                </div>
                <div class="seats-info">
                    <span class="available-seats">{{ $trip->available_seats }} chỗ trống</span>
                </div>
            </div>

            <div class="trip-footer">
                <div class="price">
                    {{ number_format($trip->gia_ve, 0, ',', '.') }}đ
                </div>
                <a href="{{ route('booking.show', $trip->id) }}" class="book-btn">Đặt vé</a>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="no-results">
        <i class="fas fa-bus"></i>
        <h3>Không tìm thấy chuyến xe phù hợp</h3>
        <p>Vui lòng thử lại với các tiêu chí tìm kiếm khác hoặc chọn ngày khác.</p>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
