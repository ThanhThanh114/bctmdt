@extends('layouts.admin')

@section('title', 'Báo cáo Doanh thu')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Báo cáo Doanh thu</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.report.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item active">Doanh thu</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Form -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Chọn năm</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.report.revenue') }}" method="GET">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label>Năm</label>
                                <select name="year" class="form-control">
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Xem
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Tổng doanh thu năm {{ $year }}</h3>
            </div>
            <div class="card-body">
                <h1 class="text-success">{{ number_format($totalRevenue) }} VNĐ</h1>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Doanh thu theo tháng</h3>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="80"></canvas>
            </div>
        </div>

        <!-- Company Revenue Table -->
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">Doanh thu theo Nhà xe</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nhà xe</th>
                            <th>Doanh thu (VNĐ)</th>
                            <th>% Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companyRevenue as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['company']->ten_nha_xe }}</td>
                            <td>{{ number_format($item['revenue']) }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $totalRevenue > 0 ? ($item['revenue'] / $totalRevenue * 100) : 0 }}%">
                                        {{ $totalRevenue > 0 ? number_format($item['revenue'] / $totalRevenue * 100, 1) : 0 }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart');
    const months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {
                    !!json_encode(array_values($monthlyRevenue)) !!
                },
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
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