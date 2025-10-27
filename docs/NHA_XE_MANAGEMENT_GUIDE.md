# QUẢN LÝ NHÀ XE - HƯỚNG DẪN SỬ DỤNG

## 📋 Tổng Quan

Module quản lý nhà xe cho phép Admin xem thông tin chi tiết và khóa/mở khóa các nhà xe trong hệ thống.

---

## ✨ Tính Năng Chính

### 1. **Xem Danh Sách Nhà Xe**
- Hiển thị tất cả nhà xe trong hệ thống
- Thống kê: Tổng số, Hoạt động, Bị khóa
- Thông tin hiển thị:
  - Mã nhà xe
  - Tên và địa chỉ
  - Số điện thoại, email
  - Số lượng chuyến xe
  - Số lượng nhân viên
  - Số lượng tài khoản
  - Trạng thái (Hoạt động/Bị khóa)

### 2. **Tìm Kiếm & Lọc**
- Tìm theo: Tên nhà xe, Email, Số điện thoại
- Lọc theo trạng thái: Tất cả, Hoạt động, Bị khóa

### 3. **Xem Chi Tiết Nhà Xe**
- Thông tin tổng quan
- Thống kê:
  - Tổng chuyến xe
  - Chuyến xe hôm nay
  - Tổng nhân viên
  - Tổng tài khoản
- Danh sách chuyến xe gần đây
- Danh sách nhân viên
- Danh sách tài khoản staff

### 4. **Khóa Nhà Xe**
- Khóa nhà xe với lý do cụ thể
- Tự động khóa tất cả tài khoản staff của nhà xe
- Lưu thông tin:
  - Lý do khóa
  - Ngày giờ khóa
  - Admin thực hiện khóa

### 5. **Mở Khóa Nhà Xe**
- Mở khóa nhà xe
- Tự động mở khóa tất cả tài khoản staff
- Xóa thông tin khóa

---

## 🚀 Cách Sử Dụng

### Truy Cập Module

1. Đăng nhập với tài khoản Admin
2. Vào menu **"Quản lý nhà xe"** (icon: 🏢)
3. URL: `http://127.0.0.1:8000/admin/nha-xe`

### Xem Danh Sách

**Trang chủ quản lý nhà xe:**
```
/admin/nha-xe
```

**Các thống kê hiển thị:**
- Tổng số nhà xe (màu xanh dương)
- Đang hoạt động (màu xanh lá)
- Bị khóa (màu đỏ)

### Tìm Kiếm

1. Nhập từ khóa vào ô "Tìm kiếm"
2. Chọn trạng thái (nếu muốn)
3. Click nút **"Tìm kiếm"**

**Có thể tìm theo:**
- Tên nhà xe
- Email
- Số điện thoại

### Xem Chi Tiết

1. Click nút **👁️ (Xem)** ở cột "Thao tác"
2. Hoặc truy cập: `/admin/nha-xe/{ma_nha_xe}`

**Thông tin hiển thị:**
- Header với logo nhà xe
- 4 thống kê chính
- Danh sách 10 chuyến xe gần nhất
- Danh sách 10 nhân viên
- Trạng thái khóa (nếu có)
- Danh sách tài khoản staff

### Khóa Nhà Xe

**Cách 1: Từ danh sách**
1. Click nút **🔒 (Khóa)** màu vàng
2. Modal hiện lên
3. Nhập lý do khóa
4. Click **"Khóa nhà xe"**

**Cách 2: Từ trang chi tiết**
1. Vào chi tiết nhà xe
2. Click nút **"Khóa nhà xe"** bên phải
3. Nhập lý do khóa
4. Xác nhận

**⚠️ Lưu ý khi khóa:**
- Tất cả tài khoản staff sẽ bị khóa
- Nhà xe không thể hoạt động
- Cần có lý do rõ ràng

### Mở Khóa Nhà Xe

**Cách 1: Từ danh sách**
1. Click nút **🔓 (Mở khóa)** màu xanh
2. Xác nhận

**Cách 2: Từ trang chi tiết**
1. Vào chi tiết nhà xe bị khóa
2. Click nút **"Mở khóa nhà xe"**
3. Xác nhận

**Kết quả:**
- Nhà xe được kích hoạt lại
- Tất cả staff được mở khóa
- Xóa thông tin khóa

---

## 🗄️ Cấu Trúc Database

### Bảng: `nha_xe`

**Các cột mới thêm:**

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| `trang_thai` | ENUM | 'hoat_dong' hoặc 'bi_khoa' |
| `ly_do_khoa` | TEXT | Lý do khóa nhà xe |
| `ngay_khoa` | TIMESTAMP | Thời gian khóa |
| `admin_khoa_id` | INT | ID admin thực hiện khóa |

---

## 📁 Các File Code

### Controller
```
app/Http/Controllers/Admin/NhaXeController.php
```

**Methods:**
- `index()` - Danh sách nhà xe
- `show($nhaxe)` - Chi tiết nhà xe
- `lock(Request, $nhaxe)` - Khóa nhà xe
- `unlock($nhaxe)` - Mở khóa nhà xe
- `destroy($nhaxe)` - Xóa (khóa vĩnh viễn)

### Views
```
resources/views/AdminLTE/admin/nha_xe/
├── index.blade.php    (Danh sách)
└── show.blade.php     (Chi tiết)
```

### Model
```
app/Models/NhaXe.php
```

