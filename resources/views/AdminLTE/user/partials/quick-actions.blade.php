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
        <a href="{{ route('tracking.tracking') }}" class="btn btn-info btn-block">
            <i class="fas fa-map-marker-alt mr-2"></i> Theo dõi chuyến
        </a>
    </div>
</div>
