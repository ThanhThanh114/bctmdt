# HƯỚNG DẪN TẠO VIEWS CHO TẤT CẢ MODULE ADMIN

## Cấu trúc thư mục views cần tạo:

```
resources/views/AdminLTE/admin/
├── nhan_vien/
│   ├── index.blade.php ✅ (Đã tạo)
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── dat_ve/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── statistics.blade.php
├── binh_luan/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── statistics.blade.php
├── doanh_thu/
│   ├── index.blade.php
│   ├── by_trip.blade.php
│   └── by_company.blade.php
├── khuyen_mai/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── tin_tuc/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── contact/
│   ├── index.blade.php
│   └── show.blade.php
└── report/
    ├── index.blade.php
    ├── bookings.blade.php
    ├── revenue.blade.php
    └── users.blade.php
```

## TẤT CẢ VIEWS ĐỀU THEO MẪU SAU:

### 1. Index View Template (List)

```php
@extends('layouts.admin')

@section('title', 'Tiêu đề')
@section('page-title', 'Tiêu đề')
@section('breadcrumb', 'Breadcrumb')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách</h3>
                <div class="card-tools">
                    <!-- Button thêm mới nếu có -->
                </div>
            </div>

            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" class="mb-3">
                    <!-- Các input filter -->
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <!-- Table headers -->
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                            <tr>
                                <!-- Table data -->
                            </tr>
                            @empty
                            <tr>
                                <td colspan="X" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer clearfix">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
```

### 2. Create View Template

```php
@extends('layouts.admin')

@section('title', 'Thêm mới')
@section('page-title', 'Thêm mới')
@section('breadcrumb', 'Thêm mới')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form thêm mới</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.module.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.module.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <!-- Form fields -->
                    <div class="form-group">
                        <label for="field">Label <span class="text-danger">*</span></label>
                        <input type="text" name="field" id="field" class="form-control @error('field') is-invalid @enderror" value="{{ old('field') }}" required>
                        @error('field')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- More fields... -->
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('admin.module.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

### 3. Edit View Template

```php
@extends('layouts.admin')

@section('title', 'Chỉnh sửa')
@section('page-title', 'Chỉnh sửa')
@section('breadcrumb', 'Chỉnh sửa')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form chỉnh sửa</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.module.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.module.update', $item) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Form fields with value="{{ old('field', $item->field) }}" -->
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.module.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

### 4. Show View Template

```php
@extends('layouts.admin')

@section('title', 'Chi tiết')
@section('page-title', 'Chi tiết')
@section('breadcrumb', 'Chi tiết')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin chi tiết</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.module.edit', $item) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <a href="{{ route('admin.module.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">Label</th>
                            <td>{{ $item->field }}</td>
                        </tr>
                        <!-- More rows... -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
```

## CÁC COMPONENT BOOTSTRAP & ADMINLTE SỬ DỤNG:

### Alert Messages

```php
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('error') }}
    </div>
@endif
```

### Badges

```php
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-danger">Danger</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-info">Info</span>
<span class="badge badge-secondary">Secondary</span>
```

### Buttons

```php
<button class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
<button class="btn btn-success"><i class="fas fa-check"></i> Duyệt</button>
<button class="btn btn-danger"><i class="fas fa-trash"></i> Xóa</button>
<button class="btn btn-warning"><i class="fas fa-edit"></i> Sửa</button>
<button class="btn btn-info"><i class="fas fa-eye"></i> Xem</button>
<button class="btn btn-secondary"><i class="fas fa-times"></i> Hủy</button>
```

### Form Groups

```php
<div class="form-group">
    <label for="field">Label <span class="text-danger">*</span></label>
    <input type="text" name="field" id="field"
           class="form-control @error('field') is-invalid @enderror"
           value="{{ old('field') }}" required>
    @error('field')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
    <small class="form-text text-muted">Help text</small>
</div>
```

### Select Dropdown

```php
<select name="field" class="form-control @error('field') is-invalid @enderror">
    <option value="">-- Chọn --</option>
    @foreach($items as $item)
        <option value="{{ $item->id }}" {{ old('field') == $item->id ? 'selected' : '' }}>
            {{ $item->name }}
        </option>
    @endforeach
</select>
```

### File Upload

```php
<div class="form-group">
    <label>Hình ảnh</label>
    <div class="custom-file">
        <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" id="image">
        <label class="custom-file-label" for="image">Chọn file...</label>
        @error('image')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <small class="form-text text-muted">Định dạng: JPG, PNG. Tối đa 2MB</small>
</div>
```

### Date Picker

```php
<input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}">
```

### Textarea

```php
<textarea name="content" class="form-control @error('content') is-invalid @enderror"
          rows="5" required>{{ old('content') }}</textarea>
```

## ICONS FONTAWESOME SỬ DỤNG:

- `fa-users` - Users
- `fa-user-tie` - Nhân viên
- `fa-ticket-alt` - Đặt vé
- `fa-comments` - Bình luận
- `fa-dollar-sign` - Doanh thu
- `fa-gift` - Khuyến mãi
- `fa-newspaper` - Tin tức
- `fa-envelope` - Liên hệ
- `fa-chart-bar` - Báo cáo
- `fa-save` - Lưu
- `fa-edit` - Sửa
- `fa-trash` - Xóa
- `fa-eye` - Xem
- `fa-search` - Tìm kiếm
- `fa-filter` - Lọc
- `fa-download` - Tải xuống

## PAGINATION

```php
<div class="card-footer clearfix">
    {{ $items->links() }}
</div>
```

Hoặc với query string:

```php
{{ $items->appends(request()->query())->links() }}
```

## LƯU Ý QUAN TRỌNG:

1. **Layout Admin**: Tất cả views phải extend `layouts.admin`
2. **CSRF Token**: Mọi form phải có `@csrf`
3. **Method Spoofing**: Update/Delete dùng `@method('PUT')` và `@method('DELETE')`
4. **Validation Errors**: Dùng `@error('field')` để hiển thị lỗi
5. **Old Input**: Dùng `old('field')` để giữ giá trị khi có lỗi
6. **Session Messages**: Hiển thị `session('success')` và `session('error')`
7. **Empty State**: Luôn có `@empty` trong `@forelse`
8. **Confirm Delete**: Dùng `onsubmit="return confirm('...')"`
9. **Responsive**: Dùng `table-responsive` cho bảng
10. **Icons**: Luôn thêm icon cho buttons

## TIẾP TỤC TẠO VIEWS:

Tôi đã tạo sẵn:
✅ nhan_vien/index.blade.php

Cần tạo tiếp:

- 31 file views còn lại theo template trên
- Mỗi file tùy chỉnh theo từng module cụ thể
- Sử dụng đúng tên biến và route name

Bạn muốn tôi tạo views nào tiếp theo?
