# 🔍 PHÂN TÍCH VÀ CẢI THIỆN TRANG HÓA ĐƠN

## 📋 TÌNH TRẠNG HIỆN TẠI

### ✅ Điểm Tốt:

1. ✓ Giao diện đẹp, responsive
2. ✓ Có chức năng in và tải PDF
3. ✓ Hiển thị đầy đủ thông tin cơ bản
4. ✓ Có QR code để kiểm tra

### ⚠️ VẤN ĐỀ CẦN SỬA:

#### 1. **VẤN ĐỀ NGHIÊM TRỌNG: Chỉ hiển thị 1 vé**

```php
// File: InvoiceController.php
$invoice_data = DB::table('dat_ve as dv')
    ->...
    ->first();  // ❌ CHỈ LẤY 1 VÉ
```

**Tình huống:**

- Khách đặt 3 ghế (A01, A02, A03) cùng mã vé `VE001`
- Database có 3 records với `ma_ve = VE001`
- Nhưng hóa đơn chỉ hiển thị ghế đầu tiên

**Hậu quả:**

- ❌ Thiếu thông tin vé
- ❌ Tính tiền sai
- ❌ Khách hàng khiếu nại

#### 2. **Tính VAT không chính xác**

```php
<div class="tot-row">
    <span>Thuế (VAT 10%)</span>
    <span>{{ number_format($total_amount * 0.1, 0, ',', '.') }}₫</span>
</div>
```

**Vấn đề:**

- Vé xe khách **KHÔNG CHỊU VAT** theo quy định
- Hoặc nếu có VAT thì phải đã bao gồm trong giá vé

#### 3. **Giảm giá chỉ lấy từ 1 vé**

```php
$discount_percentage = (float) ($invoice_data['discount_percentage'] ?? 0);
```

**Vấn đề:**

- Nếu có nhiều vé, mỗi vé có thể có mã giảm giá khác nhau
- Hiện tại chỉ lấy giảm giá của vé đầu tiên

---

## 🔧 GIẢI PHÁP ĐỀ XUẤT

### Phương án 1: Hiển thị tất cả vé (KHUYẾN NGHỊ)

#### Sửa InvoiceController.php:

```php
public function check(Request $request)
{
    $request->validate(['ma_bimat' => 'required|string']);
    $ma_bimat = $request->input('ma_bimat');

    // Lấy TẤT CẢ vé cùng mã đặt
    $invoices = DB::table('dat_ve as dv')
        ->select(
            'dv.id',
            'dv.ma_ve AS invoice_number',
            'dv.ngay_dat AS invoice_date',
            'dv.trang_thai AS invoice_status',
            'dv.so_ghe AS seat_number',
            'u.fullname AS cust_name',
            'u.phone AS cust_phone',
            'u.email AS cust_email',
            'cx.ten_xe AS bus_name',
            'cx.loai_xe AS bus_type',
            'cx.ngay_di AS trip_date',
            'cx.gio_di AS trip_time',
            'cx.gia_ve AS ticket_price',
            'tdi.ten_tram AS departure_station',
            'tdi.tinh_thanh AS departure_province',
            'tden.ten_tram AS arrival_station',
            'tden.tinh_thanh AS arrival_province',
            'nx.ten_nha_xe AS bus_company_name',
            'nx.dia_chi AS bus_company_address',
            'nx.so_dien_thoai AS bus_company_phone'
        )
        ->join('users as u', 'dv.user_id', '=', 'u.id')
        ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
        ->join('tram_xe as tdi', 'cx.ma_tram_di', '=', 'tdi.ma_tram_xe')
        ->join('tram_xe as tden', 'cx.ma_tram_den', '=', 'tden.ma_tram_xe')
        ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
        ->where('dv.ma_ve', $ma_bimat)
        ->get();  // ✅ GET() thay vì first()

    if ($invoices->isEmpty()) {
        Session::put('error_message', 'Không tìm thấy hóa đơn với mã này.');
        return redirect()->route('invoice.index');
    }

    // Lấy thông tin giảm giá
    $discounts = DB::table('ve_khuyenmai as vkm')
        ->join('khuyen_mai as km', 'vkm.ma_km', '=', 'km.ma_km')
        ->whereIn('vkm.dat_ve_id', $invoices->pluck('id'))
        ->get()
        ->keyBy('dat_ve_id');

    Session::put('invoice_data', [
        'invoices' => $invoices,
        'discounts' => $discounts,
        'booking_code' => $ma_bimat
    ]);

    return redirect()->route('invoice.show');
}
```

#### Sửa check.blade.php:

```php
@php
    $invoices = $invoice_data['invoices'];
    $firstInvoice = $invoices[0];
    $totalTickets = count($invoices);
    $basePrice = (float) $firstInvoice['ticket_price'];
    $totalBase = $basePrice * $totalTickets;

    // Tính tổng giảm giá
    $totalDiscount = 0;
    foreach ($invoices as $inv) {
        $discountId = $inv['id'];
        if (isset($invoice_data['discounts'][$discountId])) {
            $discount = $invoice_data['discounts'][$discountId];
            $totalDiscount += $basePrice * ($discount['giam_gia'] / 100);
        }
    }

    $finalAmount = $totalBase - $totalDiscount;
@endphp

<tbody>
    @foreach($invoices as $index => $invoice)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>Vé xe khách - Ghế {{ $invoice['seat_number'] }} - {{ $invoice['bus_type'] }}</td>
        <td>1</td>
        <td class="text-right">{{ number_format($basePrice, 0, ',', '.') }}₫</td>
        <td class="text-right">{{ number_format($basePrice, 0, ',', '.') }}₫</td>
    </tr>
    @endforeach
</tbody>

<!-- Trong phần totals -->
<div class="tot-row">
    <span>Tạm tính ({{ $totalTickets }} vé)</span>
    <span>{{ number_format($totalBase, 0, ',', '.') }}₫</span>
</div>
@if ($totalDiscount > 0)
<div class="tot-row">
    <span>Giảm giá</span>
    <span>-{{ number_format($totalDiscount, 0, ',', '.') }}₫</span>
</div>
@endif
<!-- BỎ VAT vì vé xe không chịu VAT -->
<div class="tot-row total">
    <span>Tổng cộng</span>
    <span>{{ number_format($finalAmount, 0, ',', '.') }}₫</span>
</div>
```

---

## 🎯 CẢI THIỆN THÊM

### 1. Thêm thông tin chi tiết hơn:

- ✓ Địa chỉ lên xe cụ thể
- ✓ Số điện thoại tài xế
- ✓ Biển số xe
- ✓ Ghi chú đặc biệt (nếu có)

### 2. Thêm điều khoản:

```html
<section class="invoice-terms">
    <h3>Điều khoản và điều kiện</h3>
    <ul>
        <li>Vui lòng có mặt trước giờ khởi hành 15-30 phút</li>
        <li>Mang theo CMND/CCCD để đối chiếu</li>
        <li>Không hoàn/đổi vé trong vòng 2 giờ trước giờ khởi hành</li>
        <li>Hành lý tối đa 20kg/người</li>
    </ul>
</section>
```

### 3. Thêm footer chuyên nghiệp:

```html
<footer class="invoice-footer">
    <p>Cảm ơn quý khách đã sử dụng dịch vụ FUTA Bus Lines</p>
    <p class="muted">Hóa đơn được tạo tự động bởi hệ thống</p>
    <p class="muted">Hotline: 1900 6067 | Email: support@futabus.vn</p>
</footer>
```

---

## 📝 CHECKLIST CẦN LÀM

- [ ] Sửa InvoiceController để lấy TẤT CẢ vé cùng mã
- [ ] Cập nhật view để hiển thị nhiều vé
- [ ] Xóa/Sửa phần VAT (vé xe không chịu VAT)
- [ ] Tính tổng tiền chính xác cho tất cả vé
- [ ] Hiển thị tất cả ghế đã đặt
- [ ] Test với các trường hợp:
    - [ ] 1 vé
    - [ ] Nhiều vé cùng mã
    - [ ] Có mã giảm giá
    - [ ] Không có mã giảm giá
    - [ ] Vé đã hủy

---

## 🧪 CÁCH TEST

### Test case 1: Booking có nhiều ghế

```sql
-- Tìm booking có nhiều ghế
SELECT ma_ve, COUNT(*) as so_ghe, GROUP_CONCAT(so_ghe) as danh_sach_ghe
FROM dat_ve
WHERE trang_thai = 'Đã thanh toán'
GROUP BY ma_ve
HAVING COUNT(*) > 1
ORDER BY ngay_dat DESC
LIMIT 5;
```

### Test case 2: Kiểm tra giảm giá

```sql
-- Kiểm tra vé có giảm giá
SELECT dv.ma_ve, dv.so_ghe, km.ten_km, km.giam_gia
FROM dat_ve dv
JOIN ve_khuyenmai vkm ON dv.id = vkm.dat_ve_id
JOIN khuyen_mai km ON vkm.ma_km = km.ma_km
WHERE dv.ma_ve = 'BK...'  -- Thay bằng mã thực tế
```

---

## 🔗 FILES LIÊN QUAN

1. **Controller**: `app/Http/Controllers/InvoiceController.php`
2. **View Index**: `resources/views/invoice/index.blade.php`
3. **View Check**: `resources/views/invoice/check.blade.php`
4. **CSS**: `public/assets/css/HoaDon.css`
5. **JS**: `public/assets/js/HoaDon.js`

---

**Tạo bởi**: GitHub Copilot  
**Ngày**: 17/10/2025  
**Phiên bản**: 1.0
