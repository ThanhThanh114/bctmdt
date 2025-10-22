<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đặt vé mới</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 30px;
        text-align: center;
        border-radius: 10px 10px 0 0;
    }

    .header h1 {
        margin: 0;
        font-size: 24px;
    }

    .content {
        background: #fff;
        padding: 30px;
        border: 1px solid #ddd;
    }

    .alert {
        background: #fff3cd;
        border: 1px solid #ffc107;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
        text-align: center;
    }

    .alert strong {
        color: #856404;
        font-size: 18px;
    }

    .booking-code {
        background: #f8f9fa;
        padding: 15px;
        border-left: 4px solid #dc3545;
        margin: 20px 0;
    }

    .booking-code strong {
        color: #dc3545;
        font-size: 18px;
    }

    .trip-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .trip-info h3 {
        margin-top: 0;
        color: #dc3545;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .customer-info {
        background: #e8f5e9;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .customer-info h3 {
        margin-top: 0;
        color: #28a745;
    }

    .seats {
        background: #fff;
        padding: 15px;
        border: 2px dashed #dc3545;
        border-radius: 8px;
        margin: 15px 0;
        text-align: center;
    }

    .seats strong {
        color: #dc3545;
        font-size: 16px;
    }

    .price-summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
    }

    .total {
        font-size: 20px;
        font-weight: bold;
        color: #dc3545;
        border-top: 2px solid #dc3545;
        padding-top: 15px;
        margin-top: 15px;
    }

    .action-buttons {
        text-align: center;
        margin: 30px 0;
    }

    .action-button {
        display: inline-block;
        padding: 12px 24px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin: 0 10px;
    }

    .footer {
        background: #f8f9fa;
        padding: 20px;
        text-align: center;
        border-radius: 0 0 10px 10px;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>🔔 THÔNG BÁO ĐẶT VÉ MỚI</h1>
        <p style="margin: 10px 0 0 0;">FUTA Bus Lines - Admin System</p>
    </div>

    <div class="content">
        <div class="alert">
            <strong>⚠️ CÓ ĐẶT VÉ MỚI CẦN XỬ LÝ!</strong>
        </div>

        <p><strong>Thời gian đặt:</strong> <?php echo e(date('d/m/Y H:i:s', strtotime($bookings->first()->ngay_dat))); ?></p>

        <div class="booking-code">
            <strong>Mã đặt vé: <?php echo e($bookingCode); ?></strong>
        </div>

        <?php
        $firstBooking = $bookings->first();
        $trip = $firstBooking->chuyenXe;
        $user = $firstBooking->user;
        ?>

        <div class="customer-info">
            <h3>👤 Thông tin khách hàng</h3>

            <div class="info-row">
                <span><strong>Họ tên:</strong></span>
                <span><?php echo e($user->fullname ?? 'N/A'); ?></span>
            </div>

            <div class="info-row">
                <span><strong>Email:</strong></span>
                <span><?php echo e($user->email ?? 'N/A'); ?></span>
            </div>

            <div class="info-row">
                <span><strong>SĐT:</strong></span>
                <span><?php echo e($user->phone ?? 'N/A'); ?></span>
            </div>
        </div>

        <div class="trip-info">
            <h3>🚌 Thông tin chuyến xe</h3>

            <div class="info-row">
                <span><strong>Tuyến:</strong></span>
                <span><?php echo e($trip->tramDi->ten_tram); ?> → <?php echo e($trip->tramDen->ten_tram); ?></span>
            </div>

            <div class="info-row">
                <span><strong>Nhà xe:</strong></span>
                <span><?php echo e($trip->nhaXe->ten_nha_xe); ?></span>
            </div>

            <div class="info-row">
                <span><strong>Loại xe:</strong></span>
                <span><?php echo e($trip->loai_xe); ?></span>
            </div>

            <div class="info-row">
                <span><strong>Ngày đi:</strong></span>
                <span><?php echo e(date('d/m/Y', strtotime($trip->ngay_di))); ?></span>
            </div>

            <div class="info-row">
                <span><strong>Giờ khởi hành:</strong></span>
                <span><?php echo e(date('H:i', strtotime($trip->gio_di))); ?></span>
            </div>
        </div>

        <div class="seats">
            <strong>Số ghế đã đặt:</strong>
            <p style="font-size: 18px; color: #dc3545; margin: 10px 0;">
                <?php echo e($bookings->pluck('so_ghe')->implode(', ')); ?>

            </p>
            <small>(Tổng: <?php echo e($bookings->count()); ?> ghế)</small>
        </div>

        <div class="price-summary">
            <h3 style="margin-top: 0; color: #dc3545;">💰 Chi tiết thanh toán</h3>

            <div class="price-row">
                <span>Giá vé (<?php echo e($bookings->count()); ?> x <?php echo e(number_format($trip->gia_ve, 0, ',', '.')); ?>đ):</span>
                <span><strong><?php echo e(number_format($trip->gia_ve * $bookings->count(), 0, ',', '.')); ?>đ</strong></span>
            </div>

            <?php if($discountAmount > 0): ?>
            <div class="price-row" style="color: #28a745;">
                <span>Giảm giá:</span>
                <span><strong>-<?php echo e(number_format($discountAmount, 0, ',', '.')); ?>đ</strong></span>
            </div>
            <?php endif; ?>

            <div class="price-row total">
                <span>TỔNG CỘNG:</span>
                <span><?php echo e(number_format($totalAmount, 0, ',', '.')); ?>đ</span>
            </div>
        </div>

        <div class="action-buttons">
            <a href="<?php echo e(url('/admin/bookings')); ?>" class="action-button">👁️ Xem chi tiết</a>
            <a href="<?php echo e(url('/admin/bookings?ma_ve=' . $bookingCode)); ?>" class="action-button">🔍 Tìm kiếm vé</a>
        </div>

        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            Email này được gửi tự động khi có đặt vé mới trong hệ thống.
        </p>
    </div>

    <div class="footer">
        <p style="margin: 0; font-size: 12px; color: #666;">
            © <?php echo e(date('Y')); ?> FUTA Bus Lines - Admin System<br>
            Email này được gửi tự động, vui lòng không trả lời.
        </p>
    </div>
</body>

</html><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/emails/admin-booking-notification.blade.php ENDPATH**/ ?>