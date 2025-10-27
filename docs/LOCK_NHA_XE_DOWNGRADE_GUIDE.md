# Hướng Dẫn Khóa Tài Khoản Nhà Xe

## Tổng Quan

Khi khóa tài khoản nhà xe (bus_owner hoặc staff), hệ thống sẽ tự động **HẠ CẤP** tài khoản xuống quyền `user` và xóa liên kết với nhà xe.

## Quy Trình Khóa Tài Khoản

### 1. Khóa Tài Khoản Đơn Lẻ

**Vị trí:** Admin → Quản lý Tài khoản Nhà Xe → Chi tiết tài khoản → Nút "Khóa tài khoản"

**Điều kiện:**
- Chỉ khóa được tài khoản đang hoạt động (`is_active = 1`)
- Cần nhập lý do khóa (bắt buộc)

**Hành động khi khóa:**
1. `is_active` → 0 (khóa tài khoản)
2. `role` → 'user' (hạ cấp xuống user)
3. `ma_nha_xe` → NULL (xóa liên kết nhà xe)
4. `locked_reason` → lý do khóa
5. `locked_at` → thời gian khóa
6. `locked_by` → ID admin thực hiện
7. Xóa session để đăng xuất ngay lập tức

**Code Controller:**
```php
// TaiKhoanNhaXeController.php - lock()
$updateData = [
    'is_active' => 0,
    'locked_reason' => $validated['ly_do_khoa'],
    'locked_at' => now(),
    'locked_by' => auth()->id(),
];

if (in_array($taikhoan->role, ['bus_owner', 'staff'])) {
    $updateData['role'] = 'user';
    $updateData['ma_nha_xe'] = null;
}

$taikhoan->update($updateData);
```

### 2. Khóa Nhà Xe (Khóa Hàng Loạt)

**Vị trí:** Admin → Quản lý Nhà Xe → Chi tiết nhà xe → Nút "Khóa nhà xe"

**Điều kiện:**
- Nhà xe đang hoạt động (`trang_thai = 'hoat_dong'`)
- Cần nhập lý do khóa (bắt buộc)

**Hành động khi khóa:**
1. Khóa nhà xe:
   - `trang_thai` → 'bi_khoa'
   - `ly_do_khoa` → lý do
   - `ngay_khoa` → thời gian khóa
   - `admin_khoa_id` → ID admin

2. Khóa TẤT CẢ tài khoản liên quan:
   - Tìm tất cả user có `ma_nha_xe` = nhà xe bị khóa
   - Chỉ khóa role: `bus_owner`, `staff`
   - Hạ cấp xuống `user`
   - Xóa `ma_nha_xe`
   - Xóa session tất cả user

**Code Controller:**
```php
// NhaXeController.php - lock()
$userIds = User::where('ma_nha_xe', (string)$nhaxe->ma_nha_xe)
    ->whereIn('role', ['staff', 'bus_owner'])
    ->pluck('id')
    ->toArray();

$affected = User::whereIn('id', $userIds)
    ->update([
        'is_active' => 0,
        'role' => 'user',
        'ma_nha_xe' => null,
        'locked_reason' => $validated['ly_do_khoa'],
        'locked_at' => now(),
        'locked_by' => auth()->id(),
    ]);
```

## Quy Trình Mở Khóa Tài Khoản

### 1. Mở Khóa Tài Khoản Đơn Lẻ

**Vị trí:** Admin → Quản lý Tài khoản Nhà Xe → Chi tiết tài khoản → Nút "Mở khóa tài khoản"

**Hành động:**
1. `is_active` → 1 (mở khóa)
2. `locked_reason` → NULL
3. `locked_at` → NULL
4. `locked_by` → NULL

**⚠️ Lưu ý:**
- Tài khoản vẫn ở quyền `user`
- Admin cần thủ công gán lại role và ma_nha_xe nếu cần

### 2. Mở Khóa Nhà Xe

**Vị trí:** Admin → Quản lý Nhà Xe → Chi tiết nhà xe → Nút "Mở khóa nhà xe"

**Hành động:**
1. Mở khóa nhà xe:
   - `trang_thai` → 'hoat_dong'
   - Xóa `ly_do_khoa`, `ngay_khoa`, `admin_khoa_id`

2. Mở khóa tài khoản:
   - `is_active` → 1
   - Xóa `locked_reason`, `locked_at`, `locked_by`

**⚠️ Lưu ý:**
- Tài khoản vẫn ở quyền `user` và `ma_nha_xe = NULL`
- Admin cần vào "Quản lý Tài khoản Nhà Xe" để:
  - Gán lại role (bus_owner/staff)
  - Gán lại ma_nha_xe

## Kiểm Tra Thay Đổi

### Trước khi khóa:
```sql
SELECT id, username, role, ma_nha_xe, is_active 
FROM users 
WHERE username = 'tienkhoa';

-- Kết quả:
-- id: 40
-- username: tienkhoa
-- role: bus_owner
-- ma_nha_xe: 7
-- is_active: 1
```