**Fillable thêm:**
- `trang_thai`
- `ly_do_khoa`
- `ngay_khoa`
- `admin_khoa_id`

### Routes
```php
// routes/web.php
Route::get('nha-xe', [NhaXeController::class, 'index'])->name('nha-xe.index');
Route::get('nha-xe/{nhaxe}', [NhaXeController::class, 'show'])->name('nha-xe.show');
Route::post('nha-xe/{nhaxe}/lock', [NhaXeController::class, 'lock'])->name('nha-xe.lock');
Route::post('nha-xe/{nhaxe}/unlock', [NhaXeController::class, 'unlock'])->name('nha-xe.unlock');
Route::delete('nha-xe/{nhaxe}', [NhaXeController::class, 'destroy'])->name('nha-xe.destroy');
```

---

## 🎨 Giao Diện

### Màu Sắc

| Trạng thái | Màu | Badge |
|-----------|-----|-------|
| Hoạt động | Xanh lá (#28a745) | ✓ Hoạt động |
| Bị khóa | Đỏ (#dc3545) | 🔒 Bị khóa |

### Icons

| Chức năng | Icon | Màu |
|-----------|------|-----|
| Xem chi tiết | 👁️ (fa-eye) | Xanh dương |
| Khóa | 🔒 (fa-lock) | Vàng |
| Mở khóa | 🔓 (fa-unlock) | Xanh lá |

---

## ⚡ Tính Năng Tự Động

### Khi Khóa Nhà Xe

1. **Cập nhật bảng nha_xe:**
   - `trang_thai` = 'bi_khoa'
   - `ly_do_khoa` = (lý do nhập vào)
   - `ngay_khoa` = thời gian hiện tại
   - `admin_khoa_id` = ID admin đang login

2. **Khóa tài khoản:**
   - Tìm tất cả User có `ma_nha_xe` = nhà xe bị khóa
   - Và `role` = 'staff'
   - Set `is_active` = 0

### Khi Mở Khóa

1. **Cập nhật bảng nha_xe:**
   - `trang_thai` = 'hoat_dong'
   - `ly_do_khoa` = NULL
   - `ngay_khoa` = NULL
   - `admin_khoa_id` = NULL

2. **Mở khóa tài khoản:**
   - Tìm tất cả User của nhà xe
   - Set `is_active` = 1

---

## 🔍 Validation

### Khóa Nhà Xe

**Rules:**
```php
'ly_do_khoa' => 'required|string|max:500'
```

**Error Messages:**
- "Vui lòng nhập lý do khóa nhà xe"
- "Lý do không được vượt quá 500 ký tự"

---

## 📊 Thống Kê

### Trang Index

- **Tổng số nhà xe:** Đếm tất cả records
- **Hoạt động:** WHERE trang_thai = 'hoat_dong'
- **Bị khóa:** WHERE trang_thai = 'bi_khoa'

### Trang Chi Tiết

- **Tổng chuyến xe:** Count relationships
- **Chuyến xe hôm nay:** WHERE ngay_di = today
- **Tổng nhân viên:** Count nhanVien
- **Tổng tài khoản:** Count users (role = staff)

---

## 💡 Tips

### Tìm Kiếm Hiệu Quả

```php
// Tìm nhiều trường cùng lúc
WHERE (ten_nha_xe LIKE '%keyword%' 
   OR email LIKE '%keyword%'
   OR so_dien_thoai LIKE '%keyword%')
```

### Phân Trang

- Mặc định: 15 items/page
- Sử dụng: `->paginate(15)`
- Bootstrap pagination tự động

### Loading Relationships

```php
$nhaXe->load([
    'chuyenXe' => function($query) {
        $query->with(['tramDi', 'tramDen'])
              ->orderBy('ngay_di', 'desc')
              ->take(10);
    }
]);
```

---

## 🛡️ Bảo Mật

### Middleware

- Route group: `middleware('role:admin')`
- Chỉ Admin mới truy cập được

### Authorization

Tất cả route đều yêu cầu:
1. User đã đăng nhập
2. Role = 'admin'

---

## 🐛 Xử Lý Lỗi

### Không Thể Xóa

**Điều kiện:**
- Nhà xe có chuyến xe đang hoạt động (ngay_di >= today)

**Xử lý:**
- Không cho xóa
- Hiển thị: "Không thể xóa nhà xe có chuyến xe đang hoạt động!"

### Session Messages

**Success:**
```php
return redirect()->back()->with('success', 'Đã khóa nhà xe thành công!');
```

**Error:**
```php
return redirect()->back()->with('error', 'Có lỗi xảy ra!');
```

---

## 📱 Responsive

- Bootstrap 4 grid system
- Card layout
- Mobile-friendly table
- Touch-friendly buttons

---

## 🚧 Chú Ý

1. **Backup trước khi khóa:** Khóa nhà xe ảnh hưởng nhiều user
2. **Lý do rõ ràng:** Luôn nhập lý do cụ thể
3. **Kiểm tra trước:** Xem chi tiết trước khi khóa
4. **Thông báo:** Nên thông báo cho nhà xe trước khi khóa

---

## 📞 Support

Nếu có vấn đề:
1. Kiểm tra migration đã chạy chưa
2. Kiểm tra permission (role admin)
3. Xem log: `storage/logs/laravel.log`
4. Check database connection

---

**Created:** 24/10/2025  
**Version:** 1.0  
**Author:** Admin System
