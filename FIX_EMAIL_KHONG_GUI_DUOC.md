# 🚨 HƯỚNG DẪN FIX LỖI EMAIL

## ❌ Vấn đề phát hiện:

File `.env` **CHƯA CẤU HÌNH** MAIL_USERNAME và MAIL_PASSWORD!

Đây là lý do tại sao:

- ❌ Không gửi được OTP quên mật khẩu
- ❌ Không gửi được email xác nhận vé xe

---

## ✅ GIẢI PHÁP - Làm theo từng bước:

### Bước 1: Tạo Gmail App Password

1. **Vào Google Account:** https://myaccount.google.com/security
2. **Bật "2-Step Verification"** (bắt buộc)
3. **Vào App passwords:** https://myaccount.google.com/apppasswords
4. Chọn:
    - App: **Mail**
    - Device: **Other** (nhập "Laravel")
5. **Click "Generate"**
6. **Copy password 16 ký tự** (dạng: `xxxx xxxx xxxx xxxx`)

---

### Bước 2: Cập nhật file .env

Mở file `.env` (ở root project) và **THÊM/SỬA** các dòng sau:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=thanhloi1141308@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="thanhloi1141308@gmail.com"
MAIL_FROM_NAME="Hệ thống đặt vé xe"
```

**Thay thế:**

- `MAIL_USERNAME`: Email Gmail của bạn
- `MAIL_PASSWORD`: App Password 16 ký tự vừa tạo (GIỮ NGUYÊN dấu cách hoặc xóa hết dấu cách)
- `MAIL_FROM_ADDRESS`: Email Gmail của bạn

---

### Bước 3: Clear config cache

```bash
php artisan config:clear
```

---

### Bước 4: Test email

```bash
php test_email_config.php
```

**Kết quả mong đợi:**

```
✅ GỬI EMAIL THÀNH CÔNG!
📬 Kiểm tra hộp thư: your-email@gmail.com
```

---

## 🧪 Test lại 2 chức năng:

### Test 1: Quên mật khẩu

1. Vào: http://127.0.0.1:8000/forgot-password
2. Nhập email: `thanhloi1141308@gmail.com`
3. Nhấn "Gửi mã OTP"
4. ✅ Chuyển sang trang nhập OTP
5. ✅ Check email → Có OTP 6 số
6. Nhập OTP → Đặt lại mật khẩu

### Test 2: Email vé xe

1. Đặt vé với email hợp lệ
2. Thanh toán thành công
3. ✅ Check email → Có email xác nhận vé

---

## 🐛 Troubleshooting

### Lỗi: "Invalid password" hoặc "Authentication failed"

**Nguyên nhân:** Dùng sai password

**Fix:**

- ❌ KHÔNG dùng mật khẩu Gmail thường
- ✅ PHẢI dùng App Password 16 ký tự
- Kiểm tra đã bật 2-Step Verification chưa

### Lỗi: "Connection refused" hoặc "Could not connect to SMTP host"

**Nguyên nhân:** Firewall/Antivirus chặn

**Fix:**

- Tắt tạm thời Firewall/Antivirus
- Hoặc cho phép PHP kết nối port 587

### Lỗi: Email vào Spam

**Bình thường:** Gmail có thể đưa vào Spam lần đầu

**Fix:**

- Check thư mục Spam
- Đánh dấu "Not spam"

---

## 📝 Tóm tắt:

1. ✅ Tạo Gmail App Password
2. ✅ Cập nhật .env với MAIL_USERNAME và MAIL_PASSWORD
3. ✅ Chạy `php artisan config:clear`
4. ✅ Test: `php test_email_config.php`
5. ✅ Test quên mật khẩu và đặt vé

---

## 💡 Lưu ý quan trọng:

- **App Password** chỉ hiển thị 1 lần khi tạo, copy ngay!
- **KHÔNG chia sẻ** App Password với ai
- Nếu quên, xóa và tạo lại App Password mới
- Gmail miễn phí có giới hạn: **500 emails/day**

---

**Sau khi làm xong các bước trên, 2 chức năng sẽ hoạt động bình thường!** ✅
