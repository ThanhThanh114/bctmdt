@extends('layouts.admin')

@section('title', 'Staff Dashboard')

@section('page-title', 'Dashboard Nhân viên')
@section('breadcrumb', 'Nhân viên')

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-ticket-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đặt vé hôm nay</span>
                <span class="info-box-number">{{ number_format($stats['today_bookings']) }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Chờ xử lý</span>
                <span class="info-box-number">{{ number_format($stats['pending_bookings']) }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đã xác nhận</span>
                <span class="info-box-number">{{ number_format($stats['confirmed_bookings']) }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-bus"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Chuyến hôm nay</span>
                <span class="info-box-number">{{ number_format($stats['today_trips']) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Pending Bookings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Đặt vé cần xử lý</h3>
                <div class="card-tools">
                    <a href="{{ route('staff.bookings.index', ['status' => 'pending']) }}" class="btn btn-sm btn-primary">
                        Xem tất cả
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Mã vé</th>
                            <th>Khách hàng</th>
                            <th>Chuyến xe</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->fullname ?? $booking->user->username }}</td>
                            <td>{{ $booking->chuyenXe->route_name ?? 'N/A' }}</td>
                            <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</td>
                            <td>
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có đặt vé nào cần xử lý</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Today's Trips -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chuyến xe hôm nay</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Giờ khởi hành</th>
                            <th>Tuyến đường</th>
                            <th>Nhà xe</th>
                            <th>Số vé còn</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($today_trips as $trip)
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $trip->gio_di)->format('H:i') }}</td>
                            <td>{{ $trip->route_name }}</td>
                            <td>{{ $trip->nhaXe->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $trip->available_seats > 10 ? 'badge-success' : ($trip->available_seats > 0 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $trip->available_seats }}
                                </span>
                            </td>
                            <td>
                                @if($trip->status == 'active')
                                    <span class="badge badge-success">Hoạt động</span>
                                @else
                                    <span class="badge badge-secondary">Không hoạt động</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Không có chuyến xe nào hôm nay</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thao tác nhanh</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('staff.bookings.index') }}" class="btn btn-primary btn-block">
                    <i class="fas fa-ticket-alt mr-2"></i> Quản lý đặt vé
                </a>
                <a href="{{ route('staff.trips.index') }}" class="btn btn-success btn-block">
                    <i class="fas fa-bus mr-2"></i> Quản lý chuyến xe
                </a>
                <a href="{{ route('staff.customers.index') }}" class="btn btn-warning btn-block">
                    <i class="fas fa-users mr-2"></i> Danh sách khách hàng
                </a>
            </div>
        </div>

        <!-- Booking Trend Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Xu hướng đặt vé (30 ngày)</h3>
            </div>
            <div class="card-body">
                <canvas id="bookingTrendChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
            </div>
        </div>

        <!-- System Status -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thống kê tổng quan</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Đặt vé tháng:</span>
                    <span class="badge badge-info">{{ number_format($stats['monthly_bookings']) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Đã hủy:</span>
                    <span class="badge badge-danger">{{ number_format($stats['cancelled_bookings']) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Khách hàng:</span>
                    <span class="badge badge-success">{{ number_format($stats['total_customers']) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Booking Trend Chart
    var ctx = document.getElementById('bookingTrendChart').getContext('2d');
    var trendData = @json($monthly_trend);

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: Object.keys(trendData).slice(-10),
            datasets: [{
                label: 'Số vé đặt',
                data: Object.values(trendData).slice(-10),
                borderColor: 'rgb(255, 193, 7)',
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
