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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .payment-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .payment-header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .payment-header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .payment-content {
            padding: 30px;
        }

        .booking-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #212529;
        }

        .total-amount {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin: 20px 0;
        }

        .total-amount h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .total-amount .amount {
            font-size: 2.5em;
            font-weight: bold;
        }

        .payment-methods {
            margin: 30px 0;
        }

        .method-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .method-card:hover {
            border-color: #FF5722;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 87, 34, 0.2);
        }

        .method-card.active {
            border-color: #FF5722;
            background: #fff3f0;
        }

        .bank-transfer {
            background: #e3f2fd;
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
        }

        .bank-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .bank-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed #ccc;
        }

        .bank-info-row:last-child {
            border-bottom: none;
        }

        .bank-label {
            font-weight: 600;
            color: #424242;
        }

        .bank-value {
            font-size: 1.1em;
            color: #d32f2f;
            font-weight: 700;
        }

        .copy-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            margin-left: 10px;
            transition: all 0.3s;
        }

        .copy-btn:hover {
            background: #1976D2;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: white;
            border-radius: 12px;
        }

        .qr-code img {
            max-width: 300px;
            border: 3px solid #e0e0e0;
            border-radius: 12px;
            padding: 15px;
        }

        .verify-section {
            text-align: center;
            margin: 30px 0;
        }

        .verify-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .verify-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .verify-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        .loading-spinner {
            display: none;
            margin: 20px auto;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #FF5722;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin: 15px 0;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .instructions {
            background: #fff9c4;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border-left: 4px solid #f57f17;
        }

        .instructions h4 {
            color: #f57f17;
            margin-bottom: 15px;
        }

        .instructions ol {
            margin-left: 20px;
        }

        .instructions li {
            margin-bottom: 10px;
            color: #424242;
        }

        @media (max-width: 768px) {
            .payment-container {
                margin: 10px;
            }

            .payment-content {
                padding: 15px;
            }

            .total-amount .amount {
                font-size: 2em;
            }
        }
    </style>
@endpush

@section('content')
    <div class="payment-container">
        <div class="payment-header">
            <h1><i class="fas fa-credit-card"></i> Thanh Toán</h1>
            <p>Mã đặt vé: <strong>{{ $code }}</strong></p>
            @php
                $firstBooking = $bookings->first();
                $bookingTime = \Carbon\Carbon::parse($firstBooking->ngay_dat);
                $expiresAt = $bookingTime->copy()->addMinutes(15);
                $remainingSeconds = max(0, now()->diffInSeconds($expiresAt, false));
            @endphp
            @if($remainingSeconds > 0)
                <div style="margin-top: 15px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 8px;">
                    <i class="fas fa-clock"></i> Thời gian còn lại: <strong id="countdown" data-expires="{{ $expiresAt->timestamp }}">{{ gmdate('i:s', $remainingSeconds) }}</strong>
                </div>
            @else
                <div style="margin-top: 15px; background: rgba(255,0,0,0.3); padding: 10px; border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle"></i> <strong>ĐÃ HẾT HẠN!</strong> Booking sẽ bị hủy
                </div>
            @endif
        </div>

        <div class="payment-content">
            <!-- Thông tin booking -->
            <div class="booking-info">
                <h3 style="margin-bottom: 15px; color: #424242;">
                    <i class="fas fa-info-circle"></i> Thông tin chuyến đi
                </h3>
                @php
                    $firstBooking = $bookings->first();
                @endphp
                <div class="info-row">
                    <span class="info-label">Tuyến đường:</span>
                    <span class="info-value">{{ $firstBooking->chuyenXe->tramDi->ten_tram }} →
                        {{ $firstBooking->chuyenXe->tramDen->ten_tram }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thời gian:</span>
                    <span
                        class="info-value">{{ date('H:i d/m/Y', strtotime($firstBooking->chuyenXe->ngay_di . ' ' . $firstBooking->chuyenXe->gio_di)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Số ghế:</span>
                    <span class="info-value">{{ $bookings->pluck('so_ghe')->join(', ') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Số lượng vé:</span>
                    <span class="info-value">{{ $bookings->count() }} vé</span>
                </div>
            </div>

            <!-- Tổng tiền -->
            <div class="total-amount">
                <h3>Tổng tiền thanh toán</h3>
                <div class="amount">{{ number_format($paymentInfo['total_amount'], 0, ',', '.') }}đ</div>
            </div>

            <!-- Hướng dẫn chuyển khoản -->
            <div class="instructions">
                <h4><i class="fas fa-lightbulb"></i> Hướng dẫn thanh toán</h4>
                <ol>
                    <li>Mở ứng dụng ngân hàng của bạn</li>
                    <li>Chọn chuyển khoản/chuyển tiền</li>
                    <li>Nhập thông tin tài khoản bên dưới</li>
                    <li><strong>QUAN TRỌNG:</strong> Nhập đúng nội dung chuyển khoản có chứa mã: <strong
                            style="color: #d32f2f;">{{ $code }}</strong></li>
                    <li>Hoàn tất chuyển khoản</li>
                    <li>Quay lại trang này và nhấn "Kiểm tra thanh toán"</li>
                </ol>
            </div>

            <!-- Thông tin chuyển khoản -->
            <div class="bank-transfer">
                <h3 style="margin-bottom: 20px; text-align: center; color: #1565C0;">
                    <i class="fas fa-university"></i> Thông tin chuyển khoản
                </h3>

                <div class="bank-info">
                    <div class="bank-info-row">
                        <span class="bank-label">Ngân hàng:</span>
                        <span class="bank-value">ACB - Ngân hàng Á Châu</span>
                    </div>
                    <div class="bank-info-row">
                        <span class="bank-label">Số tài khoản:</span>
                        <div>
                            <span class="bank-value" id="account-number">16826531</span>
                            <button class="copy-btn" onclick="copyText('16826531', 'Đã copy số tài khoản')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                    <div class="bank-info-row">
                        <span class="bank-label">Chủ tài khoản:</span>
                        <span class="bank-value">LOI LE THANH</span>
                    </div>
                    <div class="bank-info-row">
                        <span class="bank-label">Số tiền:</span>
                        <div>
                            <span class="bank-value">{{ number_format($paymentInfo['total_amount'], 0, ',', '.') }}đ</span>
                            <button class="copy-btn"
                                onclick="copyText('{{ $paymentInfo['total_amount'] }}', 'Đã copy số tiền')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                    <div class="bank-info-row">
                        <span class="bank-label">Nội dung:</span>
                        <div>
                            <span class="bank-value" id="transfer-content">{{ $code }}</span>
                            <button class="copy-btn" onclick="copyText('{{ $code }}', 'Đã copy nội dung')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                </div>

                <!-- QR Code (Optional) -->
                <div class="qr-code">
                    <h4 style="margin-bottom: 15px; color: #424242;">
                        <i class="fas fa-qrcode"></i> Quét mã QR để chuyển khoản nhanh
                    </h4>
                    <img src="https://img.vietqr.io/image/ACB-16826531-compact2.jpg?amount={{ $paymentInfo['total_amount'] }}&addInfo={{ $code }}&accountName=LOI LE THANH"
                        alt="QR Code">
                    <p style="margin-top: 10px; color: #666; font-size: 0.9em;">
                        Quét mã QR bằng ứng dụng ngân hàng để thanh toán tự động
                    </p>
                </div>
            </div>

            <!-- Alert message -->
            <div id="alert-message" style="display: none;"></div>

            <!-- Loading spinner -->
            <div class="loading-spinner" id="loading-spinner"></div>

            <!-- Nút kiểm tra thanh toán -->
            <div class="verify-section">
                <button class="verify-btn" id="verify-btn" onclick="verifyPayment()">
                    <i class="fas fa-check-circle"></i> Kiểm tra thanh toán
                </button>
                <p style="margin-top: 15px; color: #666;">
                    Sau khi chuyển khoản thành công, nhấn nút trên để hệ thống kiểm tra
                </p>
            </div>

            <!-- Link về trang chủ -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('home') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-home"></i> Về trang chủ
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyText(text, message) {
            navigator.clipboard.writeText(text).then(() => {
                alert(message || 'Đã copy!');
            }).catch(err => {
                console.error('Copy failed:', err);
                prompt('Copy nội dung này:', text);
            });
        }

        function verifyPayment() {
            const verifyBtn = document.getElementById('verify-btn');
            const loadingSpinner = document.getElementById('loading-spinner');
            const alertMessage = document.getElementById('alert-message');

            // Disable button and show loading
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...';
            loadingSpinner.style.display = 'block';
            alertMessage.style.display = 'none';

            // Call API to verify payment
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
                    loadingSpinner.style.display = 'none';

                    if (data.success) {
                        alertMessage.className = 'alert alert-success';
                        alertMessage.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                        alertMessage.style.display = 'block';

                        // Redirect to success page after 2 seconds
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    } else {
                        alertMessage.className = 'alert alert-warning';
                        alertMessage.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + data.message;
                        alertMessage.style.display = 'block';

                        verifyBtn.disabled = false;
                        verifyBtn.innerHTML = '<i class="fas fa-check-circle"></i> Kiểm tra lại';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingSpinner.style.display = 'none';
                    alertMessage.className = 'alert alert-danger';
                    alertMessage.innerHTML = '<i class="fas fa-times-circle"></i> Lỗi kết nối. Vui lòng thử lại!';
                    alertMessage.style.display = 'block';

                    verifyBtn.disabled = false;
                    verifyBtn.innerHTML = '<i class="fas fa-check-circle"></i> Kiểm tra lại';
                });
        }

        // Đếm ngược thời gian còn lại
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            const expiresAt = parseInt(countdownElement.dataset.expires);
            
            setInterval(() => {
                const now = Math.floor(Date.now() / 1000);
                const remaining = expiresAt - now;
                
                if (remaining <= 0) {
                    countdownElement.parentElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <strong>ĐÃ HẾT HẠN!</strong> Booking đã bị hủy';
                    countdownElement.parentElement.style.background = 'rgba(255,0,0,0.3)';
                    
                    // Disable nút thanh toán
                    const verifyBtn = document.getElementById('verify-payment-btn');
                    if (verifyBtn) {
                        verifyBtn.disabled = true;
                        verifyBtn.innerHTML = '<i class="fas fa-times"></i> Đã hết hạn';
                    }
                } else {
                    const minutes = Math.floor(remaining / 60);
                    const seconds = remaining % 60;
                    countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    // Cảnh báo khi còn dưới 5 phút
                    if (remaining <= 300 && remaining > 0) {
                        countdownElement.parentElement.style.background = 'rgba(255,193,7,0.3)';
                        countdownElement.style.color = '#ff9800';
                    }
                }
            }, 1000);
        }

        // TẠM THỜI TẮT AUTO CHECK ĐỂ TRÁNH SPAM API
        // Auto check every 15 seconds
        // let autoCheckInterval = setInterval(() => {
        //     verifyPayment();
        // }, 15000);

        // Stop auto check after 10 minutes
        // setTimeout(() => {
        //     clearInterval(autoCheckInterval);
        //     console.log('Auto check stopped');
        // }, 600000);
    </script>
@endsection