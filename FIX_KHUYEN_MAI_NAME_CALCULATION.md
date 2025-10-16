# Fix Khuyến Mãi - Vấn Đề Tên Khách Hàng và Tính Toán Sai

## 🔴 Vấn Đề Phát Hiện

### 1. **Tên khách hàng hiển thị "N/A"**

**Nguyên nhân:** User ID 15 có `name` là chuỗi RỖNG (`''`) trong database, không phải NULL.

```sql
SELECT id, name, email FROM users WHERE id = 15;
-- Kết quả: id=15, name='', email='admin@gmail.com'
```

### 2. **Tính toán giảm giá kỳ lạ**

**Hiển thị:**

- Số ghế: 2
- Tổng tiền: 400,000đ
- Giảm 90%: 360,000đ

**Thực tế trong DB:**

- Booking #47 chỉ có **1 ghế A20**
- Giá vé: 200,000đ
- Tổng tiền phải là: 1 × 200,000 = **200,000đ**
- Giảm 90% phải là: 200,000 × 90% = **180,000đ**

## 🔍 Nguyên Nhân Gốc Rễ

### Vấn Đề 1: Database có DỮ LIỆU SAI

```sql
-- Bảng dat_ve: CHỈ CÓ 1 RECORD
SELECT * FROM dat_ve WHERE ma_ve = 'VE1001';
| id | ma_ve  | so_ghe | user_id | chuyen_xe_id |
|----|--------|--------|---------|--------------|
| 47 | VE1001 | A20    | 15      | 17           |
-- ✅ ĐÚNG: 1 booking, 1 ghế

-- Bảng ve_khuyenmai: CÓ 2 RECORDS trỏ đến CÙNG 1 dat_ve_id!
SELECT * FROM ve_khuyenmai WHERE ma_km = 13;
| id | dat_ve_id | ma_km |
|----|-----------|-------|
| 14 | 47        | 13    |  ← Trỏ đến dat_ve #47
| 15 | 47        | 13    |  ← Trỏ đến dat_ve #47 (DUPLICATE!)
-- ❌ SAI: 2 records nhưng cùng trỏ đến 1 dat_ve
```

**Kết luận:** Có ai đó (hoặc code) đã INSERT 2 lần vào bảng `ve_khuyenmai` cho cùng 1 booking!

### Vấn Đề 2: Logic đếm ghế ban đầu SAI

**Code CŨ (đã sửa trong commit trước):**

```php
$soLuongGhe = $group->count();  // ❌ Đếm số records trong ve_khuyenmai
```

**Code MỚI (hiện tại):**

```php
$allDatVe = \App\Models\DatVe::where('ma_ve', $firstBooking->ma_ve)->get();
$soLuongGhe = $allDatVe->count();  // ✅ Đếm số records trong dat_ve
```

Tuy nhiên, vì database có dữ liệu duplicate nên vẫn tính SAI!

## ✅ Giải Pháp

### Giải pháp 1: Xóa dữ liệu duplicate (KHUYẾN NGHỊ)

```sql
-- Xóa record duplicate trong ve_khuyenmai
DELETE FROM ve_khuyenmai WHERE id = 15;
-- Giữ lại 1 record duy nhất (id = 14)
```

**Ưu điểm:**

- Dữ liệu sạch, không còn duplicate
- Logic tính toán đơn giản
- Không cần thêm code xử lý edge case

### Giải pháp 2: Sửa code để xử lý duplicate

```php
// Group by dat_ve_id UNIQUE trước khi đếm
$allDatVe = \App\Models\DatVe::where('ma_ve', $firstBooking->ma_ve)
    ->distinct('id')  // Đảm bảo unique
    ->get();
$soLuongGhe = $allDatVe->count();
```

Nhưng code hiện tại ĐÃ ĐÚNG! Vấn đề là **database bị sai**.

### Giải pháp 3: Ngăn chặn duplicate trong tương lai

Thêm UNIQUE constraint vào bảng `ve_khuyenmai`:

