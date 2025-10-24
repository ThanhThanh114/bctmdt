# HỆ THỐNG BÌNH LUẬN & ĐÁNH GIÁ CHUYẾN XE

## 📋 Tổng Quan

Hệ thống bình luận cho phép khách hàng đánh giá và bình luận về chuyến xe sau khi mua vé. Admin và nhân viên có thể xem, duyệt, và trả lời các bình luận.

## 🎯 Tính Năng Chính

### 1. **Dành cho Khách Hàng (User)**

#### Xem và Viết Bình Luận
- ✅ Xem tất cả bình luận đã được duyệt của một chuyến xe
- ✅ Viết bình luận mới (yêu cầu đã mua vé)
- ✅ Đánh giá từ 1-5 sao
- ✅ Xem điểm trung bình và số lượng đánh giá
- ✅ Xem phản hồi từ nhà xe

#### Truy Cập
- **Cách 1:** Từ trang "Vé của tôi" → Click nút "Đánh giá" bên cạnh vé
- **Cách 2:** Từ trang chi tiết vé → Click nút "Đánh giá chuyến xe"
- **Route:** `/user/binh-luan?chuyen_xe_id={id}`

#### Quy Tắc
- ⚠️ **Phải mua vé** cho chuyến xe mới có thể bình luận
- ⚠️ **Chỉ được bình luận 1 lần** cho mỗi chuyến xe
- ⚠️ Bình luận ≥3 sao: **Tự động duyệt**
- ⚠️ Bình luận ≤2 sao: **Chờ duyệt**
- ⚠️ Không thể xóa/sửa bình luận đã có phản hồi từ nhà xe

### 2. **Dành cho Nhân Viên (Staff)**

#### Quản Lý Bình Luận
- ✅ Xem danh sách bình luận của nhà xe mình
- ✅ Lọc theo trạng thái (Chờ duyệt / Đã duyệt / Từ chối)
- ✅ Lọc theo số sao (1-5 sao)
- ✅ Tìm kiếm theo nội dung hoặc tên khách hàng
- ✅ Trả lời bình luận của khách hàng
- ✅ Duyệt/Từ chối bình luận
- ✅ Xóa bình luận

#### Truy Cập
- Menu sidebar: **"Quản lý bình luận"**
- **Route:** `/staff/binh-luan`

#### Thống Kê
- Tổng số bình luận
- Số bình luận chờ duyệt
- Số bình luận đã duyệt
- Số bình luận từ chối

### 3. **Dành cho Admin**

Admin có tất cả quyền của Staff, nhưng có thể xem và quản lý bình luận của **TẤT CẢ** nhà xe.

- Menu sidebar: **"Quản lý bình luận"**
- **Route:** `/admin/binhluan`

## 📁 Cấu Trúc File

### Controllers
```
app/Http/Controllers/
├── User/
│   └── BinhLuanController.php      # Controller cho User
├── Staff/
│   └── BinhLuanController.php      # Controller cho Staff
└── Admin/
    └── BinhLuanController.php      # Controller cho Admin (đã có sẵn)
```

### Views
```
resources/views/
├── user/
│   └── binh_luan/
│       └── index.blade.php         # Trang xem và viết bình luận
├── AdminLTE/
│   ├── staff/
│   │   └── binh_luan/
│   │       ├── index.blade.php     # Danh sách bình luận
│   │       └── show.blade.php      # Chi tiết & trả lời
│   ├── admin/
│   │   └── binh_luan/              # Views cho Admin (đã có sẵn)
│   └── user/
│       └── bookings/
│           └── show.blade.php      # Trang chi tiết vé (đã thêm nút)
```

