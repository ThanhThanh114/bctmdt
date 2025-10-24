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

    <!-- Quản lý Nhà xe -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white h-100 shadow-lg"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 font-weight-bold text-white">{{ $stats['total_bus_companies'] ?? 0 }}</h2>
                        <p class="mb-0 text-white">Tổng nhà xe</p>
                    </div>
                    <div class="display-4 opacity-75 text-white"><i class="fas fa-building"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-right">
                <a href="{{ route('admin.nha-xe.index') }}" class="text-white text-decoration-none font-weight-bold">
                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