```sql
-- Tạo unique index để ngăn duplicate
ALTER TABLE ve_khuyenmai
ADD UNIQUE KEY unique_dat_ve_ma_km (dat_ve_id, ma_km);
```

Điều này đảm bảo:

- 1 `dat_ve_id` chỉ có thể có 1 `ma_km` duy nhất
- Không thể INSERT duplicate nữa

## 🔧 Fix Đã Áp Dụng

### 1. ✅ Fix hiển thị tên khách hàng rỗng

**File:** `resources/views/AdminLTE/admin/khuyen_mai/show.blade.php`

```blade
<td>
    @if(isset($booking->user) && $booking->user)
        @if(!empty($booking->user->name))
            <strong>{{ $booking->user->name }}</strong>
        @else
            {{-- Hiển thị email nếu name rỗng --}}
            <strong class="text-info">{{ $booking->user->email }}</strong>
            <br><small class="text-muted">(Chưa có tên)</small>
        @endif
    @else
        <span class="text-muted"><i>Khách vãng lai</i></span>
    @endif
</td>
```

**Kết quả:** Hiển thị email thay vì "N/A" khi name rỗng.

### 2. ✅ Fix eager loading thiếu tramDi/tramDen

**File:** `app/Http/Controllers/Admin/KhuyenMaiController.php`

```php
$veKhuyenMais = $khuyenmai->veKhuyenMai()
    ->with([
        'datVe.user',
        'datVe.chuyenXe.tramDi',  // ← Thêm
        'datVe.chuyenXe.tramDen'   // ← Thêm
    ])
    ->orderBy('id', 'desc')
    ->get();
```

### 3. ✅ Fix logic đếm ghế

**File:** `app/Http/Controllers/Admin/KhuyenMaiController.php`

```php
// Đếm dựa trên dat_ve (mỗi ghế = 1 record dat_ve)
$allDatVe = \App\Models\DatVe::where('ma_ve', $firstBooking->ma_ve)->get();
$soLuongGhe = $allDatVe->count();
$soGheList = $allDatVe->pluck('so_ghe')->implode(', ');
```

### 4. ✅ Fix code duplicate trong view

Đã xóa code bị duplicate ở giữa foreach loop.

## 📝 Hành Động Cần Làm

### Ngay lập tức:

```sql
-- 1. Xóa record duplicate
DELETE FROM ve_khuyenmai WHERE id = 15;

-- 2. Kiểm tra xem còn duplicate khác không
SELECT dat_ve_id, ma_km, COUNT(*) as count
FROM ve_khuyenmai
GROUP BY dat_ve_id, ma_km
HAVING count > 1;

-- 3. Thêm constraint để ngăn chặn
ALTER TABLE ve_khuyenmai
ADD UNIQUE KEY unique_booking_promo (dat_ve_id, ma_km);
```

### Dài hạn:

1. **Kiểm tra code booking** - Tìm nơi INSERT vào `ve_khuyenmai` và sửa logic để không INSERT duplicate
2. **Update user name** - Yêu cầu users cập nhật name nếu còn rỗng
3. **Validation** - Thêm validation bắt buộc nhập name khi register

## 🎯 Kết Quả Sau Khi Fix

### Trước khi fix (SAI):

```
Khách hàng: N/A
Số ghế: 2
Tổng tiền: 400,000đ
Giảm giá: -360,000đ (90%)
```

### Sau khi fix (ĐÚNG):

```
Khách hàng: admin@gmail.com (Chưa có tên)
Số ghế: 1
Tổng tiền: 200,000đ
Giảm giá: -180,000đ (90%)
```

## 🚨 Cảnh Báo

Nếu KHÔNG xóa duplicate record trong `ve_khuyenmai`, hệ thống sẽ tiếp tục hiển thị sai:

- Đếm sai số ghế
- Tính sai tổng tiền
- Tính sai giảm giá
- Thống kê sai báo cáo

**👉 BẮT BUỘC phải xóa record duplicate và thêm UNIQUE constraint!**
