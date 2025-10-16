@extends('layouts.admin')
@section('title', 'Chi tiết Khuyến mãi')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chi tiết Khuyến mãi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.khuyenmai.index') }}">Khuyến mãi</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <!-- Thống kê tổng quan -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $usageStats['total_uses'] ?? 0 }}</h3>
                        <p>Tổng lượt sử dụng</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $usageStats['total_bookings'] ?? 0 }}</h3>
                        <p>Số booking áp dụng</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $khuyenmai->giam_gia }}%</h3>
                        <p>Mức giảm giá</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Thông tin khuyến mãi</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Mã KM</th>
                                <td><strong>{{ $khuyenmai->ma_km }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tên KM</th>
                                <td>{{ $khuyenmai->ten_km }}</td>
                            </tr>
                            <tr>
                                <th>Mã Code</th>
                                <td><code style="font-size:16px;">{{ $khuyenmai->ma_code }}</code></td>
                            </tr>
                            <tr>
                                <th>Giảm giá</th>
                                <td><span class="badge badge-success badge-lg">{{ $khuyenmai->giam_gia }}%</span></td>
                            </tr>
                            <tr>
                                <th>Ngày bắt đầu</th>
                                <td>{{ \Carbon\Carbon::parse($khuyenmai->ngay_bat_dau)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Ngày kết thúc</th>
                                <td>{{ \Carbon\Carbon::parse($khuyenmai->ngay_ket_thuc)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                    @php
                                    $now = now();
                                    $start = \Carbon\Carbon::parse($khuyenmai->ngay_bat_dau);
                                    $end = \Carbon\Carbon::parse($khuyenmai->ngay_ket_thuc);
                                    @endphp
                                    @if($now->between($start, $end))
                                    <span class="badge badge-success badge-lg">Đang áp dụng</span>
                                    @elseif($now->lt($start))
                                    <span class="badge badge-warning badge-lg">Sắp diễn ra</span>
                                    @else
                                    <span class="badge badge-danger badge-lg">Đã hết hạn</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thao tác</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-secondary btn-block"><i
                                class="fas fa-arrow-left"></i> Quay lại</a>
                        <a href="{{ route('admin.khuyenmai.edit', $khuyenmai->ma_km) }}"
                            class="btn btn-warning btn-block mt-2"><i class="fas fa-edit"></i> Chỉnh sửa</a>
                        <form action="{{ route('admin.khuyenmai.destroy', $khuyenmai->ma_km) }}" method="POST"
                            class="mt-2" onsubmit="return confirm('Xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block"><i class="fas fa-trash"></i>
                                Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách người dùng đã sử dụng khuyến mãi -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title"><i class="fas fa-users"></i> Danh sách người dùng đã sử dụng khuyến mãi
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($recentBookings && $recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="3%" class="text-center">STT</th>
                                        <th width="7%" class="text-center">Mã Booking</th>
                                        <th width="10%">Khách hàng</th>
                                        <th width="13%">Email/SĐT</th>
                                        <th width="15%">Chuyến xe</th>
                                        <th width="6%" class="text-center">Số ghế</th>
                                        <th width="10%" class="text-center">Tổng tiền</th>
                                        <th width="10%" class="text-center">Giảm giá</th>
                                        <th width="10%" class="text-center">Phải trả</th>
                                        <th width="8%" class="text-center">Ngày đặt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $index => $booking)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.datve.show', $booking->id) }}"
                                                class="badge badge-primary badge-lg" title="Xem chi tiết booking">
                                                #{{ $booking->id }}
                                            </a>
                                        </td>
                                        <td>
                                            @if(isset($booking->user) && $booking->user)
                                            @if(!empty($booking->user->name))
                                            <strong>{{ $booking->user->name }}</strong>
                                            @else
                                            <strong class="text-info">{{ $booking->user->email }}</strong>
                                            <br><small class="text-muted">(Chưa có tên)</small>
                                            @endif
                                            @else
                                            <span class="text-muted"><i>Khách vãng lai</i></span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($booking->user) && $booking->user)
                                            <div><small><i class="fas fa-envelope"></i>
                                                    {{ $booking->user->email ?? 'N/A' }}</small></div>
                                            <div><small><i class="fas fa-phone"></i>
                                                    {{ $booking->user->phone ?? 'N/A' }}</small></div>
                                            @else
                                            <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($booking->chuyenXe) && $booking->chuyenXe)
                                            <div>
                                                <small>
                                                    <strong>{{ $booking->chuyenXe->tramDi->ten_tram ?? 'N/A' }}</strong>
                                                    <i class="fas fa-arrow-right"></i>
                                                    <strong>{{ $booking->chuyenXe->tramDen->ten_tram ?? 'N/A' }}</strong>
                                                </small>
                                            </div>
                                            <div>
                                                <small class="text-muted">
                                                    <i class="far fa-clock"></i>
                                                    {{ $booking->chuyenXe->ngay_di ? \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') : 'N/A' }}
                                                    -
                                                    {{ $booking->chuyenXe->gio_di ? \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') : 'N/A' }}
                                                </small>
                                            </div>
                                            @else
                                            <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info badge-lg"
                                                title="{{ $booking->so_ghe_list }}">{{ $booking->so_luong_ghe }}</span>
                                        </td>
                                        <td class="text-right">
                                            <strong
                                                class="text-primary">{{ number_format($booking->tong_tien, 0, ',', '.') }}đ</strong>
                                        </td>
                                        <td class="text-right text-success">
                                            <strong>-{{ number_format($booking->giam_gia, 0, ',', '.') }}đ</strong><br>
                                            <small class="text-muted">
                                                ({{ $khuyenmai->giam_gia }}%)
                                            </small>
                                        </td>
                                        <td class="text-right">
                                            <strong class="text-danger">{{ number_format($booking->so_tien_phai_tra, 0, ',', '.') }}đ</strong>
                                        </td>
                                        <td class="text-center">
                                            <small>{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y') : 'N/A' }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <th colspan="6" class="text-right">Tổng cộng:</th>
                                        <th class="text-right text-primary">
                                            <strong>{{ number_format($recentBookings->sum('tong_tien'), 0, ',', '.') }}đ</strong>
                                        </th>
                                        <th class="text-right text-success">
                                            <strong>-{{ number_format($recentBookings->sum('giam_gia'), 0, ',', '.') }}đ</strong>
                                        </th>
                                        <th class="text-right text-danger">
                                            <strong>{{ number_format($recentBookings->sum('so_tien_phai_tra'), 0, ',', '.') }}đ</strong>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mt-3">
                            <p class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Hiển thị 10 booking gần nhất đã sử dụng khuyến mãi này.
                                Tổng có <strong>{{ $usageStats['total_bookings'] ?? 0 }}</strong> booking đã áp dụng.
                            </p>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chưa có booking nào sử dụng khuyến mãi này.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection