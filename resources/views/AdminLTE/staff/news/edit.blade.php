@extends('layouts.admin')

@section('title', 'Sửa tin tức')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Sửa tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.news.index') }}">Tin tức</a></li>
                    <li class="breadcrumb-item active">Sửa</li>
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
            <form action="{{ route('staff.news.update', $tinTuc->ma_tin) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="tieu_de">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tieu_de') is-invalid @enderror"
                            id="tieu_de" name="tieu_de" value="{{ old('tieu_de', $tinTuc->tieu_de) }}" required>
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
                            <option value="{{ $nx->ma_nha_xe }}"
                                {{ old('ma_nha_xe', $tinTuc->ma_nha_xe) == $nx->ma_nha_xe ? 'selected' : '' }}>
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
                        @if($tinTuc->hinh_anh)
                        <div class="mb-2">
                            <img src="{{ asset($tinTuc->hinh_anh) }}" alt="Current image" style="max-width: 200px;">
                        </div>
                        @endif
                        <input type="file" class="form-control-file @error('hinh_anh') is-invalid @enderror"
                            id="hinh_anh" name="hinh_anh" accept="image/*">
                        <small class="form-text text-muted">Để trống nếu không muốn thay đổi hình ảnh</small>
                        @error('hinh_anh')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="noi_dung">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('noi_dung') is-invalid @enderror"
                            id="noi_dung" name="noi_dung" rows="10" required>{{ old('noi_dung', $tinTuc->noi_dung) }}</textarea>
                        @error('noi_dung')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
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