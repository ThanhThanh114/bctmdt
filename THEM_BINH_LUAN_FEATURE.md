# Thêm Chức năng "Thêm Bình luận"

## Tính năng mới

Admin có thể thêm bình luận thay mặt người dùng với đầy đủ thông tin:

- ✅ Chọn người dùng
- ✅ Chọn chuyến xe
- ✅ Đánh giá sao (1-5)
- ✅ Nội dung bình luận
- ✅ Trạng thái (Chờ duyệt/Đã duyệt/Từ chối)

## Các thay đổi đã thực hiện

### 1. Routes

**File:** `routes/web.php` - Line 125

**Thay đổi:**

```php
// ❌ Trước (không cho phép create/store)
Route::resource('binhluan', App\Http\Controllers\Admin\BinhLuanController::class)
    ->except(['create', 'store', 'edit', 'update']);

// ✅ Sau (cho phép create/store)
Route::resource('binhluan', App\Http\Controllers\Admin\BinhLuanController::class)
    ->except(['edit', 'update']);
```

**Routes được thêm:**

- `GET /admin/binhluan/create` → `admin.binhluan.create`
- `POST /admin/binhluan` → `admin.binhluan.store`

---

### 2. Controller Methods

**File:** `app/Http/Controllers/Admin/BinhLuanController.php`

#### Method: `create()` - Hiển thị form

```php
public function create()
{
    // Lấy danh sách người dùng để chọn
    $users = User::orderBy('fullname')->get();

    // Lấy danh sách chuyến xe (chỉ chuyến xe tương lai)
    $chuyenXe = ChuyenXe::with(['tramDi', 'tramDen'])
        ->where('ngay_di', '>=', now())
        ->orderBy('ngay_di')
        ->get();

    return view('AdminLTE.admin.binh_luan.create', compact('users', 'chuyenXe'));
}
```

#### Method: `store()` - Xử lý lưu dữ liệu

```php
public function store(Request $request)
{
    // Validation
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'chuyen_xe_id' => 'required|exists:chuyen_xe,id',
        'noi_dung' => 'required|string|max:1000',
        'so_sao' => 'required|integer|min:1|max:5',
        'trang_thai' => 'required|in:cho_duyet,da_duyet,tu_choi',
    ]);

    // Filter profanity
    $noiDung = ProfanityFilter::filter($validated['noi_dung']);

    // Create comment
    $binhLuan = BinhLuan::create([
        'parent_id' => null,
        'user_id' => $validated['user_id'],
        'chuyen_xe_id' => $validated['chuyen_xe_id'],
        'noi_dung' => $noiDung,
        'so_sao' => $validated['so_sao'],
        'trang_thai' => $validated['trang_thai'],
        'nv_id' => null,
        'ngay_bl' => now(),
        'ngay_duyet' => $validated['trang_thai'] === 'da_duyet' ? now() : null,
    ]);

    return redirect()->route('admin.binhluan.index')
        ->with('success', 'Thêm bình luận thành công!');
}
```

---

### 3. View Create Form

**File:** `resources/views/AdminLTE/admin/binh_luan/create.blade.php`

#### Các trường trong form:

1. **Người dùng** (Select2 dropdown)
    - Hiển thị: Tên + Email
    - Required, với validation

2. **Chuyến xe** (Select2 dropdown)
    - Hiển thị: Trạm đi → Trạm đến (Ngày - Giờ)
    - Chỉ hiển thị chuyến xe tương lai
    - Required, với validation

3. **Đánh giá sao** (Interactive star rating)
    - Click vào sao để chọn (1-5)
    - Hiệu ứng hover
    - Hiển thị text: "Rất tệ" → "Xuất sắc"
    - Required

4. **Nội dung** (Textarea)
    - Max 1000 ký tự
    - Real-time character counter
    - Đổi màu khi gần hết ký tự
    - Required

5. **Trạng thái** (Dropdown)
    - Chờ duyệt
    - Đã duyệt
    - Từ chối
    - Required

#### Tính năng UI/UX:

✅ **Star Rating Interactive:**

- Hover để preview
- Click để chọn
- Badge hiển thị text rating
- Animation smooth

✅ **Character Counter:**

- Real-time counting
- Đổi màu: xám → vàng → đỏ
- Hiển thị X/1000

✅ **Select2 Integration:**

- Search người dùng
- Search chuyến xe
- Bootstrap 4 theme

✅ **Validation:**

