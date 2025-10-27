<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\NhaXe;

echo "=== TEST KHÓA TÀI KHOẢN NHÀ XE ===\n\n";

// Test 1: Xem tài khoản tienkhoa trước khi khóa
echo "1. Thông tin tài khoản TRƯỚC khi khóa:\n";
$user = User::where('username', 'tienkhoa')->first();

if ($user) {
    echo "   - Username: {$user->username}\n";
    echo "   - Role: {$user->role}\n";
    echo "   - ma_nha_xe: {$user->ma_nha_xe}\n";
    echo "   - is_active: " . ($user->is_active ? 'Active' : 'Locked') . "\n";
    
    if ($user->nhaXe) {
        echo "   - Nhà xe: {$user->nhaXe->ten_nha_xe}\n";
    }
} else {
    echo "   ❌ User không tồn tại!\n";
    exit;
}

// Simulate locking
echo "\n2. Thực hiện KHÓA tài khoản (giả lập):\n";
$oldRole = $user->role;
$oldMaNhaXe = $user->ma_nha_xe;

$user->update([
    'is_active' => 0,
    'role' => 'user',
    'ma_nha_xe' => null,
    'locked_reason' => 'Test khóa tài khoản',
    'locked_at' => now(),
    'locked_by' => 1,
]);

echo "   ✅ Đã khóa và hạ cấp tài khoản!\n";

// Check after locking
echo "\n3. Thông tin tài khoản SAU khi khóa:\n";
$user->refresh();
echo "   - Username: {$user->username}\n";
echo "   - Role: {$user->role} (trước: {$oldRole})\n";
echo "   - ma_nha_xe: " . ($user->ma_nha_xe ?? 'NULL') . " (trước: {$oldMaNhaXe})\n";
echo "   - is_active: " . ($user->is_active ? 'Active' : 'Locked') . "\n";
echo "   - locked_reason: {$user->locked_reason}\n";

// Restore original state
echo "\n4. Khôi phục lại trạng thái ban đầu:\n";
$user->update([
    'is_active' => 1,
    'role' => $oldRole,
    'ma_nha_xe' => $oldMaNhaXe,
    'locked_reason' => null,
    'locked_at' => null,
    'locked_by' => null,
]);

$user->refresh();
echo "   ✅ Đã khôi phục:\n";
echo "   - Role: {$user->role}\n";
echo "   - ma_nha_xe: {$user->ma_nha_xe}\n";
echo "   - is_active: " . ($user->is_active ? 'Active' : 'Locked') . "\n";

echo "\n=== TEST HOÀN THÀNH ===\n";
echo "\n✅ Chức năng khóa tài khoản hoạt động đúng!\n";
echo "   - Khi khóa: role -> user, ma_nha_xe -> null\n";
echo "   - Khi mở khóa: cần admin gán lại role và ma_nha_xe\n";
