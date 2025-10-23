@extends('layouts.admin')

@section('title', 'Staff Dashboard')

@section('page-title', 'Dashboard Nhân viên')
@section('breadcrumb', 'Nhân viên')

@section('content')
<!-- Statistics Cards Row -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ number_format($stats['today_bookings']) }}</h3>
                <p>Đặt vé hôm nay</p>
            </div>
            <div class="icon"><i class="fas fa-ticket-alt"></i></div>
            <a href="{{ route('staff.bookings.today') }}" class="small-box-footer">
                Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($stats['pending_bookings']) }}</h3>
                <p>Chờ xử lý</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="{{ route('staff.bookings.pending') }}" class="small-box-footer">
                Xử lý ngay <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($stats['confirmed_bookings']) }}</h3>
                <p>Đã xác nhận</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('staff.bookings.index') }}" class="small-box-footer">
                Xem tất cả <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($stats['today_trips']) }}</h3>
                <p>Chuyến hôm nay</p>
            </div>
            <div class="icon"><i class="fas fa-bus"></i></div>
            <a href="#" class="small-box-footer">
                Xem lịch trình <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Pending Bookings Table -->
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-clock text-warning"></i> Đặt vé cần xử lý
                </h3>
                <div class="card-tools">
                    <a href="{{ route('staff.bookings.pending') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list"></i> Xem tất cả
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
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
                            <td><code>{{ $booking->ma_ve ?? $booking->id }}</code></td>
                            <td>
                                <strong>{{ $booking->user->fullname ?? $booking->user->username }}</strong><br>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            </td>
                            <td>
                                {{ $booking->chuyenXe->ten_xe ?? 'N/A' }}<br>
                                <small class="text-muted">
                                    <i class="far fa-calendar"></i> {{ $booking->chuyenXe->ngay_di ? \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') : '' }}
                                </small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') }}</td>
                            <td><strong class="text-success">{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</strong></td>
                            <td>
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <p class="text-muted mb-0">Không có đặt vé nào cần xử lý</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Today's Trips -->
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-bus text-info"></i> Chuyến xe hôm nay
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Giờ khởi hành</th>
                            <th>Tuyến đường</th>
                            <th>Nhà xe</th>
                            <th>Số vé còn</th>
                            <th>Loại</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($today_trips as $trip)
                        <tr>
                            <td>
                                <strong class="text-primary">
                                    {{ $trip->gio_di ? \Carbon\Carbon::parse($trip->gio_di)->format('H:i') : 'N/A' }}
                                </strong>
                            </td>
                            <td>{{ $trip->ten_xe }}</td>
                            <td>{{ $trip->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                            <td>
                                @php
                                $remaining = $trip->so_cho - $trip->so_ve;
                                @endphp
                                <span class="badge {{ $remaining > 10 ? 'badge-success' : ($remaining > 0 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $remaining }} / {{ $trip->so_cho }}
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
                            <td colspan="5" class="text-center py-3">
                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Không có chuyến xe nào hôm nay</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i> Thao tác nhanh
                </h3>
            </div>
            <div class="card-body">
                <a href="{{ route('staff.bookings.index') }}" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-ticket-alt mr-2"></i> Quản lý đặt vé
                </a>
                <a href="{{ route('staff.bookings.today') }}" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-calendar-day mr-2"></i> Đặt vé hôm nay
                </a>
                <a href="{{ route('staff.bookings.pending') }}" class="btn btn-warning btn-block mb-2">
                    <i class="fas fa-clock mr-2"></i> Vé chờ xử lý
                </a>
                <a href="{{ route('staff.comments.index') }}" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-comments mr-2"></i> Quản lý bình luận
                </a>
                <a href="{{ route('staff.news.index') }}" class="btn btn-secondary btn-block mb-2">
                    <i class="fas fa-newspaper mr-2"></i> Quản lý tin tức
                </a>
            </div>
        </div>

        <!-- Booking Trend Chart -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i> Xu hướng đặt vé (30 ngày)
                </h3>
            </div>
            <div class="card-body">
                <canvas id="bookingTrendChart" style="min-height: 200px; height: 200px; max-height: 200px;"></canvas>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i> Thống kê tổng quan
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Đặt vé tháng
                        <span class="badge badge-info badge-pill">{{ number_format($stats['monthly_bookings']) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Đã hủy
                        <span class="badge badge-danger badge-pill">{{ number_format($stats['cancelled_bookings']) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Tổng khách hàng
                        <span class="badge badge-success badge-pill">{{ number_format($stats['total_customers']) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Bình luận chờ duyệt
                        <span class="badge badge-warning badge-pill">{{ \App\Models\BinhLuan::where('trang_thai', 'cho_duyet')->count() }}</span>
                    </li>
                </ul>
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