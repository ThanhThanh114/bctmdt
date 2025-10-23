@extends('layouts.admin')

@section('title', 'User Dashboard')

@section('page-title', 'Dashboard Người dùng')
@section('breadcrumb', 'Người dùng')

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-ticket-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tổng vé đã đặt</span>
                <span class="info-box-number">{{ number_format($stats['total_bookings']) }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Vé đã xác nhận</span>
                <span class="info-box-number">{{ number_format($stats['confirmed_bookings']) }}</span>
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
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tổng chi tiêu</span>
                <span class="info-box-number">{{ number_format($stats['total_spent']) }}đ</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Upcoming Trips -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chuyến đi sắp tới</h3>
                <div class="card-tools">
                    <a href="{{ route('user.bookings.index') }}" class="btn btn-sm btn-primary">
                        Xem tất cả vé
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Ngày giờ</th>
                            <th>Tuyến đường</th>
                            <th>Nhà xe</th>
                            <th>Số ghế</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcoming_trips as $booking)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</td>
                            <td>{{ $booking->chuyenXe->route_name }}</td>
                            <td>{{ $booking->chuyenXe->nhaXe->name ?? 'N/A' }}</td>
                            <td>{{ $booking->seat_number }}</td>
                            <td>{{ number_format($booking->total_price) }}đ</td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <span class="badge badge-success">Đã xác nhận</span>
                                @elseif($booking->status == 'pending')
                                    <span class="badge badge-warning">Chờ xử lý</span>
                                @else
                                    <span class="badge badge-danger">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Chưa có chuyến đi nào sắp tới</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lịch sử đặt vé</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Mã vé</th>
                            <th>Ngày giờ</th>
                            <th>Tuyến đường</th>
                            <th>Số ghế</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</td>
                            <td>{{ $booking->chuyenXe->route_name }}</td>
                            <td>{{ $booking->seat_number }}</td>
                            <td>{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <span class="badge badge-success">Đã xác nhận</span>
                                @elseif($booking->status == 'pending')
                                    <span class="badge badge-warning">Chờ xử lý</span>
                                @else
                                    <span class="badge badge-danger">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Chưa có lịch sử đặt vé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- User Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin cá nhân</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-circle fa-4x text-primary"></i>
                </div>
                <h5 class="text-center">{{ $user->fullname ?? $user->username }}</h5>
                <p class="text-center text-muted">{{ $user->email }}</p>
                <p class="text-center">
                    <span class="badge badge-{{ $user->role === 'User' ? 'info' : 'success' }} badge-lg">
                        {{ $user->role }}
                    </span>
                </p>

                <div class="d-flex justify-content-between mb-2">
                    <span>Số điện thoại:</span>
                    <span>{{ $user->phone ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Ngày sinh:</span>
                    <span>{{ $user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Địa chỉ:</span>
                    <span>{{ $user->address ?? 'Chưa cập nhật' }}</span>
                </div>
            </div>
        </div>

        <!-- Nâng cấp lên Nhà xe -->
        @if($user->isUser())
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-star mr-2"></i>Nâng cấp tài khoản</h3>
            </div>
            <div class="card-body">
                @if($user->hasPendingUpgradeRequest())
                    @php $activeRequest = $user->getActiveUpgradeRequest(); @endphp
                    <div class="alert alert-info mb-3">
                        <h5><i class="fas fa-info-circle mr-2"></i>Yêu cầu đang xử lý</h5>
                        <p class="mb-2"><strong>Trạng thái:</strong> 
                            <span class="badge {{ $activeRequest->getStatusBadgeClass() }}">
                                {{ $activeRequest->getStatusLabel() }}
                            </span>
                        </p>
                        <p class="mb-0"><strong>Ngày tạo:</strong> {{ $activeRequest->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <a href="{{ route('user.upgrade.index') }}" class="btn btn-info btn-block">
                        <i class="fas fa-eye mr-2"></i>Xem chi tiết
                    </a>
                @else
                    <h5 class="text-primary">Trở thành Nhà xe!</h5>
                    <p class="text-muted">Nâng cấp để quản lý chuyến xe, nhân viên và nhiều tính năng khác.</p>
                    <ul class="pl-3 mb-3">
                        <li>Quản lý nhà xe</li>
                        <li>Thêm/sửa chuyến xe</li>
                        <li>Quản lý nhân viên</li>
                        <li>Báo cáo thống kê</li>
                    </ul>
                    <div class="text-center mb-3">
                        <h3 class="text-success mb-0">MIỄN PHÍ</h3>
                        <small class="text-muted">Nâng cấp hoàn toàn miễn phí</small>
                    </div>
                    <a href="{{ route('user.upgrade.index') }}" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-rocket mr-2"></i>Nâng cấp ngay
                    </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Popular Routes -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tuyến đường phổ biến</h3>
            </div>
            <div class="card-body">
                @forelse($popular_routes as $route)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-0">{{ $route->route_name }}</h6>
                        <small class="text-muted">{{ $route->trip_count }} chuyến</small>
                    </div>
                    <a href="{{ route('trips.search', ['route' => $route->route_name]) }}" class="btn btn-sm btn-primary">
                        Đặt vé
                    </a>
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
                <a href="{{ route('trips.trips') }}" class="btn btn-primary btn-block">
                    <i class="fas fa-search mr-2"></i> Tìm chuyến xe
                </a>
                <a href="{{ route('user.bookings.index') }}" class="btn btn-success btn-block">
                    <i class="fas fa-ticket-alt mr-2"></i> Vé của tôi
                </a>
                <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-block">
                    <i class="fas fa-user mr-2"></i> Cập nhật thông tin
                </a>
                <a href="{{ route('tracking.tracking') }}" class="btn btn-info btn-block">
                    <i class="fas fa-map-marker-alt mr-2"></i> Theo dõi chuyến
                </a>
            </div>
        </div>

        <!-- Monthly Spending -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chi tiêu năm nay</h3>
            </div>
            <div class="card-body">
                <canvas id="spendingChart" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Spending Chart
    var ctx = document.getElementById('spendingChart').getContext('2d');
    var spendingData = @json($monthly_spending);

    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
            datasets: [{
                data: [
                    spendingData[1] || 0,
                    spendingData[2] || 0,
                    spendingData[3] || 0,
                    spendingData[4] || 0,
                    spendingData[5] || 0,
                    spendingData[6] || 0,
                    spendingData[7] || 0,
                    spendingData[8] || 0,
                    spendingData[9] || 0,
                    spendingData[10] || 0,
                    spendingData[11] || 0,
                    spendingData[12] || 0
                ],
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545',
                    '#17a2b8', '#6610f2', '#fd7e14', '#20c997',
                    '#e83e8c', '#6f42c1', '#dee2e6', '#495057'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
