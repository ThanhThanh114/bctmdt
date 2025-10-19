# IMAGE DISPLAY FIX - Hướng Dẫn

## ✅ Đã Sửa

Tất cả các view hiện đã hỗ trợ **hiển thị ảnh từ cả URL và file upload**.

## 🔧 Logic Hiển Thị Ảnh

### Blade Template Pattern

```blade
@if($news->hinh_anh)
    @if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL))
        {{-- Ảnh từ URL --}}
        <img src="{{ $news->hinh_anh }}" alt="..."
            onerror="this.src='https://via.placeholder.com/600x400'">
    @else
        {{-- Ảnh từ file upload --}}
        <img src="{{ asset($news->hinh_anh) }}" alt="..."
            onerror="this.src='https://via.placeholder.com/600x400'">
    @endif
@else
    {{-- Không có ảnh --}}
    <img src="{{ asset('assets/images/header.jpg') }}" alt="...">
@endif
```

## 📁 Files Đã Cập Nhật

### 1. Frontend Views

- ✅ `resources/views/news/news.blade.php`
    - Featured news (large card)
    - Featured news sidebar (4 small cards)
    - Spotlight section (5 cards)
    - All news grid (horizontal cards)

- ✅ `resources/views/news/show.blade.php`
    - Related news (4 cards)

### 2. Admin Views

- ✅ `resources/views/AdminLTE/admin/tin_tuc/show.blade.php`
    - Chi tiết tin tức
    - Hiển thị icon + info cho URL vs File

## 🎯 Cách Phân Biệt

### URL

```
Database: https://storage.googleapis.com/.../image.png
Display:  <img src="https://storage.googleapis.com/.../image.png">
```

### File Upload

```
Database: /assets/images/news/news_xxx.jpg
Display:  <img src="{{ asset('/assets/images/news/news_xxx.jpg') }}">
```

## 🔍 Test Cases

### 1. Ảnh URL (FUTA)

```
URL: https://storage.googleapis.com/futa-busline-web-cms-prod/599_2c7fe6e7e8/599_2c7fe6e7e8.png
Expected: ✅ Hiển thị ảnh từ Google Cloud Storage
```

### 2. Ảnh Upload từ Máy

```
File: /assets/images/news/news_aB3cD4eF5gH6iJ7k_1697712000.jpg
Expected: ✅ Hiển thị ảnh từ server local
```

### 3. Không Có Ảnh

```
hinh_anh: NULL
Expected: ✅ Hiển thị ảnh mặc định (header.jpg)
```

### 4. URL Lỗi

```
URL: https://invalid-domain.com/not-found.jpg
Expected: ✅ Fallback to placeholder (onerror)
```

### 5. File Không Tồn Tại

```
File: /assets/images/news/deleted_file.jpg
Expected: ✅ Fallback to placeholder (onerror)
```

## 🚨 Lỗi Trước Đây

### Vấn Đề

```blade
{{-- ❌ Cũ - Chỉ check file exists, không hỗ trợ URL --}}
@if($news->hinh_anh && file_exists(public_path('assets/images/' . $news->hinh_anh)))
    <img src="{{ asset('assets/images/' . $news->hinh_anh) }}">
@else
    <img src="...">
@endif
```

**Lỗi:**

- URL sẽ không hiển thị (vì `file_exists()` return false)
- Đường dẫn file sai (`assets/images/` thay vì đúng path)

### Giải Pháp

```blade
{{-- ✅ Mới - Hỗ trợ cả URL và file --}}
@if($news->hinh_anh)
    @if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL))
        <img src="{{ $news->hinh_anh }}">
    @else
        <img src="{{ asset($news->hinh_anh) }}">
    @endif
@endif
```

## 📋 Checklist

- ✅ Frontend news listing (all 4 sections)
- ✅ Frontend news detail (related news)
- ✅ Admin news detail
- ✅ Fallback images (onerror handler)
- ✅ Support URL từ external sources
- ✅ Support file upload local

## 🎨 Placeholder URLs

### Frontend (Orange theme)

```
https://via.placeholder.com/800x600/FF5722/ffffff?text=Tin+Tức
https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức
https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức
```

### Admin (Grey theme)

```
https://via.placeholder.com/800x400/ccc/666?text=Lỗi+tải+ảnh
```

## 🔗 Helper Function (Optional)

Nếu muốn tạo helper để tái sử dụng:

**app/Helpers/ImageHelper.php:**

```php
<?php

if (!function_exists('getNewsImageUrl')) {
    function getNewsImageUrl($hinh_anh, $default = null) {
        if (!$hinh_anh) {
            return $default ?? asset('assets/images/header.jpg');
        }

        // Check if URL
        if (filter_var($hinh_anh, FILTER_VALIDATE_URL)) {
            return $hinh_anh;
        }

        // Local file
        return asset($hinh_anh);
    }
}
```

**Usage in Blade:**

```blade
<img src="{{ getNewsImageUrl($news->hinh_anh) }}" alt="...">
```

**composer.json:**

```json
"autoload": {
    "files": [
        "app/Helpers/ImageHelper.php"
    ]
}
```

Sau đó chạy:

```bash
composer dump-autoload
```

## ✨ Kết Quả

Bây giờ trang tin tức sẽ:

- ✅ Hiển thị ảnh URL từ FUTA
- ✅ Hiển thị ảnh upload từ máy
- ✅ Fallback tự động khi ảnh lỗi
- ✅ Responsive tất cả các breakpoint

---

**Status:** ✅ Fixed  
**Date:** October 19, 2025
