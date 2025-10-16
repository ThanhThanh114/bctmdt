# HỆ THỐNG BÁO CÁO DOANH THU & VÉ BÁN - CẢI TIẾN

## Tổng quan các cải tiến

### 1. **6 Biểu đồ thống kê**

Hệ thống hiện có 6 biểu đồ tương tác:

#### Biểu đồ Doanh thu:

- **Theo Ngày**: Hiển thị doanh thu 30 ngày gần nhất (dạng line chart)
- **Theo Tháng**: Hiển thị doanh thu 12 tháng trong năm được chọn (dạng bar chart)
- **Theo Năm**: Hiển thị doanh thu 5 năm gần nhất (dạng bar chart)

#### Biểu đồ Số vé đã bán:

- **Theo Ngày**: Hiển thị số vé bán ra trong 30 ngày gần nhất (dạng line chart)
- **Theo Tháng**: Hiển thị số vé bán ra trong 12 tháng của năm được chọn (dạng bar chart)
- **Theo Năm**: Hiển thị số vé bán ra trong 5 năm gần nhất (dạng bar chart)

### 2. **Bộ lọc thông minh**

- **Loại báo cáo**: Chọn xem theo Ngày/Tháng/Năm
- **Năm**: Chọn năm cần xem (5 năm gần nhất)
- **Tháng**: Chọn tháng cần xem (chỉ hiển thị khi cần)
- Bộ lọc tự động ẩn/hiện các trường phù hợp

### 3. **Giao diện cải tiến**

- Sử dụng Tab để chuyển đổi giữa các loại biểu đồ
- Cards với shadow và hiệu ứng hover
- Responsive trên mọi thiết bị
- Màu sắc phân biệt rõ ràng cho từng loại dữ liệu

### 4. **Thống kê tổng quan**

4 thẻ thống kê nhanh:

- Doanh thu hôm nay
- Doanh thu tháng này
- Doanh thu năm nay
- Tổng số vé đã bán

## Cấu trúc Files

### Backend

```
app/Http/Controllers/Admin/DoanhThuController.php
```

**Các phương thức chính:**

- `index()`: Hiển thị dashboard với tất cả dữ liệu
- `calculateDailyRevenue()`: Tính doanh thu theo ngày
- `calculateMonthlyRevenue()`: Tính doanh thu theo tháng
- `calculateYearlyRevenue()`: Tính doanh thu theo năm
- `calculateDailyTickets()`: Tính số vé bán theo ngày
- `calculateMonthlyTickets()`: Tính số vé bán theo tháng
- `calculateYearlyTickets()`: Tính số vé bán theo năm
- `calculateRevenueFromBookings()`: Tính tổng doanh thu từ danh sách đặt vé

### Frontend

```
resources/views/AdminLTE/admin/doanh_thu/index.blade.php
public/css/doanh_thu.css
```

### Routes

```php
Route::get('doanhthu', [DoanhThuController::class, 'index'])->name('doanhthu.index');
Route::get('doanhthu/export', [DoanhThuController::class, 'export'])->name('doanhthu.export');
```

## Cách sử dụng

### 1. Truy cập trang báo cáo

```
http://your-domain/admin/doanhthu
```

### 2. Lọc dữ liệu

1. Chọn **Loại báo cáo**: Ngày/Tháng/Năm
2. Chọn **Năm** (nếu cần)
3. Chọn **Tháng** (nếu cần)
4. Click **Lọc dữ liệu**

### 3. Xem biểu đồ

- Click vào các tab để chuyển đổi giữa:
    - Biểu đồ theo Ngày
    - Biểu đồ theo Tháng
    - Biểu đồ theo Năm

### 4. Tương tác với biểu đồ

- **Hover**: Xem chi tiết giá trị tại từng điểm
- **Legend**: Click vào chú thích để ẩn/hiện dataset
- Biểu đồ tự động format số tiền theo định dạng Việt Nam

## Chi tiết kỹ thuật

### Dữ liệu gửi từ Controller

```php
[
    'dailyRevenue' => [30 days data],
    'dailyTickets' => [30 days data],
    'monthlyRevenue' => [12 months data],
    'monthlyTickets' => [12 months data],
    'yearlyRevenue' => [5 years data],
    'yearlyTickets' => [5 years data],
    'stats' => [...],
    'topTrips' => [...],
    'revenueByCompany' => [...]
]
```

### Thư viện sử dụng

- **Chart.js**: Vẽ biểu đồ tương tác
- **Bootstrap 4**: Framework CSS
- **AdminLTE 3**: Template admin
- **Font Awesome**: Icons

### Tính năng Chart.js

- Responsive và tự động scale
- Tooltip với format tiền tệ
- Animation mượt mà
- Export ảnh (có thể mở rộng)

## Các tính năng nâng cao có thể thêm

### 1. Export dữ liệu

- Export sang Excel/CSV
- Export biểu đồ sang PNG/PDF
- Gửi báo cáo qua email

### 2. So sánh dữ liệu

- So sánh 2 khoảng thời gian
- Phân tích xu hướng tăng/giảm
- Dự đoán doanh thu

### 3. Lọc nâng cao

- Theo nhà xe cụ thể
- Theo tuyến đường
- Theo trạng thái thanh toán

### 4. Dashboard realtime

- Cập nhật dữ liệu tự động
- Thông báo khi có đơn hàng mới
- Biểu đồ động

## Xử lý lỗi phổ biến

### 1. Biểu đồ không hiển thị

**Nguyên nhân**: Thiếu dữ liệu hoặc Chart.js chưa load
**Giải pháp**:

- Kiểm tra console browser (F12)
- Đảm bảo CDN Chart.js hoạt động
- Kiểm tra dữ liệu từ controller

### 2. Lỗi "Undefined variable"

**Nguyên nhân**: Controller chưa gửi đủ biến
**Giải pháp**:

- Kiểm tra method `index()` trong DoanhThuController
- Đảm bảo compact() có đủ các biến cần thiết

### 3. Số liệu không chính xác

**Nguyên nhân**: Logic tính toán sai hoặc dữ liệu bị null
**Giải pháp**:

- Kiểm tra các method `calculate*` trong controller
- Kiểm tra relation giữa DatVe và ChuyenXe
- Đảm bảo trạng thái "Đã hủy" được filter đúng

### 4. Biểu đồ bị lỗi khi resize

**Nguyên nhân**: Chart.js responsive config
**Giải pháp**:

- Đã set `maintainAspectRatio: false`
- Container có height cố định

## Performance Tips

### 1. Cache dữ liệu

```php
Cache::remember('revenue_monthly_'.$year, 3600, function() {
    return $this->calculateMonthlyRevenue($year);
});
```

### 2. Eager loading

Đã sử dụng `with('chuyenXe')` để tránh N+1 query problem

### 3. Database indexing

Đảm bảo các cột sau có index:

- `dat_ve.ngay_dat`
- `dat_ve.trang_thai`
- `chuyen_xe.ma_nha_xe`

## Tóm tắt

✅ **Đã hoàn thành:**

- 6 biểu đồ doanh thu và vé bán (ngày/tháng/năm)
- Bộ lọc thông minh với điều kiện động
- Giao diện đẹp, responsive
- Tương tác mượt mà với Chart.js
- Format số tiền chuẩn VN
- Code sạch, dễ bảo trì

✅ **Đã sửa lỗi:**

- Thiếu dữ liệu vé bán
- Không có bộ lọc
- Biểu đồ đơn giản
- Giao diện chưa đẹp

🎯 **Kết quả:**
Một hệ thống báo cáo doanh thu hoàn chỉnh, trực quan và dễ sử dụng cho admin.
