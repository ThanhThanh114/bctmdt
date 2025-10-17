<?php
/**
 * Script để sửa lại đường dẫn ảnh tin tức trong database
 * Chạy: php fix_news_images.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔧 Đang cập nhật ảnh tin tức...\n\n";

// Map các tin tức với ảnh mặc định có sẵn
$updates = [
    ['ma_tin' => 1, 'hinh_anh' => 'km1.jpg'],      // Khuyến mãi tháng 10
    ['ma_tin' => 2, 'hinh_anh' => 'hanoi.jpg'],    // Mở tuyến mới Hà Nội - Sapa
    ['ma_tin' => 3, 'hinh_anh' => 'km2.png'],      // Tặng quà khách hàng
    ['ma_tin' => 4, 'hinh_anh' => 'dn.jpg'],       // Cập nhật lịch trình Đà Nẵng
    ['ma_tin' => 5, 'hinh_anh' => 'dalat.jpg'],    // Thông báo nghỉ lễ
    ['ma_tin' => 7, 'hinh_anh' => 'km3.png'],      // Giảm giá cuối tuần
    ['ma_tin' => 8, 'hinh_anh' => 'hue.jpg'],      // Cảnh báo lừa đảo
    ['ma_tin' => 9, 'hinh_anh' => 'tphcm.jpg'],    // Tin tuyển dụng
    ['ma_tin' => 10, 'hinh_anh' => 'nt.jpg'],      // Cập nhật ứng dụng
    ['ma_tin' => 11, 'hinh_anh' => 'anh1.jpg'],    // Giảm giá cực cháy
    ['ma_tin' => 12, 'hinh_anh' => 'anh2.jpg'],    // TEST1
    ['ma_tin' => 13, 'hinh_anh' => 'vl.jpg'],      // TEST_THEM
    ['ma_tin' => 14, 'hinh_anh' => 'lx.jpg'],      // Hoang huy
];

foreach ($updates as $update) {
    DB::table('tin_tuc')
        ->where('ma_tin', $update['ma_tin'])
        ->update(['hinh_anh' => $update['hinh_anh']]);
    
    echo "✅ Cập nhật ảnh cho tin tức #{$update['ma_tin']}: {$update['hinh_anh']}\n";
}

// Các tin tức còn lại dùng ảnh header.jpg làm mặc định
DB::table('tin_tuc')
    ->whereNotIn('ma_tin', array_column($updates, 'ma_tin'))
    ->update(['hinh_anh' => 'header.jpg']);

echo "\n✨ Hoàn tất! Tất cả ảnh tin tức đã được cập nhật.\n";
echo "🌐 Hãy làm mới trang tin tức để xem kết quả!\n";
