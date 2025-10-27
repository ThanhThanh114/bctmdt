# Hướng Dẫn Khóa và Mở Khóa Tài Khoản Nhà Xe (CẬP NHẬT)

## ✅ Cập Nhật Mới: Tự Động Khôi Phục Quyền

**Ngày cập nhật:** 27/10/2025  
**Phiên bản:** 2.0

## 🎯 Thay Đổi Chính

### Trước Đây (v1.0):
- ❌ Khóa tài khoản → Hạ cấp xuống user
- ❌ Mở khóa → Vẫn là user, admin phải gán lại thủ công

### Bây Giờ (v2.0):
- ✅ Khóa tài khoản → Hạ cấp xuống user + **LƯU THÔNG TIN GỐC**
- ✅ Mở khóa → **TỰ ĐỘNG KHÔI PHỤC** quyền và nhà xe gốc

## 🗃️ Cấu Trúc Database Mới

Đã thêm 2 cột mới vào bảng `users`:

```sql
ALTER TABLE users ADD COLUMN locked_original_role VARCHAR(20) NULL 
  COMMENT 'Role gốc trước khi bị khóa';

ALTER TABLE users ADD COLUMN locked_original_ma_nha_xe VARCHAR(10) NULL 
  COMMENT 'Mã nhà xe gốc trước khi bị khóa';

ALTER TABLE users ADD COLUMN locked_reason TEXT NULL;
ALTER TABLE users ADD COLUMN locked_at TIMESTAMP NULL;
ALTER TABLE users ADD COLUMN locked_by INT NULL;
```

## 📊 Quy Trình Hoạt Động

### 1. Khi Khóa Tài Khoản

```
BƯỚC 1: Lưu thông tin gốc
┌─────────────────────────────────┐
│ locked_original_role = bus_owner │
│ locked_original_ma_nha_xe = 7    │
└─────────────────────────────────┘

BƯỚC 2: Hạ cấp tài khoản
┌─────────────────────────────────┐
│ role → user                      │
│ ma_nha_xe → NULL                 │
│ is_active → 0                    │
│ locked_reason → "Lý do..."       │
│ locked_at → 2025-10-27           │
│ locked_by → admin_id             │
└─────────────────────────────────┘

BƯỚC 3: Đăng xuất ngay lập tức
┌─────────────────────────────────┐
│ Xóa session từ database          │
└─────────────────────────────────┘
```

### 2. Khi Mở Khóa Tài Khoản

```
BƯỚC 1: Khôi phục từ thông tin gốc
┌─────────────────────────────────┐
│ role ← locked_original_role      │
│ ma_nha_xe ← locked_original_...  │
└─────────────────────────────────┘

BƯỚC 2: Mở khóa
┌─────────────────────────────────┐
│ is_active → 1                    │
│ locked_reason → NULL             │
│ locked_at → NULL                 │
│ locked_by → NULL                 │
└─────────────────────────────────┘

BƯỚC 3: Xóa thông tin backup
┌─────────────────────────────────┐
│ locked_original_role → NULL      │
│ locked_original_ma_nha_xe → NULL │
└─────────────────────────────────┘
```

## 💻 Code Implementation

### TaiKhoanNhaXeController - lock()

```php
public function lock(Request $request, User $taikhoan)
{
    // Lưu thông tin gốc
    $oldRole = $taikhoan->role;
    $oldMaNhaXe = $taikhoan->ma_nha_xe;
    
    $updateData = [
        'is_active' => 0,
        'locked_reason' => $validated['ly_do_khoa'],
        'locked_at' => now(),
        'locked_by' => auth()->id(),
        'locked_original_role' => $oldRole, // ← LƯU ROLE GỐC
        'locked_original_ma_nha_xe' => $oldMaNhaXe, // ← LƯU NHÀ XE GỐC
    ];

    // Hạ cấp nếu là bus_owner hoặc staff
    if (in_array($taikhoan->role, ['bus_owner', 'staff'])) {
        $updateData['role'] = 'user';
        $updateData['ma_nha_xe'] = null;
    }

    $taikhoan->update($updateData);
}
```

### TaiKhoanNhaXeController - unlock()

```php
public function unlock(User $taikhoan)
{
    $updateData = [
        'is_active' => 1,
        'locked_reason' => null,
        'locked_at' => null,
        'locked_by' => null,
    ];

    // TỰ ĐỘNG KHÔI PHỤC
    if ($taikhoan->locked_original_role) {
        $updateData['role'] = $taikhoan->locked_original_role; // ← KHÔI PHỤC ROLE
    }
    if ($taikhoan->locked_original_ma_nha_xe) {
        $updateData['ma_nha_xe'] = $taikhoan->locked_original_ma_nha_xe; // ← KHÔI PHỤC NHÀ XE
    }

    // Xóa backup
    $updateData['locked_original_role'] = null;
    $updateData['locked_original_ma_nha_xe'] = null;

    $taikhoan->update($updateData);
}
```

## 🧪 Test Case

### Test Script

```bash
php test_lock_unlock_restore.php
```

### Kết Quả Mong Đợi

```
=== TEST KHÓA VÀ MỞ KHÓA VỚI KHÔI PHỤC QUYỀN ===

1️⃣  TRƯỚC KHI KHÓA:
   - role: bus_owner
   - ma_nha_xe: 7
   - is_active: Active

2️⃣  SAU KHI KHÓA:
   - role: user (đã hạ xuống)
   - ma_nha_xe: NULL (đã xóa)
   - locked_original_role: bus_owner (đã lưu)
   - locked_original_ma_nha_xe: 7 (đã lưu)

3️⃣  SAU KHI MỞ KHÓA:
   - role: bus_owner (✅ đã khôi phục)
   - ma_nha_xe: 7 (✅ đã khôi phục)
   - locked_original_role: NULL
   - locked_original_ma_nha_xe: NULL

🎉 THÀNH CÔNG! Đã khôi phục đúng quyền và nhà xe gốc!
```

## 📱 Giao Diện Người Dùng

### Modal Khóa Tài Khoản

```
┌────────────────────────────────────────────┐
│ ⚠️  CẢNH BÁO:                              │
│ • Tài khoản sẽ không thể đăng nhập         │
│ • Tài khoản sẽ bị HẠ CẤP xuống quyền USER  │
│ • Liên kết với nhà xe sẽ bị xóa            │
├────────────────────────────────────────────┤
│ ℹ️  LƯU Ý:                                 │
│ Hệ thống sẽ tự động lưu thông tin gốc.     │
│ Khi mở khóa, quyền sẽ được TỰ ĐỘNG KHÔI    │
│ PHỤC.                                       │
└────────────────────────────────────────────┘
```

## 🎬 Demo Workflow

### Scenario: Khóa và Mở Khóa Bus Owner

#### 1. Admin khóa tài khoản

```bash
Admin Panel → Quản lý Tài khoản Nhà Xe
→ Click "Khóa tài khoản" của user "tienkhoa"
→ Nhập lý do: "Tạm ngưng hoạt động"
→ Xác nhận
```

**Kết quả:**
- ✅ Tài khoản bị khóa (is_active = 0)
- ✅ Role: bus_owner → user
- ✅ ma_nha_xe: 7 → NULL
- ✅ Đã lưu: locked_original_role = "bus_owner"
- ✅ Đã lưu: locked_original_ma_nha_xe = "7"
- ✅ User bị đăng xuất ngay lập tức

#### 2. Bus Owner thử đăng nhập

```
❌ Đăng nhập thất bại
"Tài khoản của bạn đã bị khóa. Lý do: Tạm ngưng hoạt động"
```

#### 3. Admin mở khóa

```bash
Admin Panel → Quản lý Tài khoản Nhà Xe
→ Click "Mở khóa tài khoản"
→ Xác nhận
```

**Kết quả:**
- ✅ is_active: 0 → 1
- ✅ role: user → **bus_owner** (TỰ ĐỘNG KHÔI PHỤC)
- ✅ ma_nha_xe: NULL → **7** (TỰ ĐỘNG KHÔI PHỤC)
- ✅ Xóa locked_original_role
- ✅ Xóa locked_original_ma_nha_xe

