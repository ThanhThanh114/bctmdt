<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã QR Vé - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            transition: all 0.3s;
        }

        .back-link:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
        }

        .ticket-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .ticket-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .ticket-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .ticket-code {
            font-size: 18px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
        }

        .qr-container {
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
        }

        .qr-wrapper {
            background: white;
            padding: 30px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        #qrcode {
            border: 5px solid #667eea;
            border-radius: 10px;
            padding: 10px;
            background: white;
        }

        .qr-info {
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .ticket-info {
            padding: 30px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-section h3 i {
            color: #667eea;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-label {
            color: #999;
            font-size: 13px;
        }

        .info-value {
            color: #333;
            font-size: 15px;
            font-weight: 500;
        }

        .route-display {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 20px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        .route-point {
            text-align: center;
            flex: 1;
        }

        .route-point i {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .route-label {
            font-size: 12px;
            opacity: 0.8;
        }

        .route-value {
            font-size: 18px;
            font-weight: 600;
            margin-top: 5px;
        }

        .route-arrow {
            font-size: 24px;
            padding: 0 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-warning {
            background: #fff3cd;
            color: #856404;
        }

        .actions {
            padding: 20px 30px;
            background: #f8f9fa;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            flex: 1;
            min-width: 150px;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .route-display {
                flex-direction: column;
                gap: 15px;
            }

            .route-arrow {
                transform: rotate(90deg);
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        @media print {
            body {
                background: white;
            }

            .back-link,
            .actions {
                display: none !important;
            }

            .ticket-card {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('booking.history') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại lịch sử
        </a>

        <div class="ticket-card">
            <div class="ticket-header">
                <h1><i class="fas fa-ticket-alt"></i> Vé Điện Tử</h1>
                <div class="ticket-code">{{ $booking->ma_ve }}</div>
            </div>

            <div class="qr-container">
                <div class="qr-wrapper">
                    <div id="qrcode"></div>
                </div>
                <div class="qr-info">
                    <i class="fas fa-info-circle"></i>
                    Xuất trình mã QR này cho nhân viên soát vé khi lên xe
                </div>
            </div>

            <div class="ticket-info">
                <!-- Trạng thái -->
                <div class="info-section">
                    <h3><i class="fas fa-info-circle"></i> Trạng thái vé</h3>
                    <span class="status-badge status-{{ in_array($booking->trang_thai, ['Đã xác nhận', 'Đã thanh toán']) ? 'success' : 'warning' }}">
                        {{ $booking->trang_thai }}
                    </span>
                </div>

                <!-- Tuyến đường -->
                <div class="info-section">
                    <div class="route-display">
                        <div class="route-point">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="route-label">Điểm đi</div>
                            <div class="route-value">{{ $booking->chuyenXe->tramDi->ten_tram ?? $booking->chuyenXe->diem_di }}</div>
                        </div>
                        <div class="route-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="route-point">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="route-label">Điểm đến</div>
                            <div class="route-value">{{ $booking->chuyenXe->tramDen->ten_tram ?? $booking->chuyenXe->diem_den }}</div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin vé -->
                <div class="info-section">
                    <h3><i class="fas fa-ticket-alt"></i> Thông tin vé</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Mã vé</span>
                            <span class="info-value">{{ $booking->ma_ve }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Số ghế</span>
                            <span class="info-value">
                                @foreach($bookings as $b)
                                    {{ $b->so_ghe }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ngày đặt</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Giá vé</span>
                            <span class="info-value">{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</span>
                        </div>
                    </div>
                </div>

                <!-- Thông tin chuyến xe -->
                <div class="info-section">
                    <h3><i class="fas fa-bus"></i> Thông tin chuyến xe</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Tên xe</span>
                            <span class="info-value">{{ $booking->chuyenXe->ten_xe ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nhà xe</span>
                            <span class="info-value">{{ $booking->chuyenXe->nhaXe->ten_nha_xe ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ngày đi</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Giờ khởi hành</span>
                            <span class="info-value">{{ substr($booking->chuyenXe->gio_di, 0, 5) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Thông tin hành khách -->
                <div class="info-section">
                    <h3><i class="fas fa-user"></i> Thông tin hành khách</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Họ tên</span>
                            <span class="info-value">{{ $booking->user->fullname ?? $booking->user->username }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Số điện thoại</span>
                            <span class="info-value">{{ $booking->user->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $booking->user->email ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('booking.history') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button onclick="downloadQR()" class="btn btn-primary">
                    <i class="fas fa-download"></i> Tải mã QR
                </button>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> In vé
                </button>
            </div>
        </div>
    </div>

    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        // Dữ liệu QR
        const qrData = '{{ $booking->qr_code_data }}';

        // Tạo QR Code
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: qrData,
            width: 280,
            height: 280,
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
    </script>
</body>

</html>
