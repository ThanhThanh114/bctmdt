<?php

/**
 * VIEW GENERATOR SCRIPT
 * Chạy script này để tạo tất cả views còn thiếu
 * 
 * Cách chạy: php generate_views.php
 */

$basePath = __DIR__ . '/resources/views/AdminLTE/admin/';

$viewTemplates = [
    // Nhân viên
    'nhan_vien/edit.blade.php' => 'Tương tự create, thêm @method("PUT") và value="{{ old("field", $nhanvien->field) }}"',
    'nhan_vien/show.blade.php' => 'Hiển thị thông tin nhân viên dạng table',

    // Đặt vé
    'dat_ve/index.blade.php' => 'List vé với filter status, date range',
    'dat_ve/show.blade.php' => 'Chi tiết vé: thông tin user, chuyến xe, ghế, giá',
    'dat_ve/statistics.blade.php' => 'Biểu đồ thống kê đặt vé',

    // Bình luận
    'binh_luan/index.blade.php' => 'List bình luận với actions: duyệt, từ chối, xóa',
    'binh_luan/show.blade.php' => 'Chi tiết bình luận, replies',
    'binh_luan/statistics.blade.php' => 'Thống kê rating, distribution',

    // Doanh thu
    'doanh_thu/index.blade.php' => 'Dashboard doanh thu với charts',
    'doanh_thu/by_trip.blade.php' => 'Doanh thu theo chuyến xe',
    'doanh_thu/by_company.blade.php' => 'Doanh thu theo nhà xe',

    // Khuyến mãi
    'khuyen_mai/index.blade.php' => 'List khuyến mãi với status badges',
    'khuyen_mai/create.blade.php' => 'Form thêm khuyến mãi',
    'khuyen_mai/edit.blade.php' => 'Form sửa khuyến mãi',
    'khuyen_mai/show.blade.php' => 'Chi tiết + thống kê sử dụng',

    // Tin tức
    'tin_tuc/index.blade.php' => 'List tin tức với thumbnail',
    'tin_tuc/create.blade.php' => 'Form thêm tin + upload ảnh',
    'tin_tuc/edit.blade.php' => 'Form sửa tin + upload ảnh',
    'tin_tuc/show.blade.php' => 'Hiển thị tin tức đầy đủ',

    // Liên hệ
    'contact/index.blade.php' => 'List liên hệ từ khách hàng',
    'contact/show.blade.php' => 'Chi tiết liên hệ',

    // Báo cáo
    'report/index.blade.php' => 'Dashboard tổng quan hệ thống',
    'report/bookings.blade.php' => 'Báo cáo đặt vé theo thời gian',
    'report/revenue.blade.php' => 'Báo cáo doanh thu',
    'report/users.blade.php' => 'Báo cáo người dùng',
];

echo "=== VIEW GENERATOR ===\n\n";
echo "Danh sách views cần tạo:\n\n";

foreach ($viewTemplates as $file => $description) {
    $fullPath = $basePath . $file;
    $exists = file_exists($fullPath) ? '✅' : '❌';
    echo "$exists $file\n";
    echo "   └─ $description\n\n";
}

echo "\n=== HƯỚNG DẪN TẠO VIEWS ===\n\n";
echo "1. Sử dụng templates trong VIEW_CREATION_GUIDE.md\n";
echo "2. Copy từ nhan_vien/index.blade.php và chỉnh sửa\n";
echo "3. Đảm bảo dùng đúng:\n";
echo "   - Route names (admin.module.action)\n";
echo "   - Variable names ($items, $item)\n";
echo "   - Model properties\n";
echo "   - Relationships (với eager loading)\n";
echo "\n4. Mỗi view phải có:\n";
echo "   - @extends('layouts.admin')\n";
echo "   - @section('title'), @section('page-title')\n";
echo "   - @section('content')\n";
echo "   - Alert messages (success/error)\n";
echo "   - Proper Bootstrap classes\n";
echo "   - FontAwesome icons\n";
echo "\n5. Forms phải có:\n";
echo "   - @csrf token\n";
echo "   - @method('PUT') cho update\n";
echo "   - @error('field') validation\n";
echo "   - old('field') values\n";
echo "   - Required field indicators (*)\n";
echo "\n";

// Tạo cấu trúc thư mục
$directories = [
    'nhan_vien',
    'dat_ve',
    'binh_luan',
    'doanh_thu',
    'khuyen_mai',
    'tin_tuc',
    'contact',
    'report'
];

echo "=== TẠO CẤU TRÚC THƯ MỤC ===\n\n";
foreach ($directories as $dir) {
    $dirPath = $basePath . $dir;
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0755, true);
        echo "✅ Đã tạo: $dir/\n";
    } else {
        echo "⏭️  Đã tồn tại: $dir/\n";
    }
}

echo "\n=== HOÀN THÀNH ===\n";
echo "Bây giờ hãy tạo từng view theo templates!\n\n";