#### 4. Bus Owner đăng nhập lại

```
✅ Đăng nhập thành công!
✅ Truy cập Bus Owner Dashboard như bình thường
✅ Quản lý nhà xe "An Phú Bus" (ma_nha_xe = 7)
```

## 🔧 Các File Đã Thay Đổi

### Controllers
- ✅ `app/Http/Controllers/Admin/TaiKhoanNhaXeController.php`
  - `lock()`: Thêm logic lưu thông tin gốc
  - `unlock()`: Thêm logic tự động khôi phục
  - `lockByNhaXe()`: Cập nhật cho khóa hàng loạt

- ✅ `app/Http/Controllers/Admin/NhaXeController.php`
  - `lock()`: Lưu thông tin gốc khi khóa nhà xe
  - `unlock()`: Tự động khôi phục khi mở khóa

### Models
- ✅ `app/Models/User.php`
  - Thêm `locked_original_role` vào fillable
  - Thêm `locked_original_ma_nha_xe` vào fillable
  - Thêm `locked_reason`, `locked_at`, `locked_by` vào fillable

### Views
- ✅ `resources/views/AdminLTE/admin/nha_xe/show.blade.php`
- ✅ `resources/views/AdminLTE/admin/tai_khoan_nha_xe/show.blade.php`
- ✅ `resources/views/AdminLTE/admin/tai_khoan_nha_xe/index.blade.php`

### Migrations
- ✅ `database/migrations/2025_10_27_010112_add_locked_original_data_to_users_table.php`

## 📋 Checklist Triển Khai

### Bước 1: Chạy Migration
```bash
php artisan migrate
```

### Bước 2: Test Chức Năng
```bash
php test_lock_unlock_restore.php
```

### Bước 3: Kiểm Tra UI
- [ ] Vào Admin Panel
- [ ] Thử khóa 1 tài khoản bus_owner
- [ ] Kiểm tra database: locked_original_* đã được lưu
- [ ] Thử mở khóa
- [ ] Kiểm tra: role và ma_nha_xe đã được khôi phục
- [ ] Đăng nhập với tài khoản vừa mở khóa

### Bước 4: Kiểm Tra Log
```bash
tail -f storage/logs/laravel.log
```

Tìm các dòng:
```
[INFO] Admin 1 đã khóa tài khoản 40 - tienkhoa và hạ cấp từ bus_owner xuống user
[INFO] Admin 1 đã mở khóa tài khoản 40 - tienkhoa và khôi phục quyền bus_owner
```

## 🎉 Tính Năng Mới

1. **Backup Tự Động** ✨
   - Lưu role gốc
   - Lưu ma_nha_xe gốc
   - Không cần admin nhớ

2. **Khôi Phục Tự Động** ✨
   - Chỉ cần click "Mở khóa"
   - Không cần gán lại thủ công
   - Giảm sai sót

3. **Thông Báo Rõ Ràng** ✨
   - Modal hiển thị cảnh báo
   - Thông báo có thể khôi phục
   - User yên tâm hơn

## ⚠️ Lưu Ý Quan Trọng

1. **Chỉ khôi phục nếu có backup:**
   - Nếu `locked_original_role` = NULL → Không khôi phục
   - Nếu khóa từ phiên bản cũ → Cần gán lại thủ công

2. **Xóa backup sau khi khôi phục:**
   - Tránh nhầm lẫn
   - Tiết kiệm dung lượng

3. **Log đầy đủ:**
   - Ghi lại mọi thao tác
   - Dễ dàng audit

## 🚀 So Sánh Phiên Bản

| Tính năng | v1.0 | v2.0 |
|-----------|------|------|
| Khóa tài khoản | ✅ | ✅ |
| Hạ cấp xuống user | ✅ | ✅ |
| Lưu thông tin gốc | ❌ | ✅ |
| Tự động khôi phục | ❌ | ✅ |
| Thông báo khôi phục | ❌ | ✅ |
| Cần gán lại thủ công | ✅ | ❌ |

---

**🎊 Hoàn thành cập nhật v2.0!**  
**📅 Ngày: 27/10/2025**  
**👨‍💻 Developer: GitHub Copilot**
