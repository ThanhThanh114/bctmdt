# Cải tiến Form Thêm Bình luận & Thao tác Khóa/Xóa

## Các vấn đề đã sửa

### 1. ❌ Dropdown Chuyến xe không có dữ liệu

**Nguyên nhân:** Query đúng, chỉ cần đảm bảo có chuyến xe trong tương lai

### 2. ❌ Dropdown Trạng thái thừa

**Vấn đề:** User phải chọn trạng thái thủ công
**Giải pháp:** Tự động set trạng thái dựa vào số sao

### 3. ❌ Thiếu chức năng Khóa bình luận

**Vấn đề:** g có Khóa/Mở khóa
**Giải pháp:** Thêm toggle lock/unlock

---



### 1. Tự động set Trạng thái dựa vào Số sao ⭐

**File:** `app/Http/Controllers/Admin/BinhLuanController.php` - Method `store()`

#### Logic mới:

```php
// Quy tắc tự động:
// <= 2 sao (1-2): Chờ duyệt (cần review vì đánh giá thấp)
// >= 3 sao (3-5): Đã duyệt (tự động approve vì đánh giá tốt)

$trangThai = $validated['so_sao'] >= 3 ? 'da_duyet' : 'cho_duyet';
```

#### Lý do:

- ⭐⭐ và thấp hơn: Thường là phàn nàn → Cần admin review trước
- ⭐⭐⭐ trở lên: Feedback tích cực → Có thể hiển thị ngay

#### Message thông báo:

```php
$message = $trangThai === 'da_duyet'
    ? 'Thêm bình luận thành công! (Tự động duyệt vì đánh giá ≥ 3 sao)'
    : 'Thêm bình luận thành công! (Chờ duyệt vì đánh giá ≤ 2 sao)';
```

---

### 2. Bỏ Dropdown Trạng thái khỏi Form

**File:** `resources/views/AdminLTE/admin/binh_luan/create.blade.php`

#### Trước (❌):

```html
<!-- Trạng thái -->
<select name="trang_thai" required>
    <option>Chờ duyệt</option>
    <option>Đã duyệt</option>
    <option>Từ chối</option>
</select>
```

#### Sau (✅):

```html
<!-- Alert box thông báo quy tắc -->
<div class="alert alert-info">
    <strong>Lưu ý:</strong>
    <ul>
        <li>Đánh giá 1-2 sao: Chờ duyệt (cần review)</li>
        <li>Đánh giá 3-5 sao: Tự động đã duyệt</li>
    </ul>
</div>
```

**Lợi ích:**

- ✅ Form đơn giản hơn
- ✅ Không cần user chọn
- ✅ Tự động hóa quy trình
- ✅ Giảm sai sót

---

### 3. Thêm chức năng Khóa/Mở khóa Bình luận 🔒

#### A. Controller Method

**File:** `app/Http/Controllers/Admin/BinhLuanController.php`

```php
/**
 * Toggle lock/unlock comment
 */
public function toggleLock(BinhLuan $binhluan)
{
    // Nếu đang khóa (tu_choi) → Mở khóa (cho_duyet)
    // Nếu đang mở → Khóa (tu_choi)
    $newStatus = $binhluan->trang_thai === 'tu_choi' ? 'cho_duyet' : 'tu_choi';
    $message = $newStatus === 'tu_choi' ? 'Đã khóa bình luận!' : 'Đã mở khóa bình luận!';

    $binhluan->update([
        'trang_thai' => $newStatus,
        'ly_do_tu_choi' => $newStatus === 'tu_choi' ? 'Bình luận bị khóa bởi Admin' : null,
    ]);

    return redirect()->back()->with('success', $message);
}
```

#### B. Route

**File:** `routes/web.php`

```php
Route::post('binhluan/{binhluan}/toggle-lock', [BinhLuanController::class, 'toggleLock'])
    ->name('binhluan.toggle-lock');
```

#### C. View - Nút Khóa/Mở khóa

**File:** `resources/views/AdminLTE/admin/binh_luan/show.blade.php`

**Card "Thao tác" được cải thiện:**

