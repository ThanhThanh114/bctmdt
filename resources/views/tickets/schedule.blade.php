@extends('app') <!-- Sử dụng layout chung -->

@section('title', 'Lịch Trình - FUTA Bus Lines')

@section('content')
<main class="main-content">
    <div class="search-results">    
        <div class="results-header">
            <h1 class="results-title">
                @if (!empty($start) && !empty($end))
                Lịch trình {{ htmlspecialchars($start) }} - {{ htmlspecialchars($end) }}
                @else
                Lịch trình chuyến xe
                @endif
            </h1>
            <div class="results-info">
                @if (!empty($trips))
                Tìm thấy {{ $totalCount }} chuyến xe | Trang {{ $page }}/{{ $totalPages }}
                @endif
            </div>
        </div>

        @if (!empty($trips))
        <div class="trip-list">
            @foreach ($trips as $trip)
            <div class="trip-card">
                <div class="trip-route">
                    {{ htmlspecialchars($trip->diem_di) }} → {{ htmlspecialchars($trip->diem_den) }}
                </div>
                <div class="trip-datetime">
                    {{ date('d/m/Y', strtotime($trip->ngay_di)) }} - {{ date('H:i', strtotime($trip->gio_di)) }}
                </div>

                <div class="trip-details">
                    <div class="trip-info">
                        <span class="bus-type">{{ htmlspecialchars($trip->loai_xe) }} - {{ htmlspecialchars($trip->ten_nha_xe) }}</span>
                        <span class="seats-available">{{ $trip->available_seats }} chỗ trống</span>
                    </div>
                </div>

                <div class="trip-footer">
                    <div class="trip-price">
                        {{ number_format($trip->gia_ve, 0, ',', '.') }} VND
                    </div>
                    <a href="{{ route('book.ticket', ['diem_di' => $trip->diem_di, 'diem_den' => $trip->diem_den, 'ngay' => $trip->ngay_di, 'gio_di' => $trip->gio_di, 'loai_xe' => $trip->loai_xe, 'nha_xe' => $trip->ten_nha_xe, 'gia_ve' => $trip->gia_ve, 'so_cho_trong' => $trip->available_seats, 'trip_id' => $trip->id]) }}" class="book-btn">
                        Đặt vé
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Phân trang -->
        @if ($totalPages > 1)
        <div class="pagination">
            @for ($i = 1; $i <= $totalPages; $i++)
            <a href="{{ route('schedule.index', ['start' => $start, 'end' => $end, 'date' => $date, 'bus_type' => $busType, 'sort' => $sortBy, 'page' => $i]) }}" class="page-btn {{ $i === $page ? 'active' : '' }}">{{ $i }}</a>
            @endfor
        </div>
        @endif
        @else
        <div class="no-results">
            <i class="fas fa-bus"></i>
            <h3>Không tìm thấy chuyến xe phù hợp</h3>
            <p>Vui lòng thử lại với các tiêu chí tìm kiếm khác hoặc chọn ngày khác.</p>
        </div>
        @endif
    </div>
</main>
@endsection
