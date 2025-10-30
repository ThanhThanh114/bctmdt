@extends('layouts.admin')

@section('title', 'Chi tiết Vé')
@section('page-title', 'Chi tiết Đặt vé #' . $booking->id)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('bus-owner.dat-ve.index') }}">Đặt vé</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-ticket-alt mr-2"></i>Thông tin vé</h3>
                <div class="card-tools">
                    <a href="{{ route('bus-owner.dat-ve.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary"><i class="fas fa-user mr-2"></i>Thông tin khách hàng</h5>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th style="width: 150px">Họ tên:</th>
                                    <td><strong>{{ $booking->user->fullname ?? $booking->user->username ?? 'N/A' }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $booking->user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại:</th>
                                    <td>{{ $booking->user->phone ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-success"><i class="fas fa-info-circle mr-2"></i>Thông tin đặt vé</h5>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th style="width: 150px">Mã vé:</th>
                                    <td><strong>#{{ $booking->id }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Ngày đặt:</th>
                                    <td>{{ $booking->ngay_dat ? \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Vị trí ghế:</th>
                                    <td><span class="badge badge-secondary">{{ $booking->so_ghe ?? 'N/A' }}</span></td>
                                </tr>
                                <tr>
                                    <th>Số lượng vé:</th>
                                    <td><span class="badge badge-info">{{ $booking->so_luong_ve ?? 1 }}</span></td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        @if($booking->trang_thai == 'Đã thanh toán')
                                        <span class="badge badge-success badge-lg">{{ $booking->trang_thai }}</span>
                                        @elseif($booking->trang_thai == 'Đã xác nhận')
                                        <span class="badge badge-primary badge-lg">{{ $booking->trang_thai }}</span>
                                        @elseif($booking->trang_thai == 'Đã đặt')
                                        <span class="badge badge-warning badge-lg">{{ $booking->trang_thai }}</span>
                                        @else
                                        <span class="badge badge-danger badge-lg">{{ $booking->trang_thai }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <h5 class="text-info"><i class="fas fa-bus mr-2"></i>Thông tin chuyến xe</h5>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th style="width: 200px">Tên chuyến:</th>
                            <td><strong>{{ $booking->chuyenXe->ten_xe ?? 'N/A' }}</strong></td>
                        </tr>
                        @if($booking->chuyenXe->tramDi || $booking->chuyenXe->tramDen)
                        <tr>
                            <th>Tuyến đường:</th>
                            <td>
                                <i class="fas fa-map-marker-alt text-success mr-1"></i>
                                {{ $booking->chuyenXe->tramDi->ten_tram ?? 'N/A' }}
                                <i class="fas fa-arrow-right mx-2"></i>
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                {{ $booking->chuyenXe->tramDen->ten_tram ?? 'N/A' }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>Ngày khởi hành:</th>
                            <td>{{ $booking->chuyenXe->ngay_di ? \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Giờ khởi hành:</th>
                            <td><strong
                                    class="text-primary">{{ $booking->chuyenXe->gio_di ? \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') : 'N/A' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Loại xe:</th>
                            <td>{{ $booking->chuyenXe->loai_xe ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Loại chuyến:</th>
                            <td>
                                @if($booking->chuyenXe->loai_chuyen == 'Một chiều')
                                <span class="badge badge-info">Một chiều</span>
                                @else
                                <span class="badge badge-primary">Khứ hồi</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Giá vé (1 vé):</th>
                            <td><strong
                                    class="text-success">{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Tổng tiền:</th>
                            <td>
                                <h5 class="text-danger mb-0">
                                    {{ number_format(($booking->chuyenXe->gia_ve ?? 0) * ($booking->so_luong_ve ?? 1)) }}đ
                                </h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Actions Card -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cogs mr-2"></i>Thao tác</h3>
            </div>
            <div class="card-body">
                @if($booking->trang_thai == 'Đã đặt')
                <form action="{{ route('bus-owner.dat-ve.confirm', $booking->id) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-block"
                        onclick="return confirm('Xác nhận đặt vé này?')">
                        <i class="fas fa-check mr-2"></i>Xác nhận đặt vé
                    </button>
                </form>
                @endif

                @if($booking->trang_thai != 'Đã hủy')
                <form action="{{ route('bus-owner.dat-ve.cancel', $booking->id) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-block"
                        onclick="return confirm('Bạn có chắc muốn hủy vé này?')">
                        <i class="fas fa-times mr-2"></i>Hủy đặt vé
                    </button>
                </form>
                @endif

                <form action="{{ route('bus-owner.dat-ve.update-status', $booking->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label>Cập nhật trạng thái:</label>
                        <select name="trang_thai" class="form-control" required>
                            <option value="Đã đặt" {{ $booking->trang_thai == 'Đã đặt' ? 'selected' : '' }}>Đã đặt
                            </option>
                            <option value="Đã thanh toán"
                                {{ $booking->trang_thai == 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="Đã hủy" {{ $booking->trang_thai == 'Đã hủy' ? 'selected' : '' }}>Đã hủy
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-2"></i>Cập nhật
                    </button>
                </form>
            </div>
        </div>

        <!-- Trip Details Card -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Thống kê chuyến xe</h3>
            </div>
            <div class="card-body">
                @php
                $totalSeats = $booking->chuyenXe->so_cho ?? 0;
                $bookedSeats = $booking->chuyenXe->so_ve ?? 0;
                $availableSeats = $totalSeats - $bookedSeats;
                $percentage = $totalSeats > 0 ? ($bookedSeats / $totalSeats) * 100 : 0;
                @endphp
                <p><strong>Tổng số ghế:</strong> {{ $totalSeats }}</p>
                <p><strong>Đã đặt:</strong> <span class="badge badge-warning">{{ $bookedSeats }}</span></p>
                <p><strong>Còn trống:</strong> <span class="badge badge-success">{{ $availableSeats }}</span></p>
                <div class="progress mt-3">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%"
                        aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                        {{ number_format($percentage, 1) }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge-lg {
    padding: 8px 15px;
    font-size: 14px;
}
</style>
@endpush