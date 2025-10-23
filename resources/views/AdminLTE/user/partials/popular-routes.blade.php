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
