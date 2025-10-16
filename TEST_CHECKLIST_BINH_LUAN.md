# TEST CHECKLIST - Hệ thống Bình luận

## ✅ Danh sách kiểm tra sau khi triển khai

### 1. Kiểm tra Giao diện Chat

- [ ] Vào `/admin/binhluan` và click "Xem" một bình luận
- [ ] Kiểm tra giao diện chat hiển thị đúng
- [ ] Tin nhắn khách hàng hiển thị bên trái (màu xám)
- [ ] Tin nhắn admin hiển thị bên phải (màu xanh)
- [ ] Avatar và timestamp hiển thị đúng
- [ ] Auto scroll xuống tin nhắn mới nhất

### 2. Kiểm tra Chức năng Trả lời

- [ ] Form trả lời hiển thị phía dưới chat
- [ ] Nhập nội dung và click "Gửi trả lời"
- [ ] Reply xuất hiện trong chat box
- [ ] Reply có màu xanh và hiển thị bên phải
- [ ] Thông báo "Đã gửi trả lời thành công!" xuất hiện

### 3. Kiểm tra Bộ lọc từ nhạy cảm

**Test case 1**: Từ tiếng Việt

- [ ] Tạo bình luận mới với nội dung: "Xe này đm tệ quá"
- [ ] Kiểm tra lưu thành: "Xe này \*\* tệ quá"

**Test case 2**: Từ tiếng Anh

- [ ] Tạo bình luận: "This is shit service"
- [ ] Kiểm tra lưu thành: "This is \*\*\*\* service"

**Test case 3**: Reply có từ cấm

- [ ] Admin trả lời: "Xin lỗi về sự cố, chúng tôi sẽ xử lý ngu ngốc này"
- [ ] Kiểm tra lưu thành: "Xin lỗi về sự cố, chúng tôi sẽ xử lý \*\*\* này"

### 4. Kiểm tra Auto-moderation (Đánh giá thấp)

**Test case 1**: 1 sao

- [ ] Tạo bình luận mới với 1 sao
- [ ] Kiểm tra `trang_thai` = "cho_duyet"

**Test case 2**: 2 sao

- [ ] Tạo bình luận mới với 2 sao
- [ ] Kiểm tra `trang_thai` = "cho_duyet"

**Test case 3**: 3 sao trở lên

- [ ] Tạo bình luận mới với 3, 4, 5 sao
- [ ] Kiểm tra `trang_thai` = "da_duyet" (hoặc theo logic hiện tại)

### 5. Kiểm tra Duyệt/Từ chối

**Duyệt bình luận**:

- [ ] Vào bình luận có trạng thái "Chờ duyệt"
- [ ] Card "Duyệt bình luận" hiển thị
- [ ] Click "Duyệt bình luận"
- [ ] Trạng thái chuyển sang "Đã duyệt"
- [ ] Thông báo thành công

**Từ chối bình luận**:

- [ ] Click "Từ chối bình luận"
- [ ] Modal hiển thị yêu cầu nhập lý do
- [ ] Nhập lý do và submit
- [ ] Trạng thái chuyển sang "Từ chối"
- [ ] Lý do từ chối được lưu

### 6. Kiểm tra Phân trang

- [ ] Vào danh sách bình luận
- [ ] Pagination links hiển thị (nếu >15 bình luận)
- [ ] Click trang 2, 3...
- [ ] Bộ lọc được giữ nguyên khi chuyển trang

### 7. Kiểm tra Thông tin sidebar

- [ ] Thông tin chuyến xe hiển thị đúng
- [ ] Thông tin khách hàng hiển thị đúng
- [ ] Avatar khách hàng hiển thị
- [ ] Các nút thao tác hoạt động

### 8. Kiểm tra Responsive

- [ ] Giao diện hiển thị tốt trên desktop
- [ ] Giao diện hiển thị tốt trên tablet
- [ ] Giao diện hiển thị tốt trên mobile

## 🐛 Các lỗi thường gặp

### Lỗi: Undefined variable $binhLuan

✅ **Đã sửa**: Thay tất cả `$binhLuan` thành `$binhluan`

### Lỗi: Class 'App\Helpers\ProfanityFilter' not found

**Giải pháp**:

```bash
cd c:\xampp\htdocs\BusBookingBank\BusBooking
composer dump-autoload
```

### Lỗi: Route [admin.binhluan.reply] not defined

**Kiểm tra**:

- Route đã được thêm vào `routes/web.php`
- Clear route cache: `php artisan route:clear`

### Lỗi: Column 'nv_id' doesn't exist

**Giải pháp**: Tạo migration để thêm cột

```php
Schema::table('binh_luan', function (Blueprint $table) {
    $table->unsignedBigInteger('nv_id')->nullable()->after('ly_do_tu_choi');
});
```

### Lỗi: Reply không hiển thị trong chat

**Kiểm tra**:

1. Relationship `replies()` trong Model
2. Controller load relationship: `->load(['replies.user'])`
3. parent_id đã được set đúng

## 📝 Test trong Database

### Kiểm tra dữ liệu được lưu đúng

```sql
-- Check reply được tạo
SELECT * FROM binh_luan
WHERE parent_id IS NOT NULL
ORDER BY ngay_bl DESC
LIMIT 5;

-- Check profanity filter
SELECT noi_dung FROM binh_luan
WHERE noi_dung LIKE '%*%'
LIMIT 10;

-- Check auto-moderation
SELECT ma_bl, so_sao, trang_thai
FROM binh_luan
WHERE so_sao <= 2 AND parent_id IS NULL
LIMIT 10;
```

## 🔧 Commands hữu ích

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Autoload classes
composer dump-autoload

# Check logs
tail -f storage/logs/laravel.log
```

## 📊 Metrics để theo dõi

Sau khi triển khai, theo dõi:

- [ ] Số lượng reply từ admin
- [ ] Số bình luận bị filter (có chứa \*\*\*)
- [ ] Số bình luận 1-2 sao cần duyệt
- [ ] Thời gian phản hồi trung bình của admin
- [ ] Tỷ lệ bình luận được duyệt/từ chối

## ✅ Sign-off

**Tested by**: ********\_********  
**Date**: ********\_********  
**Status**: [ ] Pass [ ] Fail  
**Notes**: ********\_********

---

**Version**: 1.0.0  
**Created**: 15/10/2025
