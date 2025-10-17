@extends('layouts.admin')
@section('content')

<!-- hidden data holders for charts -->
<div id="monthlyBookingsData" data-json="{{ json_encode($monthly_bookings ?? []) }}" style="display:none;"></div>
<div id="dailyRevenue7Data" data-json="{{ json_encode($daily_revenue_7 ?? []) }}" style="display:none;"></div>
<div id="dailyRevenue30Data" data-json="{{ json_encode($daily_revenue_30 ?? []) }}" style="display:none;"></div>
<div id="monthlyRevenue12Data" data-json="{{ json_encode($monthly_revenue_12 ?? []) }}" style="display:none;"></div>
<div id="yearlyRevenueData" data-json="{{ json_encode($yearly_revenue ?? []) }}" style="display:none;"></div>
<div id="topRoutesData" data-json="{{ json_encode($top_routes ?? []) }}" style="display:none;"></div>
<div id="topCustomersData" data-json="{{ json_encode($top_customers ?? []) }}" style="display:none;"></div>

@if(config('app.debug'))
<!-- Debug info -->
<div class="alert alert-info">
    <strong>Debug Info:</strong>
    <ul class="mb-0">
        <li>Bookings 7 days: {{ count($daily_revenue_7 ?? []) }} days, Total:
            {{ number_format(array_sum($daily_revenue_7 ?? [])) }} VNĐ
        </li>
        <li>Bookings 30 days: {{ count($daily_revenue_30 ?? []) }} days, Total:
            {{ number_format(array_sum($daily_revenue_30 ?? [])) }} VNĐ
        </li>
        <li>Monthly 12: {{ count($monthly_revenue_12 ?? []) }} months, Total:
            {{ number_format(array_sum($monthly_revenue_12 ?? [])) }} VNĐ
        </li>
        <li>Yearly: {{ count($yearly_revenue ?? []) }} years, Total:
            {{ number_format(array_sum($yearly_revenue ?? [])) }} VNĐ
        </li>
    </ul>
</div>
@endif

<!-- Top metric cards - 6 Module Quản Lý Chính -->
<div class="row">
    <!-- 1. Quản lý Khách hàng -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_users'] ?? 0) }}</h2>
                        <p class="mb-0">Tổng User</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-users"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Quản lý Nhân viên -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_employees'] ?? 0) }}</h2>
                        <p class="mb-0">Tổng nhân viên</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-user-tie"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.nhanvien.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Quản lý Đặt vé -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_bookings'] ?? 0) }}</h2>
                        <p class="mb-0">Tổng vé đã bán</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-ticket-alt"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.datve.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Quản lý Bình luận -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_comments'] ?? 0) }}</h2>
                        <p class="mb-0">Tổng bình luận</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-comments"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.binhluan.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 5. Quản lý Khuyến mãi -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['total_promotions'] ?? 0) }}
                        </h2>
                        <p class="mb-0 text-dark">Tổng khuyến mãi</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-tags"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.khuyenmai.index') }}" class="text-dark text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 6. Quản lý Tin tức -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_news'] ?? 0) }}</h2>
                        <p class="mb-0">Tổng tin tức</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-newspaper"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.tintuc.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê vận chuyển - 4 Card cùng hàng -->
<div class="row mt-3">
    <!-- 1. Tổng chuyến xe -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_trips'] ?? 0) }}</h2>
                        <p class="mb-0">Tổng chuyến xe</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-bus"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="#" class="text-white text-decoration-none" title="Chức năng đang phát triển">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Tổng nhà xe -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #ff6e7f 0%, #bfe9ff 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_bus_companies'] ?? 0) }}</h2>
                        <p class="mb-0">Tổng nhà xe</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-building"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="#" class="text-white text-decoration-none" title="Chức năng đang phát triển">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Tổng trạm xe -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ \App\Models\TramXe::count() }}</h2>
                        <p class="mb-0 text-dark">Tổng trạm xe</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-map-marker-alt"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="#" class="text-dark text-decoration-none font-weight-bold" title="Chức năng đang phát triển">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Tổng tuyến đường -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @php
                        $totalRoutes = \App\Models\ChuyenXe::select('diem_di', 'diem_den')
                        ->distinct()
                        ->count();
                        @endphp
                        <h2 class="mb-0 font-weight-bold text-dark">{{ $totalRoutes }}</h2>
                        <p class="mb-0 text-dark">Tổng tuyến đường</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-route"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="#" class="text-dark text-decoration-none font-weight-bold" title="Chức năng đang phát triển">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Module Quản lý khác -->
