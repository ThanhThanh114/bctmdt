@extends('layouts.admin')

@section('title', 'Chi tiết đặt vé')

@section('page-title', 'Chi tiết đặt vé')
@section('breadcrumb', 'Đặt vé')

@section('content')
<div class="row">
    <!-- Booking Details -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin đặt vé #{{ $booking->id }}</h3>
                <div class="card-tools">
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Status Badge -->
                <div class="mb-3">
                    @if($booking->status == 'confirmed')
                        <span class="badge badge-success fs-6">Đã xác nhận</span>
                    @elseif($booking->status == 'pending')
                        <span class="badge badge-warning fs-6">Chờ xử lý</span>
                    @elseif($booking->status == 'cancelled')
                        <span class="badge badge-danger fs-6">Đã hủy</span>
                    @else
                        <span class="badge badge-secondary fs-6">{{ $booking->status }}</span>
                    @endif

                    @if($booking->payment_status == 'paid')
                        <span class="badge badge-success ml-2">Đã thanh toán</span>
                    @else
                        <span class="badge badge-warning ml-2">Chưa thanh toán</span>
                    @endif
                </div>

                <!-- Customer Information -->
                <h4 class="mb-3">Thông tin khách hàng</h4>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Tên khách hàng</th>
                        <td>{{ $booking->user->fullname ?? $booking->user->username }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $booking->user->phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $booking->user->email ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đặt vé</th>
                        <td>{{ $booking->ngay_dat ? $booking->ngay_dat->format('d/m/Y H:i:s') : 'N/A' }}</td>
                    </tr>
                </table>

                <!-- Trip Information -->
                <h4 class="mb-3 mt-4">Thông tin chuyến xe</h4>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Tuyến đường</th>
                        <td>{{ $booking->chuyenXe->route_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Nhà xe</th>
                        <td>{{ $booking->chuyenXe->nhaXe->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đi</th>
                        <td>{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Giờ khởi hành</th>
                        <td>{{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Điểm đón</th>
                        <td>{{ $booking->chuyenXe->tramDi->ten_tram ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Điểm đến</th>
                        <td>{{ $booking->chuyenXe->tramDen->ten_tram ?? 'N/A' }}</td>
                    </tr>
                </table>

                <!-- Seat Information -->
                <h4 class="mb-3 mt-4">Thông tin ghế ngồi</h4>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Số ghế</th>
                        <td>
                            <span class="badge badge-info fs-6">{{ $booking->seat_number }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Giá vé</th>
                        <td>
                            <strong class="text-primary">{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</strong>
                        </td>
                    </tr>
                    @if($booking->notes)
                    <tr>
                        <th>Ghi chú</th>
                        <td>{{ $booking->notes }}</td>
                    </tr>
                    @endif
                    @if($booking->staff_notes)
                    <tr>
                        <th>Ghi chú nhân viên</th>
                        <td>
                            <em class="text-muted">{{ $booking->staff_notes }}</em>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thao tác nhanh</h3>
            </div>
            <div class="card-body">
                @if($booking->status !== 'confirmed')
                <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Xác nhận đặt vé này?')">
                        <i class="fas fa-check mr-2"></i> Xác nhận đặt vé
                    </button>
                </form>
                @endif

                @if($booking->status !== 'cancelled')
                <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Hủy đặt vé này?')">
                        <i class="fas fa-times mr-2"></i> Hủy đặt vé
                    </button>
                </form>
                @endif

                <a href="{{ route('staff.bookings.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-list mr-2"></i> Về danh sách
                </a>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tóm tắt</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Giá vé:</span>
                    <span>{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Phí dịch vụ:</span>
                    <span>0đ</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Tổng cộng:</strong>
                    <strong class="text-primary">{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</strong>
                </div>
            </div>
        </div>

        <!-- Trip Status -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Trạng thái chuyến xe</h3>
            </div>
            <div class="card-body">
                @php
                    try {
                        // Handle case where ngay_di might be a date only or full datetime
                        $ngay_di = $booking->chuyenXe->ngay_di;
                        $gio_di = $booking->chuyenXe->gio_di;

                        // If ngay_di is already a full datetime, use it directly
                        if (strlen($ngay_di) > 10) {
                            $departure_date = \Carbon\Carbon::parse($ngay_di);
                        } else {
                            // Otherwise combine date and time
                            $departure_date = \Carbon\Carbon::parse($ngay_di . ' ' . $gio_di);
                        }

                        $now = \Carbon\Carbon::now();
                        $is_departed = $now->isAfter($departure_date);
                    } catch (Exception $e) {
                        $departure_date = null;
                        $is_departed = false;
                    }
                @endphp

                @if($is_departed)
                    <span class="badge badge-secondary">Đã khởi hành</span>
                    <br><small class="text-muted">Khởi hành: {{ $departure_date->format('d/m/Y H:i') }}</small>
                @else
                    <span class="badge badge-success">Chưa khởi hành</span>
                    <br><small class="text-muted">Còn: {{ $now->diffForHumans($departure_date, true) }}</small>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.fs-6 {
    font-size: 0.875rem;
}
</style>
@endpush
