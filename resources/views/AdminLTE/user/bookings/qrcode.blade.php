@extends('layouts.admin')

@section('title', 'Mã QR Vé')

@section('page-title', 'Mã QR Điện Tử')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('user.bookings.index') }}">Lịch sử mua vé</a></li>
<li class="breadcrumb-item active">Mã QR vé #{{ $booking->ma_ve }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title"><i class="fas fa-qrcode mr-2"></i>Vé Điện Tử</h3>
                <div class="card-tools">
                    <span class="badge badge-light">{{ $booking->ma_ve }}</span>
                </div>
            </div>
            <div class="card-body text-center">
                <!-- QR Code -->
                <div class="qr-code-container mb-4">
                    <div id="qrcode" class="d-inline-block p-4 bg-white rounded shadow-sm"></div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Hướng dẫn:</strong> Xuất trình mã QR này cho nhân viên soát vé khi lên xe
                </div>

                <!-- Thông tin vé -->
                <div class="ticket-info bg-light p-4 rounded text-left">
                    <h5 class="mb-3"><i class="fas fa-ticket-alt mr-2"></i>Thông tin vé</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Mã vé:</strong> <span class="text-primary">{{ $booking->ma_ve }}</span></p>
                            <p class="mb-2"><strong>Số ghế:</strong> <span class="badge badge-success">{{ $booking->so_ghe }}</span></p>
                            <p class="mb-2"><strong>Trạng thái:</strong> 
                                <span class="badge badge-{{ $booking->trang_thai == 'Đã xác nhận' ? 'success' : ($booking->trang_thai == 'Đã đặt' ? 'warning' : 'info') }}">
                                    {{ $booking->trang_thai }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') }}</p>
                            <p class="mb-2"><strong>Giá vé:</strong> <span class="text-danger">{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</span></p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3"><i class="fas fa-bus mr-2"></i>Thông tin chuyến xe</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tên xe:</strong> {{ $booking->chuyenXe->ten_xe ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Nhà xe:</strong> {{ $booking->chuyenXe->nhaXe->ten_nha_xe ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }}</p>
                            <p class="mb-2"><strong>Giờ khởi hành:</strong> <span class="text-info">{{ substr($booking->chuyenXe->gio_di, 0, 5) }}</span></p>
                        </div>
                    </div>

                    <div class="route-info mt-3 p-3 bg-white rounded">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-map-marker-alt text-success mr-2"></i>
                                <strong>{{ $booking->chuyenXe->tramDi->ten_tram ?? $booking->chuyenXe->diem_di }}</strong>
                            </div>
                            <div>
                                <i class="fas fa-arrow-right text-muted"></i>
                            </div>
                            <div>
                                <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                <strong>{{ $booking->chuyenXe->tramDen->ten_tram ?? $booking->chuyenXe->diem_den }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="mt-4">
                    <a href="{{ route('user.bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại
                    </a>
                    <button onclick="downloadQR()" class="btn btn-primary">
                        <i class="fas fa-download mr-2"></i>Tải mã QR
                    </button>
                    <button onclick="printTicket()" class="btn btn-info">
                        <i class="fas fa-print mr-2"></i>In vé
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // Dữ liệu QR
    const qrData = '{{ $booking->qr_code_data }}';

    // Tạo QR Code
    const qrcode = new QRCode(document.getElementById("qrcode"), {
        text: qrData,
        width: 300,
        height: 300,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // Download QR
    function downloadQR() {
        const canvas = document.querySelector('#qrcode canvas');
        const url = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = 'ticket_qr_{{ $booking->ma_ve }}.png';
        link.href = url;
        link.click();
    }

    // Print ticket
    function printTicket() {
        window.print();
    }
</script>

<style>
    @media print {
        .card-header, .btn, .breadcrumb, .main-sidebar, .main-header, .main-footer {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .qr-code-container {
            page-break-inside: avoid;
        }
    }

    .qr-code-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        display: inline-block;
    }

    #qrcode {
        border: 5px solid white;
        border-radius: 10px;
    }

    .ticket-info {
        border: 2px dashed #ddd;
    }

    .route-info {
        border: 1px solid #e0e0e0;
    }
</style>
@endsection
