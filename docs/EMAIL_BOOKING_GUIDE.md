# 📧 HƯỚNG DẪN HỆ THỐNG GỬI EMAIL XÁC NHẬN ĐẶT VÉ

## ✅ ĐÃ CÀI ĐẶT

### 1. **Chức năng tự động gửi email**

- ✅ Sau khi thanh toán thành công → Tự động gửi email xác nhận về email của khách hàng
- ✅ Email bao gồm đầy đủ thông tin:
    - Mã đặt vé
    - Thông tin chuyến xe (tuyến, ngày giờ, nhà xe)
    - Số ghế đã đặt
    - Chi tiết thanh toán (giá gốc, giảm giá, tổng tiền)
    - Lưu ý quan trọng
    - Thông tin liên hệ hỗ trợ

### 2. **Cấu hình Email đã hoàn tất**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ontapttnt@gmail.com
MAIL_PASSWORD=ntgjaxxeussamovd
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="ontapttnt@gmail.com"
MAIL_FROM_NAME="Hệ thống đặt vé xe"
```

## 📁 CÁC FILE ĐÃ TẠO/CẬP NHẬT

### 1. **Mailable Class** (`app/Mail/BookingConfirmation.php`)

- Class xử lý gửi email xác nhận đặt vé
- Chứa dữ liệu: bookings, mã vé, tổng tiền, giảm giá

### 2. **Email Template** (`resources/views/emails/booking-confirmation.blade.php`)

- Template HTML đẹp mắt, responsive
- Hiển thị đầy đủ thông tin vé
- Có styling chuyên nghiệp

### 3. **BookingController** (Đã cập nhật)

- **Phương thức `verifyPayment()`**:
    - Sau khi xác nhận thanh toán thành công
    - Tự động gửi email xác nhận đến khách hàng
    - Nếu có lỗi gửi email → Log lỗi nhưng không ảnh hưởng đến quá trình thanh toán

### 4. **Script Test** (`scripts/test_booking_email.php`)

- Dùng để test gửi email
- Chạy: `php scripts/test_booking_email.php`

## 🔄 QUY TRÌNH HOẠT ĐỘNG

```
1. Khách hàng chọn ghế và thanh toán
         ↓
2. Hệ thống xác nhận thanh toán thành công
         ↓
3. Cập nhật trạng thái vé: "Đã thanh toán"
         ↓
4. TỰ ĐỘNG GỬI EMAIL xác nhận đến khách hàng
         ↓
5. Khách hàng nhận email với đầy đủ thông tin vé
```

## 🧪 CÁCH TEST

### Test gửi email thủ công:

```bash
php scripts/test_booking_email.php
```

### Test trong flow đặt vé thực tế:

1. Đăng nhập vào hệ thống
2. Tìm kiếm chuyến xe
3. Chọn ghế
4. Thanh toán (quét QR hoặc chuyển khoản)
5. Sau khi thanh toán thành công → **Email tự động được gửi**
6. Kiểm tra hộp thư email của tài khoản đã đăng ký

## ⚠️ LƯU Ý

### Email có thể rơi vào Spam nếu:

- Gmail chưa tin tưởng địa chỉ gửi
- Lần đầu tiên nhận email từ hệ thống

### Giải pháp:

1. Kiểm tra thư mục **Spam/Junk**
2. Đánh dấu "Not spam" để lần sau vào Inbox
3. Thêm `ontapttnt@gmail.com` vào danh bạ

## 🔧 KHẮC PHỤC SỰ CỐ

### Nếu email không được gửi:

#### 1. Kiểm tra log:

```bash
cat storage/logs/laravel.log | tail -50
```

#### 2. Kiểm tra cấu hình `.env`:

```bash
# Đảm bảo các giá trị MAIL_ đúng
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
```

#### 3. Test kết nối SMTP:

```bash
php artisan tinker
Mail::raw('Test email', function($msg) {
    $msg->to('your-email@gmail.com')->subject('Test');
});
```

#### 4. Kiểm tra Gmail App Password:

- Đảm bảo `MAIL_PASSWORD` là **App Password**, không phải mật khẩu Gmail thường
- Tạo App Password mới tại: https://myaccount.google.com/apppasswords

## 📊 SO SÁNH VỚI CHỨC NĂNG QUÊN MẬT KHẨU

| Tính năng | Quên mật khẩu   | Xác nhận đặt vé |
| --------- | --------------- | --------------- |
| Gửi email | ✅ Đã hoạt động | ✅ Đã hoạt động |
| Cấu hình  | ✅ Giống nhau   | ✅ Giống nhau   |
| Template  | ✅ Có           | ✅ Có (mới tạo) |
| Tự động   | ✅ Tự động      | ✅ Tự động      |

## 💡 TIPS

1. **Tùy chỉnh template email**:
    - File: `resources/views/emails/booking-confirmation.blade.php`
    - Có thể thay đổi màu sắc, logo, nội dung

2. **Thêm file đính kèm** (PDF vé):
    - Sửa trong `app/Mail/BookingConfirmation.php`
    - Thêm method `->attach()`

3. **Gửi email cho nhiều người**:
    - Thêm CC/BCC trong BookingController
    - Ví dụ: `->cc('admin@futabus.vn')`

## 📞 HỖ TRỢ

Nếu có vấn đề, kiểm tra:

1. File log: `storage/logs/laravel.log`
2. Cấu hình: `.env`
3. Test script: `php scripts/test_booking_email.php`

---

**Tạo bởi**: GitHub Copilot  
**Ngày**: {{ date('d/m/Y') }}  
**Phiên bản**: 1.0
