@extends('layouts.admin')

@section('title', 'Chi tiết đặt vé')
@section('page-title', 'Chi tiết đặt vé')
@section('breadcrumb', 'Chi tiết')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-ticket-alt mr-2"></i>Thông tin đặt vé
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td width="35%" class="font-weight-bold">Mã vé:</td>
                            <td>
                                <span class="badge badge-primary px-3 py-2" style="font-size: 14px;">
                                    {{ $datVe->ma_ve }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Trạng thái:</td>
                            <td>
                                @if($datVe->trang_thai == 'Đã đặt')
                                <span class="badge badge-warning px-3 py-2">
                                    <i class="fas fa-clock mr-1"></i>{{ $datVe->trang_thai }}
                                </span>
                                @elseif($datVe->trang_thai == 'Đã thanh toán')
                                <span class="badge badge-success px-3 py-2">
                                    <i class="fas fa-check-circle mr-1"></i>{{ $datVe->trang_thai }}
                                </span>
                                @else
                                <span class="badge badge-danger px-3 py-2">
                                    <i class="fas fa-times-circle mr-1"></i>{{ $datVe->trang_thai }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Ngày đặt:</td>
                            <td>
                                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                {{ $datVe->ngay_dat ? $datVe->ngay_dat->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Số ghế:</td>
                            <td>
                                @foreach($bookings as $booking)
                                <span class="badge badge-info mr-1">{{ $booking->so_ghe }}</span>
                                @endforeach
                                <small class="text-muted">({{ $bookings->count() }} ghế)</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Giá vé/ghế:</td>
                            <td>
                                <strong class="text-success" style="font-size: 18px;">
                                    {{ $datVe->chuyenXe ? number_format($datVe->chuyenXe->gia_ve, 0, ',', '.') : '0' }}
                                    VNĐ
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-success text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-user mr-2"></i>Thông tin khách hàng
                </h3>
            </div>
            <div class="card-body">
                @if($datVe->user)
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td width="35%" class="font-weight-bold">Họ tên:</td>
                            <td>{{ $datVe->user->fullname }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Email:</td>
                            <td>
                                <a href="mailto:{{ $datVe->user->email }}">
                                    <i class="fas fa-envelope text-primary mr-1"></i>
                                    {{ $datVe->user->email }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Số điện thoại:</td>
                            <td>
                                @if($datVe->user->phone)
                                <a href="tel:{{ $datVe->user->phone }}">
                                    <i class="fas fa-phone text-success mr-1"></i>
                                    {{ $datVe->user->phone }}
                                </a>
                                @else
                                <span class="text-muted">Chưa cập nhật</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tên đăng nhập:</td>
                            <td>{{ $datVe->user->username }}</td>
                        </tr>
                    </tbody>
                </table>
                @else
                <p class="text-muted mb-0">Không tìm thấy thông tin khách hàng</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h3 class="card-title mb-0">
                    <i class="fas fa-bus mr-2"></i>Thông tin chuyến xe
                </h3>
            </div>
            <div class="card-body">
                @if($datVe->chuyenXe)
                <div class="text-center mb-3">
                    <div class="mb-2">
                        <i class="fas fa-map-marker-alt text-success fa-2x"></i>
                        <h5 class="mt-2">{{ $datVe->chuyenXe->tramDi->ten_tram ?? 'N/A' }}</h5>
                    </div>
                    <div class="my-2">
                        <i class="fas fa-arrow-down fa-2x text-primary"></i>
                    </div>
                    <div>
                        <i class="fas fa-map-marker-alt text-danger fa-2x"></i>
                        <h5 class="mt-2">{{ $datVe->chuyenXe->tramDen->ten_tram ?? 'N/A' }}</h5>
                    </div>
                </div>
                <hr>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td width="40%" class="font-weight-bold">Ngày đi:</td>
                            <td>{{ $datVe->chuyenXe->ngay_di ? \Carbon\Carbon::parse($datVe->chuyenXe->ngay_di)->format('d/m/Y') : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Giờ đi:</td>
                            <td><strong class="text-primary">{{ $datVe->chuyenXe->gio_di ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Nhà xe:</td>
                            <td>{{ $datVe->chuyenXe->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Loại xe:</td>
                            <td>
                                <span class="badge badge-info">{{ $datVe->chuyenXe->loai_xe ?? 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Số chỗ:</td>
                            <td>{{ $datVe->chuyenXe->so_cho ?? 'N/A' }} ghế</td>
                        </tr>
                    </tbody>
                </table>
                @else
                <p class="text-muted mb-0">Không tìm thấy thông tin chuyến xe</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title mb-0">
                    <i class="fas fa-cogs mr-2"></i>Thao tác
                </h3>
            </div>
            <div class="card-body p-2">
                <a href="{{ route('admin.datve.index') }}" class="btn btn-secondary btn-block mb-2">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
                </a>

                @if($datVe->trang_thai !== 'Đã thanh toán')
                <form action="{{ route('admin.datve.cancel', $datVe) }}" method="POST" class="mb-2"
                    onsubmit="return confirm('Bạn có chắc muốn hủy vé này?')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-ban mr-1"></i> Hủy vé
                    </button>
                </form>
                @else
                <button class="btn btn-secondary btn-block mb-2" disabled title="Vé đã thanh toán không thể hủy">
                    <i class="fas fa-lock mr-1"></i> Không thể hủy
                </button>
                @endif

                <button onclick="window.print()" class="btn btn-info btn-block">
                    <i class="fas fa-print mr-1"></i> In vé
                </button>
            </div>
        </div>

        @if($datVe->khuyenMais && $datVe->khuyenMais->count() > 0)
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-tags mr-2"></i>Khuyến mãi
                </h3>
            </div>
            <div class="card-body">
                @foreach($datVe->khuyenMais as $km)
                <div class="alert alert-success mb-2">
                    <strong>{{ $km->ten_km }}</strong><br>
                    <small>Giảm {{ $km->giam_gia }}%</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<style>
@media print {

    .content-header,
    .card-header,
    .btn,
    .breadcrumb,
    .sidebar,
    .main-header,
    .main-footer {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection