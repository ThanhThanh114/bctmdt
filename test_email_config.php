<?php
/**
 * TEST EMAIL CONFIGURATION
 * Chạy: php test_email_config.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "🔧 KIỂM TRA CẤU HÌNH EMAIL\n";
echo "========================================\n\n";

// Kiểm tra .env
echo "📋 Cấu hình từ .env:\n";
echo "   MAIL_USERNAME: " . (env('MAIL_USERNAME') ?: '❌ CHƯA CÓ') . "\n";
echo "   MAIL_PASSWORD: " . (env('MAIL_PASSWORD') ? '✓ Đã có (' . strlen(env('MAIL_PASSWORD')) . ' ký tự)' : '❌ CHƯA CÓ') . "\n";
echo "   MAIL_FROM_ADDRESS: " . (env('MAIL_FROM_ADDRESS') ?: '❌ CHƯA CÓ') . "\n\n";

if (!env('MAIL_USERNAME') || !env('MAIL_PASSWORD')) {
    echo "❌ LỖI: Chưa cấu hình MAIL_USERNAME hoặc MAIL_PASSWORD trong .env\n\n";
    echo "📝 Hướng dẫn:\n";
    echo "1. Mở file .env\n";
    echo "2. Thêm/sửa các dòng:\n";
    echo "   MAIL_USERNAME=your-email@gmail.com\n";
    echo "   MAIL_PASSWORD=your-16-char-app-password\n";
    echo "   MAIL_FROM_ADDRESS=your-email@gmail.com\n\n";
    echo "3. Tạo Gmail App Password:\n";
    echo "   - Vào: https://myaccount.google.com/apppasswords\n";
    echo "   - Chọn 'Mail' và 'Other'\n";
    echo "   - Copy password 16 ký tự\n\n";
    exit(1);
}

echo "🔄 Đang test kết nối SMTP...\n\n";

try {
    $mail = new PHPMailer(true);

    // Bật debug
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function ($str, $level) {
        echo "   📝 $str\n";
    };

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = env('MAIL_USERNAME');
    $mail->Password = env('MAIL_PASSWORD');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom(env('MAIL_FROM_ADDRESS'), 'Test System');

    // Thay đổi email nhận tại đây
    $testEmail = env('MAIL_USERNAME'); // Gửi về chính email gửi
    $mail->addAddress($testEmail, 'Test User');

    $mail->isHTML(true);
    $mail->Subject = 'Test Email - ' . date('H:i:s d/m/Y');
    $mail->Body = '
        <h2>✅ Email test thành công!</h2>
        <p>Hệ thống email đã hoạt động bình thường.</p>
        <p><strong>Thời gian:</strong> ' . date('H:i:s d/m/Y') . '</p>
    ';

    echo "\n📧 Đang gửi email test đến: $testEmail\n\n";

    $mail->send();

    echo "\n";
    echo "========================================\n";
    echo "✅ GỬI EMAIL THÀNH CÔNG!\n";
    echo "========================================\n\n";
    echo "📬 Kiểm tra hộp thư: $testEmail\n";
    echo "💡 Nếu không thấy email, check thư mục Spam\n\n";

} catch (Exception $e) {
    echo "\n";
    echo "========================================\n";
    echo "❌ GỬI EMAIL THẤT BẠI!\n";
    echo "========================================\n\n";
    echo "🐛 Lỗi: {$mail->ErrorInfo}\n\n";
    echo "💡 Các nguyên nhân có thể:\n";
    echo "   1. MAIL_PASSWORD sai (phải dùng App Password 16 ký tự)\n";
    echo "   2. Gmail chưa bật 2-Step Verification\n";
    echo "   3. Firewall/Antivirus chặn port 587\n";
    echo "   4. Internet connection issue\n\n";
}
