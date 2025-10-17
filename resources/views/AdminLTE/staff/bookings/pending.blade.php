@extends('layouts.staff')

@section('title', 'Đặt vé chờ xử lý')

@section('page-title', 'Đặt vé chờ xử lý')
@section('breadcrumb', 'Đặt vé')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Đặt vé cần xử lý ({{ $pending_bookings->total() }} vé)</h3>
                <div class="card-tools">
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-list mr-1"></i> Tất cả đặt vé
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
                            <th>Giờ khởi hành</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_bookings as $booking)
                        <tr>
                            <td>
                                <strong>#{{ $booking->id }}</strong>
                                <i class="fas fa-clock text-warning" title="Chờ xử lý"></i>
                            </td>
                            <td>
                                <div>{{ $booking->user->fullname ?? $booking->user->username }}</div>
                                <small class="text-muted">{{ $booking->user->phone }}</small>
                            </td>
                            <td>{{ $booking->chuyenXe->route_name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    try {
                                        $tripDateTime = $booking->chuyenXe->ngay_di && $booking->chuyenXe->gio_di ?
                                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->chuyenXe->ngay_di . ' ' . $booking->chuyenXe->gio_di)->format('d/m/Y H:i') :
                                            'N/A';
                                        echo $tripDateTime;
                                    } catch (Exception $e) {
                                        echo 'N/A';
                                    }
                                @endphp
                            </td>
                            <td>{{ $booking->ngay_dat ? $booking->ngay_dat->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                <strong>{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</strong>
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
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <p class="text-muted">Tuyệt vời! Không có đặt vé nào chờ xử lý</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pending_bookings->hasPages())
            <div class="card-footer">
                {{ $pending_bookings->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
