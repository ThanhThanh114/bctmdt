@extends('layouts.staff')

@section('title', 'Đặt vé hôm nay')

@section('page-title', 'Đặt vé hôm nay')
@section('breadcrumb', 'Đặt vé')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Đặt vé trong ngày ({{ $today_bookings->count() }} vé)</h3>
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
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($today_bookings as $booking)
                        <tr>
                            <td>
                                <strong>#{{ $booking->id }}</strong>
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
                                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->chuyenXe->ngay_di . ' ' . $booking->chuyenXe->gio_di)->format('H:i') :
                                            'N/A';
                                        echo $tripDateTime;
                                    } catch (Exception $e) {
                                        echo 'N/A';
                                    }
                                @endphp
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
                            <td>
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-calendar-day fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Không có đặt vé nào hôm nay</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
