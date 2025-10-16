@extends('layouts.admin')

@section('title', 'Báo cáo Nhà xe')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Báo cáo Nhà xe</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.report.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item active">Nhà xe</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Total Revenue Card -->
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Tổng doanh thu tất cả nhà xe ({{ date('Y') }})</h3>
            </div>
            <div class="card-body">
                <h1 class="text-success">{{ number_format($totalRevenue) }} VNĐ</h1>
            </div>
        </div>

        <!-- Operators Table -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Thống kê chi tiết theo nhà xe</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nhà xe</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Số chuyến</th>
                                <th>Số vé bán</th>
                                <th>Doanh thu (VNĐ)</th>
                                <th>Đánh giá TB</th>
                                <th>Bình luận</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operators as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item['operator']->ten_nha_xe }}</strong></td>
                                <td>{{ $item['operator']->email }}</td>
                                <td>{{ $item['operator']->sdt }}</td>
                                <td><span class="badge badge-info">{{ $item['total_trips'] }}</span></td>
                                <td><span class="badge badge-primary">{{ $item['total_bookings'] }}</span></td>
                                <td><strong class="text-success">{{ number_format($item['revenue']) }}</strong></td>
                                <td>
                                    @if($item['avg_rating'] > 0)
                                    <span class="badge badge-warning">
                                        {{ number_format($item['avg_rating'], 1) }}
                                        <i class="fas fa-star"></i>
                                    </span>
                                    @else
                                    <span class="text-muted">Chưa có</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-secondary">{{ $item['total_comments'] }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Chưa có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">Biểu đồ doanh thu theo nhà xe</h3>
            </div>
            <div class="card-body">
                <canvas id="operatorsChart" height="80"></canvas>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('operatorsChart');
    const operators = {
        !!json_encode($operators - > pluck('operator.ten_nha_xe') - > values()) !!
    };
    const revenues = {
        !!json_encode($operators - > pluck('revenue') - > values()) !!
    };

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: operators,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: revenues,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value);
                        }
                    }
                }
            }
        }
    });
</script>
@endsection