### Sau khi khóa:
```sql
SELECT id, username, role, ma_nha_xe, is_active, locked_reason 
FROM users 
WHERE username = 'tienkhoa';

-- Kết quả:
-- id: 40
-- username: tienkhoa
-- role: user (đã hạ cấp)
-- ma_nha_xe: NULL (đã xóa liên kết)
-- is_active: 0
-- locked_reason: "Vi phạm chính sách..."
```

### Sau khi mở khóa:
```sql
SELECT id, username, role, ma_nha_xe, is_active 
FROM users 
WHERE username = 'tienkhoa';

-- Kết quả:
-- id: 40
-- username: tienkhoa
-- role: user (vẫn là user)
-- ma_nha_xe: NULL (vẫn NULL)
-- is_active: 1
```

## Các File Đã Sửa Đổi

1. **Controller:**
   - `app/Http/Controllers/Admin/TaiKhoanNhaXeController.php`
     - Hàm `lock()`: Thêm logic hạ cấp role
     - Hàm `unlock()`: Thêm thông báo cần gán lại role
     - Hàm `lockByNhaXe()`: Thêm logic hạ cấp role

   - `app/Http/Controllers/Admin/NhaXeController.php`
     - Hàm `lock()`: Thêm logic hạ cấp tất cả user
     - Hàm `unlock()`: Chỉ mở khóa, không tự động khôi phục role

2. **View:**
   - `resources/views/AdminLTE/admin/nha_xe/show.blade.php`
     - Modal khóa: Thêm cảnh báo về hạ cấp role

   - `resources/views/AdminLTE/admin/tai_khoan_nha_xe/show.blade.php`
     - Modal khóa: Thêm cảnh báo về hạ cấp role

   - `resources/views/AdminLTE/admin/tai_khoan_nha_xe/index.blade.php`
     - Modal khóa: Thêm cảnh báo về hạ cấp role

## Test Script

Chạy test để kiểm tra chức năng:
```bash
php test_lock_account.php
```

Kết quả mong đợi:
```
=== TEST KHÓA TÀI KHOẢN NHÀ XE ===

1. Thông tin tài khoản TRƯỚC khi khóa:
   - Role: bus_owner
   - ma_nha_xe: 7
   - is_active: Active

2. Thực hiện KHÓA tài khoản:
   ✅ Đã khóa và hạ cấp tài khoản!

3. Thông tin tài khoản SAU khi khóa:
   - Role: user (đã hạ cấp)
   - ma_nha_xe: NULL (đã xóa)
   - is_active: Locked

✅ CHỨC NĂNG HOẠT ĐỘNG ĐÚNG!
```

## Lưu Ý Quan Trọng

1. **Không tự động khôi phục role:**
   - Khi mở khóa, role vẫn là `user`
   - Admin phải thủ công gán lại role nếu cần

2. **Xóa session:**
   - Khi khóa, session bị xóa → user bị đăng xuất ngay lập tức
   - Bảo mật cao hơn

3. **Log đầy đủ:**
   - Mọi thao tác khóa/mở khóa đều được ghi log
   - Theo dõi được ai khóa, lúc nào, lý do gì

4. **Cảnh báo rõ ràng:**
   - Modal hiển thị cảnh báo về hạ cấp role
   - User biết rõ hành động sẽ diễn ra

## Workflow Thực Tế

### Scenario 1: Khóa tài khoản vi phạm
1. Admin phát hiện bus_owner vi phạm
2. Admin → Quản lý Tài khoản Nhà Xe → Tìm tài khoản
3. Click "Khóa tài khoản"
4. Nhập lý do: "Vi phạm chính sách bán vé"
5. Xác nhận → Tài khoản bị khóa và hạ xuống user
6. Bus owner bị đăng xuất, không truy cập được dashboard

### Scenario 2: Khóa toàn bộ nhà xe
1. Admin phát hiện nhà xe hoạt động không đúng
2. Admin → Quản lý Nhà Xe → Chi tiết nhà xe
3. Click "Khóa nhà xe"
4. Nhập lý do: "Giấy phép hết hạn"
5. Xác nhận → Nhà xe + tất cả tài khoản bị khóa
6. Tất cả staff/bus_owner bị hạ xuống user

### Scenario 3: Mở khóa và phục hồi
1. Nhà xe giải quyết xong vấn đề
2. Admin → Mở khóa nhà xe
3. Admin → Quản lý Tài khoản Nhà Xe
4. Tìm từng tài khoản cần khôi phục
5. Cập nhật:
   - Role → bus_owner/staff
   - ma_nha_xe → mã nhà xe
6. Tài khoản hoạt động trở lại

## API Routes

```php
// Khóa/mở khóa tài khoản đơn
POST /admin/tai-khoan-nha-xe/{taikhoan}/lock
POST /admin/tai-khoan-nha-xe/{taikhoan}/unlock

// Khóa/mở khóa nhà xe (và tất cả tài khoản)
POST /admin/nha-xe/{nhaxe}/lock
POST /admin/nha-xe/{nhaxe}/unlock
```

---

**Ngày cập nhật:** 27/10/2025
**Phiên bản:** 1.0
