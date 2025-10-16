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

<div class="row">
    <div class="col-md-8">
        <!-- Monthly Revenue Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Doanh thu theo tháng</h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                            <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $trip->gio_di)->format('H:i') }}</td>
                            <td>{{ $trip->route_name }}</td>
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
    </div>

    <div class="col-md-4">
        <!-- Company Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin nhà xe</h3>
            </div>
            <div class="card-body">
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
                        <h6 class="mb-0">{{ $trip->route_name }}</h6>
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
                <a href="{{ route('bus-owner.revenue.index') }}" class="btn btn-warning btn-block">
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
    // Revenue Chart
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var revenueData = @json($monthly_revenue);

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
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
