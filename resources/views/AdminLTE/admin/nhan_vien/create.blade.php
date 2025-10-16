@extends('layouts.admin')

@section('title', 'Thêm nhân viên')
@section('page-title', 'Thêm nhân viên')
@section('breadcrumb', 'Thêm nhân viên')

@section('content')
<div class="row">
    <!-- Form Section -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title mb-0">
                    <i class="fas fa-user-plus mr-2 text-success"></i>Form thêm nhân viên mới
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.nhanvien.store') }}">
                @csrf
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ten_nv">
                                    <i class="fas fa-user text-primary"></i> Họ và tên
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="ten_nv" id="ten_nv"
                                        class="form-control @error('ten_nv') is-invalid @enderror"
                                        value="{{ old('ten_nv') }}"
                                        placeholder="Nhập họ và tên nhân viên"
                                        required>
                                    @error('ten_nv')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="so_dien_thoai">
                                    <i class="fas fa-phone text-success"></i> Số điện thoại
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                    </div>
                                    <input type="text" name="so_dien_thoai" id="so_dien_thoai"
                                        class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                        value="{{ old('so_dien_thoai') }}"
                                        placeholder="Nhập số điện thoại"
                                        maxlength="15">
                                    @error('so_dien_thoai')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope text-primary"></i> Email
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="Nhập địa chỉ email">
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="chuc_vu">
                                    <i class="fas fa-user-tag text-warning"></i> Vai trò
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                    </div>
                                    <select name="chuc_vu" id="chuc_vu" class="form-control @error('chuc_vu') is-invalid @enderror"
                                        required>
                                        <option value="">-- Chọn vai trò --</option>
                                        <option value="tài xế" {{ old('chuc_vu') == 'tài xế' ? 'selected' : '' }}>
                                            🚗 Tài xế
                                        </option>
                                        <option value="phụ xe" {{ old('chuc_vu') == 'phụ xe' ? 'selected' : '' }}>
                                            👥 Phụ xe
                                        </option>
                                        <option value="nhân viên văn phòng"
                                            {{ old('chuc_vu') == 'nhân viên văn phòng' ? 'selected' : '' }}>
                                            💼 Nhân viên văn phòng
                                        </option>
                                        <option value="quản lý" {{ old('chuc_vu') == 'quản lý' ? 'selected' : '' }}>
                                            👔 Quản lý
                                        </option>
                                    </select>
                                    @error('chuc_vu')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ma_nha_xe">
                                    <i class="fas fa-bus text-info"></i> Nhà xe
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <select name="ma_nha_xe" id="ma_nha_xe"
                                        class="form-control @error('ma_nha_xe') is-invalid @enderror" required>
                                        <option value="">-- Chọn nhà xe --</option>
                                        @foreach($nhaXes as $nhaXe)
                                        <option value="{{ $nhaXe->ma_nha_xe }}"
                                            {{ old('ma_nha_xe') == $nhaXe->ma_nha_xe ? 'selected' : '' }}>
                                            {{ $nhaXe->ten_nha_xe }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('ma_nha_xe')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-save mr-2"></i> Lưu
                    </button>
                    <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-secondary btn-lg px-4">
                        <i class="fas fa-times mr-2"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-md-4">
        <div class="card shadow-sm bg-gradient-success">
            <div class="card-body text-white text-center p-4">
                <i class="fas fa-user-plus fa-4x mb-3"></i>
                <h4>Thêm nhân viên mới</h4>
                <p class="mb-0">Điền đầy đủ thông tin để tạo hồ sơ nhân viên mới</p>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2 text-info"></i>Hướng dẫn
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Họ và tên:</strong> Bắt buộc nhập
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Vai trò:</strong> Chọn chức vụ phù hợp
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Nhà xe:</strong> Chọn đơn vị làm việc
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info mr-2"></i>
                        <strong>SĐT & Email:</strong> Tùy chọn
                    </li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-users mr-2"></i>Các vai trò
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="badge badge-primary mr-2">🚗</span>
                    <strong>Tài xế:</strong> Điều khiển xe
                </div>
                <div class="mb-2">
                    <span class="badge badge-info mr-2">👥</span>
                    <strong>Phụ xe:</strong> Hỗ trợ tài xế
                </div>
                <div class="mb-2">
                    <span class="badge badge-success mr-2">💼</span>
                    <strong>NV Văn phòng:</strong> Hành chính
                </div>
                <div class="mb-0">
                    <span class="badge badge-warning mr-2">👔</span>
                    <strong>Quản lý:</strong> Điều hành
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }

    .form-control {
        border-left: none;
    }

    .form-control:focus {
        border-left: none;
    }

    .card {
        border-radius: 0.5rem;
    }
</style>
@endsection