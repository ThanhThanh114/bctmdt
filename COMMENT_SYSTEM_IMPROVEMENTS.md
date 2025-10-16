# COMMENT SYSTEM IMPROVEMENTS - DOCUMENTATION

## Ngày cập nhật: 15/10/2025

## Tổng quan các tính năng mới

### 1. Giao diện Chat qua lại (show.blade.php)

- **Mô tả**: Thiết kế lại trang chi tiết bình luận thành giao diện chat để dễ theo dõi cuộc hội thoại giữa khách hàng và admin
- **Tính năng**:
    - Hiển thị tin nhắn của khách hàng bên trái (màu xám)
    - Hiển thị tin nhắn của admin bên phải (màu xanh)
    - Tự động scroll xuống tin nhắn mới nhất
    - Hiển thị avatar, tên người dùng, thời gian
    - Hiển thị đánh giá sao trong tin nhắn gốc
    - Phân biệt rõ ràng giữa khách hàng và admin

### 2. Nút thêm bình luận/Trả lời

- **Vị trí**: Phía dưới hộp chat
- **Chức năng**:
    - Admin có thể nhập và gửi câu trả lời trực tiếp
    - Form textarea cho phép nhập tối đa 1000 ký tự
    - Nút "Gửi trả lời" và "Xóa" (clear form)
    - Tự động duyệt reply của admin
    - Lưu thông tin người trả lời (nv_id)

### 3. Bộ lọc từ ngữ nhạy cảm (ProfanityFilter)

- **File**: `app/Helpers/ProfanityFilter.php`
- **Chức năng**:
    - Tự động phát hiện và thay thế từ ngữ nhạy cảm, chửi thề bằng dấu `***`
    - Hỗ trợ cả tiếng Việt và tiếng Anh
    - Normalize text để bắt được các biến thể có dấu
    - Tự động áp dụng khi:
        - Tạo bình luận mới
        - Cập nhật bình luận
        - Admin trả lời bình luận

- **Danh sách từ cấm** (có thể mở rộng):
    - Tiếng Việt: đm, dm, đụ, địt, lồn, buồi, cặc, vãi, đéo, cc, vcl, chó, đĩ, ngu, khốn, v.v.
    - Tiếng Anh: fuck, shit, bitch, asshole, damn, bastard, v.v.

- **Sử dụng**:

    ```php
    use App\Helpers\ProfanityFilter;

    // Filter text
    $cleanText = ProfanityFilter::filter($dirtyText);

    // Check if contains profanity
    $hasBadWords = ProfanityFilter::containsProfanity($text);

    // Add more words to filter list
    ProfanityFilter::addWords(['từ_mới_1', 'từ_mới_2']);
    ```

### 4. Tự động kiểm duyệt đánh giá thấp

- **Quy tắc**: Bình luận có đánh giá 1-2 sao sẽ tự động chuyển sang trạng thái "Chờ duyệt"
- **Lý do**:
    - Đánh giá thấp có thể ảnh hưởng xấu đến nhà xe
    - Admin cần xem xét trước khi công khai
    - Ngăn chặn spam/fake review
- **Triển khai**: Sử dụng Model Observer trong `BinhLuan::boot()`
- **Logic**:
    ```php
    if ($binhLuan->so_sao <= 2) {
        $binhLuan->trang_thai = 'cho_duyet';
    }
    ```

### 5. Phân trang đã được cải thiện

- **Vị trí**: `index.blade.php`
- **Tính năng**:
    - Giữ nguyên các bộ lọc khi chuyển trang
    - Hiển thị 15 bình luận/trang
    - Sử dụng Laravel pagination

## Files đã thay đổi

### 1. Controller: `app/Http/Controllers/Admin/BinhLuanController.php`

**Thêm mới**:

- `use App\Helpers\ProfanityFilter;` - Import helper
- Method `reply()` - Xử lý admin trả lời bình luận

**Cập nhật**:

- Method `approve()` - Lưu thêm nv_id khi duyệt

### 2. Model: `app/Models/BinhLuan.php`

**Thêm mới**:

- `use App\Helpers\ProfanityFilter;`
- Method `boot()` - Observer cho auto-moderation và profanity filter
- Thêm 'nv_id', 'ngay_bl', 'ngay_tl', 'ngay_tao' vào $fillable

**Cập nhật**:

- Event `creating` - Auto-moderate và filter profanity
- Event `updating` - Filter profanity khi update

### 3. View: `resources/views/AdminLTE/admin/binh_luan/show.blade.php`

**Thay đổi hoàn toàn**:

- Thiết kế lại toàn bộ giao diện thành chat style
- Thêm form trả lời inline
- Thêm JavaScript auto-scroll
- Thêm CSS custom cho chat bubbles
- Sửa lỗi undefined variable `$binhLuan` -> `$binhluan`

### 4. Routes: `routes/web.php`

**Thêm mới**:

```php
Route::post('binhluan/{binhluan}/reply', [BinhLuanController::class, 'reply'])->name('binhluan.reply');
```

### 5. Helper: `app/Helpers/ProfanityFilter.php` (Mới)

**Các method**:

- `filter($text)` - Lọc và thay thế từ nhạy cảm
- `containsProfanity($text)` - Kiểm tra có chứa từ cấm
- `normalizeVietnamese($text)` - Chuẩn hóa tiếng Việt
- `addWords($words)` - Thêm từ mới vào danh sách cấm
- `getWords()` - Lấy danh sách từ cấm

## Cách sử dụng

### Admin trả lời bình luận

1. Vào trang **Quản lý Bình luận** (`/admin/binhluan`)
2. Click vào nút "Xem" (mắt) của bình luận muốn trả lời
3. Scroll xuống form "Nhập câu trả lời của bạn..."
4. Nhập nội dung trả lời
5. Click "Gửi trả lời"
6. Hệ thống tự động:
    - Lọc từ ngữ nhạy cảm
    - Lưu reply với trạng thái "Đã duyệt"
    - Hiển thị trong cuộc hội thoại

### Duyệt/Từ chối bình luận

1. Nếu bình luận đang "Chờ duyệt", sẽ hiển thị card "Duyệt bình luận"
2. Click "Duyệt bình luận" để phê duyệt
3. Click "Từ chối bình luận" để từ chối (cần nhập lý do)

## Cấu trúc Database

### Bảng: `binh_luan`

```sql
ma_bl           INT PRIMARY KEY AUTO_INCREMENT
parent_id       INT NULL (ID của bình luận cha nếu là reply)
user_id         INT (ID người dùng)
chuyen_xe_id    INT (ID chuyến xe)
noi_dung        TEXT (Nội dung bình luận)
noi_dung_tl     TEXT (Nội dung trả lời - deprecated, dùng parent_id)
so_sao          TINYINT (1-5, 0 cho reply)
trang_thai      ENUM('cho_duyet', 'da_duyet', 'tu_choi')
ngay_bl         DATETIME (Ngày bình luận)
ngay_tl         DATETIME (Ngày trả lời)
ngay_duyet      DATETIME (Ngày duyệt)
ly_do_tu_choi   TEXT (Lý do từ chối)
nv_id           INT NULL (ID nhân viên xử lý)
ngay_tao        DATETIME (Ngày tạo)
```

## Testing

### Test cases cần kiểm tra:

1. ✅ Hiển thị đúng giao diện chat
2. ✅ Admin gửi reply thành công
3. ✅ Từ ngữ nhạy cảm được lọc thành `***`
4. ✅ Bình luận 1-2 sao tự động chờ duyệt
5. ✅ Phân trang hoạt động đúng
6. ✅ Duyệt/Từ chối bình luận
7. ✅ Auto-scroll xuống tin nhắn mới

### Test profanity filter:

```php
// Test trong tinker
php artisan tinker
>>> use App\Helpers\ProfanityFilter;
>>> ProfanityFilter::filter("Xe này đm rất tệ")
=> "Xe này ** rất tệ"
>>> ProfanityFilter::containsProfanity("Xe tốt lắm")
=> false
```

## Lưu ý quan trọng

1. **Biến trong Blade**: Đã sửa từ `$binhLuan` sang `$binhluan` để match với controller
2. **Auto-moderation**: Chỉ áp dụng cho bình luận gốc (parent_id = null), không áp dụng cho reply
3. **Profanity filter**: Tự động chạy trong Model observer, không cần gọi thủ công
4. **Reply của admin**: Luôn được duyệt tự động (trang_thai = 'da_duyet')
5. **CSS**: Custom CSS được nhúng trực tiếp trong view, có thể tách ra file riêng nếu cần

## Cải tiến trong tương lai

1. Real-time notification khi có reply mới
2. Upload hình ảnh trong reply
3. Template câu trả lời nhanh cho admin
4. Xuất báo cáo bình luận
5. AI auto-reply cho các câu hỏi thường gặp
6. Cấu hình từ điển từ cấm qua giao diện admin
7. Webhook notify cho nhà xe khi có bình luận mới

## Troubleshooting

### Lỗi: Undefined variable $binhLuan

**Giải pháp**: Đã sửa trong show.blade.php, thay tất cả `$binhLuan` thành `$binhluan`

### Lỗi: Class 'App\Helpers\ProfanityFilter' not found

**Giải pháp**:

```bash
composer dump-autoload
```

### Lỗi: Column 'nv_id' doesn't exist

**Giải pháp**: Chạy migration để thêm cột nv_id vào bảng binh_luan

### Reply không hiển thị

**Kiểm tra**:

- Relationship `replies()` trong Model BinhLuan
- Load eager trong controller: `->load(['replies.user'])`
- parent_id được set đúng

## Liên hệ & Hỗ trợ

Nếu có vấn đề hoặc cần hỗ trợ, vui lòng liên hệ team dev.

---

**Version**: 1.0.0  
**Last Updated**: 15/10/2025  
**Developer**: GitHub Copilot
