@extends('layouts.admin')

@section('title', 'Soát Vé')

@section('page-title', 'Soát Vé QR Code')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Soát vé</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('staff.ticket-scanner.today-trips') }}" class="btn btn-info">
            <i class="fas fa-list mr-1"></i>Xem Chuyến Xe Hôm Nay
        </a>
    </div>
</div>

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
                    <div id="reader" style="width: 100%; min-height: 300px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                        <div class="text-center text-muted">
                            <i class="fas fa-camera fa-3x mb-3"></i>
                            <p>Nhấn "Bật Camera" để bắt đầu quét</p>
                        </div>
                    </div>
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

                <div class="divider my-3">
                    <span>HOẶC</span>
                </div>

                <div class="manual-input-section">
                    <div class="input-group">
                        <input type="text" id="manualTicketCode" class="form-control" placeholder="Nhập mã vé thủ công (nếu không quét được)">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success" onclick="verifyManualTicket()">
                                <i class="fas fa-search mr-1"></i>Kiểm tra
                            </button>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2 text-center">Nhập mã vé nếu không thể quét QR</small>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    Hướng camera vào mã QR, upload ảnh, hoặc nhập mã vé
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
<!-- jsQR for image file scanning -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
    let html5QrcodeScanner;
    let lastScannedData = null;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle image upload for QR scanning - Sử dụng jsQR
    function handleImageUpload(event) {
        const file = event.target.files[0];
        if (!file) {
            return;
        }

        // Validate file type
        if (!file.type.match('image.*')) {
            Swal.fire({
                icon: 'error',
                title: 'File không hợp lệ',
                text: 'Vui lòng chọn file ảnh (JPG, PNG, etc.)',
                confirmButtonColor: '#007bff'
            });
            event.target.value = '';
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Đang xử lý...',
            html: 'Đang đọc mã QR từ ảnh',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Stop camera if running
        if (html5QrcodeScanner) {
            stopScanner();
        }

        // Read file as image using FileReader
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = new Image();
            
            img.onload = function() {
                try {
                    // Create canvas to draw image
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);
                    
                    // Get image data
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    
                    // Scan for QR code using jsQR
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });
                    
                    // Clear file input
                    event.target.value = '';
                    
                    if (code && code.data) {
                        // QR code found!
                        Swal.close();
                        verifyTicket(code.data);
                    } else {
                        // No QR code found
                        Swal.fire({
                            icon: 'error',
                            title: 'Không thể quét mã QR',
                            html: '<strong>Ảnh không chứa mã QR hợp lệ</strong><br><br>' +
                                  'Vui lòng thử:<br>' +
                                  '• Chụp ảnh QR rõ hơn<br>' +
                                  '• Đảm bảo mã QR nằm trong khung hình<br>' +
                                  '• Sử dụng camera để quét trực tiếp<br>' +
                                  '• Hoặc nhập mã vé thủ công',
                            confirmButtonText: 'Đóng',
                            confirmButtonColor: '#007bff'
                        });
                    }
                } catch (error) {
                    event.target.value = '';
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi xử lý ảnh',
                        text: 'Không thể xử lý file ảnh này: ' + error.message,
                        confirmButtonColor: '#007bff'
                    });
                }
            };
            
            img.onerror = function() {
                event.target.value = '';
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể tải ảnh. Vui lòng thử file khác.',
                    confirmButtonColor: '#007bff'
                });
            };
            
            img.src = e.target.result;
        };
        
        reader.onerror = function() {
            event.target.value = '';
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Không thể đọc file. Vui lòng thử lại.',
                confirmButtonColor: '#007bff'
            });
        };
        
        // Read the file
        reader.readAsDataURL(file);
    }

    function startScanner() {
        document.getElementById('startScanBtn').classList.add('d-none');
        document.getElementById('stopScanBtn').classList.remove('d-none');

        try {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: true
                },
                /* verbose= */ false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        } catch (error) {
            console.error('Error starting scanner:', error);
            document.getElementById('startScanBtn').classList.remove('d-none');
            document.getElementById('stopScanBtn').classList.add('d-none');
            
            Swal.fire({
                icon: 'error',
                title: 'Lỗi khởi động camera',
                text: 'Không thể khởi động camera. Vui lòng kiểm tra quyền truy cập camera hoặc sử dụng chức năng Upload ảnh.',
                confirmButtonColor: '#007bff'
            });
        }
    }

    function stopScanner() {
        if (html5QrcodeScanner) {
            try {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            } catch (error) {
                console.error('Error stopping scanner:', error);
            }
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

    function verifyManualTicket() {
        const ticketCode = document.getElementById('manualTicketCode').value.trim();
        
        if (!ticketCode) {
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu thông tin',
                text: 'Vui lòng nhập mã vé',
                confirmButtonColor: '#007bff'
            });
            return;
        }

        // Show loading
        showLoading();

        // Search by ticket code
        fetch('{{ route("staff.ticket-scanner.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ 
                ticket_code: ticketCode,
                manual: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTicketInfo(data.ticket, null);
                document.getElementById('manualTicketCode').value = '';
            } else {
                showError(data.message || 'Không tìm thấy vé với mã này');
            }
        })
        .catch(error => {
            showError('Lỗi kết nối: ' + error.message);
        });
    }

    function displayTicketInfo(ticket, qrData) {
        // Hiển thị thông báo quét thành công
        Swal.fire({
            icon: 'success',
            title: 'Quét QR Thành Công!',
            html: `<strong>Mã vé:</strong> ${ticket.ticket_code}<br>` +
                  `<strong>Hành khách:</strong> ${ticket.passenger.name}<br>` +
                  `<strong>Số ghế:</strong> ${ticket.seats}`,
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true
        });

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
                    <th width="40%">Mã chuyến</th>
                    <td><strong class="text-primary">${ticket.trip.trip_code}</strong></td>
                </tr>
                <tr>
                    <th>Tên xe</th>
                    <td><strong>${ticket.trip.name}</strong></td>
                </tr>
                <tr>
                    <th>Nhà xe</th>
                    <td>${ticket.trip.company}</td>
                </tr>
                <tr>
                    <th>Loại xe</th>
                    <td><span class="badge badge-info">${ticket.trip.vehicle_type}</span></td>
                </tr>
                <tr>
                    <th>Tổng số ghế</th>
                    <td>${ticket.trip.total_seats} ghế</td>
                </tr>
                <tr>
                    <th>Tài xế</th>
                    <td>${ticket.trip.driver}</td>
                </tr>
                <tr>
                    <th>SĐT tài xế</th>
                    <td><a href="tel:${ticket.trip.driver_phone}">${ticket.trip.driver_phone}</a></td>
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

            <div class="mt-3 text-center">
                <a href="{{ url('/staff/ticket-scanner/trip') }}/${ticket.trip.trip_id}" class="btn btn-info btn-sm" target="_blank">
                    <i class="fas fa-users mr-1"></i>Xem danh sách hành khách
                </a>
            </div>

            <div class="mt-4">
                ${!ticket.already_scanned ? `
                    <button onclick="checkInTicket('${ticket.booking_id}', '${qrData}')" class="btn btn-success btn-lg btn-block">
                        <i class="fas fa-check-circle mr-2"></i>CHECK-IN - KHÁCH ĐÃ LÊN XE
                    </button>
                ` : `
                    <div class="alert alert-success text-center mb-3">
                        <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                        <strong>KHÁCH ĐÃ LÊN XE</strong>
                    </div>
                `}
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

    // Không tự động bật camera - để người dùng tự bật khi cần
    // Điều này tránh loading không cần thiết và tiết kiệm tài nguyên

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

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #dee2e6;
    }

    .divider span {
        padding: 0 10px;
        color: #6c757d;
        font-weight: bold;
        font-size: 12px;
    }

    .manual-input-section {
        margin-top: 15px;
    }

    #manualTicketCode {
        height: 45px;
        font-size: 16px;
    }

    .manual-input-section .btn {
        height: 45px;
    }

    /* Ẩn loading spinner của html5-qrcode khi đang xử lý file */
    #temp-qr-reader {
        display: none !important;
    }

    /* Ẩn tất cả loading indicator không mong muốn */
    #reader__dashboard_section_csr,
    #reader__scan_region__dashboard {
        display: none !important;
    }

    /* Chỉ hiện loading khi đang scan camera, không phải upload file */
    .scanner-container.scanning #reader__dashboard_section_csr {
        display: block !important;
    }
</style>
@endsection
