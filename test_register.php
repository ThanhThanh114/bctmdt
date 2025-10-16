<?php
/**
 * TEST ĐĂNG KÝ TÀI KHOẢN
 * Chạy: php test_register.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🧪 TEST ĐĂNG KÝ TÀI KHOẢN\n";
echo "========================================\n\n";

// Kiểm tra cấu trúc bảng users
echo "📋 Kiểm tra cấu trúc bảng users:\n";
try {
    $columns = DB::select('DESCRIBE users');
    echo "   Các cột trong bảng users:\n";
    foreach ($columns as $col) {
        echo "   - {$col->Field} ({$col->Type}) " . ($col->Null === 'YES' ? 'nullable' : 'NOT NULL') . "\n";
    }
    echo "\n";
} catch (\Exception $e) {
    echo "   ❌ Lỗi: " . $e->getMessage() . "\n\n";
}

// Test tạo user
echo "🔄 Test tạo tài khoản mới:\n";
$testData = [
    'username' => 'testuser' . time(),
    'fullname' => 'Test User',
    'email' => 'test' . time() . '@example.com',
    'phone' => '090' . rand(1000000, 9999999),
    'password' => 'password123',
    'role' => 'user'
];

echo "   Dữ liệu test:\n";
foreach ($testData as $key => $value) {
    if ($key !== 'password') {
        echo "   - {$key}: {$value}\n";
    } else {
        echo "   - {$key}: ******\n";
    }
}
echo "\n";

try {
    $user = User::create([
        'username' => $testData['username'],
        'fullname' => $testData['fullname'],
        'email' => $testData['email'],
        'phone' => $testData['phone'],
        'password' => Hash::make($testData['password']),
        'role' => $testData['role']
    ]);

    echo "✅ TẠO TÀI KHOẢN THÀNH CÔNG!\n";
    echo "========================================\n";
    echo "   ID: {$user->id}\n";
    echo "   Username: {$user->username}\n";
    echo "   Email: {$user->email}\n";
    echo "   Phone: {$user->phone}\n";
    echo "   Role: {$user->role}\n\n";

    // Xóa user test
    echo "🗑️  Xóa user test...\n";
    $user->delete();
    echo "✅ Đã xóa user test\n\n";

} catch (\Exception $e) {
    echo "\n❌ TẠO TÀI KHOẢN THẤT BẠI!\n";
    echo "========================================\n";
    echo "🐛 Lỗi: " . $e->getMessage() . "\n\n";

    if (strpos($e->getMessage(), 'Unknown column') !== false) {
        echo "💡 Nguyên nhân: Bảng users thiếu cột\n";
        echo "   Kiểm tra lại migration hoặc cấu trúc database\n\n";
    } elseif (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        echo "💡 Nguyên nhân: Username, email hoặc phone đã tồn tại\n\n";
    }

    echo "📝 Stack trace:\n";
    echo $e->getTraceAsString() . "\n\n";
}

// Kiểm tra Model User fillable
echo "📋 Kiểm tra Model User:\n";
$user = new User();
$fillable = $user->getFillable();
echo "   Các trường fillable:\n";
foreach ($fillable as $field) {
    echo "   - {$field}\n";
}
echo "\n";
