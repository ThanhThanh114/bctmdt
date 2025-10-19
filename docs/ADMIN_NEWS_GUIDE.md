# Hướng Dẫn Quản Lý Tin Tức - Admin

## 📋 Tổng Quan

Hệ thống quản lý tin tức cho phép admin thêm/sửa tin tức với 2 cách upload ảnh:

1. **Upload từ máy** - Upload file ảnh từ máy tính
2. **Nhập URL** - Sử dụng link ảnh từ internet

## 🚀 Truy Cập Admin

### URL

```
http://localhost:8000/admin/tintuc
```

### Yêu Cầu Đăng Nhập

- Tài khoản admin
- Middleware: `auth` + `role:admin`

## ✨ Tính Năng

### 1. Danh Sách Tin Tức (`/admin/tintuc`)

- Hiển thị tất cả tin tức với phân trang (15 bài/trang)
- Tìm kiếm theo tiêu đề/nội dung
- Lọc theo nhà xe
- Lọc theo tác giả
- Thống kê: Tổng số, hôm nay, tháng này

### 2. Thêm Tin Tức (`/admin/tintuc/create`)

#### Form Nhập Liệu

```
┌─────────────────────────────────────┐
│ Tiêu đề (*)                         │
├─────────────────────────────────────┤
│ Nội dung (*) - 15 dòng              │
├─────────────────────────────────────┤
│ Hình ảnh đại diện:                  │
│ ┌─────────────┬─────────────┐       │
│ │ Upload File │  Nhập URL   │       │
│ └─────────────┴─────────────┘       │
│                                     │
│ [Chọn file...] hoặc                 │
│ [https://example.com/image.jpg]     │
│                                     │
│ [Preview Image]                     │
├─────────────────────────────────────┤
│ Nhà xe: [Dropdown]                  │
├─────────────────────────────────────┤
│ [Đăng tin] [Hủy]                    │
└─────────────────────────────────────┘
```

#### Cách Upload Ảnh

**Phương án 1: Upload từ máy**

1. Click radio button "Upload từ máy"
2. Click "Chọn ảnh..."
3. Chọn file ảnh từ máy (JPG, PNG, GIF, WEBP)
4. Xem preview trước khi lưu
5. Click "Đăng tin"

**Phương án 2: Nhập URL**

1. Click radio button "Nhập URL"
2. Paste URL ảnh vào ô input
    - Ví dụ: `https://futabus.vn/images/news/sample.jpg`
3. Xem preview tự động
4. Click "Đăng tin"

### 3. Sửa Tin Tức (`/admin/tintuc/{id}/edit`)

- Tương tự form thêm mới
- Hiển thị "Ảnh hiện tại"
- Có thể thay đổi từ file → URL hoặc ngược lại
- Preview "Ảnh mới" khi chọn ảnh mới

### 4. Xem Chi Tiết (`/admin/tintuc/{id}`)

- Xem toàn bộ thông tin tin tức
- Thông tin tác giả, nhà xe, ngày đăng

### 5. Xóa Tin Tức

- Xóa từng bài
- Xóa hàng loạt (bulk delete)
- Tự động xóa file ảnh trên server (nếu là file upload)

## 🔧 Cấu Hình Kỹ Thuật

### Validation Rules

**Thêm/Sửa Tin Tức:**

```php
[
    'tieu_de' => 'required|string|max:200',
    'noi_dung' => 'required|string',
    'image_type' => 'required|in:file,url',
    'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
    'image_url' => 'nullable|url|max:500',
    'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe',
]
```

### Lưu Trữ File

**Thư mục:**

```
public/assets/images/news/
```

**Tên file:**

```
news_{random16}_{timestamp}.{ext}
```

**Ví dụ:**

```
news_aB3cD4eF5gH6iJ7k_1697712000.jpg
```

### Database

**Bảng:** `tin_tuc`

**Cột `hinh_anh`:**

- **File upload:** `/assets/images/news/news_xxx.jpg`
- **URL:** `https://example.com/image.jpg`

**Phân biệt:**

```php
$isUrl = filter_var($hinh_anh, FILTER_VALIDATE_URL);
```

## 📝 Ví Dụ Thực Tế

### Ví Dụ 1: Upload File

