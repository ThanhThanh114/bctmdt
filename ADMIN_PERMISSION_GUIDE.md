# HỆ THỐNG PHÂN QUYỀN ADMIN - QUẢN LÝ ĐẶT VÉ XE

## Tổng quan

Hệ thống phân quyền đầy đủ cho trang Admin với 7 module quản lý chính:

## 1. QUẢN LÝ NGƯỜI DÙNG (Users)

**Controller:** `Admin\UsersController`
**Routes:**

- `GET /admin/users` - Danh sách người dùng
- `GET /admin/users/create` - Form tạo người dùng
- `POST /admin/users` - Lưu người dùng mới
- `GET /admin/users/{user}` - Xem chi tiết
- `GET /admin/users/{user}/edit` - Form sửa
- `PUT /admin/users/{user}` - Cập nhật
- `DELETE /admin/users/{user}` - Xóa

**Chức năng:**

- Xem danh sách tất cả người dùng
- Lọc theo role (user, admin, staff, bus_owner)
- Tìm kiếm theo tên, email, phone
- Thêm/sửa/xóa người dùng
- Xem lịch sử đặt vé của người dùng

---

## 2. QUẢN LÝ NHÂN VIÊN (NhanVien)

**Controller:** `Admin\NhanVienController`
**Routes:**

- `GET /admin/nhanvien` - Danh sách nhân viên
- `GET /admin/nhanvien/create` - Form tạo nhân viên
- `POST /admin/nhanvien` - Lưu nhân viên mới
- `GET /admin/nhanvien/{nhanvien}` - Xem chi tiết
- `GET /admin/nhanvien/{nhanvien}/edit` - Form sửa
- `PUT /admin/nhanvien/{nhanvien}` - Cập nhật
- `DELETE /admin/nhanvien/{nhanvien}` - Xóa

**Chức năng:**

- Quản lý tất cả nhân viên trong hệ thống
- Lọc theo chức vụ: tài xế, phụ xe, nhân viên văn phòng, quản lý
- Lọc theo nhà xe
- Tìm kiếm theo tên, SĐT, email
- Thêm/sửa/xóa nhân viên

---

## 3. QUẢN LÝ ĐẶT VÉ (DatVe)

**Controller:** `Admin\DatVeController`
**Routes:**

- `GET /admin/datve` - Danh sách vé đã đặt
- `GET /admin/datve/{datve}` - Xem chi tiết vé
- `PATCH /admin/datve/{datve}/status` - Cập nhật trạng thái
- `DELETE /admin/datve/{datve}` - Xóa vé
- `GET /admin/datve-statistics` - Thống kê đặt vé
- `GET /admin/datve-export` - Xuất báo cáo

**Chức năng:**

- Xem tất cả vé đã đặt
- Lọc theo trạng thái: Đã đặt, Đã thanh toán, Đã hủy
- Lọc theo khoảng thời gian
- Tìm kiếm theo mã vé, tên khách hàng, SĐT
- Cập nhật trạng thái vé
- Xem thống kê: tổng vé, doanh thu
- Xuất báo cáo Excel/PDF

---

## 4. QUẢN LÝ BÌNH LUẬN (BinhLuan)

**Controller:** `Admin\BinhLuanController`
**Routes:**

- `GET /admin/binhluan` - Danh sách bình luận
- `GET /admin/binhluan/{binhluan}` - Xem chi tiết
- `POST /admin/binhluan/{binhluan}/approve` - Duyệt bình luận
- `POST /admin/binhluan/{binhluan}/reject` - Từ chối bình luận
- `POST /admin/binhluan/bulk-approve` - Duyệt hàng loạt
- `POST /admin/binhluan/bulk-delete` - Xóa hàng loạt
- `DELETE /admin/binhluan/{binhluan}` - Xóa bình luận
- `GET /admin/binhluan-statistics` - Thống kê bình luận

**Chức năng:**

- Xem tất cả bình luận và trả lời
- Lọc theo trạng thái: chờ duyệt, đã duyệt, từ chối
- Lọc theo số sao (1-5)
- Duyệt/từ chối bình luận
- Duyệt/xóa hàng loạt
- Thống kê: tổng bình luận, đánh giá trung bình
- Phân bố đánh giá theo sao

---

## 5. QUẢN LÝ DOANH THU (DoanhThu)

**Controller:** `Admin\DoanhThuController`
**Routes:**

