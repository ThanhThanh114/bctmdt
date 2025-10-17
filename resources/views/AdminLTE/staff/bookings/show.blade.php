@extends('layouts.staff')

@section('title', 'Chi tiết đặt vé')

@section('page-title', 'Chi tiết đặt vé')
@section('breadcrumb', 'Đặt vé')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Booking Info Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-ticket-alt mr-2"></i>Thông tin đặt vé #{{ $booking->id }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Mã vé:</th>
                                <td>
                                    <span class="badge badge-primary px-3 py-2" style="font-size: 14px;">
                                        {{ $booking->ma_ve ?? '#'.$booking->id }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($booking->status == 'confirmed')
                                        <span class="badge badge-success px-3 py-2">
                                            <i class="fas fa-check-circle mr-1"></i>{{ $booking->trang_thai ?? 'Đã xác nhận' }}
                                        </span>
                                    @elseif($booking->status == 'pending')
                                        <span class="badge badge-warning px-3 py-2">
                                            <i class="fas fa-clock mr-1"></i>{{ $booking->trang_thai ?? 'Chờ xử lý' }}
                                        </span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="badge badge-danger px-3 py-2">
                                            <i class="fas fa-times-circle mr-1"></i>{{ $booking->trang_thai ?? 'Đã hủy' }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2">{{ $booking->trang_thai ?? $booking->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày đặt:</th>
                                <td>
                                    <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                    {{ $booking->ngay_dat ? $booking->ngay_dat->format('d/m/Y H:i:s') : 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Số ghế:</th>
                                <td>
                                    <span class="badge badge-info">
                                        {{-- Debug info: {{ $booking->id }} - {{ $booking->so_ghe ?? 'NULL' }} --}}
                                        {{ $booking->so_ghe ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-bus mr-2"></i>Thông tin chuyến xe
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($booking->chuyenXe)
                                <h6 class="text-primary">{{ $booking->chuyenXe->route_name ?? 'N/A' }}</h6>

                                <!-- Trip Route Information -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-success mr-2"></i>
                                        <strong>Điểm đi:</strong>
                                        <span class="ml-2">{{ $booking->chuyenXe->tramDi->ten_tram ?? 'N/A' }}</span>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                        <strong>Điểm đến:</strong>
                                        <span class="ml-2">{{ $booking->chuyenXe->tramDen->ten_tram ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <strong>Khởi hành:</strong>
                                    @php
                                        try {
                                            $departureTime = 'N/A';
                                            if ($booking->chuyenXe->ngay_di && $booking->chuyenXe->gio_di) {
                                                // Handle both date and datetime formats
                                                if (is_string($booking->chuyenXe->gio_di)) {
                                                    $departureTime = $booking->chuyenXe->ngay_di->format('d/m/Y') . ' ' . substr($booking->chuyenXe->gio_di, 0, 5);
                                                } else {
                                                    $departureTime = $booking->chuyenXe->ngay_di->format('d/m/Y H:i');
                                                }
                                            }
                                            echo $departureTime;
                                        } catch (Exception $e) {
                                            echo 'N/A';
                                        }
                                    @endphp
                                </div>
                                <div class="mb-2">
                                    <strong>Giá vé:</strong>
                                    <span class="text-success">{{ number_format($booking->chuyenXe->gia_ve ?? 0, 0, ',', '.') }} VNĐ</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Nhà xe:</strong>
                                    <span class="badge badge-info">{{ $booking->chuyenXe->nhaXe->ten_nha_xe ?? 'N/A' }}</span>
                                </div>
                                @else
                                <p class="text-muted mb-0">Không có thông tin chuyến xe</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        @if($booking->user)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-2"></i>Thông tin khách hàng
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%" class="font-weight-bold">Họ tên:</td>
                                <td>{{ $booking->user->fullname ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Tên đăng nhập:</td>
                                <td>{{ $booking->user->username }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Email:</td>
                                <td>
                                    <a href="mailto:{{ $booking->user->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope text-primary mr-1"></i>
                                        {{ $booking->user->email }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%" class="font-weight-bold">Số điện thoại:</td>
                                <td>
                                    @if($booking->user->phone)
                                    <a href="tel:{{ $booking->user->phone }}" class="text-decoration-none">
                                        <i class="fas fa-phone text-success mr-1"></i>
                                        {{ $booking->user->phone }}
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Địa chỉ:</td>
                                <td>{{ $booking->user->address ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Vai trò:</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($booking->user->role) }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        @if($booking->status === 'pending')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cogs mr-2"></i>Thao tác
                </h3>
            </div>
            <div class="card-body">
                <div class="btn-group">
                    <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận đặt vé này?')">
                            <i class="fas fa-check mr-2"></i> Xác nhận đặt vé
                        </button>
                    </form>

                    <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Hủy đặt vé này?')">
                            <i class="fas fa-times mr-2"></i> Hủy đặt vé
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
