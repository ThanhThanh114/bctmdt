@extends('layouts.admin')

@section('title', 'Doanh thu')
@section('page-title', 'Báo cáo Doanh thu')
@section('breadcrumb', 'Doanh thu')

@section('content')
<!-- Filter Form -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('bus-owner.doanh-thu.index') }}" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="year" class="mr-2">Năm:</label>
                        <select name="year" id="year" class="form-control">
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label for="month" class="mr-2">Tháng:</label>
                        <select name="month" id="month" class="form-control">
                            @for($m = 1; $m <= 12; $m++) <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                Tháng {{ $m }}
                                </option>
                                @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stats Boxes -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($stats['monthly_revenue']) }}đ</h3>
                <p>Doanh thu tháng {{ $month }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($stats['yearly_revenue']) }}đ</h3>
                <p>Doanh thu năm {{ $year }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($stats['monthly_bookings']) }}</h3>
                <p>Vé đã bán (tháng {{ $month }})</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($stats['average_booking']) }}đ</h3>
                <p>Giá trung bình/vé</p>
            </div>
            <div class="icon">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Doanh thu theo tháng năm {{ $year }}</h3>
            </div>
            <div class="card-body">
                <canvas id="monthlyRevenueChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Doanh thu hôm nay</h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Doanh thu</span>
                        <span class="info-box-number">{{ number_format($stats['today_revenue']) }}đ</span>
                    </div>
                </div>
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Số vé</span>
                        <span class="info-box-number">{{ $stats['today_bookings'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Trips -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top 10 chuyến xe có doanh thu cao nhất (tháng {{ $month }}/{{ $year }})</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên chuyến xe</th>
                            <th>Ngày đi</th>
                            <th>Số vé đã bán</th>
                            <th>Giá vé</th>
                            <th>Tổng doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topTrips as $index => $trip)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $trip->ten_xe }}</td>
                            <td>{{ \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') }}</td>
                            <td>{{ $trip->bookings_count }}</td>
                            <td>{{ number_format($trip->gia_ve) }}đ</td>
                            <td><strong>{{ number_format($trip->total_revenue) }}đ</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Chưa có dữ liệu</td>
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
        // Monthly Revenue Chart
        const ctx = document.getElementById('monthlyRevenueChart');
        const monthlyData = @json($monthlyRevenue);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => 'Tháng ' + d.month),
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: monthlyData.map(d => d.revenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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
                                return value.toLocaleString('vi-VN') + 'đ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush