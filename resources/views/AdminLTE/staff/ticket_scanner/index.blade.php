@extends('layouts.admin')

@section('title', 'Soát Vé')

@section('page-title', 'Soát Vé QR Code')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Soát vé</li>
@endsection

@section('content')
<div class="row">
    <!-- Thống kê -->
    <div class="col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="scannedCount">{{ $todayScanned }}</h3>
                <p>Vé đã soát hôm nay</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $todayBookings }}</h3>
                <p>Tổng vé hôm nay</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Camera Scanner -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-camera mr-2"></i>Quét Mã QR</h3>
            </div>
            <div class="card-body">
                <div class="scanner-container">
                    <div id="reader" style="width: 100%;"></div>
                </div>

                <div class="mt-3">
                    <button id="startScanBtn" class="btn btn-success btn-block" onclick="startScanner()">
                        <i class="fas fa-play mr-2"></i>Bật Camera
                    </button>
                    <button id="stopScanBtn" class="btn btn-danger btn-block d-none" onclick="stopScanner()">
                        <i class="fas fa-stop mr-2"></i>Tắt Camera
                    </button>
                </div>

                <div class="divider my-3">
                    <span>HOẶC</span>
                </div>

                <div class="upload-section">
                    <button type="button" class="btn btn-primary btn-block btn-upload" onclick="document.getElementById('qrImageInput').click()">
                        <i class="fas fa-upload mr-2"></i>Upload ảnh QR từ máy
                    </button>
                    <input type="file" id="qrImageInput" accept="image/*" style="display: none;" onchange="handleImageUpload(event)">
                    <small class="text-muted d-block mt-2 text-center">Chọn ảnh chứa mã QR từ thư viện hoặc chụp ảnh</small>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    Hướng camera vào mã QR hoặc upload ảnh chứa mã QR
                </div>
            </div>
        </div>
    </div>

    <!-- Kết quả soát vé -->
    <div class="col-md-6">
        <div class="card" id="resultCard" style="display: none;">
            <div class="card-header" id="resultHeader">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Thông tin vé</h3>
            </div>
            <div class="card-body" id="resultBody">
                <!-- Kết quả sẽ được hiển thị ở đây -->
            </div>
        </div>

        <!-- Hướng dẫn -->
        <div class="card card-info" id="instructionCard">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-question-circle mr-2"></i>Hướng dẫn sử dụng</h3>
            </div>
            <div class="card-body">
                <ol class="pl-3">
                    <li>Nhấn nút "Bật Camera" để khởi động máy quét</li>
                    <li>Cho phép trình duyệt truy cập camera</li>
                    <li>Hướng camera vào mã QR trên vé của khách</li>
                    <li><strong>Hoặc</strong> nhấn "Upload ảnh QR" để chọn ảnh từ máy</li>
                    <li>Hệ thống tự động quét và hiển thị thông tin</li>
                    <li>Kiểm tra thông tin và nhấn "Check-in" để xác nhận</li>
                </ol>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Lưu ý:</strong> Mỗi vé chỉ được soát 1 lần. Vé đã soát sẽ không thể check-in lại.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrcodeScanner;
    let lastScannedData = null;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle image upload for QR scanning
    function handleImageUpload(event) {
        console.log('handleImageUpload called');
        const file = event.target.files[0];
        if (!file) {
            console.log('No file selected');
            return;
        }

        console.log('File selected:', file.name, file.type, file.size);

        // Show loading state
        const uploadBtn = document.querySelector('.btn-upload');
        const originalText = uploadBtn.innerHTML;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        uploadBtn.disabled = true;

        // Stop camera scanner if running
        if (html5QrcodeScanner) {
            console.log('Stopping camera scanner');
            stopScanner();
        }

        // Create a hidden div for scanning
        let tempDiv = document.getElementById('temp-qr-reader');
        if (!tempDiv) {
            console.log('Creating temp div for QR scanning');
            tempDiv = document.createElement('div');
            tempDiv.id = 'temp-qr-reader';
            tempDiv.style.display = 'none';
            document.body.appendChild(tempDiv);
        }

        // Create Html5Qrcode instance for file scanning
        console.log('Creating Html5Qrcode instance');
        const html5QrCode = new Html5Qrcode("temp-qr-reader");
        
        console.log('Starting scanFile...');
        html5QrCode.scanFile(file, true)
            .then(decodedText => {
                console.log('✅ QR Code decoded from image:', decodedText);
                
                // Reset button immediately
                uploadBtn.innerHTML = originalText;
                uploadBtn.disabled = false;
                
                // Clear file input
                event.target.value = '';
                
                // Clear temp scanner
                html5QrCode.clear().catch(e => console.log('Clear error:', e));
                
                // Verify ticket
                console.log('Calling verifyTicket...');
                verifyTicket(decodedText);
            })
            .catch(err => {
                console.error('❌ Error scanning image:', err);
                
                // Reset button
                uploadBtn.innerHTML = originalText;
                uploadBtn.disabled = false;
                
                // Clear file input
                event.target.value = '';
                
                // Clear temp scanner
                html5QrCode.clear().catch(e => console.log('Clear error:', e));
                
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi quét mã QR',
                    html: 'Không thể đọc mã QR từ ảnh.<br><br>' +
                          '<strong>Nguyên nhân có thể:</strong><br>' +
                          '• Ảnh không chứa mã QR<br>' +
                          '• Mã QR bị mờ hoặc không rõ<br>' +
                          '• Định dạng ảnh không được hỗ trợ<br><br>' +
                          'Vui lòng thử:<br>' +
                          '• Chụp ảnh rõ hơn<br>' +
                          '• Sử dụng camera để quét trực tiếp',
                    confirmButtonColor: '#6f42c1'
                });
            });
    }

    function startScanner() {
        document.getElementById('startScanBtn').classList.add('d-none');
        document.getElementById('stopScanBtn').classList.remove('d-none');

        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            }
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function stopScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
        document.getElementById('startScanBtn').classList.remove('d-none');
        document.getElementById('stopScanBtn').classList.add('d-none');
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Tránh quét liên tục cùng 1 mã
        if (lastScannedData === decodedText) {
            return;
        }
        lastScannedData = decodedText;

        // Dừng scanner tạm thời
        stopScanner();

        // Gửi request xác thực vé
        verifyTicket(decodedText);
    }

    function onScanFailure(error) {
        // console.warn(`QR Code scan error: ${error}`);
    }

    function verifyTicket(qrData) {
        // Hiển thị loading
        showLoading();

        console.log('Verifying ticket with QR data:', qrData);

        fetch('{{ route("staff.ticket-scanner.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ qr_data: qrData })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                displayTicketInfo(data.ticket, qrData);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showError('Lỗi kết nối: ' + error.message);
        });
    }

    function displayTicketInfo(ticket, qrData) {
        document.getElementById('instructionCard').style.display = 'none';
        document.getElementById('resultCard').style.display = 'block';

        const resultHeader = document.getElementById('resultHeader');
        const resultBody = document.getElementById('resultBody');

        // Đổi màu header theo trạng thái
        resultHeader.className = 'card-header ' + (ticket.already_scanned ? 'bg-warning' : 'bg-success');

        resultBody.innerHTML = `
            ${ticket.already_scanned ? `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Vé đã được soát!</strong><br>
                    Thời gian: ${ticket.scanned_info.scanned_at}<br>
                    Nhân viên: ${ticket.scanned_info.scanned_by}
                </div>
            ` : `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Vé hợp lệ - Chưa soát</strong>
                </div>
            `}

            <h5 class="mb-3"><i class="fas fa-ticket-alt mr-2"></i>Thông tin vé</h5>
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

            <h5 class="mb-3 mt-4"><i class="fas fa-user mr-2"></i>Hành khách</h5>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="40%">Họ tên</th>
                    <td>${ticket.passenger.name}</td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td>${ticket.passenger.phone}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>${ticket.passenger.email}</td>
                </tr>
            </table>

            <h5 class="mb-3 mt-4"><i class="fas fa-bus mr-2"></i>Thông tin chuyến xe</h5>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="40%">Tên xe</th>
                    <td>${ticket.trip.name}</td>
                </tr>
                <tr>
                    <th>Nhà xe</th>
                    <td>${ticket.trip.company}</td>
                </tr>
                <tr>
                    <th>Điểm đi</th>
                    <td><i class="fas fa-map-marker-alt text-success mr-2"></i>${ticket.trip.from}</td>
                </tr>
                <tr>
                    <th>Điểm đến</th>
                    <td><i class="fas fa-map-marker-alt text-danger mr-2"></i>${ticket.trip.to}</td>
                </tr>
                <tr>
                    <th>Ngày đi</th>
                    <td>${ticket.trip.departure_date}</td>
                </tr>
                <tr>
                    <th>Giờ khởi hành</th>
                    <td><strong class="text-info">${ticket.trip.departure_time}</strong></td>
                </tr>
                <tr>
                    <th>Giá vé</th>
                    <td><strong class="text-danger">${ticket.trip.price}</strong></td>
                </tr>
            </table>

            <div class="mt-4">
                ${!ticket.already_scanned ? `
                    <button onclick="checkInTicket('${ticket.booking_id}', '${qrData}')" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-check mr-2"></i>CHECK-IN VÉ
                    </button>
                ` : ''}
                <button onclick="resetScanner()" class="btn btn-secondary btn-block">
                    <i class="fas fa-redo mr-2"></i>Quét vé tiếp theo
                </button>
            </div>
        `;
    }

    function checkInTicket(bookingId, qrData) {
        if (!confirm('Xác nhận check-in vé này?')) {
            return;
        }

        fetch('{{ route("staff.ticket-scanner.check-in") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ 
                booking_id: bookingId,
                qr_data: qrData
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-in thành công!',
                    text: data.message,
                    timer: 2000
                });

                // Cập nhật số liệu
                const scannedCount = parseInt(document.getElementById('scannedCount').textContent);
                document.getElementById('scannedCount').textContent = scannedCount + 1;

                // Reset sau 2 giây
                setTimeout(resetScanner, 2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Lỗi kết nối: ' + error.message
            });
        });
    }

    function showLoading() {
        document.getElementById('instructionCard').style.display = 'none';
        document.getElementById('resultCard').style.display = 'block';
        document.getElementById('resultBody').innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Đang xử lý...</span>
                </div>
                <p class="mt-3">Đang xác thực vé...</p>
            </div>
        `;
    }

    function showError(message) {
        document.getElementById('instructionCard').style.display = 'none';
        document.getElementById('resultCard').style.display = 'block';
        document.getElementById('resultHeader').className = 'card-header bg-danger';
        document.getElementById('resultBody').innerHTML = `
            <div class="alert alert-danger">
                <h5><i class="fas fa-times-circle mr-2"></i>Lỗi!</h5>
                <p class="mb-0">${message}</p>
            </div>
            <button onclick="resetScanner()" class="btn btn-secondary btn-block mt-3">
                <i class="fas fa-redo mr-2"></i>Quét lại
            </button>
        `;
    }

    function resetScanner() {
        lastScannedData = null;
        document.getElementById('resultCard').style.display = 'none';
        document.getElementById('instructionCard').style.display = 'block';
        startScanner();
    }

    function getStatusBadge(status) {
        const statusMap = {
            'Đã xác nhận': 'success',
            'Đã thanh toán': 'success',
            'Đã đặt': 'warning',
            'Đã hủy': 'danger'
        };
        return statusMap[status] || 'secondary';
    }

    // Tự động bật camera khi load trang
    window.addEventListener('load', function() {
        startScanner();
    });
</script>

<style>
    .scanner-container {
        background: #f8f9fa;
        border: 3px dashed #dee2e6;
        border-radius: 10px;
        padding: 10px;
    }

    #reader {
        border-radius: 5px;
        overflow: hidden;
    }

    .table th {
        background-color: #f8f9fa;
    }
</style>
@endsection
