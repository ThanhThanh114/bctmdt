@extends('layouts.admin')

@section('title', 'Chuyến Xe Hôm Nay')

@section('page-title', 'Chuyến Xe Hôm Nay')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('staff.ticket-scanner.index') }}">Soát vé</a></li>
<li class="breadcrumb-item active">Chuyến xe hôm nay</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                Hiển thị các chuyến xe chạy hôm nay: <strong>{{ date('d/m/Y') }}</strong>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @forelse($tripsWithStats as $item)
            <div class="card mb-3">
                <div class="card-header {{ $item['checked_in'] == $item['total_bookings'] && $item['total_bookings'] > 0 ? 'bg-success' : 'bg-light' }}">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-bus mr-2"></i>
                                <strong>{{ $item['trip']->ten_xe ?? $item['trip']->ma_xe ?? 'N/A' }}</strong>
                            </h5>
                            <small class="text-dark"><i class="fas fa-building mr-1"></i>{{ $item['trip']->nhaXe->ten_nha_xe ?? 'N/A' }}</small>
                        </div>
                        <div class="col-md-4">
                            <span class="text-dark">
                                <i class="fas fa-map-marker-alt text-success mr-1"></i>
                                <strong>{{ $item['trip']->tramDi->ten_tram ?? 'N/A' }}</strong>
                                <i class="fas fa-arrow-right mx-2"></i>
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                <strong>{{ $item['trip']->tramDen->ten_tram ?? 'N/A' }}</strong>
                            </span>
                        </div>
                        <div class="col-md-2 text-dark">
                            <i class="fas fa-clock mr-1"></i>
                            <strong>{{ $item['trip']->gio_di }}</strong>
                        </div>
                        <div class="col-md-2">
                            <span class="badge badge-info">{{ $item['trip']->loai_xe }}</span>
                            <span class="badge badge-secondary ml-2">{{ $item['trip']->so_cho }} ghế</span>
                        </div>
                        <div class="col-md-1 text-right">
                            <div class="btn-group" role="group">
                                <button class="btn btn-success btn-sm scan-qr-btn"
                                        data-trip-id="{{ $item['trip']->id }}"
                                        data-trip-name="{{ $item['trip']->ten_xe ?? $item['trip']->ma_xe ?? 'N/A' }}"
                                        title="Quét QR vé">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                                <a href="{{ route('staff.ticket-scanner.trip', $item['trip']->id) }}"
                                   class="btn btn-primary btn-sm"
                                   title="Xem danh sách hành khách">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-2 bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <small class="text-dark">
                                <i class="fas fa-user mr-1"></i><strong>{{ $item['trip']->ten_tai_xe ?? 'N/A' }}</strong>
                                @if($item['trip']->sdt_tai_xe)
                                    - <a href="tel:{{ $item['trip']->sdt_tai_xe }}" class="text-primary">{{ $item['trip']->sdt_tai_xe }}</a>
                                @endif
                            </small>
                        </div>
                        <div class="col-md-7">
                            <div class="row text-center">
                                <div class="col-3">
                                    <h5 class="mb-0 text-info"><strong>{{ $item['total_bookings'] }}</strong></h5>
                                    <small class="text-dark"><strong>Tổng vé</strong></small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0 text-success"><strong>{{ $item['checked_in'] }}</strong></h5>
                                    <small class="text-dark"><strong>Đã lên</strong></small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0 text-warning"><strong>{{ $item['not_checked_in'] }}</strong></h5>
                                    <small class="text-dark"><strong>Chưa lên</strong></small>
                                </div>
                                <div class="col-3">
                                    @if($item['total_bookings'] > 0)
                                        <h5 class="mb-0 text-primary"><strong>{{ number_format(($item['checked_in'] / $item['total_bookings']) * 100, 1) }}%</strong></h5>
                                        <small class="text-dark"><strong>Tỷ lệ</strong></small>
                                    @else
                                        <h5 class="mb-0 text-muted"><strong>0%</strong></h5>
                                        <small class="text-dark"><strong>Tỷ lệ</strong></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @if($item['total_bookings'] > 0)
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ ($item['checked_in'] / $item['total_bookings']) * 100 }}%"
                                     aria-valuenow="{{ $item['checked_in'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $item['total_bookings'] }}">
                                    {{ number_format(($item['checked_in'] / $item['total_bookings']) * 100, 1) }}%
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Không có chuyến xe nào hôm nay</h4>
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($tripsWithStats->hasPages())
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Hiển thị {{ $tripsWithStats->firstItem() }} - {{ $tripsWithStats->lastItem() }} 
                            trong tổng số {{ $tripsWithStats->total() }} chuyến xe
                        </div>
                        <div>
                            {{ $tripsWithStats->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Quét QR -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScannerModalLabel">
                    <i class="fas fa-qrcode mr-2"></i>Quét QR Code - <span id="currentTripName"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Camera Scanner -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0"><i class="fas fa-camera mr-2"></i>Camera</h6>
                            </div>
                            <div class="card-body">
                                <div id="qrReader" style="width: 100%; min-height: 250px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 5px;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-camera fa-2x mb-2"></i>
                                        <p class="mb-0">Nhấn "Bật Camera" để bắt đầu</p>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <button id="startQrScanBtn" class="btn btn-success btn-sm">
                                        <i class="fas fa-play mr-1"></i>Bật Camera
                                    </button>
                                    <button id="stopQrScanBtn" class="btn btn-danger btn-sm d-none">
                                        <i class="fas fa-stop mr-1"></i>Tắt Camera
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kết quả -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i>Kết quả quét</h6>
                            </div>
                            <div class="card-body">
                                <div id="scanResult" class="text-center text-muted">
                                    <i class="fas fa-qrcode fa-3x mb-3"></i>
                                    <p>Chưa có kết quả quét</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin vé (ẩn ban đầu) -->
                <div id="ticketInfoSection" class="mt-3" style="display: none;">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h6 class="card-title mb-0 text-white">
                                <i class="fas fa-check-circle mr-2"></i>Thông tin vé hợp lệ
                            </h6>
                        </div>
                        <div class="card-body" id="ticketInfoContent">
                            <!-- Thông tin vé sẽ được hiển thị ở đây -->
                        </div>
                        <div class="card-footer text-center">
                            <button id="checkInBtn" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle mr-2"></i>CHECK-IN - KHÁCH ĐÃ LÊN XE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header {
        border-bottom: 3px solid #dee2e6;
    }
    .card-header.bg-light {
        background-color: #f8f9fa !important;
        border-left: 5px solid #007bff;
    }
    .card-header.bg-success {
        background-color: #d4edda !important;
        border-left: 5px solid #28a745;
    }
    .text-dark {
        color: #212529 !important;
    }
    .card-body {
        background-color: #ffffff;
    }
    .progress {
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
    }

    /* QR Scanner Modal Styles */
    #qrReader {
        border-radius: 5px;
        overflow: hidden;
    }

    .modal-lg {
        max-width: 900px;
    }

    .scan-qr-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Cải thiện hiển thị link điện thoại */
    .text-primary {
        color: #007bff !important;
        text-decoration: none;
    }

    .text-primary:hover {
        color: #0056b3 !important;
        text-decoration: underline;
    }
