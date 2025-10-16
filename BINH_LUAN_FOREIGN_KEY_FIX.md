# Sửa lỗi Foreign Key và Hiển thị Thông tin Bình luận

## Vấn đề

### 1. Lỗi Foreign Key Constraint

```
SQLSTATE[23000]: Integrity constraint violation: 1452
Cannot add or update a child row: a foreign key constraint fails
(`tmdt_bc`.`binh_luan`, CONSTRAINT `fk_bl_nv` FOREIGN KEY (`nv_id`)
REFERENCES `nhan_vien` (`ma_nv`) ON DELETE CASCADE)
```

**Nguyên nhân:**

- Code set `nv_id = auth()->id()` (ID từ bảng `users`)
- Nhưng cột `nv_id` có foreign key constraint đến bảng `nhan_vien.ma_nv`
- Admin login qua bảng `users`, không có trong bảng `nhan_vien`

### 2. Lỗi Hiển thị Giao diện

- Tên người dùng hiển thị "N/A"
- Thông tin chuyến xe không hiển thị
- Đánh giá sao không hiển thị
- Số phản hồi không hiển thị

**Nguyên nhân:**

- View sử dụng tên cột sai: `ho_ten` → `fullname`, `danh_gia` → `so_sao`
- Relationship không được load đầy đủ
- Tên relationship sai: `tramXeDi` → `tramDi`, `tramXeDen` → `tramDen`

## Giải pháp đã thực hiện

### 1. Sửa Foreign Key Constraint

#### File: `app/Http/Controllers/Admin/BinhLuanController.php`

**Method:** `reply()` - Line 95

```php
// ❌ SAI - Gây lỗi foreign key
'nv_id' => auth()->id(), // ID từ bảng users

// ✅ ĐÚNG - Set NULL cho admin reply
'nv_id' => null, // Set to null since admin users are not in nhan_vien table
```

**Lý do:**

- Admin đăng nhập qua bảng `users`, không có trong `nhan_vien`
- `nv_id` dùng cho nhân viên, admin không cần field này
- Cột đã cho phép NULL từ migration trước

### 2. Sửa Controller - Load đầy đủ Relationship

#### File: `app/Http/Controllers/Admin/BinhLuanController.php`

**Method:** `index()` - Line 20-24

```php
// ❌ SAI - Thiếu relationship
$query = BinhLuan::with(['user', 'chuyenXe', 'parent']);

// ✅ ĐÚNG - Đầy đủ relationship
$query = BinhLuan::with([
    'user',                      // Thông tin người dùng
    'chuyenXe.tramDi',          // Trạm đi của chuyến xe
    'chuyenXe.tramDen',         // Trạm đến của chuyến xe
    'parent',                    // Bình luận cha (nếu là reply)
    'replies'                    // Các phản hồi
]);
```

### 3. Sửa View - Hiển thị đúng thông tin

#### File: `resources/views/AdminLTE/admin/binh_luan/index.blade.php`

**Thay đổi 1: Cột ID**

```php
// ❌ SAI
<td>{{ $item->id }}</td>

// ✅ ĐÚNG
<td>{{ $item->ma_bl }}</td>
```

**Thay đổi 2: Tên người dùng**

```php
// ❌ SAI
<strong>{{ $item->user->ho_ten ?? 'N/A' }}</strong>

// ✅ ĐÚNG
<strong>{{ $item->user->fullname ?? 'N/A' }}</strong>
```

**Thay đổi 3: Thông tin chuyến xe**

```php
// ❌ SAI
{{ $item->chuyenXe->tramXeDi->ten_tram ?? '' }}
{{ $item->chuyenXe->tramXeDen->ten_tram ?? '' }}
{{ $item->chuyenXe->ngay_di }}

// ✅ ĐÚNG
<i class="fas fa-map-marker-alt text-success"></i>
{{ $item->chuyenXe->tramDi->ten_tram ?? 'N/A' }}
<i class="fas fa-arrow-right text-primary"></i>
<i class="fas fa-map-marker-alt text-danger"></i>
{{ $item->chuyenXe->tramDen->ten_tram ?? 'N/A' }}<br>
<small class="text-muted">
    <i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($item->chuyenXe->ngay_di)->format('d/m/Y') }}
    <i class="far fa-clock ml-2"></i> {{ $item->chuyenXe->gio_di ?? '' }}
</small>
```

