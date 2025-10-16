<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

echo "=== TEST ĐĂNG KÝ TÀI KHOẢN ===\n\n";

// Test data
$testData = [
    'username' => 'testuser' . time(),
    'fullname' => 'Nguyễn Test User',
    'email' => 'test' . time() . '@example.com',
    'phone' => '0901234567',
    'password' => Hash::make('password123'),
    'role' => 'user'
];

echo "📝 Dữ liệu test:\n";
echo "Username: " . $testData['username'] . "\n";
echo "Email: " . $testData['email'] . "\n";
echo "Phone: " . $testData['phone'] . "\n\n";

// Kiểm tra cấu trúc bảng
echo "🔍 Cấu trúc bảng users:\n";
$columns = DB::select("SHOW COLUMNS FROM users");
foreach ($columns as $column) {
    echo "- " . $column->Field . " (" . $column->Type . ") " .
        ($column->Null == 'YES' ? 'NULL' : 'NOT NULL') .
        ($column->Default ? " DEFAULT " . $column->Default : '') . "\n";
}
echo "\n";

// Thử tạo user
try {
    echo "🚀 Đang tạo user...\n";

    $user = User::create($testData);

    echo "✅ TẠO USER THÀNH CÔNG!\n";
    echo "ID: " . $user->id . "\n";
    echo "Username: " . $user->username . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Phone: " . $user->phone . "\n";
    echo "Role: " . $user->role . "\n\n";

    // Kiểm tra trong database
    echo "🔍 Kiểm tra trong database:\n";
    $dbUser = DB::table('users')->where('id', $user->id)->first();
    if ($dbUser) {
        echo "✅ Tìm thấy user trong database!\n";
        echo "Username: " . $dbUser->username . "\n";
        echo "Email: " . $dbUser->email . "\n";

        // Xóa user test
        echo "\n🗑️ Đang xóa user test...\n";
        User::destroy($user->id);
        echo "✅ Đã xóa user test\n";
    } else {
        echo "❌ KHÔNG tìm thấy user trong database!\n";
    }

} catch (\Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== KẾT THÚC TEST ===\n";
