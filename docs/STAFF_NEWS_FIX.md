# Sửa lỗi trang tin tức của Staff

## Ngày cập nhật: 18/10/2025

## Vấn đề
1. Trang tạo và chỉnh sửa tin tức của staff có giao diện hỗ trợ 2 cách upload ảnh (từ máy và từ URL) nhưng controller chỉ xử lý upload file
2. **View staff đang dùng sai routes** - dùng `admin.tintuc.*` thay vì `staff.news.*` gây lỗi 403 Forbidden
3. File BookingsController bị lỗi syntax do có ký tự git diff

## Các thay đổi đã thực hiện

### 1. Model TinTuc (`app/Models/TinTuc.php`)
- ✅ Thêm trường `ngay_dang` vào `$fillable` để có thể mass-assign khi tạo tin tức mới

### 2. Controller NewsController (`app/Http\Controllers\Staff\NewsController.php`)

#### Phương thức `store()`:
- ✅ Thêm validation cho `image_url` (nullable|url)
- ✅ Thêm validation cho `hinh_anh` hỗ trợ thêm định dạng WEBP
- ✅ Tăng kích thước file upload tối đa lên 5MB (5120 KB)
- ✅ Thêm logic xử lý URL ảnh: nếu không có file upload nhưng có URL thì sử dụng URL
- ✅ Gán `ngay_dang` = now() khi tạo tin tức mới

#### Phương thức `update()`:
- ✅ Thêm validation cho `image_url` (nullable|url)
- ✅ Thêm validation cho `hinh_anh` hỗ trợ thêm định dạng WEBP
- ✅ Tăng kích thước file upload tối đa lên 5MB
- ✅ Thêm logic xử lý URL ảnh
- ✅ Kiểm tra xem ảnh cũ có phải là URL không trước khi xóa (chỉ xóa file local)
- ✅ Xóa ảnh cũ khi cập nhật ảnh mới bằng URL

### 3. View Edit (`resources/views/AdminLTE/staff/news/edit.blade.php`)
- ✅ Thêm toggle button để chọn giữa upload file và nhập URL
- ✅ Thêm input cho URL ảnh
- ✅ Thêm preview ảnh (cả file và URL)
- ✅ Thêm JavaScript để xử lý toggle giữa 2 cách upload
- ✅ Thêm JavaScript preview ảnh từ file
- ✅ Thêm JavaScript preview ảnh từ URL
- ✅ Hiển thị ảnh hiện tại đúng cách (URL hoặc local)

### 4. View Create (`resources/views/AdminLTE/staff/news/create.blade.php`)
- ✅ **Sửa routes từ `admin.tintuc.*` thành `staff.news.*`** để tránh lỗi 403
- ✅ Sửa breadcrumb từ `admin.dashboard` thành `staff.dashboard`
- ✅ Sửa link hủy từ `admin.tintuc.index` thành `staff.news.index`

### 5. Controller BookingsController (`app/Http/Controllers/Staff/BookingsController.php`)
- ✅ **Sửa lỗi syntax** - file bị lỗi do có ký tự git diff nhầm vào code
- ✅ Viết lại toàn bộ file để loại bỏ các ký tự lỗi
- ✅ Đảm bảo các phương thức index, show, updateStatus, todayBookings, pendingBookings hoạt động đúng

## Cách sử dụng

### Tạo tin tức mới:
1. Truy cập trang "Thêm Tin tức mới"
2. Nhập tiêu đề và nội dung
3. Chọn cách upload ảnh:
   - **Upload từ máy**: Click nút "Upload từ máy", chọn file ảnh (JPG, PNG, GIF, WEBP, tối đa 5MB)
   - **Nhập URL**: Click nút "Nhập URL", paste URL của ảnh
4. Chọn nhà xe (tùy chọn)
5. Click "Đăng tin"

### Chỉnh sửa tin tức:
1. Truy cập trang danh sách tin tức
2. Click nút "Sửa" trên tin tức cần chỉnh sửa
3. Chỉnh sửa thông tin
4. Để thay đổi ảnh:
   - **Upload ảnh mới**: Click "Upload từ máy", chọn file
   - **Dùng URL mới**: Click "Nhập URL", paste URL
   - **Giữ ảnh cũ**: Không chọn gì cả
5. Click "Cập nhật"

## Lưu ý kỹ thuật
- Ảnh upload sẽ được lưu tại `public/assets/images/news/`
- Ảnh từ URL sẽ được lưu trực tiếp URL vào database
- Khi xóa/cập nhật tin tức, hệ thống sẽ kiểm tra và chỉ xóa file local (không xóa ảnh URL)
- Preview ảnh hoạt động cho cả file upload và URL

## Testing
Để kiểm tra:
1. Đăng nhập với tài khoản staff
2. Truy cập `/staff/news` hoặc click menu "Tin tức"
3. Thử tạo tin tức mới với upload file
4. Thử tạo tin tức mới với URL ảnh
5. Thử chỉnh sửa tin tức và thay đổi ảnh
6. Kiểm tra preview ảnh hoạt động đúng
7. Kiểm tra ảnh hiển thị đúng trên trang danh sách và chi tiết tin tức

## Lỗi đã sửa
- ❌ **Lỗi 403 Forbidden**: Do view dùng sai routes `admin.tintuc.*` thay vì `staff.news.*`
- ❌ **Lỗi Parse Error**: File BookingsController bị lỗi syntax do có ký tự git diff
- ❌ **Lỗi Emoji/UTF-8**: Bảng `tin_tuc` không hỗ trợ emoji do dùng charset `utf8` thay vì `utf8mb4`
- ✅ **Đã sửa**: 
  - Tất cả routes giờ dùng đúng `staff.news.*`
  - File BookingsController đã được viết lại
  - Bảng `tin_tuc` đã được chuyển sang `utf8mb4` để hỗ trợ emoji 🎉

## Migration đã chạy
- ✅ `2025_10_19_082932_fix_tin_tuc_charset_for_emoji_support.php` - Chuyển đổi bảng tin_tuc sang utf8mb4
