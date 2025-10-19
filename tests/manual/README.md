# Manual Test Files

Thư mục này chứa các file test thủ công.

## Files

### Email & Booking Tests

- **test_booking_email.php** - Test gửi email xác nhận đặt vé
- **test_invoice.php** - Test tạo hóa đơn PDF

### Form Tests

- **test_register_form.html** - Test form đăng ký người dùng

## Cách Sử Dụng

### Test PHP Files

```bash
php tests/manual/test_booking_email.php
php tests/manual/test_invoice.php
```

### Test HTML Files

Mở file trong trình duyệt:

```
tests/manual/test_register_form.html
```

## Ghi Chú

- File test PHP cần chạy từ thư mục gốc của project
- File test HTML có thể mở trực tiếp trong trình duyệt
