@extends('layouts.admin')

@section('title', 'Thêm tin tức mới')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Thêm tin tức mới</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.news.index') }}">Tin tức</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin tin tức</h3>
            </div>
            <form action="{{ route('staff.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="tieu_de">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tieu_de') is-invalid @enderror"
                            id="tieu_de" name="tieu_de" value="{{ old('tieu_de') }}" required>
                        @error('tieu_de')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="ma_nha_xe">Nhà xe</label>
                        <select class="form-control @error('ma_nha_xe') is-invalid @enderror"
                            id="ma_nha_xe" name="ma_nha_xe">
                            <option value="">-- Tất cả nhà xe --</option>
                            @foreach($nhaXe as $nx)
                            <option value="{{ $nx->ma_nha_xe }}" {{ old('ma_nha_xe') == $nx->ma_nha_xe ? 'selected' : '' }}>
                                {{ $nx->ten_nha_xe }}
                            </option>
                            @endforeach
                        </select>
                        @error('ma_nha_xe')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hinh_anh">Hình ảnh</label>
                        <input type="file" class="form-control-file @error('hinh_anh') is-invalid @enderror"
                            id="hinh_anh" name="hinh_anh" accept="image/*">
                        @error('hinh_anh')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="noi_dung">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('noi_dung') is-invalid @enderror"
                            id="noi_dung" name="noi_dung" rows="10" required>{{ old('noi_dung') }}</textarea>
                        @error('noi_dung')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('staff.news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection