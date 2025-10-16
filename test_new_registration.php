<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\User;

echo "=== TEST ĐĂNG KÝ VỚI DỮ LIỆU MỚI ===\n\n";

$timestamp = time();
$testData = [
    'username' => 'newuser' . $timestamp,
    'fullname' => 'User Test New',
    'email' => 'newtest' . $timestamp . '@example.com',
    'phone' => '09' . substr($timestamp, -8), // Tạo số phone từ timestamp
    'password' => Hash::make('password123'),
    'role' => 'user'
];

echo "📝 Dữ liệu test:\n";
echo "Username: " . $testData['username'] . "\n";
echo "Email: " . $testData['email'] . "\n";
echo "Phone: " . $testData['phone'] . "\n\n";

// Kiểm tra trùng
echo "🔍 Kiểm tra trùng lặp...\n";
$exists = User::where('username', $testData['username'])
    ->orWhere('email', $testData['email'])
    ->orWhere('phone', $testData['phone'])
    ->exists();

if ($exists) {
    echo "❌ Dữ liệu đã tồn tại!\n";
    exit;
}
echo "✅ Không có trùng lặp\n\n";

// Thử tạo
try {
    echo "🚀 Đang tạo user...\n";
    $user = User::create($testData);

    echo "✅ THÀNH CÔNG!\n";
    echo "ID: " . $user->id . "\n";
    echo "Username: " . $user->username . "\n\n";

    // Thử đăng nhập với user này
    echo "🔐 Test đăng nhập:\n";
    echo "Username: " . $user->username . "\n";
    echo "Password: password123\n\n";

    // Xóa
    echo "🗑️ Xóa user test...\n";
    User::destroy($user->id);
    echo "✅ Đã xóa\n";

} catch (\Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
}

echo "\n💡 HÃY THỬ ĐĂNG KÝ VỚI THÔNG TIN SAU:\n";
echo "Username: testuser" . time() . "\n";
echo "Fullname: Test User\n";
echo "Email: test" . time() . "@example.com\n";
echo "Phone: 0909" . substr(time(), -6) . "\n";
echo "Password: password123\n";
echo "Confirm Password: password123\n";

echo "\n=== KẾT THÚC ===\n";