</style>

<!-- HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
$(document).ready(function() {
    let qrScanner = null;
    let currentTripId = null;
    let currentTripName = null;
    let lastScannedData = null;
    let currentTicket = null;

    // Xử lý click nút quét QR
    $('.scan-qr-btn').on('click', function() {
        currentTripId = $(this).data('trip-id');
        currentTripName = $(this).data('trip-name');

        $('#currentTripName').text(currentTripName);
        $('#qrScannerModal').modal('show');

        // Reset modal state
        resetQrScanner();
    });

    // Modal events
    $('#qrScannerModal').on('shown.bs.modal', function() {
        // Auto start scanner when modal opens
        setTimeout(startQrScanner, 500);
    });

    $('#qrScannerModal').on('hidden.bs.modal', function() {
        stopQrScanner();
        resetQrScanner();
    });

    function startQrScanner() {
        $('#startQrScanBtn').addClass('d-none');
        $('#stopQrScanBtn').removeClass('d-none');

        try {
            qrScanner = new Html5QrcodeScanner(
                "qrReader",
                {
                    fps: 10,
                    qrbox: { width: 200, height: 200 },
                    aspectRatio: 1.0,
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: true
                },
                false
            );

            qrScanner.render(onQrScanSuccess, onQrScanFailure);
        } catch (error) {
            console.error('Error starting QR scanner:', error);
            showScanError('Không thể khởi động camera. Vui lòng kiểm tra quyền truy cập camera.');
        }
    }

    function stopQrScanner() {
        if (qrScanner) {
            try {
                qrScanner.clear();
                qrScanner = null;
            } catch (error) {
                console.error('Error stopping QR scanner:', error);
            }
        }
        $('#startQrScanBtn').removeClass('d-none');
        $('#stopQrScanBtn').addClass('d-none');
    }

    function onQrScanSuccess(decodedText, decodedResult) {
        // Tránh quét liên tục cùng 1 mã
        if (lastScannedData === decodedText) {
            return;
        }
        lastScannedData = decodedText;

        // Dừng scanner tạm thời
        stopQrScanner();

        // Xác thực vé
        verifyTicket(decodedText);
    }

    function onQrScanFailure(error) {
        // console.warn(`QR Code scan error: ${error}`);
    }

    function verifyTicket(qrData) {
        $('#scanResult').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Đang xử lý...</span>
                </div>
                <p class="mt-2">Đang xác thực vé...</p>
            </div>
        `);

        fetch('{{ route("staff.ticket-scanner.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ qr_data: qrData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTicketInfo(data.ticket, qrData);
            } else {
                showScanError(data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showScanError('Lỗi kết nối: ' + error.message);
        });
    }

    function displayTicketInfo(ticket, qrData) {
        currentTicket = ticket;

        $('#scanResult').html(`
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                <strong>Quét thành công!</strong><br>
                Mã vé: <strong>${ticket.ticket_code}</strong><br>
                Hành khách: <strong>${ticket.passenger.name}</strong>
            </div>
        `);

        // Hiển thị thông tin vé chi tiết
        $('#ticketInfoContent').html(`
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-ticket-alt mr-2"></i>Thông tin vé</h6>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th width="40%">Mã vé</th>
                            <td><strong class="text-primary">${ticket.ticket_code}</strong></td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td><span class="badge badge-${getStatusBadge(ticket.status)}">${ticket.status}</span></td>
                        </tr>
                        <tr>
                            <th>Số ghế</th>
                            <td><span class="badge badge-success">${ticket.seats}</span></td>
                        </tr>
                        <tr>
                            <th>Ngày đặt</th>
                            <td>${ticket.booking_date}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-user mr-2"></i>Hành khách</h6>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th width="40%">Họ tên</th>
                            <td>${ticket.passenger.name}</td>
                        </tr>
                        <tr>
                            <th>SĐT</th>
                            <td>${ticket.passenger.phone}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>${ticket.passenger.email}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <h6><i class="fas fa-bus mr-2"></i>Chuyến xe</h6>
                    <div class="alert alert-info">
                        <strong>${ticket.trip.name}</strong> - ${ticket.trip.from} → ${ticket.trip.to}<br>
                        Giờ khởi hành: <strong>${ticket.trip.departure_time}</strong> (${ticket.trip.departure_date})
                    </div>
                </div>
            </div>
        `);

        $('#ticketInfoSection').show();

        // Kiểm tra xem vé đã được soát chưa
        if (ticket.already_scanned) {
            $('#checkInBtn').prop('disabled', true).html(`
                <i class="fas fa-check-circle mr-2"></i>ĐÃ CHECK-IN
            `).removeClass('btn-success').addClass('btn-secondary');

            $('#scanResult').html(`
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Vé đã được soát trước đó!</strong><br>
                    Thời gian: ${ticket.scanned_info.scanned_at}<br>
                    Nhân viên: ${ticket.scanned_info.scanned_by}
                </div>
            `);
        } else {
            $('#checkInBtn').prop('disabled', false).html(`
                <i class="fas fa-check-circle mr-2"></i>CHECK-IN - KHÁCH ĐÃ LÊN XE
            `).removeClass('btn-secondary').addClass('btn-success');
        }
    }

    function showScanError(message) {
        $('#scanResult').html(`
            <div class="alert alert-danger">
                <i class="fas fa-times-circle mr-2"></i>
                <strong>Lỗi!</strong><br>
                ${message}
            </div>
        `);
        $('#ticketInfoSection').hide();
    }

    function resetQrScanner() {
        lastScannedData = null;
        currentTicket = null;
        $('#scanResult').html(`
            <div class="text-center text-muted">
                <i class="fas fa-qrcode fa-3x mb-3"></i>
                <p>Chưa có kết quả quét</p>
            </div>
        `);
        $('#ticketInfoSection').hide();
    }

    // Xử lý check-in
    $('#checkInBtn').on('click', function() {
        if (!currentTicket) return;

        if (!confirm('Xác nhận check-in vé này?')) {
            return;
        }

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...');

        fetch('{{ route("staff.ticket-scanner.check-in") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                booking_id: currentTicket.booking_id,
                qr_data: currentTicket.ticket_code
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật giao diện
                $('#checkInBtn').removeClass('btn-success').addClass('btn-secondary').html(`
                    <i class="fas fa-check-circle mr-2"></i>ĐÃ CHECK-IN
                `);

                $('#scanResult').html(`
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>Check-in thành công!</strong><br>
                        ${data.message}
                    </div>
                `);

                // Cập nhật thống kê trên trang chính (nếu cần)
                updateTripStats(currentTripId);

                // Reset sau 3 giây
                setTimeout(function() {
                    resetQrScanner();
                    startQrScanner();
                }, 3000);
            } else {
                $('#checkInBtn').prop('disabled', false).html(`
                    <i class="fas fa-check-circle mr-2"></i>CHECK-IN - KHÁCH ĐÃ LÊN XE
                `);
                showScanError(data.message);
            }
        })
        .catch(error => {
            $('#checkInBtn').prop('disabled', false).html(`
                <i class="fas fa-check-circle mr-2"></i>CHECK-IN - KHÁCH ĐÃ LÊN XE
            `);
            showScanError('Lỗi kết nối: ' + error.message);
        });
    });

    // Manual start/stop buttons
    $('#startQrScanBtn').on('click', startQrScanner);
    $('#stopQrScanBtn').on('click', stopQrScanner);

    function getStatusBadge(status) {
        const statusMap = {
            'Đã xác nhận': 'success',
            'Đã thanh toán': 'success',
            'Đã đặt': 'warning',
            'Đã hủy': 'danger'
        };
        return statusMap[status] || 'secondary';
    }

    function updateTripStats(tripId) {
        // Reload trang để cập nhật thống kê
        location.reload();
    }
});
</script>
@endsection
