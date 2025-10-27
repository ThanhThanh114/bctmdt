<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== TEST KHÓA VÀ MỞ KHÓA VỚI KHÔI PHỤC QUYỀN ===\n\n";

// Test với user tienkhoa
$user = User::where('username', 'tienkhoa')->first();

if (!$user) {
    echo "❌ User không tồn tại!\n";
    exit;
}

echo "1️⃣  TRƯỚC KHI KHÓA:\n";
echo "   - username: {$user->username}\n";
echo "   - role: {$user->role}\n";
echo "   - ma_nha_xe: {$user->ma_nha_xe}\n";
echo "   - is_active: " . ($user->is_active ? 'Active' : 'Locked') . "\n";
echo "   - locked_original_role: " . ($user->locked_original_role ?? 'NULL') . "\n";
echo "   - locked_original_ma_nha_xe: " . ($user->locked_original_ma_nha_xe ?? 'NULL') . "\n";

// Lưu thông tin gốc
$originalRole = $user->role;
$originalMaNhaXe = $user->ma_nha_xe;

echo "\n2️⃣  THỰC HIỆN KHÓA:\n";
$user->update([
    'is_active' => 0,
    'locked_reason' => 'Test khóa tài khoản',
    'locked_at' => now(),
    'locked_by' => 1,
    'locked_original_role' => $originalRole,
    'locked_original_ma_nha_xe' => $originalMaNhaXe,
    'role' => 'user',
    'ma_nha_xe' => null,
]);
echo "   ✅ Đã khóa!\n";

// Kiểm tra sau khi khóa
$user->refresh();
echo "\n3️⃣  SAU KHI KHÓA:\n";
echo "   - role: {$user->role} (đã hạ xuống user)\n";
echo "   - ma_nha_xe: " . ($user->ma_nha_xe ?? 'NULL') . " (đã xóa)\n";
echo "   - is_active: " . ($user->is_active ? 'Active' : 'Locked') . "\n";
echo "   - locked_original_role: {$user->locked_original_role} (đã lưu)\n";
echo "   - locked_original_ma_nha_xe: {$user->locked_original_ma_nha_xe} (đã lưu)\n";

echo "\n4️⃣  THỰC HIỆN MỞ KHÓA:\n";
$user->update([
    'is_active' => 1,
    'locked_reason' => null,
    'locked_at' => null,
    'locked_by' => null,
    'role' => $user->locked_original_role ?? 'user',
    'ma_nha_xe' => $user->locked_original_ma_nha_xe,
    'locked_original_role' => null,
    'locked_original_ma_nha_xe' => null,
]);
echo "   ✅ Đã mở khóa và khôi phục!\n";

// Kiểm tra sau khi mở khóa
$user->refresh();
echo "\n5️⃣  SAU KHI MỞ KHÓA:\n";
echo "   - role: {$user->role} (đã khôi phục)\n";
echo "   - ma_nha_xe: {$user->ma_nha_xe} (đã khôi phục)\n";
echo "   - is_active: " . ($user->is_active ? 'Active' : 'Locked') . "\n";
echo "   - locked_original_role: " . ($user->locked_original_role ?? 'NULL') . "\n";
echo "   - locked_original_ma_nha_xe: " . ($user->locked_original_ma_nha_xe ?? 'NULL') . "\n";

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ TEST HOÀN THÀNH!\n\n";

if ($user->role === $originalRole && $user->ma_nha_xe === $originalMaNhaXe) {
    echo "🎉 THÀNH CÔNG! Đã khôi phục đúng quyền và nhà xe gốc!\n";
    echo "   - Role gốc: {$originalRole} → Hiện tại: {$user->role}\n";
    echo "   - Ma nhà xe gốc: {$originalMaNhaXe} → Hiện tại: {$user->ma_nha_xe}\n";
} else {
    echo "❌ LỖI! Không khôi phục đúng:\n";
    echo "   - Role: {$originalRole} → {$user->role}\n";
    echo "   - Ma nhà xe: {$originalMaNhaXe} → {$user->ma_nha_xe}\n";
}
