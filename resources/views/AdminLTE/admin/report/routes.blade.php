@extends('layouts.admin')

@section('title', 'Báo cáo Tuyến đường')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Báo cáo Tuyến đường</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.report.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item active">Tuyến đường</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Top Routes Table -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Top 15 Tuyến đường phổ biến nhất</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Điểm đi</th>
                                <th style="width: 50px; text-align: center;"><i class="fas fa-arrow-right"></i></th>
                                <th>Điểm đến</th>
                                <th style="width: 120px; text-align: center;">Số chuyến</th>
                                <th style="width: 120px; text-align: center;">Số vé bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($routes as $index => $route)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $route['from'] }}</strong></td>
                                <td style="text-align: center;"><i class="fas fa-arrow-right text-primary"></i></td>
                                <td><strong>{{ $route['to'] }}</strong></td>
                                <td style="text-align: center;">
                                    <span class="badge badge-info badge-lg">{{ $route['trip_count'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-success badge-lg">{{ $route['booking_count'] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Biểu đồ số vé bán theo tuyến</h3>
            </div>
            <div class="card-body">
                <canvas id="routesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('routesChart');
    const labels = {
        !!json_encode($routes - > map(function($r) {
            return $r['from'].
            ' → '.$r['to'];
        }) - > values()) !!
    };
    const bookings = {
        !!json_encode($routes - > pluck('booking_count') - > values()) !!
    };

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Số vé đã bán',
                data: bookings,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection