@extends('layouts.admin')

@section('title', 'Báo cáo Tổng hợp')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Báo cáo Tổng hợp</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Báo cáo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Quick Navigation -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <div class="row">
                                <div class="col-md-2 col-6 mb-2">
                                    <a href="{{ route('admin.report.index') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-chart-pie"></i><br>Tổng hợp
                                    </a>
                                </div>
                                <div class="col-md-2 col-6 mb-2">
                                    <a href="{{ route('admin.report.bookings') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-ticket-alt"></i><br>Đặt vé
                                    </a>
                                </div>
                                <div class="col-md-2 col-6 mb-2">
                                    <a href="{{ route('admin.report.revenue') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-dollar-sign"></i><br>Doanh thu
                                    </a>
                                </div>
                                <div class="col-md-2 col-6 mb-2">
                                    <a href="{{ route('admin.report.users') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-users"></i><br>Người dùng
                                    </a>
                                </div>
                                <div class="col-md-2 col-6 mb-2">
                                    <a href="{{ route('admin.report.comments') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-comments"></i><br>Bình luận
                                    </a>
                                </div>
                                <div class="col-md-2 col-6 mb-2">
                                    <a href="{{ route('admin.report.operators') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-building"></i><br>Nhà xe
                                    </a>
                                </div>
                                <div class="col-md-2 col-6 mb-2">
                                    <a href="{{ route('admin.report.routes') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-route"></i><br>Tuyến đường
                                    </a>
                                </div>
                                <div class="col-md-2 col-6 mb-2">
                                    <a href="{{ route('admin.report.contacts') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-envelope"></i><br>Liên hệ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overview Stats -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $overview['total_users'] }}</h3>
                        <p>Tổng người dùng</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $overview['total_bookings'] }}</h3>
                        <p>Tổng đặt vé</p>
                    </div>
                    <div class="icon"><i class="fas fa-ticket-alt"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $overview['total_trips'] }}</h3>
                        <p>Tổng chuyến xe</p>
                    </div>
                    <div class="icon"><i class="fas fa-bus"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $overview['total_operators'] }}</h3>
                        <p>Tổng nhà xe</p>
                    </div>
                    <div class="icon"><i class="fas fa-building"></i></div>
                </div>
            </div>
        </div>

        <!-- Additional Stats Row -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-info">
                    <div class="inner">
                        <h3>{{ number_format($overview['total_revenue']) }}</h3>
                        <p>Tổng doanh thu (VNĐ)</p>
                    </div>
                    <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-success">
                    <div class="inner">
                        <h3>{{ $overview['total_comments'] }}</h3>
                        <p>Tổng bình luận</p>
                    </div>
                    <div class="icon"><i class="fas fa-comments"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-warning">
                    <div class="inner">
                        <h3>{{ $overview['total_contacts'] }}</h3>
                        <p>Tổng liên hệ</p>
                    </div>
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-danger">
                    <div class="inner">
                        <h3>{{ number_format($monthlyStats['revenue']) }}</h3>
                        <p>Doanh thu tháng này (VNĐ)</p>
                    </div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
        </div>

        <!-- Monthly Stats -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Thống kê tháng này</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Doanh thu</th>
                                <td class="text-right"><strong
                                        class="text-success">{{ number_format($monthlyStats['revenue']) }} VNĐ</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>Số vé đã bán</th>
                                <td class="text-right"><span
                                        class="badge badge-info">{{ $monthlyStats['bookings'] }}</span></td>
                            </tr>
                            <tr>
                                <th>Người dùng mới</th>
                                <td class="text-right"><span
                                        class="badge badge-success">{{ $monthlyStats['new_users'] }}</span></td>
                            </tr>
                            <tr>
                                <th>Bình luận mới</th>
                                <td class="text-right"><span
                                        class="badge badge-warning">{{ $monthlyStats['comments'] }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Top 5 Người dùng</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số vé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topUsers as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge badge-primary">{{ $user->booking_count }}</span></td>
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
        </div>

        <!-- Top Trips -->
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">Top 5 Chuyến xe phổ biến</h3>
                <div class="card-tools">
                    <button class="btn btn-sm btn-success" onclick="exportReport()">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tuyến đường</th>
                            <th>Nhà xe</th>
                            <th>Ngày đi</th>
                            <th>Số vé đã bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topTrips as $index => $trip)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $trip->tramDi->ten_tram ?? 'N/A' }} → {{ $trip->tramDen->ten_tram ?? 'N/A' }}
                            </td>
                            <td>{{ $trip->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') }}</td>
                            <td><span class="badge badge-success badge-lg">{{ $trip->booking_count }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function exportReport() {
        window.location.href = "{{ route('admin.report.export') }}";
    }
</script>
@endsection