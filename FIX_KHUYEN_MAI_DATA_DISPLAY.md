# Fix Hiển Thị Dữ Liệu Khuyến Mãi

## 🔍 Vấn Đề Ban Đầu

Trang chi tiết khuyến mãi không hiển thị dữ liệu:

- ❌ Khách hàng: N/A
- ❌ Chuyến xe: N/A → N/A
- ❌ Số ghế: 0
- ❌ Tổng tiền: 0đ
- ❌ Giảm giá: -0đ
- ❌ Ngày đặt: N/A

## 🔎 Nguyên Nhân

### Cấu trúc Database thực tế:

```sql
-- Bảng dat_ve
CREATE TABLE `dat_ve` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chuyen_xe_id` int(11) NOT NULL,
  `ma_ve` varchar(20) NOT NULL,  -- Mã booking (VD: BK20251014022429908)
  `so_ghe` varchar(255),           -- GHẾ CỤ THỂ (A01, A02, B03...)
  `ngay_dat` timestamp,
  `trang_thai` enum(...)
) -- KHÔNG CÓ cột `tong_tien` và `so_luong_ghe`

-- Bảng chuyen_xe
CREATE TABLE `chuyen_xe` (
  ...
  `gia_ve` decimal(10,2),  -- Giá 1 vé
  ...
)