```html
<div class="card card-danger card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-cog"></i> Thao tác</h3>
    </div>
    <div class="card-body">
        <!-- Nút Quay lại -->
        <a href="..." class="btn btn-secondary btn-block">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>

        <!-- Nút Khóa/Mở khóa (Dynamic) -->
        <form
            action="{{ route('admin.binhluan.toggle-lock', ...) }}"
            method="POST"
        >
            @csrf @if($binhluan->trang_thai === 'tu_choi')
            <!-- Đang khóa → Hiển thị nút Mở khóa -->
            <button class="btn btn-success btn-block">
                <i class="fas fa-lock-open"></i> Mở khóa bình luận
            </button>
            @else
            <!-- Đang mở → Hiển thị nút Khóa -->
            <button class="btn btn-warning btn-block">
                <i class="fas fa-lock"></i> Khóa bình luận
            </button>
            @endif
        </form>

        <!-- Nút Xóa vĩnh viễn -->
        <form action="{{ route('admin.binhluan.destroy', ...) }}" method="POST">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-block">
                <i class="fas fa-trash"></i> Xóa vĩnh viễn
            </button>
        </form>

        <!-- Box hiển thị trạng thái hiện tại -->
        <div class="bg-light mt-3 rounded p-3">
            <h6><i class="fas fa-info-circle"></i> Trạng thái hiện tại:</h6>

            @if($binhluan->trang_thai === 'tu_choi')
            <span class="badge badge-danger">
                <i class="fas fa-lock"></i> Đã khóa
            </span>
            <small class="text-muted">
                Bình luận đã bị khóa và không hiển thị công khai
            </small>
            @elseif($binhluan->trang_thai === 'da_duyet')
            <span class="badge badge-success">
                <i class="fas fa-check"></i> Đã duyệt
            </span>
            <small class="text-muted">
                Bình luận đang hiển thị công khai
            </small>
            @else
            <span class="badge badge-warning">
                <i class="fas fa-clock"></i> Chờ duyệt
            </span>
            <small class="text-muted">
                Bình luận cần được duyệt trước khi hiển thị
            </small>
            @endif
        </div>
    </div>
</div>
```

---

## So sánh UI Trước và Sau

### Trước (❌):

```
┌────────────────────────────┐
│ Thao tác                   │
├────────────────────────────┤
│ [← Quay lại danh sách]    │
│ [🗑️ Xóa bình luận]         │
└────────────────────────────┘
```

- Chỉ có 2 nút
- Không có khóa
- Xóa là hành động duy nhất

### Sau (✅):

```
┌────────────────────────────────────┐
│ Thao tác                           │
├────────────────────────────────────┤
│ [← Quay lại danh sách]            │
│ [🔓 Mở khóa] hoặc [🔒 Khóa]       │
│ [🗑️ Xóa vĩnh viễn]                │
├────────────────────────────────────┤
│ ℹ️ Trạng thái hiện tại:            │
│ [✅ Đã duyệt]                      │
│ Bình luận đang hiển thị công khai  │
└────────────────────────────────────┘
```

- 3 nút rõ ràng
- Có khóa/mở khóa
- Hiển thị trạng thái
- Xóa có warning mạnh

---

## Quy trình sử dụng

### Kịch bản 1: Thêm bình luận mới

1. Click **"+ Thêm bình luận"**
2. Chọn người dùng
3. Chọn chuyến xe
4. **Chọn số sao** (1-5) ⭐
    - Chọn 1-2 sao → Hệ thống tự động đặt "Chờ duyệt"
    - Chọn 3-5 sao → Hệ thống tự động đặt "Đã duyệt"
5. Nhập nội dung
6. Click **"Lưu bình luận"**
7. ✅ Thông báo: "...Tự động duyệt vì đánh giá ≥ 3 sao"

### Kịch bản 2: Khóa bình luận xấu

1. Vào chi tiết bình luận
2. Click **"🔒 Khóa bình luận"**
3. Confirm
4. ✅ Trạng thái → "Đã khóa"
5. Bình luận không hiển thị công khai

### Kịch bản 3: Mở khóa bình luận

