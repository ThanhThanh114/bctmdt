@extends('layouts.admin')

@section('title', 'Đặt vé chờ xử lý')

@section('page-title', 'Đặt vé chờ xử lý')
@section('breadcrumb', 'Đặt vé')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Đặt vé chờ xử lý</h3>
                <div class="card-tools">
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
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
                        @forelse($pending_bookings as $booking)
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
                            <td>{{ $booking->ngay_dat ? $booking->ngay_dat->format('d/m/Y H:i') : 'N/A' }}</td>
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
                                <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Không có đặt vé nào chờ xử lý</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($pending_bookings->hasPages())
                <div class="card-footer">
                    {{ $pending_bookings->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pending_bookings->count() }}</h3>
                <p>Tổng vé chờ xử lý</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $pending_bookings->sum(function($booking) { return $booking->chuyenXe->gia_ve ?? 0; }) }}</h3>
                <p>Tổng giá trị (VNĐ)</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $pending_bookings->where('payment_status', 'paid')->count() }}</h3>
                <p>Đã thanh toán</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>
@endsection
