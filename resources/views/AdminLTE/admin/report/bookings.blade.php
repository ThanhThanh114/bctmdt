@extends('layouts.admin')

@section('title', 'Báo cáo Đặt vé')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Báo cáo Đặt vé</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.report.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item active">Đặt vé</li>
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
                <h3 class="card-title">Bộ lọc</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.report.bookings') }}" method="GET">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Từ ngày</label>
                                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Đến ngày</label>
                                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Lọc
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Tổng đặt vé</p>
                    </div>
                    <div class="icon"><i class="fas fa-ticket-alt"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['confirmed'] }}</h3>
                        <p>Đã thanh toán</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['pending'] }}</h3>
                        <p>Chờ thanh toán</p>
                    </div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['cancelled'] }}</h3>
                        <p>Đã hủy</p>
                    </div>
                    <div class="icon"><i class="fas fa-times-circle"></i></div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Doanh thu trong khoảng thời gian</h3>
            </div>
            <div class="card-body">
                <h2 class="text-success">{{ number_format($stats['revenue']) }} VNĐ</h2>
            </div>
        </div>

        <!-- Daily Chart -->
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">Biểu đồ đặt vé theo ngày</h3>
            </div>
            <div class="card-body">
                <canvas id="dailyChart" height="80"></canvas>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('dailyChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {
                !!json_encode(array_keys($dailyBookings)) !!
            },
            datasets: [{
                label: 'Số vé đặt',
                data: {
                    !!json_encode(array_values($dailyBookings)) !!
                },
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endsection