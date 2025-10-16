@extends('layouts.admin')

@section('title', 'Thêm Khuyến mãi')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Thêm Khuyến mãi mới</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.khuyenmai.index') }}">Khuyến mãi</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.khuyenmai.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin khuyến mãi</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Tên khuyến mãi <span class="text-danger">*</span></label>
                                <input type="text" name="ten_km"
                                    class="form-control @error('ten_km') is-invalid @enderror"
                                    value="{{ old('ten_km') }}" required placeholder="VD: Giảm giá mùa hè">
                                @error('ten_km')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Mã Code <span class="text-danger">*</span></label>
                                <input type="text" name="ma_code"
                                    class="form-control @error('ma_code') is-invalid @enderror"
                                    value="{{ old('ma_code') }}" required placeholder="VD: SUMMER2025">
                                @error('ma_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="ngay_bat_dau"
                                            class="form-control @error('ngay_bat_dau') is-invalid @enderror"
                                            value="{{ old('ngay_bat_dau') }}" required>
                                        @error('ngay_bat_dau')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày kết thúc <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="ngay_ket_thuc"
                                            class="form-control @error('ngay_ket_thuc') is-invalid @enderror"
                                            value="{{ old('ngay_ket_thuc') }}" required>
                                        @error('ngay_ket_thuc')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cài đặt</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Giảm giá (%) <span class="text-danger">*</span></label>
                                <input type="number" name="giam_gia"
                                    class="form-control @error('giam_gia') is-invalid @enderror"
                                    value="{{ old('giam_gia') }}" min="1" max="100" required>
                                @error('giam_gia')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Lưu
                            </button>
                            <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection