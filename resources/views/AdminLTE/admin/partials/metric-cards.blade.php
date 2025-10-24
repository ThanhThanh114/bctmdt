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
