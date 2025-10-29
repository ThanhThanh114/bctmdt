@extends('layouts.admin')

@section('title', 'Danh Sách Hành Khách')

@section('page-title', 'Danh Sách Hành Khách - ' . ($trip->ten_xe ?? $trip->ma_xe ?? 'N/A'))
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('staff.ticket-scanner.index') }}">Soát vé</a></li>
<li class="breadcrumb-item active">Danh sách hành khách</li>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
    </div>
    @endif
    <!-- Thông tin chuyến xe -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bus mr-2"></i>Thông tin chuyến xe</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Mã chuyến:</th>
                                    <td><strong class="text-primary">{{ $trip->ma_xe }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tên xe:</th>
                                    <td><strong>{{ $trip->ten_xe ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Nhà xe:</th>
                                    <td>{{ $trip->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Loại xe:</th>
                                    <td><span class="badge badge-info">{{ $trip->loai_xe }}</span></td>
                                </tr>
                                <tr>
                                    <th>Số ghế:</th>
                                    <td>{{ $trip->so_cho }} ghế</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Tài xế:</th>
                                    <td>{{ $trip->ten_tai_xe ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>SĐT tài xế:</th>
                                    <td><a href="tel:{{ $trip->sdt_tai_xe }}">{{ $trip->sdt_tai_xe ?? 'N/A' }}</a></td>
                                </tr>
                                <tr>
                                    <th>Điểm đi:</th>
                                    <td><i class="fas fa-map-marker-alt text-success mr-2"></i>{{ $trip->tramDi->ten_tram ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Điểm đến:</th>
                                    <td><i class="fas fa-map-marker-alt text-danger mr-2"></i>{{ $trip->tramDen->ten_tram ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Giờ khởi hành:</th>
                                    <td><strong class="text-info">{{ $trip->ngay_di }} {{ $trip->gio_di }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tổng số vé</span>
                    <span class="info-box-number">{{ $stats['total_bookings'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Đã lên xe</span>
                    <span class="info-box-number">{{ $stats['checked_in'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Chưa lên xe</span>
                    <span class="info-box-number">{{ $stats['not_checked_in'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách hành khách -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users mr-2"></i>Danh Sách Hành Khách</h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-success" onclick="window.print()">
                            <i class="fas fa-print mr-1"></i>In danh sách
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">STT</th>
                                <th width="15%">Mã vé</th>
                                <th width="20%">Họ tên</th>
                                <th width="15%">Số điện thoại</th>
                                <th width="10%">Số ghế</th>
                                <th width="12%">Trạng thái</th>
                                <th width="13%">Check-in</th>
                                <th width="10%">Ngày đặt</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($passengers as $index => $passenger)
                            <tr class="{{ $passenger['checked_in'] ? 'table-success' : '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td><strong class="text-primary">{{ $passenger['ticket_code'] }}</strong></td>
                                <td>{{ $passenger['passenger_name'] }}</td>
                                <td>{{ $passenger['passenger_phone'] }}</td>
                                <td><span class="badge badge-info">{{ $passenger['seats'] }}</span></td>
                                <td>
                                    @if($passenger['status'] == 'Đã thanh toán')
                                        <span class="badge badge-success">{{ $passenger['status'] }}</span>
                                    @elseif($passenger['status'] == 'Đã xác nhận')
                                        <span class="badge badge-primary">{{ $passenger['status'] }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ $passenger['status'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($passenger['checked_in'])
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>Đã lên xe
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i>Chưa lên
                                        </span>
                                    @endif
                                </td>
                                <td><small>{{ $passenger['booking_date'] }}</small></td>
                                <td>
                                    @if(!$passenger['checked_in'])
                                        <button class="btn btn-sm btn-success check-in-btn"
                                                data-booking-id="{{ $passenger['booking_id'] }}"
                                                data-ticket-code="{{ $passenger['ticket_code'] }}">
                                            <i class="fas fa-check mr-1"></i>V
                                        </button>
                                    @else
                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Chuyến xe chưa có hành khách đặt vé
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="float-right">
                        <strong>Tỷ lệ check-in: </strong>
                        <span class="badge badge-lg badge-{{ $stats['checked_in'] == $stats['total_bookings'] ? 'success' : 'warning' }}">
                            {{ $stats['total_bookings'] > 0 ? number_format(($stats['checked_in'] / $stats['total_bookings']) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.check-in-btn').on('click', function() {
        var bookingId = $(this).data('booking-id');
        var ticketCode = $(this).data('ticket-code');
        var button = $(this);

        if (confirm('Xác nhận hành khách đã lên xe với mã vé: ' + ticketCode + '?')) {
            // Disable button to prevent double-click
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ route("staff.ticket-scanner.check-in") }}',
                method: 'POST',
                data: {
                    booking_id: bookingId,
                    qr_data: ticketCode, // Sử dụng ticket code làm qr_data tạm thời
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Cập nhật giao diện
                        button.closest('td').html('<span class="text-success"><i class="fas fa-check-circle"></i></span>');
                        button.closest('tr').addClass('table-success');
                        button.closest('tr').find('td:nth-child(7)').html('<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Đã lên xe</span>');

                        // Cập nhật thống kê mà không reload trang
                        updateStatsWithoutReload();

                        alert('Check-in thành công!');
                    } else {
                        // Re-enable button if failed
                        button.prop('disabled', false).html('<i class="fas fa-check mr-1"></i>V');
                        alert('Lỗi: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', status, error, xhr.responseText);
                    // Re-enable button if error
                    button.prop('disabled', false).html('<i class="fas fa-check mr-1"></i>V');
                    alert('Có lỗi xảy ra khi check-in! Kiểm tra console để biết chi tiết.');
                }
            });
        }
    });

    function updateStats() {
        // Reload trang để cập nhật thống kê
        location.reload();
    }

    function updateStatsWithoutReload() {
        // Cập nhật thống kê mà không reload trang
        var checkedInCount = parseInt($('.info-box.bg-success .info-box-number').text()) || 0;
        var notCheckedInCount = parseInt($('.info-box.bg-warning .info-box-number').text()) || 0;
        var totalBookings = parseInt($('.info-box.bg-info .info-box-number').text()) || 0;

        // Tăng số lượng đã check-in
        checkedInCount++;
        notCheckedInCount--;

        // Cập nhật các info-box
        $('.info-box.bg-success .info-box-number').text(checkedInCount);
        $('.info-box.bg-warning .info-box-number').text(notCheckedInCount);

        // Cập nhật tỷ lệ
        var percentage = totalBookings > 0 ? ((checkedInCount / totalBookings) * 100).toFixed(1) : 0;
        $('.card-footer .badge').text(percentage + '%');

        // Thay đổi màu badge nếu tỷ lệ đạt 100%
        if (checkedInCount === totalBookings) {
            $('.card-footer .badge').removeClass('badge-warning').addClass('badge-success');
        }
    }
});
</script>

<style>
@media print {
    .sidebar, .main-header, .main-footer, .card-tools, .breadcrumb {
        display: none !important;
    }
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
    }
}

.check-in-btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
</style>
@endsection