<div class="row mt-3">
    <!-- Quản lý Liên hệ -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['total_contacts'] ?? 0) }}
                        </h2>
                        <p class="mb-0 text-dark">Tổng liên hệ</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-envelope"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.contact.index') }}" class="text-dark text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quản lý Báo cáo -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-dark"><i class="fas fa-chart-line"></i></h2>
                        <p class="mb-0 text-dark">Báo cáo thống kê</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-chart-bar"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.report.index') }}" class="text-dark text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Khuyến mãi đang áp dụng -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold">{{ $stats['active_promotions'] ?? 0 }}</h2>
                        <p class="mb-0">KM đang áp dụng</p>
                    </div>
                    <div class="display-4 opacity-75"><i class="fas fa-gift"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.khuyenmai.index') }}" class="text-white text-decoration-none">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Người dùng mới tháng này -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @php
                        $newUsersThisMonth = \App\Models\User::whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'))
                        ->count();
                        @endphp
                        <h2 class="mb-0 font-weight-bold text-dark">{{ $newUsersThisMonth }}</h2>
                        <p class="mb-0 text-dark">Người dùng mới</p>
                    </div>
                    <div class="display-4 opacity-75 text-dark"><i class="fas fa-user-plus"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.users.index') }}" class="text-dark text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Revenue bars: Today and This Month -->
