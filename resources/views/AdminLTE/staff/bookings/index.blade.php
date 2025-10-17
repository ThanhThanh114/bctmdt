@extends('layouts.admin')

@section('title', 'Quản lý đặt vé')

@section('page-title', 'Quản lý đặt vé')
@section('breadcrumb', 'Đặt vé')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách đặt vé</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{ route('staff.bookings.today') }}" class="btn btn-sm btn-info">
                            <i class="fas fa-calendar-day mr-1"></i> Hôm nay
                        </a>
                        <a href="{{ route('staff.bookings.pending') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-clock mr-1"></i> Chờ xử lý ({{ App\Models\DatVe::whereStatus('pending')->count() }})
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card-header border-0">
                <form method="GET" class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm mã vé hoặc tên khách..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-1"></i> Lọc
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('staff.bookings.index') }}" class="btn btn-secondary btn-block">
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
                            <th>Khách hàng</th>
                            <th>Chuyến xe</th>
                            <th>Số ghế</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
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
                                <div>{{ $booking->user->fullname ?? $booking->user->username }}</div>
                                <small class="text-muted">{{ $booking->user->phone }}</small>
                            </td>
                            <td>
                                <div>{{ $booking->chuyenXe->route_name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $booking->seat_number }}</span>
                            </td>
                            <td>{{ $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
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
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Xác nhận đặt vé này?')">
                                                    <i class="fas fa-check text-success mr-2"></i> Xác nhận
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Hủy đặt vé này?')">
                                                    <i class="fas fa-times text-danger mr-2"></i> Hủy vé
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-ticket-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Không tìm thấy đặt vé nào</p>
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
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ App\Models\DatVe::whereStatus('pending')->count() }}</h3>
                <p>Chờ xử lý</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('staff.bookings.index', ['status' => 'pending']) }}" class="small-box-footer">
                Xem tất cả <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ App\Models\DatVe::whereStatus('confirmed')->count() }}</h3>
                <p>Đã xác nhận</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('staff.bookings.index', ['status' => 'confirmed']) }}" class="small-box-footer">
                Xem tất cả <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ App\Models\DatVe::whereDate('ngay_dat', date('Y-m-d'))->count() }}</h3>
                <p>Hôm nay</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <a href="{{ route('staff.bookings.today') }}" class="small-box-footer">
                Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ App\Models\DatVe::whereStatus('cancelled')->count() }}</h3>
                <p>Đã hủy</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('staff.bookings.index', ['status' => 'cancelled']) }}" class="small-box-footer">
                Xem tất cả <i class="fas fa-arrow-circle-right"></i>
            </a>
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

.bg-info {
    background-color: #17a2b8 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}
</style>
@endpush