### Routes
```php
// User routes (web.php)
Route::prefix('user')->name('user.')->middleware('role:user')->group(function () {
    Route::get('/binh-luan', [BinhLuanController::class, 'index'])->name('binh-luan.index');
    Route::post('/binh-luan', [BinhLuanController::class, 'store'])->name('binh-luan.store');
    Route::put('/binh-luan/{binhLuan}', [BinhLuanController::class, 'update'])->name('binh-luan.update');
    Route::delete('/binh-luan/{binhLuan}', [BinhLuanController::class, 'destroy'])->name('binh-luan.destroy');
});

// Staff routes
Route::prefix('staff')->name('staff.')->middleware('role:staff')->group(function () {
    Route::get('binh-luan', [BinhLuanController::class, 'index'])->name('binh-luan.index');
    Route::get('binh-luan/{binhLuan}', [BinhLuanController::class, 'show'])->name('binh-luan.show');
    Route::post('binh-luan/{binhLuan}/reply', [BinhLuanController::class, 'reply'])->name('binh-luan.reply');
    Route::post('binh-luan/{binhLuan}/approve', [BinhLuanController::class, 'approve'])->name('binh-luan.approve');
    Route::post('binh-luan/{binhLuan}/reject', [BinhLuanController::class, 'reject'])->name('binh-luan.reject');
    Route::delete('binh-luan/{binhLuan}', [BinhLuanController::class, 'destroy'])->name('binh-luan.destroy');
});
```

## 🔄 Quy Trình Hoạt Động

### Luồng Bình Luận

```
1. Khách hàng mua vé
   ↓
2. Xem trang chi tiết vé → Click nút "Đánh giá chuyến xe"
   ↓
3. Viết bình luận + chọn số sao (1-5)
   ↓
4. Hệ thống kiểm tra:
   - Có mua vé không? ✓
   - Đã bình luận chưa? ✓
   - Lọc từ ngữ không phù hợp ✓
   ↓
5. Tự động xử lý:
   - ≥3 sao: Tự động duyệt → Hiển thị ngay
   - ≤2 sao: Chờ duyệt → Cần Staff/Admin duyệt
   ↓
6. Staff/Admin xem và trả lời
   ↓
7. Khách hàng thấy phản hồi từ nhà xe
```

### Trạng Thái Bình Luận

| Trạng thái | Mô tả | Hiển thị công khai |
|-----------|-------|-------------------|
| `cho_duyet` | Chờ duyệt (≤2 sao) | ❌ Không |
| `da_duyet` | Đã duyệt | ✅ Có |
| `tu_choi` | Từ chối | ❌ Không |

## 💡 Tính Năng Đặc Biệt

### 1. Tự Động Kiểm Duyệt
- Bình luận **≤2 sao** sẽ được giữ lại để kiểm tra trước khi hiển thị
- Bình luận **≥3 sao** sẽ tự động duyệt và hiển thị ngay

### 2. Lọc Từ Ngữ
- Hệ thống tự động lọc các từ ngữ không phù hợp
- Sử dụng `ProfanityFilter` helper

### 3. Phản Hồi Từ Nhà Xe
- Staff/Admin có thể trả lời bình luận
- Phản hồi hiển thị với badge "NHÀ XE"
- Phản hồi được tự động duyệt

### 4. Điểm Trung Bình
- Tính điểm trung bình từ tất cả bình luận đã duyệt
- Hiển thị số sao và tổng số đánh giá

## 🎨 Giao Diện

### Trang Khách Hàng
- Card thông tin chuyến xe với gradient đẹp mắt
- Form đánh giá sao tương tác (hover effect)
- Hiển thị avatar người dùng
- Badge "NHÀ XE" cho phản hồi
- Responsive design

### Trang Staff/Admin
- Thống kê tổng quan (cards với icons)
- Bộ lọc mạnh mẽ (trạng thái, số sao, tìm kiếm)
- Table hiển thị compact với đầy đủ thông tin
- Form trả lời với editor
- Modal xác nhận từ chối

## 🔒 Bảo Mật & Quyền Hạn

### User
- ✅ Xem bình luận đã duyệt
- ✅ Viết bình luận (nếu đã mua vé)
- ✅ Xóa bình luận của mình (nếu chưa có phản hồi)
- ❌ Không xem bình luận của người khác chưa duyệt