<div class="row mt-4">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center text-white">
                    <i class="fas fa-calendar-day fa-3x mr-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">Doanh thu hôm nay</h5>
                        @php
                        $todayRev = array_values($daily_revenue_7 ?? [])[count($daily_revenue_7 ?? [])-1] ?? 0;
                        @endphp
                        <h2 class="mb-0">{{ number_format($todayRev) }} đ</h2>
                        <div class="progress mt-2" style="height:8px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: 70%;"></div>
                        </div>
                        <small>{{ date('d/m/Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex align-items-center text-white">
                    <i class="fas fa-calendar-alt fa-3x mr-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">Doanh thu tháng này</h5>
                        <h2 class="mb-0">{{ number_format($stats['monthly_revenue'] ?? 0) }} đ</h2>
                        <div class="progress mt-2" style="height:8px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: 85%;"></div>
                        </div>
                        <small>Tháng {{ date('m/Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top lists: Bus companies and routes -->
<div class="row mt-3">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white"><i class="fas fa-building mr-2"></i>Top 5 Nhà xe có nhiều chuyến
                nhất</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>STT</th>
                            <th>TÊN NHÀ XE</th>
                            <th>SỐ ĐIỆN THOẠI</th>
                            <th class="text-right">SỐ CHUYẾN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bus_company_stats ?? [] as $idx => $company)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td><strong>{{ $company->ten_nha_xe ?? 'N/A' }}</strong></td>
                            <td>{{ $company->so_dien_thoai ?? 'N/A' }}</td>
                            <td class="text-right"><span
                                    class="badge badge-primary">{{ $company->chuyen_xe_count ?? 0 }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white"><i class="fas fa-route mr-2"></i>Top 5 Tuyến phổ biến</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>STT</th>
                            <th>TUYẾN ĐƯỜNG</th>
                            <th class="text-right">SỐ CHUYẾN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $routeIdx = 0; @endphp
                        @forelse(($top_routes ?? collect())->take(5) as $route => $count)
                        @php $routeIdx++; @endphp
                        <tr>
                            <td>{{ $routeIdx }}</td>
                            <td><strong>{{ $route }}</strong></td>
                            <td class="text-right"><span class="badge badge-success">{{ $count }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent users and Top customers -->
<div class="row mt-3">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white"><i class="fas fa-user-plus mr-2"></i>Khách hàng mới đăng ký
            </div>
            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>TÊN</th>
                            <th>EMAIL</th>
                            <th>SỐ ĐIỆN THOẠI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recent_users ?? [])->take(10) as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->fullname ?? ($u->username ?? 'N/A') }}</td>
                            <td>{{ $u->email ?? 'N/A' }}</td>
                            <td>{{ $u->phone ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark"><i class="fas fa-crown mr-2"></i>Top 10 khách hàng đặt vé
                nhiều nhất</div>
            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>TÊN KHÁCH HÀNG</th>
                            <th>EMAIL</th>
                            <th>SỐ ĐIỆN THOẠI</th>
                            <th class="text-right">SỐ LƯỢNG VÉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($top_customers ?? [])->take(10) as $tc)
                        @php $usr = $tc['user'] ?? null; @endphp
                        <tr>
                            <td><span class="badge badge-pill"
                                    style="background: linear-gradient(135deg, #667eea, #764ba2); color: #fff;">{{ $loop->iteration }}</span>
                            </td>
                            <td>{{ optional($usr)->fullname ?? optional($usr)->username ?? 'N/A' }}</td>
                            <td>{{ optional($usr)->email ?? 'Chưa cập nhật' }}</td>
                            <td>{{ optional($usr)->phone ?? 'Chưa cập nhật' }}</td>
                            <td class="text-right"><span class="badge badge-success"><i
                                        class="fas fa-ticket-alt mr-1"></i>{{ $tc['tickets'] ?? 0 }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Map section (bus companies location) -->
<div class="row mt-3">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-map-marked-alt mr-2"></i>Vị trí các nhà xe trên bản đồ</span>
                <div>
                    <button class="btn btn-sm btn-light"><i class="fas fa-search-location mr-1"></i>Tổng:
                        {{ $stats['total_bus_companies'] ?? 15 }} nhà xe</button>
                    <button class="btn btn-sm btn-info ml-2"><i class="fas fa-search-plus mr-1"></i>Zoom để xem chi
                        tiết</button>
                </div>
            </div>
            <div class="card-body p-0" style="height: 400px; overflow: hidden; position: relative;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125423.2745799879!2d106.57958515!3d10.823098950000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175284f2ce0866d%3A0x2a4c4b3d34c0f58d!2zSG8gQ2hpIE1pbmggQ2l0eSwgVmlldG5hbQ!5e0!3m2!1sen!2s!4v1634234567890!5m2!1sen!2s"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                <div
                    style="position: absolute; bottom: 10px; left: 10px; background: rgba(255,255,255,0.9); padding: 8px 12px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <small><strong>Hiện thị vị trí 15 nhà xe đối tác</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent trips table -->
<div class="row mt-3">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white"><i class="fas fa-bus mr-2"></i>Chuyến xe gần đây</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>TÊN XE</th>
                            <th>NHÀ XE</th>
                            <th>TUYẾN</th>
                            <th>NGÀY ĐI</th>
                            <th>GIỜ ĐI</th>
                            <th>GIÁ VÉ</th>
                            <th>CÒN LẠI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recent_trips ?? [])->take(10) as $trip)
                        @php
                        $from = optional($trip->tramDi)->ten_tram ?? optional($trip->tramDi)->dia_chi ?? 'N/A';
                        $to = optional($trip->tramDen)->ten_tram ?? optional($trip->tramDen)->dia_chi ?? 'N/A';
                        @endphp
                        <tr>
                            <td>{{ $trip->id }}</td>
                            <td><strong>{{ $trip->ten_xe }}</strong></td>
                            <td>{{ optional($trip->nhaXe)->ten_nha_xe ?? 'N/A' }}</td>
                            <td>{{ $from }} <i class="fas fa-arrow-right mx-1"></i> {{ $to }}</td>
                            <td>{{ $trip->ngay_di ? \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>{{ $trip->gio_di ? \Carbon\Carbon::parse($trip->gio_di)->format('H:i') : 'N/A' }}</td>
                            <td><strong>{{ number_format($trip->gia_ve ?? 0) }} đ</strong></td>
                            <td><span
                                    class="badge badge-success">{{ ($trip->so_cho ?? 0) - ($trip->so_ve ?? 0) }}/{{ $trip->so_cho ?? 0 }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Chưa có chuyến xe</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent bookings table - moved to the end -->
<div class="row mt-3">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white"><i class="fas fa-ticket-alt mr-2"></i>Vé đặt gần đây</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>KHÁCH HÀNG</th>
                            <th>CHUYẾN XE</th>
                            <th>SỐ GHẾ</th>
                            <th>NGÀY ĐẶT</th>
                            <th>TỔNG TIỀN</th>
                            <th>TRẠNG THÁI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_bookings ?? [] as $booking)
                        @php
                        // Get price
                        $rawPrice = optional($booking->chuyenXe)->gia_ve ?? 0;
                        $cleanPrice = preg_replace('/[^0-9\.]/', '', (string)$rawPrice);
                        $price = $cleanPrice === '' ? 0.0 : (float)$cleanPrice;

                        // Count seats from string like "A01, A02,A03"
                        $rawSeats = $booking->so_ghe ?? '';
                        if(empty($rawSeats)) {
                        $seatCount = 0;
                        $seatDisplay = 'N/A';
                        } else {
                        $seatArray = array_filter(array_map('trim', explode(',', (string)$rawSeats)));
                        $seatCount = count($seatArray);
                        $seatDisplay = $rawSeats; // Show original string
                        }

                        // Calculate total
                        $total = $price * $seatCount;
                        @endphp
                        <tr>
                            <td>{{ $booking->ma_ve ?? $booking->id }}</td>
                            <td>
                                <strong>{{ optional($booking->user)->full_name ?? optional($booking->user)->username ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ optional($booking->user)->email ?? '' }}</small>
                            </td>
                            <td>
                                <strong>{{ optional($booking->chuyenXe)->ten_xe ?? 'N/A' }}</strong><br>
                                <small class="text-muted">
                                    {{ optional(optional($booking->chuyenXe)->tramDi)->ten_tram ?? 'N/A' }}
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    {{ optional(optional($booking->chuyenXe)->tramDen)->ten_tram ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                @if($seatCount > 0)
                                <span class="badge badge-primary badge-pill"
                                    title="{{ $seatDisplay }}">{{ $seatCount }}</span>
                                @else
                                <span class="badge badge-secondary badge-pill">0</span>
                                @endif
                            </td>
                            <td>{{ $booking->ngay_dat ? \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                @if($total > 0)
                                <strong class="text-success">{{ number_format($total, 0, ',', '.') }} đ</strong>
                                @else
                                <strong class="text-muted">0 đ</strong>
                                @endif
                            </td>
                            <td>
                                @if($booking->trang_thai == 'Đã thanh toán')
                                <span class="badge badge-success">Đã thanh toán</span>
                                @elseif($booking->trang_thai == 'Đã đặt')
                                <span class="badge badge-warning">Đã đặt</span>
                                @else
                                <span class="badge badge-danger">{{ $booking->trang_thai ?? 'Đã hủy' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                <p class="text-muted mb-0">Chưa có vé đặt nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    console.log('Dashboard charts initializing...');

    // Helper to parse JSON data
    function parseChartData(elId) {
        var el = document.getElementById(elId);
        if (!el) {
            console.warn('Element not found:', elId);
            return {};
        }
        try {
            var data = JSON.parse(el.getAttribute('data-json') || '{}');
            console.log('Parsed data for', elId, ':', data);
            return data;
        } catch (e) {
            console.error('Error parsing data for', elId, ':', e);
            return {};
        }
    }

    // Debug: Log all chart data
    console.log('Monthly Bookings:', parseChartData('monthlyBookingsData'));
    console.log('Daily Revenue 7:', parseChartData('dailyRevenue7Data'));
    console.log('Daily Revenue 30:', parseChartData('dailyRevenue30Data'));
    console.log('Monthly Revenue 12:', parseChartData('monthlyRevenue12Data'));
    console.log('Yearly Revenue:', parseChartData('yearlyRevenueData'));

    // 7-day revenue chart
    var daily7 = parseChartData('dailyRevenue7Data');
    console.log('7-day data keys:', Object.keys(daily7).length);
    if (document.getElementById('chart7Day')) {
        if (Object.keys(daily7).length > 0) {
            console.log('Creating 7-day chart...');
            var labels7 = Object.keys(daily7);
            var data7 = labels7.map(function(k) {
                return daily7[k] || 0;
            });
            new Chart(document.getElementById('chart7Day').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels7,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: data7,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3,
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
                    animation: {
                        duration: 1200
                    }
                }
            });
        } else {
            console.warn('7-day chart: No data available');
        }
    } else {
        console.error('7-day chart: Canvas element not found');
    }

    // 30-day revenue chart
    var daily30 = parseChartData('dailyRevenue30Data');
    console.log('30-day data keys:', Object.keys(daily30).length);
    if (document.getElementById('chart30Day')) {
        if (Object.keys(daily30).length > 0) {
            console.log('Creating 30-day chart...');
            var labels30 = Object.keys(daily30);
            var data30 = labels30.map(function(k) {
                return daily30[k] || 0;
            });
            new Chart(document.getElementById('chart30Day').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels30,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: data30,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1
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
                    animation: {
                        duration: 1200
                    }
                }
            });
        } else {
            console.warn('30-day chart: No data available');
        }
    } else {
        console.error('30-day chart: Canvas element not found');
    }

    // 12-month revenue chart
    var monthly12 = parseChartData('monthlyRevenue12Data');
    console.log('12-month data keys:', Object.keys(monthly12).length);
    if (document.getElementById('chart12Month')) {
        if (Object.keys(monthly12).length > 0) {
            console.log('Creating 12-month chart...');
            var labelsM = Object.keys(monthly12);
            var dataM = labelsM.map(function(k) {
                return monthly12[k] || 0;
            });
            new Chart(document.getElementById('chart12Month').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsM,
                    datasets: [{
                        label: 'Doanh thu theo tháng (VNĐ)',
                        data: dataM,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderColor: 'rgb(255, 159, 64)',
                        borderWidth: 1
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
                    animation: {
                        duration: 1200
                    }
                }
            });
        } else {
            console.warn('12-month chart: No data available');
        }
    } else {
        console.error('12-month chart: Canvas element not found');
    }

    // Yearly revenue pie
    var yearly = parseChartData('yearlyRevenueData');
    console.log('Yearly data keys:', Object.keys(yearly).length);
    if (document.getElementById('chartYearly')) {
        if (Object.keys(yearly).length > 0) {
            console.log('Creating yearly chart...');
            var labelsY = Object.keys(yearly);
            var dataY = labelsY.map(function(k) {
                return yearly[k] || 0;
            });
            new Chart(document.getElementById('chartYearly').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: labelsY,
                    datasets: [{
                        data: dataY,
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff',
                            '#ff9f40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1500
                    }
                }
            });
        } else {
            console.warn('Yearly chart: No data available');
        }
    } else {
        console.error('Yearly chart: Canvas element not found');
    }

    // Fade-in animation for cards
    $('.card').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        }).delay(index * 50).animate({
            'opacity': '1'
        }, {
            duration: 600,
            step: function(now) {
                $(this).css('transform', 'translateY(' + (20 - now * 20) + 'px)');
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced dashboard styles with animations */
.card {
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.badge {
    font-weight: 600;
    padding: 4px 8px;
}

.info-box .info-box-icon {
    box-shadow: none;
}

/* Gradient backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
}

/* Progress bar animations */
.progress-bar {
    animation: progressAnimation 1.5s ease-in-out;
}

@keyframes progressAnimation {
    from {
        width: 0;
    }
}

/* Table hover effects */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    cursor: pointer;
    transition: background-color 0.2s ease;
}

/* Card header improvements */
.card-header {
    font-weight: 600;
    border-bottom: 2px solid rgba(0, 0, 0, 0.05);
}

/* Smooth scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Pulse animation for badges */
@keyframes pulse {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.05);
    }
}

.badge-success,
.badge-primary,
.badge-warning,
.badge-danger {
    animation: pulse 2s infinite;
}

/* Card fade-in on load */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.row>div {
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
}

.row>div:nth-child(1) {
    animation-delay: 0.1s;
}

.row>div:nth-child(2) {
    animation-delay: 0.2s;
}

.row>div:nth-child(3) {
    animation-delay: 0.3s;
}

.row>div:nth-child(4) {
    animation-delay: 0.4s;
}

.row>div:nth-child(5) {
    animation-delay: 0.5s;
}

.row>div:nth-child(6) {
    animation-delay: 0.6s;
}

/* Icon animations - DISABLED */
/*
.fa-bus,
.fa-ticket-alt,
.fa-dollar-sign,
.fa-users {
    animation: iconFloat 3s ease-in-out infinite;
}

@keyframes iconFloat {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
}
*/

/* Loading spinner for charts */
.chart-loading {
    position: relative;
}

.chart-loading::after {
    content: "Loading...";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 14px;
    color: #999;
}
</style>
@endpush