<?php
/**
 * SCRIPT CHUẨN HÓA DỮ LIỆU CHUYẾN XE
 * Chạy: php fix_trip_data.php
 */

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo str_repeat("=", 70) . "\n";
echo "🔧 CHUẨN HÓA DỮ LIỆU CHUYẾN XE\n";
echo str_repeat("=", 70) . "\n\n";

// 1. Kiểm tra chuyến xe có giá vé bất thường
echo "📊 KIỂM TRA GIÁ VÉ BẤT THƯỜNG:\n";
echo str_repeat("-", 70) . "\n";

$abnormalPrices = DB::table('chuyen_xe')
    ->select('id', 'ten_xe', 'gia_ve')
    ->where(function($q) {
        $q->where('gia_ve', '>', 1000000)  // Quá 1 triệu
          ->orWhere('gia_ve', '<', 50000); // Dưới 50k
    })
    ->get();

if ($abnormalPrices->isEmpty()) {
    echo "✅ Không có chuyến xe nào có giá bất thường\n\n";
} else {
    echo "⚠️  Tìm thấy " . $abnormalPrices->count() . " chuyến xe có giá bất thường:\n\n";
    foreach ($abnormalPrices as $trip) {
        echo "   ID: {$trip->id} | {$trip->ten_xe} | Giá: " . number_format($trip->gia_ve) . "đ\n";
    }
    echo "\n";
}

// 2. Kiểm tra chuyến xe có số chỗ bất thường
echo "📊 KIỂM TRA SỐ CHỖ BẤT THƯỜNG:\n";
echo str_repeat("-", 70) . "\n";

$abnormalSeats = DB::table('chuyen_xe')
    ->select('id', 'ten_xe', 'so_cho')
    ->where(function($q) {
        $q->where('so_cho', '>', 50)  // Quá 50 chỗ
          ->orWhere('so_cho', '<', 10); // Dưới 10 chỗ
    })
    ->get();

if ($abnormalSeats->isEmpty()) {
    echo "✅ Không có chuyến xe nào có số chỗ bất thường\n\n";
} else {
    echo "⚠️  Tìm thấy " . $abnormalSeats->count() . " chuyến xe có số chỗ bất thường:\n\n";
    foreach ($abnormalSeats as $trip) {
        echo "   ID: {$trip->id} | {$trip->ten_xe} | Số chỗ: {$trip->so_cho}\n";
    }
    echo "\n";
}

// 3. Thống kê giá vé theo khoảng
echo "📊 THỐNG KÊ GIÁ VÉ:\n";
echo str_repeat("-", 70) . "\n";

$priceRanges = [
    ['min' => 0, 'max' => 100000, 'label' => 'Dưới 100k'],
    ['min' => 100000, 'max' => 200000, 'label' => '100k - 200k'],
    ['min' => 200000, 'max' => 300000, 'label' => '200k - 300k'],
    ['min' => 300000, 'max' => 500000, 'label' => '300k - 500k'],
    ['min' => 500000, 'max' => 1000000, 'label' => '500k - 1 triệu'],
    ['min' => 1000000, 'max' => PHP_INT_MAX, 'label' => 'Trên 1 triệu'],
];

foreach ($priceRanges as $range) {
    $count = DB::table('chuyen_xe')
        ->where('gia_ve', '>=', $range['min'])
        ->where('gia_ve', '<', $range['max'])
        ->count();
    
    echo sprintf("   %-20s: %d chuyến\n", $range['label'], $count);
}
echo "\n";

// 4. Kiểm tra field so_ve (đã deprecated)
echo "📊 KIỂM TRA FIELD 'SO_VE' (deprecated):\n";
echo str_repeat("-", 70) . "\n";

$soVeStats = DB::table('chuyen_xe')
    ->selectRaw('COUNT(*) as total, SUM(so_ve) as total_so_ve')
    ->first();

echo "   Tổng số chuyến xe: {$soVeStats->total}\n";
echo "   Tổng 'so_ve': {$soVeStats->total_so_ve}\n";

if ($soVeStats->total_so_ve > 0) {
    echo "   ⚠️  Field 'so_ve' vẫn có dữ liệu (nên xóa)\n";
} else {
    echo "   ✅ Field 'so_ve' đã được làm sạch\n";
}
echo "\n";

// 5. Gợi ý fix
echo str_repeat("=", 70) . "\n";
echo "💡 GỢI Ý CHUẨN HÓA:\n";
echo str_repeat("=", 70) . "\n";

if (!$abnormalPrices->isEmpty()) {
    echo "1. Sửa giá vé bất thường:\n";
    foreach ($abnormalPrices as $trip) {
        $suggestedPrice = 200000; // Giá mặc định
        echo "   UPDATE chuyen_xe SET gia_ve = {$suggestedPrice} WHERE id = {$trip->id};\n";
    }
    echo "\n";
}

echo "2. Nên set so_ve = 0 cho tất cả (field này không còn dùng):\n";
echo "   UPDATE chuyen_xe SET so_ve = 0;\n\n";

echo "3. Giới hạn số vé đặt tối đa: 10 vé/lần (đã cập nhật trong view)\n\n";

echo str_repeat("=", 70) . "\n";
echo "✅ HOÀN TẤT KIỂM TRA!\n";
echo str_repeat("=", 70) . "\n";
