<?php
// Test file để kiểm tra tính năng lọc tài xế theo nhà xe

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Khởi tạo Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST TÍNH NĂNG LỌC TÀI XẾ THEO NHÀ XE ===\n\n";

// Test 1: Kiểm tra API endpoint với tất cả nhà xe
echo "1. Test API với tất cả nhà xe:\n";
try {
    $response = Http::get('http://127.0.0.1:8000/api/drivers-by-company', [
        'bus_company' => 'all'
    ]);

    if ($response->successful()) {
        $data = $response->json();
        echo "✓ API thành công\n";
        echo "Số lượng tài xế: " . count($data['drivers']) . "\n";
        if (count($data['drivers']) > 0) {
            echo "Một số tài xế: " . implode(', ', array_slice($data['drivers'], 0, 3)) . "...\n";
        }
    } else {
        echo "✗ API thất bại: " . $response->status() . "\n";
    }
} catch (Exception $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Kiểm tra API với nhà xe cụ thể (nếu có)
echo "2. Test API với nhà xe cụ thể:\n";
try {
    // Lấy một nhà xe từ database
    $nhaXe = \App\Models\NhaXe::first();
    if ($nhaXe) {
        echo "Testing với nhà xe: " . $nhaXe->ten_nha_xe . "\n";

        $response = Http::get('http://127.0.0.1:8000/api/drivers-by-company', [
            'bus_company' => $nhaXe->ma_nha_xe
        ]);

        if ($response->successful()) {
            $data = $response->json();
            echo "✓ API thành công\n";
            echo "Số lượng tài xế của nhà xe này: " . count($data['drivers']) . "\n";
            if (count($data['drivers']) > 0) {
                echo "Tài xế: " . implode(', ', $data['drivers']) . "\n";
            }
        } else {
            echo "✗ API thất bại: " . $response->status() . "\n";
        }
    } else {
        echo "Không có nhà xe trong database để test\n";
    }
} catch (Exception $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Kiểm tra cấu trúc database
echo "3. Kiểm tra cấu trúc database:\n";
try {
    // Kiểm tra bảng chuyen_xe
    $chuyenXeCount = \App\Models\ChuyenXe::count();
    echo "Tổng số chuyến xe: " . $chuyenXeCount . "\n";

    // Kiểm tra số lượng tài xế
    $taiXeCount = \App\Models\ChuyenXe::whereNotNull('ten_tai_xe')
        ->where('ten_tai_xe', '!=', '')
        ->distinct('ten_tai_xe')
        ->count();
    echo "Số lượng tài xế: " . $taiXeCount . "\n";

    // Kiểm tra số lượng nhà xe
    $nhaXeCount = \App\Models\NhaXe::count();
    echo "Số lượng nhà xe: " . $nhaXeCount . "\n";

    // Kiểm tra mối quan hệ
    $driversWithCompany = \App\Models\ChuyenXe::with('nhaXe')
        ->whereNotNull('ten_tai_xe')
        ->where('ten_tai_xe', '!=', '')
        ->distinct('ten_tai_xe')
        ->limit(5)
        ->get();

    echo "Mẫu tài xế và nhà xe:\n";
    foreach ($driversWithCompany as $driver) {
        echo "- " . $driver->ten_tai_xe . " (Nhà xe: " . $driver->nhaXe->ten_nha_xe . ")\n";
    }

} catch (Exception $e) {
    echo "✗ Lỗi database: " . $e->getMessage() . "\n";
}

echo "\n=== KẾT THÚC TEST ===\n";