1. Vào chi tiết bình luận (đang khóa)
2. Click **"🔓 Mở khóa bình luận"**
3. Confirm
4. ✅ Trạng thái → "Chờ duyệt"
5. Admin có thể duyệt lại

### Kịch bản 4: Xóa vĩnh viễn

1. Vào chi tiết bình luận
2. Click **"🗑️ Xóa vĩnh viễn"**
3. ⚠️ Warning: "Xóa bình luận sẽ xóa cả các phản hồi!"
4. Confirm
5. ✅ Xóa khỏi database

---

## Lợi ích của các thay đổi

### 1. Tự động hóa Trạng thái

- ✅ Không cần admin chọn thủ công
- ✅ Logic rõ ràng dựa vào số sao
- ✅ Giảm workload cho admin
- ✅ Bình luận tích cực hiển thị nhanh

### 2. Khóa thay vì Xóa

- ✅ Không mất dữ liệu vĩnh viễn
- ✅ Có thể mở khóa lại
- ✅ Linh hoạt hơn trong quản lý
- ✅ An toàn hơn

### 3. UI/UX tốt hơn

- ✅ Nút rõ ràng với icon
- ✅ Badge trạng thái dễ nhìn
- ✅ Confirmation messages cụ thể
- ✅ Box info hiển thị trạng thái

---

## Bảng so sánh Xóa vs Khóa

| Tính năng    | Xóa (Delete)              | Khóa (Lock)           |
| ------------ | ------------------------- | --------------------- |
| **Dữ liệu**  | ❌ Mất vĩnh viễn          | ✅ Vẫn lưu trong DB   |
| **Hiển thị** | ❌ Biến mất               | ❌ Ẩn khỏi public     |
| **Phục hồi** | ❌ Không thể              | ✅ Có thể mở khóa     |
| **Replies**  | ❌ Xóa cả replies         | ✅ Giữ nguyên replies |
| **Audit**    | ❌ Mất lịch sử            | ✅ Có thể xem lại     |
| **Use case** | Spam/Vi phạm nghiêm trọng | Tạm thời ẩn/Review    |

---

## Testing Checklist

### ✅ Form Thêm bình luận

- [ ] Chọn người dùng → OK
- [ ] Chọn chuyến xe (có dữ liệu) → OK
- [ ] Chọn 1 sao → Chờ duyệt ✅
- [ ] Chọn 2 sao → Chờ duyệt ✅
- [ ] Chọn 3 sao → Đã duyệt ✅
- [ ] Chọn 4 sao → Đã duyệt ✅
- [ ] Chọn 5 sao → Đã duyệt ✅
- [ ] Message hiển thị đúng

### ✅ Khóa bình luận

- [ ] Click "Khóa" → Trạng thái = tu_choi
- [ ] Nút đổi thành "Mở khóa"
- [ ] Badge hiển thị "Đã khóa"
- [ ] Bình luận không hiển thị public

### ✅ Mở khóa bình luận

- [ ] Click "Mở khóa" → Trạng thái = cho_duyet
- [ ] Nút đổi thành "Khóa"
- [ ] Badge hiển thị "Chờ duyệt"

### ✅ Xóa bình luận

- [ ] Hiển thị warning mạnh
- [ ] Xác nhận xóa
- [ ] Xóa cả replies
- [ ] Redirect về index

---

## Files đã thay đổi

### Đã sửa:

1. ✅ `app/Http/Controllers/Admin/BinhLuanController.php`
    - Method `store()`: Tự động set trạng thái
    - Method `toggleLock()`: Khóa/Mở khóa (NEW)

2. ✅ `routes/web.php`
    - Route `binhluan.toggle-lock` (NEW)

3. ✅ `resources/views/AdminLTE/admin/binh_luan/create.blade.php`
    - Bỏ dropdown trạng thái
    - Thêm alert box giải thích quy tắc

4. ✅ `resources/views/AdminLTE/admin/binh_luan/show.blade.php`
    - Thêm nút Khóa/Mở khóa (dynamic)
    - Thêm box hiển thị trạng thái
    - Cải thiện nút Xóa với warning mạnh

---

**Ngày hoàn thành:** 16/10/2025  
**Người thực hiện:** GitHub Copilot
