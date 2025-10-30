@extends('layouts.admin')

@section('title', 'Chi tiết vé')

@section('page-title', 'Chi tiết đặt vé #' . $booking->ma_ve)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('user.bookings.index') }}">Vé của tôi</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <!-- Booking Details -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-ticket-alt mr-2"></i>Thông tin vé
                </h3>
            </div>
            <div class="card-body">
                <!-- Status Badge -->
                <div class="mb-3">
                    @if($booking->trang_thai == 'Đã xác nhận' || $booking->trang_thai == 'Đã thanh toán')
                        <span class="badge badge-success badge-lg px-3 py-2">
                            <i class="fas fa-check-circle mr-1"></i>{{ $booking->trang_thai }}
                        </span>
                    @elseif($booking->trang_thai == 'Đã đặt')
                        <span class="badge badge-info badge-lg px-3 py-2">
                            <i class="fas fa-clock mr-1"></i>{{ $booking->trang_thai }}
                        </span>
                    @elseif($booking->trang_thai == 'Đã hủy')
                        <span class="badge badge-danger badge-lg px-3 py-2">
                            <i class="fas fa-times-circle mr-1"></i>{{ $booking->trang_thai }}
                        </span>
                    @else
                        <span class="badge badge-secondary badge-lg px-3 py-2">{{ $booking->trang_thai }}</span>
                    @endif
                </div>

                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td width="35%" class="font-weight-bold">Mã vé:</td>
                            <td>
                                <span class="badge badge-primary px-3 py-2" style="font-size: 14px;">
                                    {{ $booking->ma_ve }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tuyến đường:</td>
                            <td>
                                <i class="fas fa-map-marker-alt text-success mr-1"></i>
                                {{ $booking->chuyenXe->tramDi->ten_tram }}
                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                {{ $booking->chuyenXe->tramDen->ten_tram }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Nhà xe:</td>
                            <td>
                                <i class="fas fa-bus mr-2 text-primary"></i>
                                {{ $booking->chuyenXe->nhaXe->ten_nha_xe }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Ngày đi:</td>
                            <td>
                                <i class="fas fa-calendar-alt mr-2 text-info"></i>
                                {{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Giờ khởi hành:</td>
                            <td>
                                <i class="fas fa-clock mr-2 text-warning"></i>
                                {{ date('H:i', strtotime($booking->chuyenXe->gio_di)) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Loại xe:</td>
                            <td>{{ $booking->chuyenXe->loai_xe }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Số ghế:</td>
                            <td>
                                @foreach($bookings as $b)
                                    <span class="badge badge-info mr-1">{{ $b->so_ghe }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Giá vé:</td>
                            <td>
                                <span class="text-danger font-weight-bold" style="font-size: 18px;">
                                    {{ number_format($booking->chuyenXe->gia_ve) }}đ
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Ngày đặt:</td>
                            <td>{{ \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Actions Sidebar -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-cog mr-2"></i>Thao tác
                </h3>
            </div>
            <div class="card-body p-2">
                @if(in_array($booking->trang_thai, ['Đã xác nhận', 'Đã thanh toán']))
                <a href="{{ route('user.bookings.qrcode', $booking) }}" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-qrcode mr-2"></i>Xem mã QR
                </a>
                
                <!-- Nút Bình luận -->
                <a href="{{ route('user.binh-luan.index', ['chuyen_xe_id' => $booking->chuyen_xe_id]) }}" 
                   class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-comment-dots mr-2"></i>Đánh giá chuyến xe
                </a>
                @endif

                <a href="{{ route('user.bookings.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-list mr-2"></i>Về danh sách
                </a>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-success text-white">
                <h3 class="card-title mb-0">Tóm tắt</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Giá vé:</span>
                    <span>{{ number_format($booking->chuyenXe->gia_ve) }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Phí dịch vụ:</span>
                    <span>0đ</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Tổng cộng:</strong>
                    <strong class="text-danger">{{ number_format($booking->chuyenXe->gia_ve) }}đ</strong>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i>Lưu ý
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        Mang theo CMND/CCCD khi lên xe
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        Có mặt trước giờ khởi hành 15 phút
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        Xuất trình mã QR khi lên xe
                    </li>
                    <li>
                        <i class="fas fa-check text-success mr-2"></i>
                        Liên hệ hotline nếu cần hỗ trợ
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
