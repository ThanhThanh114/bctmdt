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
