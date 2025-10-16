@extends('layouts.admin')

@section('title', 'Báo cáo Bình luận')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Báo cáo Bình luận</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.report.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item active">Bình luận</li>
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
                <form action="{{ route('admin.report.comments') }}" method="GET">
                    <div class="row">
                        <div class="col-md-10">
                            <select name="year" class="form-control">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Xem
                            </button>
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
                        <p>Tổng bình luận</p>
                    </div>
                    <div class="icon"><i class="fas fa-comments"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['approved'] }}</h3>
                        <p>Đã duyệt</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['pending'] }}</h3>
                        <p>Chờ duyệt</p>
                    </div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['this_month'] }}</h3>
                        <p>Tháng này</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">Bình luận theo tháng ({{ $year }})</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="commentsChart" height="80"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">Phân bố đánh giá sao</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="ratingChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Commented Trips -->
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Top 10 Chuyến xe có nhiều bình luận nhất</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tuyến đường</th>
                            <th>Nhà xe</th>
                            <th>Số bình luận</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCommentedTrips as $index => $trip)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $trip->tramDi->ten_tram ?? 'N/A' }} → {{ $trip->tramDen->ten_tram ?? 'N/A' }}</td>
                            <td>{{ $trip->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                            <td><span class="badge badge-success badge-lg">{{ $trip->comment_count }}</span></td>
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
    // Comments by month chart
    const ctx1 = document.getElementById('commentsChart');
    const months = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];

    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Số bình luận',
                data: {
                    !!json_encode(array_values($commentsByMonth)) !!
                },
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Rating distribution chart
    const ctx2 = document.getElementById('ratingChart');
    const ratingData = {
        !!json_encode(array_values($ratingDistribution)) !!
    };

    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['1 sao', '2 sao', '3 sao', '4 sao', '5 sao'],
            datasets: [{
                data: ratingData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 205, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection