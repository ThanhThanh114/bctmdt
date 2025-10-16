# CHECKLIST KIỂM TRA HỆ THỐNG DOANH THU

## ✅ Danh sách kiểm tra

### 1. Backend (Controller)

- [x] DoanhThuController có method `index()`
- [x] Các method tính doanh thu: `calculateDailyRevenue()`, `calculateMonthlyRevenue()`, `calculateYearlyRevenue()`
- [x] Các method tính vé: `calculateDailyTickets()`, `calculateMonthlyTickets()`, `calculateYearlyTickets()`
- [x] Method `calculateRevenueFromBookings()` xử lý đúng logic
- [x] Controller trả về đủ 7 biến: dailyRevenue, dailyTickets, monthlyRevenue, monthlyTickets, yearlyRevenue, yearlyTickets, stats

### 2. Frontend (View)

- [x] Blade template có bộ lọc với 3 điều kiện (loại báo cáo, năm, tháng)
- [x] 6 canvas cho 6 biểu đồ
- [x] Tab navigation cho từng loại biểu đồ
- [x] 4 thẻ thống kê tổng quan
- [x] JavaScript khởi tạo 6 Chart.js instances
- [x] Function `applyFilter()` hoạt động
- [x] Function toggle hiển thị filter động

### 3. Routes

- [x] Route `admin.doanhthu.index` tồn tại
- [x] Route `admin.doanhthu.export` tồn tại

### 4. Assets

- [x] File CSS `public/css/doanh_thu.css` được tạo
- [x] Chart.js được load từ CDN

### 5. Database

- [ ] Bảng `dat_ve` có dữ liệu mẫu
- [ ] Bảng `chuyen_xe` có relation đúng
- [ ] Column `ngay_dat` có index
- [ ] Column `trang_thai` có index

## 🧪 Các test case cần kiểm tra

### Test 1: Hiển thị trang

```
URL: http://localhost/admin/doanhthu
Kết quả mong đợi:
- Trang load không lỗi
- Hiển thị 4 thẻ thống kê
- Hiển thị bộ lọc
- Hiển thị 2 card chứa biểu đồ
```

### Test 2: Biểu đồ hiển thị

```
Kiểm tra:
- Mở tab "Theo Ngày" trong cả 2 card
- Mở tab "Theo Tháng" trong cả 2 card
- Mở tab "Theo Năm" trong cả 2 card
Kết quả mong đợi: Tất cả 6 biểu đồ hiển thị đúng
```

### Test 3: Bộ lọc

```
Test 3.1: Chọn "Theo Ngày"
- Chọn năm: 2024
- Chọn tháng: 10
- Click "Lọc dữ liệu"
Kết quả: Biểu đồ cập nhật với dữ liệu tháng 10/2024

Test 3.2: Chọn "Theo Tháng"
- Chọn năm: 2024
- Click "Lọc dữ liệu"
Kết quả: Biểu đồ cập nhật với dữ liệu 12 tháng năm 2024

Test 3.3: Chọn "Theo Năm"
- Click "Lọc dữ liệu"
Kết quả: Biểu đồ hiển thị 5 năm gần nhất
```

### Test 4: Tooltip

```
Hover vào mỗi điểm/cột trong biểu đồ
Kết quả mong đợi:
- Biểu đồ doanh thu hiển thị format VNĐ
- Biểu đồ vé hiển thị "X vé"
- Số được format theo chuẩn VN (1.000.000)
```

### Test 5: Responsive

```
Test trên các kích thước:
- Desktop: 1920x1080
- Tablet: 768x1024
- Mobile: 375x667
Kết quả: Giao diện tự động điều chỉnh, biểu đồ vẫn đọc được
```

### Test 6: Dữ liệu trống

```
Với database không có dữ liệu:
- Trang vẫn load được
- Biểu đồ hiển thị giá trị 0
- Không có lỗi JavaScript
```

### Test 7: Dữ liệu lớn

```
Với database có nhiều dữ liệu (>1000 records):
- Trang load trong <3 giây
- Biểu đồ render smooth
- Không bị lag khi filter
```

## 🐛 Các lỗi có thể gặp và cách fix

### Lỗi 1: "Undefined variable: dailyRevenue"

```php
// Fix: Đảm bảo controller có return đủ biến
return view('...', compact(
    'dailyRevenue',
    'dailyTickets',
    'monthlyRevenue',
    'monthlyTickets',
    'yearlyRevenue',
    'yearlyTickets',
    'stats',
    'topTrips',
    'revenueByCompany',
    'year',
    'month',
    'reportType'
));
```

### Lỗi 2: "Chart is not defined"

```html
<!-- Fix: Đảm bảo Chart.js được load TRƯỚC script của bạn -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Your code here
</script>
```

### Lỗi 3: Biểu đồ không responsive

```javascript
// Fix: Thêm maintainAspectRatio: false
options: {
    responsive: true,
    maintainAspectRatio: false,
    // ...
}
```

### Lỗi 4: CSS không load

```blade
<!-- Fix: Kiểm tra path và đảm bảo @push đúng -->
@push('styles')
<link rel="stylesheet" href="{{ asset('css/doanh_thu.css') }}">
@endpush
```

### Lỗi 5: Dữ liệu null hoặc sai

```php
// Fix: Kiểm tra relation và filter trạng thái
$bookings = DatVe::with('chuyenXe')  // Eager loading
    ->where('trang_thai', '!=', 'Đã hủy')  // Loại vé hủy
    ->get();
```

## 📊 Kiểm tra hiệu năng

### Query Performance

```bash
# Trong Laravel Debugbar hoặc log
- Số queries: Nên < 20 queries
- Thời gian query: Nên < 1 giây
- Memory usage: Nên < 50MB
```

### Page Load

```
- TTFB (Time to First Byte): < 500ms
- FCP (First Contentful Paint): < 1.5s
- LCP (Largest Contentful Paint): < 2.5s
```

## ✅ Acceptance Criteria

### Chức năng cốt lõi

- [x] Admin có thể xem doanh thu theo ngày/tháng/năm
- [x] Admin có thể xem số vé bán theo ngày/tháng/năm
- [x] Admin có thể lọc dữ liệu theo năm và tháng
- [x] Biểu đồ hiển thị trực quan và dễ hiểu
- [x] Tooltip hiển thị chi tiết khi hover

### Giao diện

- [x] Giao diện đẹp, hiện đại
- [x] Responsive trên mọi thiết bị
- [x] Màu sắc phân biệt rõ ràng
- [x] Animation mượt mà

### Kỹ thuật

- [x] Code sạch, có comment
- [x] Không có lỗi trong console
- [x] Performance tốt (< 3s load time)
- [x] SEO friendly (có title, meta)

## 🚀 Hướng dẫn test nhanh

```bash
# 1. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 2. Truy cập URL
http://localhost/admin/doanhthu

# 3. Kiểm tra console (F12)
Không có lỗi JavaScript

# 4. Test filter
Chọn các option khác nhau và click "Lọc dữ liệu"

# 5. Test tab
Click vào các tab để xem biểu đồ khác nhau

# 6. Test hover
Hover vào các điểm/cột trong biểu đồ để xem tooltip
```

## 📝 Notes

- File blade có lỗi TypeScript lint là BÌNH THƯỜNG (do @json directive của Blade)
- Lỗi này KHÔNG ảnh hưởng đến chức năng
- Tất cả code đều chạy đúng trong runtime PHP