```
Tiêu đề: KHAI TRƯƠNG VĂN PHÒNG PHÚ TÀI
Nội dung: Công ty Phương Trang chính thức...
Loại ảnh: ✓ Upload từ máy
File: office_opening.jpg (2.3 MB)
Nhà xe: Phương Trang
```

**Kết quả trong DB:**

```
hinh_anh: /assets/images/news/news_aB3cD4eF5gH6iJ7k_1697712000.jpg
```

### Ví Dụ 2: Nhập URL

```
Tiêu đề: ƯU ĐÃI THÁNG 10
Nội dung: Giảm giá 20% cho tất cả...
Loại ảnh: ✓ Nhập URL
URL: https://futabus.vn/images/promotions/oct2024.jpg
Nhà xe: FUTA Bus Lines
```

**Kết quả trong DB:**

```
hinh_anh: https://futabus.vn/images/promotions/oct2024.jpg
```

## 🎨 Giao Diện Frontend

### Hiển Thị Ảnh

```blade
@if($news->hinh_anh)
    @if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL))
        {{-- URL --}}
        <img src="{{ $news->hinh_anh }}" alt="{{ $news->tieu_de }}">
    @else
        {{-- File upload --}}
        <img src="{{ asset($news->hinh_anh) }}" alt="{{ $news->tieu_de }}">
    @endif
@else
    {{-- Placeholder --}}
    <img src="{{ asset('images/no-image.png') }}" alt="No Image">
@endif
```

## 🔒 Bảo Mật

### Xử Lý Xóa File

```php
// Chỉ xóa file trên server, không xóa URL
if ($news->hinh_anh && !filter_var($news->hinh_anh, FILTER_VALIDATE_URL)) {
    $filePath = public_path($news->hinh_anh);
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}
```

### Kiểm Tra URL

- Frontend: `<img onerror="...">` để handle lỗi
- Validation: `'url'` rule Laravel
- Preview: Hiển thị lỗi nếu ảnh không load

## 📱 Responsive

- Desktop: 2 cột (content 8, sidebar 4)
- Tablet: 1 cột full width
- Mobile: Form stack vertically

## 🐛 Xử Lý Lỗi

### Lỗi Thường Gặp

**1. File quá lớn**

```
Validation error: Kích thước hình ảnh không được vượt quá 5MB
```

**2. URL không hợp lệ**

```
Validation error: URL hình ảnh không hợp lệ
```

**3. Ảnh từ URL không load**

```
JavaScript alert: Không thể tải ảnh từ URL này. Vui lòng kiểm tra lại.
```

## 🚀 Tips & Tricks

### 1. Copy URL Ảnh FUTA

```
1. Vào https://futabus.vn/tin-tuc
2. F12 → Network → Img
3. Click refresh
4. Copy URL ảnh từ danh sách
5. Paste vào form admin
```

### 2. Tối Ưu Kích Thước

- Khuyến nghị: < 500KB
- Tối đa: 5MB
- Tools: TinyPNG, Squoosh.app

### 3. Định Dạng Khuyến Nghị

- **WebP** - Nhẹ nhất, hiện đại
- **JPG** - Ảnh phức tạp, nhiều màu
- **PNG** - Logo, ảnh có nền trong suốt

## 📊 Thống Kê

### Dashboard Metrics

```
┌──────────────────────────────────┐
│ Tổng số tin tức: 127             │
│ Đăng hôm nay: 3                  │
│ Đăng tháng này: 18               │
└──────────────────────────────────┘
```

## 🔗 Routes

```php
GET    /admin/tintuc              - Danh sách
GET    /admin/tintuc/create       - Form thêm
POST   /admin/tintuc              - Lưu mới
GET    /admin/tintuc/{id}         - Chi tiết
GET    /admin/tintuc/{id}/edit    - Form sửa
PUT    /admin/tintuc/{id}         - Cập nhật
DELETE /admin/tintuc/{id}         - Xóa
POST   /admin/tintuc/bulk-delete  - Xóa nhiều
```

## 💡 Ghi Chú

- ✅ Hỗ trợ cả file upload và URL
- ✅ Preview trước khi lưu
- ✅ Tự động xóa file cũ khi update
- ✅ Validation đầy đủ
- ✅ Responsive design
- ⚠️ URL ảnh phải public, không yêu cầu auth
- ⚠️ Kiểm tra CORS nếu ảnh không hiển thị

---

**Tác giả:** GitHub Copilot  
**Cập nhật:** October 19, 2025