- `GET /admin/doanhthu` - Dashboard doanh thu
- `GET /admin/doanhthu/by-trip` - Doanh thu theo chuyến xe
- `GET /admin/doanhthu/by-company` - Doanh thu theo nhà xe
- `GET /admin/doanhthu/export` - Xuất báo cáo

**Chức năng:**

- Dashboard tổng quan doanh thu
- Thống kê theo ngày/tháng/năm
- Doanh thu hôm nay, tháng này, năm nay
- Biểu đồ doanh thu theo tháng trong năm
- Biểu đồ doanh thu theo ngày trong tháng
- Top 10 chuyến xe có doanh thu cao nhất
- Doanh thu theo từng nhà xe
- Tổng vé đã bán/hủy
- Xuất báo cáo Excel/PDF

---

## 6. QUẢN LÝ KHUYẾN MÃI (KhuyenMai)

**Controller:** `Admin\KhuyenMaiController`
**Routes:**

- `GET /admin/khuyenmai` - Danh sách khuyến mãi
- `GET /admin/khuyenmai/create` - Form tạo khuyến mãi
- `POST /admin/khuyenmai` - Lưu khuyến mãi mới
- `GET /admin/khuyenmai/{khuyenmai}` - Xem chi tiết
- `GET /admin/khuyenmai/{khuyenmai}/edit` - Form sửa
- `PUT /admin/khuyenmai/{khuyenmai}` - Cập nhật
- `DELETE /admin/khuyenmai/{khuyenmai}` - Xóa
- `POST /admin/khuyenmai/{khuyenmai}/toggle-status` - Kích hoạt/vô hiệu hóa
- `POST /admin/khuyenmai/check-code` - Kiểm tra mã khuyến mãi (API)

**Chức năng:**

- Quản lý tất cả mã khuyến mãi
- Lọc theo trạng thái: đang hoạt động, sắp diễn ra, hết hạn
- Tìm kiếm theo tên, mã code
- Thêm/sửa/xóa khuyến mãi
- Kích hoạt/vô hiệu hóa khuyến mãi
- Xem thống kê sử dụng
- Xem danh sách vé đã dùng mã
- API kiểm tra mã khuyến mãi hợp lệ

---

## 7. QUẢN LÝ TIN TỨC (TinTuc)

**Controller:** `Admin\TinTucController`
**Routes:**

- `GET /admin/tintuc` - Danh sách tin tức
- `GET /admin/tintuc/create` - Form tạo tin tức
- `POST /admin/tintuc` - Lưu tin tức mới
- `GET /admin/tintuc/{tintuc}` - Xem chi tiết
- `GET /admin/tintuc/{tintuc}/edit` - Form sửa
- `PUT /admin/tintuc/{tintuc}` - Cập nhật
- `DELETE /admin/tintuc/{tintuc}` - Xóa
- `POST /admin/tintuc/bulk-delete` - Xóa hàng loạt
- `POST /admin/tintuc/{tintuc}/toggle-pin` - Ghim tin
- `POST /admin/tintuc/{tintuc}/toggle-publish` - Xuất bản/ẩn

**Chức năng:**

- Quản lý tất cả tin tức
- Lọc theo nhà xe
- Lọc theo người đăng
- Tìm kiếm theo tiêu đề, nội dung
- Thêm/sửa/xóa tin tức
- Upload hình ảnh (max 2MB)
- Xóa hàng loạt
- Thống kê: tổng tin, hôm nay, tháng này
- Ghim tin quan trọng (đang phát triển)
- Xuất bản/ẩn tin (đang phát triển)

---

## PHÂN QUYỀN & BẢO MẬT

### Middleware CheckRole

- Tất cả routes admin được bảo vệ bởi middleware `role:admin`
- Chỉ user có role = 'admin' mới truy cập được
- User khác role sẽ nhận lỗi 403 Forbidden
- Chưa đăng nhập sẽ redirect về trang login

### Cấu trúc Middleware

```php
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {
            // Tất cả routes admin ở đây
        });
});
```

---

## CẤU TRÚC FILES

### Controllers (app/Http/Controllers/Admin/)

- `DashboardController.php` - Dashboard tổng quan
- `UsersController.php` - Quản lý users
- `NhanVienController.php` - Quản lý nhân viên
- `DatVeController.php` - Quản lý đặt vé
- `BinhLuanController.php` - Quản lý bình luận
- `DoanhThuController.php` - Quản lý doanh thu
- `KhuyenMaiController.php` - Quản lý khuyến mãi
- `TinTucController.php` - Quản lý tin tức

### Models (app/Models/)

