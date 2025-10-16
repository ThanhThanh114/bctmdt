# 📊 HỆ THỐNG BÁO CÁO TỔNG HỢP - BUS BOOKING

## 🎯 Tổng quan

Hệ thống báo cáo cung cấp 8 module báo cáo chi tiết giúp quản lý và phân tích dữ liệu toàn diện.

---

## 📋 DANH SÁCH BÁO CÁO

### 1. 📊 **Báo cáo Tổng hợp** (`/admin/report`)

**Chức năng:**

- Hiển thị 8 box thống kê tổng quan hệ thống
- Thống kê tháng hiện tại (doanh thu, vé bán, người dùng mới, bình luận mới)
- Top 5 người dùng đặt vé nhiều nhất
- Top 5 chuyến xe phổ biến nhất
- Menu điều hướng nhanh đến 8 báo cáo khác

**Dữ liệu:**

- Tổng người dùng
- Tổng đặt vé
- Tổng chuyến xe
- Tổng nhà xe
- Tổng doanh thu
- Tổng bình luận
- Tổng liên hệ
- Doanh thu tháng này

---

### 2. 🎫 **Báo cáo Đặt vé** (`/admin/report/bookings`)

**Chức năng:**

- Bộ lọc theo khoảng thời gian (từ ngày - đến ngày)
- Thống kê theo trạng thái (Tổng/Đã thanh toán/Chờ thanh toán/Đã hủy)
- Hiển thị tổng doanh thu trong khoảng thời gian
- Biểu đồ đường theo ngày (Chart.js)

**Sử dụng:**

1. Chọn khoảng thời gian cần xem
2. Click "Lọc"
3. Xem biểu đồ và thống kê

---

### 3. 💰 **Báo cáo Doanh thu** (`/admin/report/revenue`)

**Chức năng:**

- Bộ lọc theo năm
- Hiển thị tổng doanh thu năm
- Biểu đồ cột doanh thu theo 12 tháng
- Bảng doanh thu theo từng nhà xe (có % và progress bar)

**Phân tích:**

- So sánh doanh thu các tháng trong năm
- Xác định nhà xe có doanh thu cao nhất
- Phân tích tỷ lệ đóng góp của từng nhà xe

---

### 4. 👥 **Báo cáo Người dùng** (`/admin/report/users`)

**Chức năng:**

- 4 box thống kê theo role (Khách hàng/Nhân viên/Chủ xe/Admin)
- Biểu đồ đường người dùng mới theo tháng trong năm
- Top 10 người dùng tích cực nhất (đặt nhiều vé nhất)

**Insights:**

- Theo dõi tăng trưởng người dùng
- Xác định khách hàng VIP
- Phân tích phân bố role

---

### 5. 💬 **Báo cáo Bình luận** (`/admin/report/comments`)

**Chức năng:**

- Thống kê tổng quan (Tổng/Tháng này/Đã duyệt/Chờ duyệt)
- Biểu đồ bình luận theo tháng
- Top chuyến xe có nhiều bình luận nhất
- Phân bố đánh giá sao (1-5 sao)

**Sử dụng:**

- Theo dõi feedback khách hàng
- Xác định chuyến xe được quan tâm
- Phân tích mức độ hài lòng

---

### 6. 🏢 **Báo cáo Nhà xe** (`/admin/report/operators`)

**Chức năng:**

- Thống kê chi tiết từng nhà xe:
    - Số chuyến xe
    - Số vé bán được
    - Doanh thu
    - Đánh giá trung bình
    - Số bình luận
- Biểu đồ ngang (horizontal bar) doanh thu các nhà xe
- Tổng doanh thu toàn hệ thống

**Phân tích:**

- So sánh hiệu quả kinh doanh các nhà xe
- Xác định đối tác chiến lược
- Đánh giá chất lượng dịch vụ

---

### 7. 🛣️ **Báo cáo Tuyến đường** (`/admin/report/routes`)

**Chức năng:**

- Top 15 tuyến đường phổ biến nhất
- Hiển thị:
    - Điểm đi → Điểm đến
    - Số chuyến
    - Số vé đã bán

**Insights:**

- Xác định tuyến đường hot
- Lập kế hoạch mở tuyến mới
- Tối ưu lịch trình

---

### 8. 📧 **Báo cáo Liên hệ** (`/admin/report/contacts`)

**Chức năng:**

- Thống kê tổng quan (Tổng/Tháng này)
- Biểu đồ liên hệ theo tháng
- Phân bố liên hệ theo chi nhánh

**Sử dụng:**

- Theo dõi tương tác khách hàng
- Đánh giá hiệu quả chăm sóc
- Phân tích nhu cầu theo khu vực

---

## 🔧 TÍNH NĂNG CHUNG

### Biểu đồ (Chart.js)

