@extends('app')
@push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }

        .payment-container {
            max-width: 100%;
            margin: 0;
            background: white;
            min-height: 100vh;
        }

        .payment-header {
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .payment-header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .booking-code {
            font-size: 1.3em;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 10px 0;
        }

        .countdown-timer {
            margin-top: 15px;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 20px;
            border-radius: 8px;
            display: inline-block;
        }

        .countdown-timer.warning {
            background: rgba(255, 193, 7, 0.3);
        }

        .countdown-timer.expired {
            background: rgba(244, 67, 54, 0.3);
        }

        .countdown-text {
            font-size: 1.1em;
            font-weight: 600;
        }

        /* Layout 2 cột */
        .payment-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 40px;
        }

        /* Cột trái - Thông tin */
        .payment-info {
            padding-right: 20px;
        }

        .section-title {
            font-size: 1.4em;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #FF7B39;
        }

        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: #333;
            font-weight: 500;
            text-align: right;
        }

        .bank-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .bank-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .bank-row:last-child {
            border-bottom: none;
        }

        .bank-label {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .bank-value {
            font-size: 1.1em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .copy-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            padding: 5px 12px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s;
        }

        .copy-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .total-amount {
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .total-amount .label {
            font-size: 1em;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .total-amount .amount {
            font-size: 2.5em;
            font-weight: bold;
        }

        /* Cột phải - QR Code */
        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-left: 20px;
            border-left: 2px solid #e0e0e0;
        }

        .qr-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            width: 100%;
        }

        .qr-title {
            font-size: 1.3em;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .qr-code-wrapper {
            background: white;
            padding: 20px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        .qr-code-wrapper img {
            display: block;
            width: 300px;
            height: 300px;
            border-radius: 8px;
        }

        .qr-instructions {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            text-align: left;
            margin-top: 20px;
        }

        .qr-instructions ol {
            margin: 10px 0 10px 20px;
            color: #856404;
        }

        .qr-instructions li {
            margin: 8px 0;
        }

        /* Status indicator */
        .payment-status {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
            text-align: center;
            border: 2px dashed #dee2e6;
        }

        .status-icon {
            font-size: 3em;
            margin-bottom: 10px;
        }

        .status-icon.checking {
            color: #ffc107;
            animation: pulse 1.5s infinite;
        }

        .status-icon.success {
            color: #28a745;
        }

        .status-icon.error {
            color: #dc3545;
        }

        .status-text {
            font-size: 1.1em;
            color: #666;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-body {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .qr-section {
                border-left: none;
                border-top: 2px solid #e0e0e0;
                padding-left: 0;
                padding-top: 20px;
                margin-top: 20px;
            }
        }

        .note-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .note-box p {
            color: #1565c0;
            margin: 5px 0;
        }
    </style>
@endpush

@section('content')
    <div class="payment-container">
        <!-- Header -->
        <div class="payment-header">
            <h1><i class="fas fa-credit-card"></i> Thanh Toán Đặt Vé</h1>
            <div class="booking-code">
                <i class="fas fa-ticket-alt"></i> {{ $code }}
            </div>

            @php
                $firstBooking = $bookings->first();
                // Tính hạn thanh toán từ thời gian hiện tại + 15 phút
                $expiresAt = now()->addMinutes(15);
                $remainingSeconds = max(0, $expiresAt->diffInSeconds(now()));
            @endphp

            <div class="countdown-timer" id="countdown-container" data-expires="{{ $expiresAt->timestamp }}">
                <i class="fas fa-clock"></i>
                <span class="countdown-text">
                    Thời gian còn lại: <strong id="countdown">{{ gmdate('i:s', $remainingSeconds) }}</strong>
                </span>
            </div>
        </div>

        <!-- Body 2 cột -->
        <div class="payment-body">
            <!-- Cột trái - Thông tin -->
            <div class="payment-info">
                <h2 class="section-title">
                    <i class="fas fa-info-circle"></i> Thông Tin Đặt Vé
                </h2>

                <!-- Thông tin chuyến -->
                <div class="info-card">
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-route"></i> Tuyến đường
                        </span>
                        <span class="info-value">
                            {{ $firstBooking->chuyenXe->tramDi->ten_tram }} →
                            {{ $firstBooking->chuyenXe->tramDen->ten_tram }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar-alt"></i> Ngày đi
                        </span>
                        <span class="info-value">
                            {{ \Carbon\Carbon::parse($firstBooking->chuyenXe->ngay_di)->format('d/m/Y') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-clock"></i> Giờ khởi hành
                        </span>
                        <span class="info-value">
                            {{ \Carbon\Carbon::parse($firstBooking->chuyenXe->gio_di)->format('H:i') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-chair"></i> Số ghế
                        </span>
                        <span class="info-value">
                            {{ $bookings->pluck('so_ghe')->join(', ') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-ticket-alt"></i> Số lượng vé
                        </span>
                        <span class="info-value">
                            {{ $bookings->count() }} vé
                        </span>
                    </div>
                </div>

                <!-- Tổng tiền -->
                <div class="total-amount">
                    <div class="label">TỔNG TIỀN THANH TOÁN</div>
                    <div class="amount">{{ number_format($paymentInfo['total_amount'], 0, ',', '.') }}đ</div>
                </div>

                <!-- Thông tin ngân hàng -->
                <h2 class="section-title">
                    <i class="fas fa-university"></i> Thông Tin Chuyển Khoản
                </h2>

                <div class="bank-info-card">
                    <div class="bank-row">
                        <span class="bank-label">Ngân hàng</span>
                        <span class="bank-value">ACB - Ngân hàng Á Châu</span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">Số tài khoản</span>
                        <span class="bank-value">
                            16826531
                            <button class="copy-btn" onclick="copyText('16826531', 'số tài khoản')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">Chủ tài khoản</span>
                        <span class="bank-value">LOI LE THANH</span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">Số tiền</span>
                        <span class="bank-value">
                            {{ number_format($paymentInfo['total_amount'], 0, ',', '.') }}đ
                            <button class="copy-btn" onclick="copyText('{{ $paymentInfo['total_amount'] }}', 'số tiền')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">Nội dung CK</span>
                        <span class="bank-value">
                            {{ $code }}
                            <button class="copy-btn" onclick="copyText('{{ $code }}', 'mã booking')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <div class="note-box">
                    <p><i class="fas fa-exclamation-circle"></i> <strong>Lưu ý quan trọng:</strong></p>
                    <p>• Vui lòng chuyển <strong>ĐÚNG SỐ TIỀN</strong> và ghi <strong>ĐÚNG NỘI DUNG</strong></p>
                    <p>• Hệ thống sẽ <strong>TỰ ĐỘNG</strong> xác nhận khi nhận được tiền</p>
                    <p>• Booking sẽ <strong>TỰ ĐỘNG HỦY</strong> sau 15 phút nếu chưa thanh toán</p>
                </div>
            </div>

            <!-- Cột phải - QR Code + Status -->
            <div class="qr-section">
                <div class="qr-card">
                    <div class="qr-title">
                        <i class="fas fa-qrcode"></i> Quét mã QR để thanh toán
                    </div>

                    <div class="qr-code-wrapper">
                        <img src="https://img.vietqr.io/image/ACB-16826531-compact2.jpg?amount={{ $paymentInfo['total_amount'] }}&addInfo={{ $code }}&accountName=LOI LE THANH"
                            alt="QR Code thanh toán">
                    </div>

                    <div class="qr-instructions">
                        <strong><i class="fas fa-mobile-alt"></i> Hướng dẫn thanh toán:</strong>
                        <ol>
                            <li>Mở ứng dụng ngân hàng trên điện thoại</li>
                            <li>Chọn <strong>Quét QR</strong> hoặc <strong>Chuyển khoản</strong></li>
                            <li>Quét mã QR bên trên</li>
                            <li>Kiểm tra thông tin và xác nhận chuyển tiền</li>
                            <li>Hệ thống sẽ tự động xác nhận ngay lập tức</li>
                        </ol>
                    </div>
                </div>

                <!-- Trạng thái thanh toán -->
                <div class="payment-status" id="payment-status">
                    <div class="status-icon checking">
                        <i class="fas fa-sync-alt spinner"></i>
                    </div>
                    <div class="status-text">
                        Đang chờ thanh toán...<br>
                        <small>Hệ thống đang tự động kiểm tra</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Copy to clipboard
        function copyText(text, label) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✓ Đã copy ' + label + ': ' + text);
            });
        }

        // Countdown timer
        const countdownElement = document.getElementById('countdown');
        const countdownContainer = document.getElementById('countdown-container');
        const expiresAt = parseInt(countdownContainer.dataset.expires);

        const countdownInterval = setInterval(() => {
            const now = Math.floor(Date.now() / 1000);
            const remaining = expiresAt - now;

            if (remaining <= 0) {
                countdownContainer.classList.add('expired');
                countdownContainer.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span class="countdown-text">ĐÃ HẾT HẠN!</span>';
                clearInterval(countdownInterval);
                clearInterval(checkPaymentInterval);

                // Update status
                document.getElementById('payment-status').innerHTML = `
                        <div class="status-icon error">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="status-text">
                            Booking đã hết hạn<br>
                            <small>Vui lòng đặt vé mới</small>
                        </div>
                    `;
            } else {
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                // Warning khi còn < 5 phút
                if (remaining <= 300) {
                    countdownContainer.classList.add('warning');
                }
            }
        }, 1000);

        // Auto check payment
        const checkPaymentInterval = setInterval(() => {
            fetch('{{ route('payment.verify') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    booking_code: '{{ $code }}'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        clearInterval(checkPaymentInterval);
                        clearInterval(countdownInterval);

                        // Show success
                        document.getElementById('payment-status').innerHTML = `
                            <div class="status-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="status-text">
                                <strong>Thanh toán thành công!</strong><br>
                                <small>Đang chuyển trang...</small>
                            </div>
                        `;

                        // Redirect after 2 seconds
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }, 5000); // Check every 5 seconds
    </script>
@endsection