### Staff
- ✅ Xem tất cả bình luận của nhà xe mình
- ✅ Trả lời, duyệt, từ chối, xóa bình luận
- ❌ Không xem bình luận của nhà xe khác

### Admin
- ✅ Toàn quyền với tất cả bình luận
- ✅ Xem thống kê tổng thể

## 📊 Database

Model sử dụng: `BinhLuan` (đã có sẵn)

Các trường quan trọng:
- `parent_id`: ID bình luận cha (null = bình luận gốc, có giá trị = phản hồi)
- `user_id`: ID người bình luận
- `chuyen_xe_id`: ID chuyến xe
- `noi_dung`: Nội dung bình luận
- `so_sao`: Số sao đánh giá (1-5, null cho phản hồi)
- `trang_thai`: Trạng thái (cho_duyet, da_duyet, tu_choi)
- `ngay_bl`: Ngày bình luận
- `ngay_duyet`: Ngày duyệt
- `ly_do_tu_choi`: Lý do từ chối (nếu có)

## 🚀 Hướng Dẫn Sử Dụng

### Cho Khách Hàng

1. **Đăng nhập** vào hệ thống
2. Vào **"Vé của tôi"** từ menu sidebar
3. Tìm vé đã thanh toán
4. Click nút **"Đánh giá"** (icon comment)
5. Chọn số sao và viết nội dung
6. Click **"Gửi đánh giá"**

### Cho Nhân Viên

1. **Đăng nhập** với tài khoản Staff
2. Vào **"Quản lý bình luận"** từ menu sidebar
3. Xem danh sách bình luận chờ duyệt
4. Click **"Xem & Trả lời"** để xem chi tiết
5. Viết nội dung trả lời và gửi
6. Duyệt hoặc từ chối bình luận nếu cần

### Cho Admin

Tương tự Staff nhưng có thể:
- Xem tất cả bình luận của mọi nhà xe
- Truy cập thêm trang thống kê chi tiết

## 📝 Lưu Ý Quan Trọng

1. ⚠️ **Phải mua vé trước** mới có thể bình luận
2. ⚠️ **Mỗi người chỉ bình luận 1 lần** cho mỗi chuyến xe
3. ⚠️ Bình luận có phản hồi **không thể xóa/sửa**
4. ⚠️ Bình luận ≤2 sao cần **kiểm duyệt** trước khi hiển thị
5. ⚠️ Hệ thống tự động **lọc từ ngữ** không phù hợp

## 🎯 Tips & Tricks

- Sử dụng bộ lọc để nhanh chóng tìm bình luận cần xử lý
- Trả lời bình luản tiêu cực một cách chuyên nghiệp
- Khuyến khích khách hàng đánh giá bằng cách gửi email sau chuyến đi
- Theo dõi điểm đánh giá trung bình để cải thiện dịch vụ

## 🔧 Troubleshooting

### Không thể viết bình luận?
- ✓ Kiểm tra đã đăng nhập chưa
- ✓ Kiểm tra đã mua vé cho chuyến xe này chưa
- ✓ Kiểm tra vé đã thanh toán chưa
- ✓ Kiểm tra đã bình luận cho chuyến này chưa

### Bình luận không hiển thị?
- ✓ Kiểm tra số sao đánh giá (≤2 sao cần duyệt)
- ✓ Đợi Staff/Admin duyệt bình luận
- ✓ Bình luận có thể đã bị từ chối

### Không thể xóa bình luận?
- ✓ Bình luận đã có phản hồi từ nhà xe không thể xóa
- ✓ Chỉ chủ bình luận mới có thể xóa

## 📞 Hỗ Trợ

Nếu có vấn đề, vui lòng liên hệ:
- Email: support@futabus.com
- Hotline: 1900 xxxx

---

**Ngày tạo:** {{ date('d/m/Y') }}  
**Phiên bản:** 1.0  
**Người tạo:** Development Team
