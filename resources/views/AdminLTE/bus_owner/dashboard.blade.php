@extends('layouts.admin')

@section('title', 'Bus Owner Dashboard')

@section('page-title', 'Dashboard Nhà xe')
@section('breadcrumb', 'Nhà xe')

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-bus"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Chuyến xe</span>
                <span class="info-box-number">{{ number_format($stats['total_trips']) }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-ticket-alt"></i></span>
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
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Doanh thu tháng</span>
                <span class="info-box-number">{{ number_format($stats['monthly_revenue']) }}đ</span>
            </div>
        </div>
    </div>
</div>

<!-- Additional Statistics Row -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>{{ number_format($stats['total_bookings']) }}</h3>
                <p>Tổng số vé đã bán</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>{{ number_format($stats['weekly_revenue']) }}đ</h3>
                <p>Doanh thu 7 ngày qua</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>{{ number_format($stats['total_customers']) }}</h3>
                <p>Tổng khách hàng</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>{{ number_format($stats['confirmed_bookings']) }}</h3>
                <p>Vé đã xác nhận</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Weekly Revenue Trend -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Xu hướng doanh thu 7 ngày qua</h3>
            </div>
            <div class="card-body">
                <canvas id="weeklyTrendChart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Doanh thu theo tháng</h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>

        <!-- Upcoming Trips -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chuyến xe sắp tới (7 ngày)</h3>
                <div class="card-tools">
                    <a href="{{ route('bus-owner.trips.index') }}" class="btn btn-sm btn-primary">
                        Xem tất cả
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Tuyến đường</th>
                            <th>Số vé đã đặt</th>
                            <th>Số chỗ còn</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcoming_trips as $trip)
                        <tr>
                            <td>{{ $trip->ngay_di ? \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>{{ $trip->gio_di ? \Carbon\Carbon::parse($trip->gio_di)->format('H:i') : 'N/A' }}</td>
                            <td>{{ $trip->ten_xe }}</td>
                            <td>
                                <span class="badge badge-info">{{ $trip->dat_ve_count }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge {{ ($trip->so_cho - $trip->dat_ve_count) > 10 ? 'badge-success' : (($trip->so_cho - $trip->dat_ve_count) > 0 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $trip->so_cho - $trip->dat_ve_count }}
                                </span>
                            </td>
                            <td>
                                @if($trip->loai_chuyen == 'Một chiều')
                                <span class="badge badge-success">Một chiều</span>
                                @else
                                <span class="badge badge-info">Khứ hồi</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có chuyến xe nào trong 7 ngày tới</td>
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
                <div class="card-tools">
                    <a href="{{ route('bus-owner.trips.index') }}" class="btn btn-sm btn-primary">
                        Quản lý chuyến xe
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Giờ khởi hành</th>
                            <th>Tuyến đường</th>
                            <th>Số vé còn</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($today_trips as $trip)
                        <tr>
                            <td>{{ $trip->gio_di ? \Carbon\Carbon::parse($trip->gio_di)->format('H:i') : 'N/A' }}</td>
                            <td>{{ $trip->ten_xe }}</td>
                            <td>
                                <span
                                    class="badge {{ ($trip->so_cho - $trip->so_ve) > 10 ? 'badge-success' : (($trip->so_cho - $trip->so_ve) > 0 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $trip->so_cho - $trip->so_ve }}
                                </span>
                            </td>
                            <td>
                                @if($trip->loai_chuyen == 'Một chiều')
                                <span class="badge badge-success">Một chiều</span>
                                @else
                                <span class="badge badge-info">Khứ hồi</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('bus-owner.trips.show', $trip) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
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

        <!-- Recent Bookings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Đặt vé gần đây</h3>
                <div class="card-tools">
                    <a href="{{ route('bus-owner.dat-ve.index') }}" class="btn btn-sm btn-primary">
                        Xem tất cả
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Ngày đặt</th>
                            <th>Khách hàng</th>
                            <th>Chuyến xe</th>
                            <th>Số lượng vé</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_bookings as $booking)
                        <tr>
                            <td>{{ $booking->ngay_dat ? \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>{{ $booking->user->name ?? 'N/A' }}</td>
                            <td>{{ $booking->chuyenXe->ten_xe ?? 'N/A' }}</td>
                            <td>{{ $booking->so_luong_ve ?? 1 }}</td>
                            <td>
                                @if($booking->trang_thai == 'Đã xác nhận')
                                <span class="badge badge-success">Đã xác nhận</span>
                                @elseif($booking->trang_thai == 'Đã đặt')
                                <span class="badge badge-warning">Đã đặt</span>
                                @elseif($booking->trang_thai == 'Đã hủy')
                                <span class="badge badge-danger">Đã hủy</span>
                                @else
                                <span class="badge badge-secondary">{{ $booking->trang_thai }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Chưa có đặt vé nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Company Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin nhà xe</h3>
            </div>
            <div class="card-body">
                @if($bus_company)
                <h4 class="text-primary">{{ $bus_company->name }}</h4>
                <p class="text-muted">{{ $bus_company->description ?? 'Chưa có mô tả' }}</p>
                <div class="d-flex justify-content-between mb-2">
                    <span>Số điện thoại:</span>
                    <span>{{ $bus_company->phone ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Email:</span>
                    <span>{{ $bus_company->email ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Địa chỉ:</span>
                    <span>{{ $bus_company->address ?? 'Chưa cập nhật' }}</span>
                </div>
                @else
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Bạn chưa được gán cho nhà xe nào. Vui lòng liên hệ admin để được hỗ trợ.
                </div>
                @endif
            </div>
        </div>

        <!-- Trip Performance -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hiệu suất tuyến đường</h3>
            </div>
            <div class="card-body">
                @forelse($trip_performance as $trip)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-0">{{ $trip->ten_xe }}</h6>
                        <small class="text-muted">{{ $trip->bookings_count }} vé đã đặt</small>
                    </div>
                    <span class="badge badge-success">{{ $trip->bookings_count }}</span>
                </div>
                @empty
                <p class="text-center text-muted">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thao tác nhanh</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('bus-owner.trips.create') }}" class="btn btn-primary btn-block">
                    <i class="fas fa-plus mr-2"></i> Thêm chuyến xe
                </a>
                <a href="{{ route('bus-owner.trips.index') }}" class="btn btn-success btn-block">
                    <i class="fas fa-bus mr-2"></i> Quản lý chuyến xe
                </a>
                <a href="{{ route('bus-owner.doanh-thu.index') }}" class="btn btn-warning btn-block">
                    <i class="fas fa-chart-line mr-2"></i> Xem doanh thu
                </a>
                <a href="{{ route('profile.edit') }}" class="btn btn-info btn-block">
                    <i class="fas fa-edit mr-2"></i> Cập nhật thông tin
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Weekly Trend Chart
        var weeklyCtx = document.getElementById('weeklyTrendChart').getContext('2d');
        var weeklyData = @json($weekly_trend);

        var weeklyLabels = weeklyData.map(item => {
            var date = new Date(item.date);
            return date.getDate() + '/' + (date.getMonth() + 1);
        });
        var weeklyRevenues = weeklyData.map(item => item.revenue);
        var weeklyBookings = weeklyData.map(item => item.bookings);

        var weeklyChart = new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: weeklyRevenues,
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    borderColor: 'rgb(23, 162, 184)',
                    borderWidth: 2,
                    fill: true,
                    yAxisID: 'y'
                }, {
                    label: 'Số vé đã bán',
                    data: weeklyBookings,
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderColor: 'rgb(40, 167, 69)',
                    borderWidth: 2,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return (value / 1000).toFixed(0) + 'K';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });

        // Revenue Chart
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var revenueData = @json($monthly_revenue);

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11',
                    'Th12'
                ],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: [
                        revenueData[1] || 0,
                        revenueData[2] || 0,
                        revenueData[3] || 0,
                        revenueData[4] || 0,
                        revenueData[5] || 0,
                        revenueData[6] || 0,
                        revenueData[7] || 0,
                        revenueData[8] || 0,
                        revenueData[9] || 0,
                        revenueData[10] || 0,
                        revenueData[11] || 0,
                        revenueData[12] || 0
                    ],
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: 'rgb(40, 167, 69)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush