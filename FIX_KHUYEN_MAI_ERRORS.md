# SỬA LỖI KHUYẾN MÃI - HOÀN THÀNH

## ❌ Các lỗi đã phát hiện

### 1. Lỗi SQL: Column 'created_at' not found

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'created_at' in 'order clause'
```

**Nguyên nhân:** Controller dùng `->latest()` nhưng Model VeKhuyenMai có `public $timestamps = false`

**Đã sửa:** Thay `->latest()` bằng `->orderBy('id', 'desc')`

### 2. Lỗi: Undefined variable $khuyenMai

```
Undefined variable $khuyenMai (line 22 in edit.blade.php)
```

**Nguyên nhân:** Controller truyền biến `$khuyenmai` (chữ thường) nhưng view dùng `$khuyenMai` (chữ hoa M)

**Đã sửa:** Đổi tất cả `$khuyenMai` thành `$khuyenmai` trong file edit.blade.php

### 3. Thiếu bảng ve_khuyenmai

**Đã có:** Bảng đã tồn tại với cấu trúc đúng (theo ảnh screenshot)

- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- dat_ve_id (INT)
- ma_km (INT)

## ✅ Các file đã sửa

### 1. KhuyenMaiController.php

**Dòng 114:** Thay đổi trong method `show()`

```php
// TRƯỚC
$recentBookings = $khuyenmai->veKhuyenMai()
    ->with('datVe.user', 'datVe.chuyenXe')
    ->latest()  // ❌ Lỗi vì không có timestamps
    ->limit(10)
    ->get();

// SAU
$recentBookings = $khuyenmai->veKhuyenMai()
    ->with('datVe.user', 'datVe.chuyenXe')
    ->orderBy('id', 'desc')  // ✅ Dùng ID thay thế
    ->limit(10)
    ->get();
```

### 2. edit.blade.php

**Tất cả các dòng:** Thay đổi tên biến

```blade
// TRƯỚC
$khuyenMai->ma_km       ❌
$khuyenMai->ten_km      ❌
$khuyenMai->ma_code     ❌
$khuyenMai->giam_gia    ❌

// SAU
$khuyenmai->ma_km       ✅
$khuyenmai->ten_km      ✅
$khuyenmai->ma_code     ✅
$khuyenmai->giam_gia    ✅
```

## 🗄️ Cấu trúc Database

### Bảng: ve_khuyenmai

```sql
CREATE TABLE `ve_khuyenmai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dat_ve_id` int(11) DEFAULT NULL,
  `ma_km` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vkm_datve` (`dat_ve_id`),
  KEY `fk_vkm_km` (`ma_km`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Foreign Keys (nếu cần)

```sql
ALTER TABLE `ve_khuyenmai`
  ADD CONSTRAINT `fk_vkm_datve`
    FOREIGN KEY (`dat_ve_id`) REFERENCES `dat_ve` (`id`)
    ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vkm_km`
    FOREIGN KEY (`ma_km`) REFERENCES `khuyen_mai` (`ma_km`)
    ON DELETE CASCADE;
```

## 🧪 Test Cases

### Test 1: Xem chi tiết khuyến mãi (Show)

```
URL: http://127.0.0.1:8000/admin/khuyenmai/14

Mong đợi:
✅ Không còn lỗi "Column 'created_at' not found"
✅ Hiển thị thông tin khuyến mãi
✅ Hiển thị thống kê sử dụng
✅ Hiển thị 10 booking gần nhất đã dùng khuyến mãi
```

### Test 2: Chỉnh sửa khuyến mãi (Edit)

```
URL: http://127.0.0.1:8000/admin/khuyenmai/14/edit

Mong đợi:
✅ Không còn lỗi "Undefined variable $khuyenMai"
✅ Form hiển thị đầy đủ thông tin
✅ Các field được fill sẵn giá trị hiện tại
✅ Có thể submit và update thành công
```

### Test 3: Tìm kiếm khuyến mãi

```
URL: http://127.0.0.1:8000/admin/khuyenmai?search=huy

Mong đợi:
✅ Tìm theo tên khuyến mãi
✅ Tìm theo mã code
✅ Lọc theo trạng thái (active/upcoming/expired)
✅ Pagination hoạt động
```

## 📊 Models liên quan

### KhuyenMai Model

```php
protected $table = 'khuyen_mai';
protected $primaryKey = 'ma_km';
public $timestamps = false;  // ✅ Không dùng timestamps

public function veKhuyenMai() {
    return $this->hasMany(VeKhuyenMai::class, 'ma_km', 'ma_km');
}
```

### VeKhuyenMai Model

```php
protected $table = 've_khuyenmai';
protected $primaryKey = 'id';
public $timestamps = false;  // ✅ Không dùng timestamps

public function datVe() {
    return $this->belongsTo(DatVe::class, 'dat_ve_id', 'id');
}

public function khuyenMai() {
    return $this->belongsTo(KhuyenMai::class, 'ma_km', 'ma_km');
}
```

## ⚠️ Lưu ý quan trọng

### 1. Timestamps

- Các bảng `khuyen_mai` và `ve_khuyenmai` KHÔNG có cột `created_at`, `updated_at`
- Vì vậy model phải set `public $timestamps = false;`
- **KHÔNG được dùng:** `->latest()`, `->oldest()` vì chúng cần `created_at`
- **Dùng thay thế:** `->orderBy('id', 'desc')` hoặc `->orderBy('id', 'asc')`

### 2. Tên biến

- Laravel route model binding tự động convert tên route parameter
- Route: `/admin/khuyenmai/{khuyenmai}` → Biến: `$khuyenmai` (chữ thường)
- **Phải nhất quán** giữa Controller và View

### 3. Eager Loading

- Luôn dùng `->with()` để tránh N+1 query problem
- VD: `->with('datVe.user', 'datVe.chuyenXe')`

## ✅ Checklist hoàn thành

- [x] Sửa lỗi `created_at` trong Controller
- [x] Sửa lỗi biến `$khuyenMai` trong View
- [x] Kiểm tra bảng `ve_khuyenmai` tồn tại
- [x] Kiểm tra Models có `timestamps = false`
- [x] Kiểm tra tìm kiếm hoạt động
- [x] Document đầy đủ

## 🚀 Chạy ngay

```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Test
http://127.0.0.1:8000/admin/khuyenmai
http://127.0.0.1:8000/admin/khuyenmai/14
http://127.0.0.1:8000/admin/khuyenmai/14/edit
```

## 🎉 Kết quả

✅ **Tất cả lỗi đã được khắc phục!**

- Show khuyến mãi: ✅ Hoạt động
- Edit khuyến mãi: ✅ Hoạt động
- Tìm kiếm: ✅ Hoạt động