- **Line Chart**: Dùng cho dữ liệu theo thời gian
- **Bar Chart**: So sánh các nhóm dữ liệu
- **Horizontal Bar**: Dùng cho danh sách dài
- Hỗ trợ: Hover tooltips, responsive, animation

### Bộ lọc

- **Theo thời gian**: Ngày, tháng, năm
- **Theo đối tượng**: Nhà xe, người dùng, chi nhánh
- **Khoảng thời gian**: Từ ngày - đến ngày

### Export (Đang phát triển)

- Excel (.xlsx)
- PDF
- CSV

---

## 📊 CÔNG THỨC TÍNH TOÁN

### Doanh thu

```php
Doanh thu = Σ (Số ghế × Giá vé)
// Chỉ tính các vé không bị hủy
```

### Đánh giá trung bình

```php
Đánh giá TB = Σ Số sao / Tổng số bình luận đã duyệt
```

### Tỷ lệ phần trăm

```php
% = (Giá trị riêng / Tổng) × 100
```

---

## 🎨 GIAO DIỆN

### Theme

- AdminLTE 3
- Bootstrap 4
- Font Awesome icons
- Responsive design

### Màu sắc

- **Primary** (Xanh dương): #007bff - Thông tin chính
- **Success** (Xanh lá): #28a745 - Doanh thu, thành công
- **Warning** (Vàng): #ffc107 - Cảnh báo, chờ xử lý
- **Danger** (Đỏ): #dc3545 - Lỗi, đã hủy
- **Info** (Xanh nhạt): #17a2b8 - Thông tin bổ sung

---

## 🚀 HƯỚNG DẪN SỬ DỤNG

### Truy cập báo cáo

1. Đăng nhập với role **Admin**
2. Vào menu **Báo cáo** → **Tổng hợp**
3. Click vào icon tương ứng để xem báo cáo chi tiết

### Lọc dữ liệu

1. Chọn bộ lọc (năm, tháng, khoảng thời gian)
2. Click nút **Lọc** hoặc **Xem**
3. Kết quả hiển thị tức thì

### Xem biểu đồ

- Hover chuột lên biểu đồ để xem chi tiết
- Click legend để ẩn/hiện dataset
- Biểu đồ tự động responsive

---

## 🔐 PHÂN QUYỀN

### Admin

- ✅ Xem tất cả 8 báo cáo
- ✅ Export dữ liệu
- ✅ Truy cập mọi thời điểm

### Staff

- ❌ Không có quyền truy cập báo cáo
- (Có thể mở rộng trong tương lai)

---

## 📝 GHI CHÚ KỸ THUẬT

### Controller

- File: `App\Http\Controllers\Admin\ReportController.php`
- Methods: 8 public methods + helper methods
- Database: Tối ưu với eager loading

### Views

- Folder: `resources/views/AdminLTE/admin/report/`
- Files:
    - `index.blade.php` - Tổng hợp
    - `bookings.blade.php` - Đặt vé
    - `revenue.blade.php` - Doanh thu
    - `users.blade.php` - Người dùng
    - `comments.blade.php` - Bình luận (cần tạo)
    - `operators.blade.php` - Nhà xe
    - `routes.blade.php` - Tuyến đường (cần tạo)
    - `contacts.blade.php` - Liên hệ (cần tạo)

### Routes

```php
Route::get('report', 'index')->name('report.index');
Route::get('report/bookings', 'bookings')->name('report.bookings');
Route::get('report/revenue', 'revenue')->name('report.revenue');
Route::get('report/users', 'users')->name('report.users');
Route::get('report/comments', 'comments')->name('report.comments');
Route::get('report/operators', 'operators')->name('report.operators');
Route::get('report/routes', 'routes')->name('report.routes');
Route::get('report/contacts', 'contacts')->name('report.contacts');
```

---

## 🐛 TROUBLESHOOTING

### Lỗi GROUP BY

**Triệu chứng**: SQL error về GROUP BY  
**Nguyên nhân**: MySQL strict mode  
**Giải pháp**: Liệt kê đầy đủ các cột trong GROUP BY

### Lỗi relationship không tồn tại

**Triệu chứng**: Call to undefined relationship  
**Nguyên nhân**: Tên relationship sai  
**Giải pháp**: Kiểm tra tên trong model (tramDi, tramDen, nhaXe)

### Biểu đồ không hiển thị

**Triệu chứng**: Canvas trống  
**Nguyên nhân**: Thiếu Chart.js  
**Giải pháp**: Thêm CDN Chart.js vào view

---

## 🎯 TƯƠNG LAI

### Tính năng sắp tới

- [ ] Export PDF/Excel
- [ ] Lọc nâng cao hơn
- [ ] Real-time updates
- [ ] Email báo cáo tự động
- [ ] Dashboard widgets
- [ ] Mobile app reports
- [ ] API endpoints cho third-party

---

**Phát triển bởi**: Your Team  
**Cập nhật lần cuối**: {{ date('d/m/Y') }}  
**Phiên bản**: 1.0.0
