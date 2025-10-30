@extends('layouts.admin')

@section('title', 'Bus Owner Dashboard')

@section('page-title', 'Dashboard Nhà xe')
@section('breadcrumb', 'Nhà xe')

@section('content')

<!-- Thông tin nhà xe -->
@if($bus_company)
<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-building fa-4x opacity-75"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="mb-1 font-weight-bold">{{ $bus_company->ten_nha_xe }}</h2>
                        <p class="mb-1"><i class="fas fa-envelope mr-2"></i>{{ $bus_company->email }}</p>
                        <p class="mb-0"><i class="fas fa-phone mr-2"></i>{{ $bus_company->so_dien_thoai }}</p>
                    </div>
                    <div class="text-right">
                        <h4 class="mb-0">Mã nhà xe: <span class="badge badge-light">{{ $bus_company->ma_nha_xe }}</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Top metric cards - Hàng 1: 6 Module Quản Lý Chính -->
<div class="row">
    <!-- 1. Tổng chuyến xe -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_trips']) }}</h2>
                        <p class="mb-0">Tổng chuyến xe</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-bus"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('bus-owner.trips.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Chuyến xe hôm nay -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['today_trips']) }}</h2>
                        <p class="mb-0">Chuyến hôm nay</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-calendar-day"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <small class="text-white">{{ date('d/m/Y') }}</small>
            </div>
        </div>
    </div>

    <!-- 3. Tổng đặt vé -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_bookings']) }}</h2>
                        <p class="mb-0">Tổng vé đã bán</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-ticket-alt"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('bus-owner.dat-ve.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Vé hôm nay -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['today_bookings']) }}</h2>
                        <p class="mb-0">Vé hôm nay</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-calendar-check"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <small class="text-white">{{ date('d/m/Y') }}</small>
            </div>
        </div>
    </div>

    <!-- 5. Vé chờ xử lý -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['pending_bookings']) }}</h2>
                        <p class="mb-0 text-dark">Chờ xử lý</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-clock"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('bus-owner.dat-ve.index') }}" class="text-dark text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 6. Vé đã xác nhận -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['confirmed_bookings']) }}
                        </h2>
                        <p class="mb-0 text-dark">Đã xác nhận</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('bus-owner.dat-ve.index') }}" class="text-dark text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Hàng 2: 4 Thống Kê Vận Chuyển -->
<div class="row mt-3">
    <!-- 1. Tổng trạm xe -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['total_stations']) }}</h2>
                        <p class="mb-0 text-dark">Tổng trạm xe</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-map-marker-alt"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('bus-owner.tram-xe.index') }}"
                    class="text-dark text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Tổng tuyến đường -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['total_routes']) }}</h2>
                        <p class="mb-0 text-dark">Tổng tuyến đường</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-route"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('bus-owner.trips.index') }}" class="text-dark text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Tổng khách hàng -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_customers']) }}</h2>
                        <p class="mb-0">Tổng khách hàng</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-users"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <small class="text-white">Khách đã đặt vé</small>
            </div>
        </div>
    </div>

    <!-- 4. Tỷ lệ lấp đầy -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['occupancy_rate'], 1) }}%</h2>
                        <p class="mb-0">Tỷ lệ lấp đầy</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-chart-pie"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <small class="text-white">Tỷ lệ ghế đã đặt</small>
            </div>
        </div>
    </div>
</div>

<!-- Hàng 3: Doanh thu & Tăng trưởng -->
<div class="row mt-3">
    <!-- Doanh thu tháng này -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['monthly_revenue'] / 1000000, 1) }}M
                        </h2>
                        <p class="mb-0">Doanh thu tháng {{ date('m') }}</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-dollar-sign"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('bus-owner.doanh-thu.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Doanh thu tuần -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">
                            {{ number_format($stats['weekly_revenue'] / 1000, 0) }}K</h2>
                        <p class="mb-0 text-dark">Doanh thu 7 ngày</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <small class="text-dark font-weight-bold">7 ngày qua</small>
            </div>
        </div>
    </div>

    <!-- Tỷ lệ tăng trưởng -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">
                            @if($stats['growth_rate'] > 0)
                            <i class="fas fa-arrow-up text-success"></i>
                            @elseif($stats['growth_rate'] < 0) <i class="fas fa-arrow-down text-danger"></i>
                                @endif
                                {{ number_format(abs($stats['growth_rate']), 1) }}%
                        </h2>
                        <p class="mb-0 text-dark">Tăng trưởng</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-chart-area"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <small class="text-dark font-weight-bold">So với tháng trước</small>
            </div>
        </div>
    </div>

    <!-- Giá vé trung bình -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">
                            {{ number_format($stats['average_booking_value'], 0) }}</h2>
                        <p class="mb-0 text-dark">Giá vé TB</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-receipt"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <small class="text-dark font-weight-bold">Giá vé trung bình</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
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
                            <td>{{ $trip->tramDi->ten_tram ?? 'N/A' }} → {{ $trip->tramDen->ten_tram ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $trip->dat_ve_count ?? 0 }}</span>
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
                            <td>{{ $booking->user->fullname ?? 'N/A' }}</td>
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
                <h4 class="text-primary">{{ $bus_company->ten_nha_xe }}</h4>
                <p class="text-muted">{{ $bus_company->mo_ta ?? 'Chưa có mô tả' }}</p>
                <div class="d-flex justify-content-between mb-2">
                    <span>Số điện thoại:</span>
                    <span>{{ $bus_company->so_dien_thoai ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Email:</span>
                    <span>{{ $bus_company->email ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Địa chỉ:</span>
                    <span>{{ $bus_company->dia_chi ?? 'Chưa cập nhật' }}</span>
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
                <h3 class="card-title">Top 5 Tuyến đường</h3>
            </div>
            <div class="card-body">
                @forelse($trip_performance as $trip)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <h6 class="mb-0">{{ $trip->ten_xe }}</h6>
                        <small class="text-muted">{{ $trip->bookings_count }} vé đã đặt - Tỷ lệ lấp đầy:
                            {{ $trip->occupancy_rate ?? 0 }}%</small>
                    </div>
                    <span class="badge badge-success">{{ $trip->bookings_count }}</span>
                </div>
                @empty
                <p class="text-center text-muted">Chưa có dữ liệu tuyến đường</p>
                @endforelse
            </div>
        </div>

        <!-- Top Routes Revenue -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top 5 Tuyến Doanh Thu</h3>
            </div>
            <div class="card-body">
                @forelse($top_routes as $route)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <h6 class="mb-0">{{ $route->ten_xe }}</h6>
                        <small class="text-muted">{{ $route->bookings_count }} vé -
                            {{ number_format($route->total_revenue, 0) }}đ</small>
                    </div>
                    <span class="badge badge-info">{{ number_format($route->total_revenue / 1000, 0) }}K</span>
                </div>
                @empty
                <p class="text-center text-muted">Chưa có dữ liệu doanh thu</p>
                @endforelse
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
    var weeklyData = @json($weekly_data);

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