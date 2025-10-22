<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt vé</title>
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
            background: linear-gradient(135deg, #FF6F3C 0%, #FF8C42 100%);
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

        .booking-code {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #FF6F3C;
            margin: 20px 0;
        }

        .booking-code strong {
            color: #FF6F3C;
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
            color: #FF6F3C;
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

        .seats {
            background: #fff;
            padding: 15px;
            border: 2px dashed #FF6F3C;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
        }

        .seats strong {
            color: #FF6F3C;
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
            color: #FF6F3C;
            border-top: 2px solid #FF6F3C;
            padding-top: 15px;
            margin-top: 15px;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            margin-top: 20px;
        }

        .note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .contact-info {
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🎫 XÁC NHẬN ĐẶT VÉ THÀNH CÔNG</h1>
        <p style="margin: 10px 0 0 0;">FUTA Bus Lines</p>
    </div>

    <div class="content">
        <p>Kính chào <strong><?php echo e($bookings->first()->user->fullname ?? 'Quý khách'); ?></strong>,</p>

        <p>Cảm ơn bạn đã sử dụng dịch vụ của FUTA Bus Lines. Vé của bạn đã được đặt và thanh toán thành công!</p>

        <div class="booking-code">
            <strong>Mã đặt vé: <?php echo e($bookingCode); ?></strong>
        </div>

        <?php
            $firstBooking = $bookings->first();
            $trip = $firstBooking->chuyenXe;
        ?>

        <div class="trip-info">
            <h3>📍 Thông tin chuyến xe</h3>

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
            <p style="font-size: 18px; color: #FF6F3C; margin: 10px 0;">
                <?php echo e($bookings->pluck('so_ghe')->implode(', ')); ?>

            </p>
            <small>(Tổng: <?php echo e($bookings->count()); ?> ghế)</small>
        </div>

        <div class="price-summary">
            <h3 style="margin-top: 0; color: #FF6F3C;">💰 Chi tiết thanh toán</h3>

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

        <div class="note">
            <strong>⚠️ Lưu ý quan trọng:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Vui lòng có mặt tại bến xe trước giờ khởi hành <strong>15-30 phút</strong></li>
                <li>Mang theo <strong>CMND/CCCD</strong> để đối chiếu khi lên xe</li>
                <li>Xuất trình <strong>mã đặt vé</strong> này cho nhân viên</li>
                <li>Liên hệ hotline nếu cần hỗ trợ hoặc thay đổi lịch trình</li>
            </ul>
        </div>

        <div class="contact-info">
            <strong>📞 Liên hệ hỗ trợ:</strong><br>
            Hotline: <strong>1900 6067</strong><br>
            Email: <strong>support@futabus.vn</strong><br>
            Website: <strong>www.futabus.vn</strong>
        </div>

        <p style="margin-top: 30px;">Chúc bạn có một chuyến đi an toàn và vui vẻ! 🚌✨</p>

        <p style="margin-top: 20px; font-size: 14px; color: #666;">
            Trân trọng,<br>
            <strong style="color: #FF6F3C;">Đội ngũ FUTA Bus Lines</strong>
        </p>
    </div>

    <div class="footer">
        <p style="margin: 0; font-size: 12px; color: #666;">
            © <?php echo e(date('Y')); ?> FUTA Bus Lines. All rights reserved.<br>
            Email này được gửi tự động, vui lòng không trả lời.
        </p>
    </div>
</body>

</html><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/emails/booking-confirmation.blade.php ENDPATH**/ ?>