- Frontend: HTML5 required
- Backend: Laravel validation
- Hiển thị lỗi rõ ràng

✅ **Responsive:**

- Layout 2 cột trên desktop
- Full width trên mobile
- Button block style

---

### 4. View Index - Thêm nút

**File:** `resources/views/AdminLTE/admin/binh_luan/index.blade.php`

**Thêm nút ở Card Header:**

```php
<div class="card-header">
    <h3 class="card-title">
        <i class="fas fa-comments"></i> Danh sách bình luận
    </h3>
    <div class="card-tools">
        <a href="{{ route('admin.binhluan.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Thêm bình luận
        </a>
    </div>
</div>
```

**Thêm hiển thị thông báo:**

- Success message sau khi thêm
- Error message nếu có lỗi

---

## Screenshots của tính năng

### Trang danh sách với nút "Thêm bình luận"

```
┌─────────────────────────────────────────────────┐
│ 📊 Danh sách bình luận    [+ Thêm bình luận]   │
├─────────────────────────────────────────────────┤
│ 🔍 [Tìm kiếm...]                     [Tìm kiếm] │
├─────────────────────────────────────────────────┤
│ # │ Người dùng │ Chuyến xe │ Nội dung │ ...     │
└─────────────────────────────────────────────────┘
```

### Form thêm bình luận

```
┌────────────────────────────────────────────┐
│ 📝 Form thêm bình luận                     │
├────────────────────────────────────────────┤
│ 👤 Người dùng: [Select dropdown...]       │
│ 🚌 Chuyến xe:  [Select dropdown...]       │
│ ⭐ Đánh giá:   ★★★★★ [Xuất sắc (5/5)]     │
│ 💬 Nội dung:   [Textarea...] 0/1000       │
│ 🔘 Trạng thái: [Select dropdown...]       │
│                                            │
│ [✓ Lưu bình luận]  [✗ Hủy bỏ]            │
└────────────────────────────────────────────┘
```

---

## Testing Checklist

### ✅ Route Testing

- [ ] Truy cập `/admin/binhluan/create` hiển thị form
- [ ] Submit form redirect về index
- [ ] Validation errors hiển thị đúng

### ✅ Form Testing

- [ ] Dropdown người dùng load đúng
- [ ] Dropdown chuyến xe load đúng (chỉ tương lai)
- [ ] Star rating hoạt động (click, hover)
- [ ] Character counter đếm chính xác
- [ ] Select2 search hoạt động

### ✅ Validation Testing

- [ ] Submit không chọn user → Lỗi
- [ ] Submit không chọn chuyến xe → Lỗi
- [ ] Submit không chọn sao → Lỗi
- [ ] Submit nội dung trống → Lỗi
- [ ] Submit nội dung > 1000 ký tự → Lỗi
- [ ] Submit không chọn trạng thái → Lỗi

### ✅ Data Testing

- [ ] Bình luận được lưu vào database
- [ ] `nv_id` = NULL (không gây lỗi FK)
- [ ] `ngay_duyet` set đúng nếu "Đã duyệt"
- [ ] Profanity filter hoạt động
- [ ] Redirect về index với success message

---

## Files đã thêm/sửa

### Đã thêm:

1. ✅ `resources/views/AdminLTE/admin/binh_luan/create.blade.php` (NEW)

### Đã sửa:

1. ✅ `routes/web.php` (Line 125)
2. ✅ `app/Http/Controllers/Admin/BinhLuanController.php` (Methods: create, store)
3. ✅ `resources/views/AdminLTE/admin/binh_luan/index.blade.php` (Thêm nút + alerts)

---

## Lưu ý quan trọng

⚠️ **Foreign Key `nv_id`:**

- Luôn set `nv_id = null` khi admin thêm bình luận
- Không dùng `auth()->id()` vì sẽ gây lỗi FK constraint

⚠️ **Profanity Filter:**

- Nội dung được filter trước khi lưu
- Sử dụng `ProfanityFilter::filter()`

⚠️ **Select2 Dependency:**

- Cần có AdminLTE theme đã cài Select2
- Nếu không có, xóa class `select2` và dùng select thường

⚠️ **Ngày duyệt:**

- Chỉ set `ngay_duyet` nếu trạng thái = "Đã duyệt"
- Nếu "Chờ duyệt" hoặc "Từ chối" → NULL

---

**Ngày hoàn thành:** 16/10/2025  
**Người thực hiện:** GitHub Copilot
