@extends('layouts.admin')

@section('title', 'Vé của tôi')

@section('page-title', 'Vé của tôi')
@section('breadcrumb', 'Đặt vé')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lịch sử đặt vé</h3>
                <div class="card-tools">
                    <a href="{{ route('trips.trips') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Đặt vé mới
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card-header border-0">
                <form method="GET" class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Tìm mã vé hoặc tuyến đường..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-1"></i> Lọc
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('user.bookings.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-redo mr-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Mã vé</th>
                            <th>Chuyến xe</th>
                            <th>Ngày giờ</th>
                            <th>Số ghế</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <strong>#{{ $booking->id }}</strong>
                                @if($booking->payment_status == 'paid')
                                    <i class="fas fa-check-circle text-success" title="Đã thanh toán"></i>
                                @else
                                    <i class="fas fa-clock text-warning" title="Chưa thanh toán"></i>
                                @endif
                            </td>
                            <td>
                                <div>{{ $booking->chuyenXe->route_name }}</div>
                                <small class="text-muted">{{ $booking->chuyenXe->nhaXe->name ?? 'N/A' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</td>
                            <td>
                                <span class="badge badge-info">{{ $booking->seat_number }}</span>
                            </td>
                            <td>
                                <strong>{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</strong>
                            </td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <span class="badge badge-success">Đã xác nhận</span>
                                @elseif($booking->status == 'pending')
                                    <span class="badge badge-warning">Chờ xử lý</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge badge-danger">Đã hủy</span>
                                @else
                                    <span class="badge badge-secondary">{{ $booking->status }}</span>
                                @endif
                            </td>
                            <td>{{ $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('user.bookings.show', $booking) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @php
                                        $canShowQR = in_array($booking->trang_thai, ['Đã đặt', 'Đã thanh toán', 'Đã xác nhận']);
                                    @endphp
                                    
                                    @if($canShowQR)
                                    <a href="{{ route('user.bookings.qrcode', $booking) }}" class="btn btn-sm btn-success" title="Xem mã QR">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    @endif
                                    
                                    @if($booking->status == 'confirmed' && \Carbon\Carbon::parse($booking->chuyenXe->ngay_di . ' ' . $booking->chuyenXe->gio_di) > now()->addHours(2))
                                    <form method="POST" action="{{ route('user.bookings.cancel', $booking) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn hủy vé này?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hủy vé">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-ticket-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Chưa có đặt vé nào</p>
                                <a href="{{ route('trips.trips') }}" class="btn btn-primary">Đặt vé ngay</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                <p>Vé đã xác nhận</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('user.bookings.index', ['status' => 'confirmed']) }}" class="small-box-footer">
                Xem tất cả <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $bookings->where('status', 'pending')->count() }}</h3>
                <p>Chờ xử lý</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('user.bookings.index', ['status' => 'pending']) }}" class="small-box-footer">
                Xem tất cả <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $bookings->where('status', 'cancelled')->count() }}</h3>
                <p>Đã hủy</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('user.bookings.index', ['status' => 'cancelled']) }}" class="small-box-footer">
                Xem tất cả <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($bookings->where('status', 'confirmed')->sum(function($booking) { return $booking->chuyenXe->gia_ve ?? 0; })) }}đ</h3>
                <p>Tổng chi tiêu</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="small-box-footer">
                &nbsp;
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.small-box {
    border-radius: 0.375rem;
    margin-bottom: 1.5rem;
    position: relative;
    display: block;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.125);
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.small-box .icon {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 3rem;
    color: rgba(255,255,255,.15);
}

.small-box .inner {
    padding: 10px;
}

.small-box h3 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    white-space: nowrap;
    padding: 0;
}

.small-box p {
    font-size: 1rem;
    margin: 0;
}

.small-box-footer {
    background-color: rgba(0,0,0,.1);
    color: rgba(255,255,255,.8);
    display: block;
    padding: 3px 10px;
    position: relative;
    text-decoration: none;
    transition: all .15s linear;
}

.small-box-footer:hover {
    text-decoration: none;
    color: #fff;
}

.bg-primary {
    background-color: #007bff !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

.bg-info {
    background-color: #17a2b8 !important;
}

.bg-success {
    background-color: #28a745 !important;
}
</style>
@endpush
