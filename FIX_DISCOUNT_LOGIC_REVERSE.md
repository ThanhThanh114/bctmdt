# Fix Logic Giảm Giá - Đảo Ngược %

## 🔄 Thay Đổi Logic

### **YÊU CẦU MỚI:**

Trường `giam_gia` trong database lưu **% khách phải TRẢ**, không phải **% được GIẢM**.

### **Ví dụ:**

```
Tổng tiền: 200,000đ

Mã giảm 10% → Khách trả 10% = 20,000đ → Được giảm 90% = 180,000đ
Mã giảm 50% → Khách trả 50% = 100,000đ → Được giảm 50% = 100,000đ
Mã giảm 90% → Khách trả 90% = 180,000đ → Được giảm 10% = 20,000đ
```

## ✅ Code Đã Sửa

### 1. **Controller** (KhuyenMaiController.php)

**TRƯỚC:**

```php
$giamGia = $tongTien * ($khuyenmai->giam_gia / 100);
// VD: 200,000 × (10/100) = 20,000đ giảm → SAI!
```

**SAU:**

```php
// Logic: giam_gia = % khách phải trả
// VD: giam_gia = 10% → khách trả 10% → giảm 90%
$phanTramKhachTra = $khuyenmai->giam_gia;
$phanTramGiam = 100 - $phanTramKhachTra;
$giamGia = $tongTien * ($phanTramGiam / 100);
$soTienPhaiTra = $tongTien - $giamGia;

// VD: giam_gia = 10%
// → phanTramGiam = 100 - 10 = 90%
// → giamGia = 200,000 × (90/100) = 180,000đ
// → phaiTra = 200,000 - 180,000 = 20,000đ ✅
```

### 2. **View** (show.blade.php)

**TRƯỚC:**

```blade
<small class="text-muted">({{ $khuyenmai->giam_gia }}%)</small>
{{-- Hiển thị: (10%) - GÂY NHẦM LẪN --}}
```

**SAU:**

```blade
<small class="text-muted">
    (Giảm {{ $booking->phan_tram_giam }}%)
</small>
{{-- Hiển thị: (Giảm 90%) - RÕ RÀNG --}}
```

## 📊 Bảng So Sánh

| DB: giam_gia | Ý nghĩa cũ (SAI) | Ý nghĩa mới (ĐÚNG)              | Với 200k     |
| ------------ | ---------------- | ------------------------------- | ------------ |
| 10%          | Giảm 10% = 20k   | Trả 10% = 20k, Giảm 90% = 180k  | **Trả 20k**  |
| 50%          | Giảm 50% = 100k  | Trả 50% = 100k, Giảm 50% = 100k | **Trả 100k** |
| 90%          | Giảm 90% = 180k  | Trả 90% = 180k, Giảm 10% = 20k  | **Trả 180k** |
| 99%          | Giảm 99% = 198k  | Trả 99% = 198k, Giảm 1% = 2k    | **Trả 198k** |

## 🎯 Hiển Thị Mới

### Ví dụ với mã giảm 10%:

**TRƯỚC (gây nhầm lẫn):**

```
Tổng tiền: 200,000đ
Giảm giá: -20,000đ (10%)  ← Khách nghĩ giảm 10%?
```

**SAU (rõ ràng):**

```
Tổng tiền: 200,000đ
Giảm giá: -180,000đ (Giảm 90%)  ← Rõ ràng được giảm 90%!
```

## ⚠️ Lưu Ý Quan Trọng

### **Tên cột `giam_gia` GÂY NHẦM LẪN!**

Tên cột `giam_gia` làm mọi người nghĩ là "% được giảm", nhưng thực tế lại lưu "% phải trả".

**Đề xuất:**

1. **Đổi tên cột** (khuyến nghị):

```sql
ALTER TABLE khuyen_gia
CHANGE COLUMN giam_gia phan_tram_khach_tra DECIMAL(5,2);
```

2. **Hoặc thêm comment**:

```sql
ALTER TABLE khuyen_gia
MODIFY COLUMN giam_gia DECIMAL(5,2)
COMMENT 'Phần trăm khách phải trả (10 = khách trả 10%, được giảm 90%)';
```

3. **Thêm cột mô tả** trong form:

```html
<label>Khách phải trả (%)</label>
<input type="number" name="giam_gia" />
<small>VD: Nhập 10 → Khách trả 10% = 20,000đ (Giảm 90%)</small>
```

## 🧪 Test Cases

```bash
cd c:\xampp\htdocs\BusBookingBank\BusBooking
php test_new_discount_logic.php
```

**Kết quả:**

```
Khách trả 10%:
  Giảm: 90% = 180.000đ ✅
  Trả: 10% = 20.000đ ✅

Khách trả 90%:
  Giảm: 10% = 20.000đ ✅
  Trả: 90% = 180.000đ ✅
```

## 📝 Files Đã Thay Đổi

1. ✅ `app/Http/Controllers/Admin/KhuyenMaiController.php`
    - Thêm logic đảo ngược: `phanTramGiam = 100 - giam_gia`
    - Thêm field `phan_tram_giam` và `so_tien_phai_tra`

2. ✅ `resources/views/AdminLTE/admin/khuyen_mai/show.blade.php`
    - Hiển thị "Giảm X%" thay vì chỉ hiển thị số %
    - Rõ ràng hơn cho người xem

## 🎉 Kết Quả

Bây giờ khi nhập:

- **Mã giảm 10%** → Khách chỉ trả 20,000đ (được giảm 180,000đ)
- **Mã giảm 90%** → Khách trả 180,000đ (được giảm 20,000đ)

✅ **ĐÚNG** theo yêu cầu của bạn!
