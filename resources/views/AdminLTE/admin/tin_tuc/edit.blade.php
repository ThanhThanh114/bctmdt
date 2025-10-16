@extends('layouts.admin')
@section('title', 'Chỉnh sửa Tin tức')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chỉnh sửa Tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tintuc.index') }}">Tin tức</a></li>
                    <li class="breadcrumb-item active">Sửa</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.tintuc.update', $tinTuc->ma_tin) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror" value="{{ old('tieu_de', $tinTuc->tieu_de) }}" required>
                                @error('tieu_de')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>Nội dung <span class="text-danger">*</span></label>
                                <textarea name="noi_dung" class="form-control @error('noi_dung') is-invalid @enderror" rows="15" required>{{ old('noi_dung', $tinTuc->noi_dung) }}</textarea>
                                @error('noi_dung')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>Hình ảnh mới</label>
                                <input type="file" name="hinh_anh" class="form-control-file @error('hinh_anh') is-invalid @enderror" accept="image/*">
                                @error('hinh_anh')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                @if($tinTuc->hinh_anh)
                                <div class="mt-2"><img src="{{ asset('assets/image/' . $tinTuc->hinh_anh) }}" alt="Image" style="max-width:200px;"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nhà xe</label>
                                <select name="ma_nha_xe" class="form-control">
                                    <option value="">-- Chọn nhà xe --</option>
                                    @foreach($nhaXe as $nx)
                                    <option value="{{ $nx->ma_nha_xe }}" {{ old('ma_nha_xe', $tinTuc->ma_nha_xe) == $nx->ma_nha_xe ? 'selected' : '' }}>{{ $nx->ten_nha_xe }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Lưu</button>
                            <a href="{{ route('admin.tintuc.index') }}" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection