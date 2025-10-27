<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Kiểm tra cấu trúc Staff:\n";
echo "=====================================\n";

$staff = \App\Models\User::where('role', 'staff')->first();

if ($staff) {
    echo "✅ Tìm thấy staff:\n";
    echo "ID: " . $staff->id . "\n";
    echo "Username: " . $staff->username . "\n";
    echo "Role: " . $staff->role . "\n";
    echo "Mã nhà xe: " . ($staff->ma_nha_xe ?? 'NULL') . "\n";
    
    if ($staff->ma_nha_xe && $staff->nhaXe) {
        echo "Tên nhà xe: " . $staff->nhaXe->ten_nha_xe . "\n";
    }
} else {
    echo "❌ Không tìm thấy staff nào\n";
}

echo "\nDanh sách staff:\n";
$allStaff = \App\Models\User::where('role', 'staff')->get();
foreach ($allStaff as $s) {
    echo "  - " . $s->username . " (Nhà xe: " . ($s->ma_nha_xe ?? 'NULL') . ")\n";
}

echo "\n";