- `User.php` - Model người dùng
- `NhanVien.php` - Model nhân viên
- `DatVe.php` - Model đặt vé
- `BinhLuan.php` - Model bình luận
- `KhuyenMai.php` - Model khuyến mãi
- `VeKhuyenMai.php` - Model pivot vé-khuyến mãi
- `TinTuc.php` - Model tin tức
- `ChuyenXe.php` - Model chuyến xe
- `NhaXe.php` - Model nhà xe
- `TramXe.php` - Model trạm xe

### Views (resources/views/AdminLTE/admin/)

- `dashboard.blade.php` - Dashboard
- `users/` - Views quản lý users
- `nhan_vien/` - Views quản lý nhân viên
- `dat_ve/` - Views quản lý đặt vé
- `binh_luan/` - Views quản lý bình luận
- `doanh_thu/` - Views quản lý doanh thu
- `khuyen_mai/` - Views quản lý khuyến mãi
- `tin_tuc/` - Views quản lý tin tức

---

## DATABASE RELATIONSHIPS

### DatVe (Đặt vé)

- `belongsTo` User
- `belongsTo` ChuyenXe
- `belongsToMany` KhuyenMai (qua bảng ve_khuyenmai)

### BinhLuan (Bình luận)

- `belongsTo` User
- `belongsTo` ChuyenXe
- `belongsTo` BinhLuan (parent) - bình luận cha
- `hasMany` BinhLuan (replies) - các trả lời

### KhuyenMai (Khuyến mãi)

- `hasMany` VeKhuyenMai
- `belongsToMany` DatVe (qua bảng ve_khuyenmai)

### ChuyenXe (Chuyến xe)

- `belongsTo` NhaXe
- `belongsTo` TramXe (tramDi)
- `belongsTo` TramXe (tramDen)
- `hasMany` DatVe
- `hasMany` BinhLuan

---

## KIỂM TRA HỆ THỐNG

### Kiểm tra Routes

```bash
php artisan route:list --name=admin
```

### Clear Cache

```bash
php artisan route:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### Test Middleware

Thử truy cập các URL sau khi chưa đăng nhập:

- Sẽ redirect về `/login`

Thử truy cập với user role khác admin:

- Sẽ nhận lỗi 403

---

## HƯỚNG DẪN SỬ DỤNG

### 1. Đăng nhập Admin

- URL: `http://127.0.0.1:8000/login`
- Username: admin
- Email: admin@gmail.com

### 2. Truy cập Dashboard

- URL: `http://127.0.0.1:8000/admin/dashboard`

### 3. Các trang quản lý

- **Users:** `/admin/users`
- **Nhân viên:** `/admin/nhanvien`
- **Đặt vé:** `/admin/datve`
- **Bình luận:** `/admin/binhluan`
- **Doanh thu:** `/admin/doanhthu`
- **Khuyến mãi:** `/admin/khuyenmai`
- **Tin tức:** `/admin/tintuc`

---

## CHÚ Ý QUAN TRỌNG

1. **Views chưa tạo**: Cần tạo các view Blade tương ứng trong `resources/views/AdminLTE/admin/`

2. **Auth Guard**: Đảm bảo `auth()->user()` trả về User model với thuộc tính `role`

3. **Validation**: Tất cả form đều có validation đầy đủ với message tiếng Việt

4. **File Upload**: Khuyến mãi upload vào `public/assets/image/`

5. **Pagination**: Mặc định 15 items/page, có thể tùy chỉnh

6. **Soft Delete**: Chưa implement, có thể thêm sau

7. **Activity Log**: Chưa implement logging hành động admin

8. **API**: Có endpoint check mã khuyến mãi cho frontend

---

## TÍNH NĂNG SẼ PHÁT TRIỂN

1. Export Excel/PDF cho báo cáo
2. Ghim tin tức quan trọng
3. Xuất bản/ẩn tin tức
4. Activity logging (log hành động admin)
5. Soft delete cho các model
6. Real-time notifications
7. Advanced filters & sorting
8. Bulk actions (cập nhật hàng loạt)

---

## KẾT LUẬN

Hệ thống phân quyền admin đã được thiết lập đầy đủ với:
✅ 7 module quản lý chính
✅ 54 routes được bảo vệ
✅ Middleware phân quyền role:admin
✅ Controllers với đầy đủ CRUD
✅ Models với relationships
✅ Validation & error handling
✅ Search & filter functionality
✅ Statistics & reporting
✅ Bulk operations

**Chỉ cần tạo views để hoàn thiện hệ thống!**