**Thay đổi 4: Đánh giá sao**

```php
// ❌ SAI
@for($i = 1; $i <= 5; $i++)
    @if($i <= $item->danh_gia)
        <i class="fas fa-star text-warning"></i>
    @endif
@endfor
<small>({{ $item->danh_gia }}/5)</small>

// ✅ ĐÚNG
@if($item->so_sao)
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $item->so_sao)
            <i class="fas fa-star text-warning"></i>
        @else
            <i class="far fa-star text-muted"></i>
        @endif
    @endfor
    <br><small>({{ $item->so_sao }}/5)</small>
@else
    <small class="text-muted">Chưa đánh giá</small>
@endif
```

**Thay đổi 5: Thêm hiển thị số phản hồi**

```php
@if($item->replies && $item->replies->count() > 0)
    <small class="text-info">
        <i class="fas fa-reply"></i> {{ $item->replies->count() }} phản hồi
    </small>
@endif
```

## Cấu trúc Database liên quan

### Foreign Key Constraints của bảng `binh_luan`

```
+-----------------+--------------+-----------------------+------------------------+
| CONSTRAINT_NAME | COLUMN_NAME  | REFERENCED_TABLE_NAME | REFERENCED_COLUMN_NAME |
+-----------------+--------------+-----------------------+------------------------+
| fk_bl_chuyen    | chuyen_xe_id | chuyen_xe             | id                     |
| fk_bl_nv        | nv_id        | nhan_vien             | ma_nv                  | ⚠️
| fk_bl_parent    | parent_id    | binh_luan             | ma_bl                  |
| fk_bl_user      | user_id      | users                 | id                     |
+-----------------+--------------+-----------------------+------------------------+
```

⚠️ **Lưu ý:** `nv_id` tham chiếu đến `nhan_vien.ma_nv`, KHÔNG phải `users.id`

## Kết quả

✅ **Đã sửa:**

1. Không còn lỗi foreign key khi admin trả lời bình luận
2. Tên người dùng hiển thị đúng
3. Thông tin chuyến xe đầy đủ (tên trạm, ngày, giờ) với icon đẹp
4. Đánh giá sao hiển thị chính xác (hoặc "Chưa đánh giá" nếu null)
5. Số lượng phản hồi hiển thị
6. ID bình luận hiển thị đúng

✅ **Cải thiện thêm:**

- Thêm icon cho trạm đi/đến
- Format ngày tháng đẹp hơn
- Hiển thị giờ khởi hành
- Xử lý trường hợp null/empty

## Testing

1. ✅ Truy cập `/admin/binhluan` - Xem danh sách
2. ✅ Kiểm tra tên người dùng hiển thị
3. ✅ Kiểm tra thông tin chuyến xe
4. ✅ Kiểm tra đánh giá sao
5. ✅ Click vào bình luận để xem chi tiết
6. ✅ Gửi trả lời - Không còn lỗi foreign key

## Files đã thay đổi

1. ✅ `app/Http/Controllers/Admin/BinhLuanController.php`
    - Method `index()`: Thêm relationship
    - Method `reply()`: Set `nv_id = null`

2. ✅ `resources/views/AdminLTE/admin/binh_luan/index.blade.php`
    - Sửa tên cột: `ho_ten` → `fullname`, `danh_gia` → `so_sao`
    - Sửa relationship: `tramXeDi` → `tramDi`, `tramXeDen` → `tramDen`
    - Thêm icon và format đẹp hơn
    - Xử lý null/empty cases

---

**Ngày sửa:** 16/10/2025  
**Người thực hiện:** GitHub Copilot