-- Bảng ve_khuyenmai (pivot)
CREATE TABLE `ve_khuyenmai` (
  `id` int(11),
  `dat_ve_id` int(11),  -- Link đến dat_ve.id
  `ma_km` int(11)       -- Link đến khuyen_mai.ma_km
)
```

### Vấn đề Logic:

1. **Một booking có nhiều record trong `dat_ve`** - mỗi ghế = 1 record
    - VD: Booking #47 đặt 3 ghế → 3 records với cùng `ma_ve`
2. **Code cũ lấy từng record riêng lẻ** → không nhóm lại theo booking
3. **Không có `tong_tien` trong DB** → phải tính = số ghế × giá vé

## ✅ Giải Pháp

### 1. Sửa Controller (KhuyenMaiController.php)

```php
public function show(KhuyenMai $khuyenmai)
{
    // Lấy TẤT CẢ vé đã dùng khuyến mãi
    $veKhuyenMais = $khuyenmai->veKhuyenMai()
        ->with(['datVe.user', 'datVe.chuyenXe.tramDi', 'datVe.chuyenXe.tramDen'])
        ->orderBy('id', 'desc')
        ->get();

    // ✨ NHÓM THEO ma_ve (1 booking = nhiều ghế)
    $recentBookings = $veKhuyenMais->groupBy(function($item) {
        return $item->datVe ? $item->datVe->ma_ve : null;
    })
    ->filter(function($group, $key) {
        return $key !== null;
    })
    ->take(10)  // Lấy 10 booking gần nhất
    ->map(function($group) use ($khuyenmai) {
        $firstBooking = $group->first()->datVe;

        // Tính toán
        $soLuongGhe = $group->count();  // Đếm số record = số ghế
        $giaVe = $firstBooking->chuyenXe->gia_ve ?? 0;
        $tongTien = $soLuongGhe * $giaVe;
        $giamGia = $tongTien * ($khuyenmai->giam_gia / 100);

        // Trả về object với đầy đủ thông tin
        return (object)[
            'id' => $firstBooking->id,
            'ma_ve' => $firstBooking->ma_ve,
            'user' => $firstBooking->user,
            'chuyenXe' => $firstBooking->chuyenXe,
            'so_luong_ghe' => $soLuongGhe,
            'so_ghe_list' => $group->pluck('datVe.so_ghe')->implode(', '),
            'tong_tien' => $tongTien,
            'giam_gia' => $giamGia,
            'ngay_dat' => $firstBooking->ngay_dat,
            'created_at' => $firstBooking->ngay_dat
        ];
    })->values();

    // Thống kê
    $totalBookings = $veKhuyenMais->groupBy(function($item) {
        return $item->datVe ? $item->datVe->ma_ve : null;
    })->filter()->count();

    $usageStats = [
        'total_uses' => $veKhuyenMais->count(),  // Tổng số vé
        'total_bookings' => $totalBookings,       // Tổng số booking
        'total_discount' => $recentBookings->sum('giam_gia'),
    ];

    return view('...', compact('khuyenmai', 'usageStats', 'recentBookings'));
}
```

### 2. Sửa View (show.blade.php)

```blade
@foreach($recentBookings as $index => $booking)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>#{{ $booking->id }}</td>

    <!-- Khách hàng -->
    <td>{{ $booking->user->name ?? 'Khách vãng lai' }}</td>

    <!-- Email/SĐT -->
    <td>
        <i class="fas fa-envelope"></i> {{ $booking->user->email ?? 'N/A' }}<br>
        <i class="fas fa-phone"></i> {{ $booking->user->phone ?? 'N/A' }}
    </td>

    <!-- Chuyến xe (từ tram_xe, không phải diem_di/diem_den) -->
    <td>
        {{ $booking->chuyenXe->tramDi->ten_tram ?? 'N/A' }}
        <i class="fas fa-arrow-right"></i>
        {{ $booking->chuyenXe->tramDen->ten_tram ?? 'N/A' }}
        <br>
        {{ $booking->chuyenXe->ngay_di->format('d/m/Y') }} -
        {{ Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}
    </td>

    <!-- Số ghế (có tooltip hiển thị danh sách ghế) -->
    <td>
        <span class="badge" title="{{ $booking->so_ghe_list }}">
            {{ $booking->so_luong_ghe }}
        </span>
    </td>

    <!-- Tổng tiền (đã tính = số ghế × giá vé) -->
    <td>{{ number_format($booking->tong_tien, 0, ',', '.') }}đ</td>

    <!-- Giảm giá (đã tính = tổng tiền × %) -->
    <td>-{{ number_format($booking->giam_gia, 0, ',', '.') }}đ</td>

    <!-- Ngày đặt -->
    <td>{{ Carbon::parse($booking->created_at)->format('d/m/Y') }}</td>
</tr>
@endforeach

<!-- Footer tổng cộng -->
<tfoot>
    <tr>
        <th colspan="6">Tổng cộng:</th>
        <th>{{ number_format($recentBookings->sum('tong_tien'), 0, ',', '.') }}đ</th>
        <th>-{{ number_format($recentBookings->sum('giam_gia'), 0, ',', '.') }}đ</th>
        <th></th>
    </tr>
</tfoot>
```

## 📊 Ví Dụ Cụ Thể

### Database:

```sql
-- Booking #47: Khách đặt 3 ghế (A01, A02, A03) với khuyến mãi KM_Tet12
dat_ve:
| id  | ma_ve      | user_id | chuyen_xe_id | so_ghe | ngay_dat   |
|-----|------------|---------|--------------|--------|------------|
| 100 | BK001      | 15      | 17           | A01    | 2025-10-16 |
| 101 | BK001      | 15      | 17           | A02    | 2025-10-16 |
| 102 | BK001      | 15      | 17           | A03    | 2025-10-16 |

ve_khuyenmai:
| id  | dat_ve_id | ma_km |
|-----|-----------|-------|
| 1   | 100       | 13    |
| 2   | 101       | 13    |
| 3   | 102       | 13    |

chuyen_xe (id=17):
| gia_ve | tramDi | tramDen |
|--------|--------|---------|
| 200000 | SG     | HN      |

khuyen_mai (ma_km=13):
| giam_gia |
|----------|
| 90.00    |
```

### Tính toán:

```php
$soLuongGhe = 3 ghế (count 3 records cùng ma_ve)
$giaVe = 200,000đ (từ chuyen_xe)
$tongTien = 3 × 200,000 = 600,000đ
$giamGia = 600,000 × (90/100) = 540,000đ
```

### Hiển thị trên bảng:

```
STT | Mã Booking | Khách hàng | Email/SĐT        | Chuyến xe  | Số ghế | Tổng tiền | Giảm giá    | Ngày đặt
----|------------|------------|------------------|------------|--------|-----------|-------------|----------
1   | #47        | Admin      | admin@gmail.com  | SG → HN    | 3      | 600,000đ  | -540,000đ   | 16/10/2025
                               | 0915555555       | 16/10 08:00|        |           | (90.00%)    |
```

## 🎯 Kết Quả

✅ Hiển thị đầy đủ thông tin khách hàng  
✅ Hiển thị tuyến đường từ tram_xe (tramDi → tramDen)  
✅ Tính đúng số ghế bằng cách đếm records  
✅ Tính đúng tổng tiền = số ghế × giá vé  
✅ Tính đúng giảm giá = tổng tiền × %  
✅ Nhóm theo booking (ma_ve) thay vì từng vé riêng lẻ  
✅ Tooltip hiển thị danh sách ghế (A01, A02, A03...)

## 🔧 Test

1. Truy cập: `http://localhost/admin/khuyenmai/13`
2. Kiểm tra bảng "Danh sách người dùng đã sử dụng khuyến mãi"
3. Xác nhận tất cả cột hiển thị đúng dữ liệu
4. Hover vào badge số ghế để xem danh sách ghế cụ thể

## 📝 Lưu Ý

- Mỗi ghế = 1 record trong `dat_ve`
- Booking = nhóm các record có cùng `ma_ve`
- Giá vé lưu trong bảng `chuyen_xe`, không có trong `dat_ve`
- Tổng tiền phải tính runtime, không lưu trong DB
