# ✅ FIX QUÊN MẬT KHẨU & GỬI EMAIL VÉ XE - HOÀN THÀNH

## 🎯 Đã hoàn thành

### 1. Fix trang Quên mật khẩu ✅

**Vấn đề:**

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'reset_token_expires_at'
```

**Giải pháp:**

- ✅ Tạo migration thêm cột `reset_token_expires_at` vào bảng `users`
- ✅ Chạy migration thành công
- ✅ Tích hợp PHPMailer để gửi OTP qua email
- ✅ Email template đẹp với mã OTP 6 số
- ✅ OTP có hiệu lực 5 phút

**Files:**

- `database/migrations/2025_10_16_075221_add_reset_token_to_users_table.php`
- `app/Http/Controllers/AuthController.php`

---

### 2. Gửi email xác nhận vé xe sau thanh toán ✅

**Tính năng:**

- ✅ Tự động gửi email khi thanh toán thành công
- ✅ Email chứa đầy đủ thông tin:
    - Mã đặt vé
    - Thông tin chuyến đi (Tuyến, Ngày giờ, Biển số xe)
    - Thông tin hành khách
    - Danh sách ghế
    - Chi tiết thanh toán
    - Mã giảm giá (nếu có)
- ✅ Email template responsive, đẹp mắt
- ✅ Chỉ gửi nếu khách hàng có email
- ✅ Không ảnh hưởng thanh toán nếu email fail

**Files:**

- `app/Http/Controllers/BookingController.php`

---

## 📧 Cấu hình Email (file .env)

```env
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS="noreply@example.com"
```

**Lưu ý Gmail:**

- Tạo App Password tại: https://myaccount.google.com/apppasswords
- Bật 2-Step Verification trước
- Dùng App Password 16 ký tự (không phải mật khẩu Gmail thường)

---

## 🧪 Test

### Test 1: Quên mật khẩu

1. Vào http://127.0.0.1:8000/login
2. Nhấn "Quên mật khẩu"
3. Nhập email: `thanhloi1141308@gmail.com`
4. Nhấn "Gửi mã OTP"
5. ✅ Thấy "Mã OTP đã được gửi đến email của bạn"
6. Check email → Có OTP 6 số
7. Nhập OTP → Đặt lại mật khẩu

### Test 2: Email vé xe

1. Đặt vé với email hợp lệ
2. Chọn ghế, nhập thông tin (có email)
3. Áp dụng mã giảm giá (optional)
4. Thanh toán thành công
5. ✅ Email tự động gửi
6. Check inbox → Có email xác nhận vé

---

## 📋 Cấu trúc Database

### Bảng `users` - Các cột mới:

- `reset_token` (varchar 10, nullable) - Mã OTP
- `reset_token_expires_at` (timestamp, nullable) - Thời gian hết hạn OTP

---

## 🎨 Email Templates

### Email 1: Reset Password OTP

- 🔐 Header gradient cam
- 🔢 Mã OTP 6 số to, rõ ràng
- ⏰ Thông báo hiệu lực 5 phút
- ⚠️ Cảnh báo bảo mật

### Email 2: Booking Confirmation

- 🎫 Header gradient cam
- 📋 Mã đặt vé nổi bật
- 📍 Thông tin chuyến đi đầy đủ
- 💺 Ghế hiển thị dạng badge
- 💰 Chi tiết thanh toán với discount
- ✓ Trạng thái thanh toán
- 📝 Lưu ý cho khách

---

## 🔧 Troubleshooting

### Không nhận email?

1. Kiểm tra .env có đúng MAIL_USERNAME và MAIL_PASSWORD
2. Kiểm tra Gmail App Password (16 ký tự)
3. Kiểm tra storage/logs/laravel.log xem có lỗi gì
4. Test gửi email đơn giản:

```php
php artisan tinker

$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = env('MAIL_USERNAME');
$mail->Password = env('MAIL_PASSWORD');
$mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->setFrom('noreply@example.com');
$mail->addAddress('test@example.com');
$mail->Subject = 'Test';
$mail->Body = 'Test email';
$mail->send();
```

### Email vào Spam?

- Kiểm tra FROM address hợp lệ
- Thêm SPF record cho domain
- Sử dụng dịch vụ email chuyên nghiệp (SendGrid, Mailgun, etc.)

---

## ✨ Tính năng nổi bật

- ✅ OTP reset password qua email
- ✅ Email xác nhận vé tự động
- ✅ PHPMailer integration
- ✅ Error handling an toàn
- ✅ Email templates đẹp, responsive
- ✅ Hỗ trợ mã giảm giá trong email
- ✅ Không ảnh hưởng flow chính nếu email fail

---

**Ngày:** 16/10/2025  
**Status:** ✅ Production Ready
