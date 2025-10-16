<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== KIỂM TRA DỮ LIỆU TRÙNG LẶP ===\n\n";

// Kiểm tra email
$email = 'lethanhem01011975@gmail.com';
echo "🔍 Kiểm tra email: $email\n";
$userByEmail = User::where('email', $email)->first();
if ($userByEmail) {
    echo "❌ EMAIL ĐÃ TỒN TẠI!\n";
    echo "   ID: {$userByEmail->id}\n";
    echo "   Username: {$userByEmail->username}\n";
    echo "   Fullname: {$userByEmail->fullname}\n\n";
} else {
    echo "✅ Email chưa tồn tại\n\n";
}

// Kiểm tra phone
$phone = '0966421557';
echo "🔍 Kiểm tra phone: $phone\n";
$userByPhone = User::where('phone', $phone)->first();
if ($userByPhone) {
    echo "❌ PHONE ĐÃ TỒN TẠI!\n";
    echo "   ID: {$userByPhone->id}\n";
    echo "   Username: {$userByPhone->username}\n";
    echo "   Fullname: {$userByPhone->fullname}\n\n";
} else {
    echo "✅ Phone chưa tồn tại\n\n";
}

// Kiểm tra username
$usernames = ['afh3xvas', 'hahaha'];
foreach ($usernames as $username) {
    echo "🔍 Kiểm tra username: $username\n";
    $userByUsername = User::where('username', $username)->first();
    if ($userByUsername) {
        echo "❌ USERNAME ĐÃ TỒN TẠI!\n";
        echo "   ID: {$userByUsername->id}\n";
        echo "   Email: {$userByUsername->email}\n";
        echo "   Phone: {$userByUsername->phone}\n\n";
    } else {
        echo "✅ Username chưa tồn tại\n\n";
    }
}

echo "=== KẾT THÚC KIỂM TRA ===\n";
