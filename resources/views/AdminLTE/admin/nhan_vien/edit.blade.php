@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhân viên')
@section('page-title', 'Chỉnh sửa nhân viên')
@section('breadcrumb', 'Chỉnh sửa nhân viên')

@section('content')
<div class="row">
    <!-- Form Section -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title mb-0">
                    <i class="fas fa-edit mr-2 text-warning"></i>Chỉnh sửa thông tin nhân viên
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.nhanvien.show', $nhanvien) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.nhanvien.update', $nhanvien) }}">
                @csrf
                @method('PUT')
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
                                        value="{{ old('ten_nv', $nhanvien->ten_nv) }}"
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
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                    </div>
                                    <input type="text" name="so_dien_thoai" id="so_dien_thoai"
                                        class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                        value="{{ old('so_dien_thoai', $nhanvien->so_dien_thoai) }}"
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
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $nhanvien->email) }}"
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
                                        <option value="tài xế" {{ old('chuc_vu', $nhanvien->chuc_vu) == 'tài xế' ? 'selected' : '' }}>
                                            🚗 Tài xế
                                        </option>
                                        <option value="phụ xe" {{ old('chuc_vu', $nhanvien->chuc_vu) == 'phụ xe' ? 'selected' : '' }}>
                                            👥 Phụ xe
                                        </option>
                                        <option value="nhân viên văn phòng"
                                            {{ old('chuc_vu', $nhanvien->chuc_vu) == 'nhân viên văn phòng' ? 'selected' : '' }}>
                                            💼 Nhân viên văn phòng
                                        </option>
                                        <option value="quản lý" {{ old('chuc_vu', $nhanvien->chuc_vu) == 'quản lý' ? 'selected' : '' }}>
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
                                            {{ old('ma_nha_xe', $nhanvien->ma_nha_xe) == $nhaXe->ma_nha_xe ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save mr-2"></i> Lưu thay đổi
                    </button>
                    <a href="{{ route('admin.nhanvien.show', $nhanvien) }}" class="btn btn-secondary btn-lg px-4">
                        <i class="fas fa-times mr-2"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i>Thông tin nhân viên
                </h3>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="rounded-circle bg-gradient-primary d-inline-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                </div>
                <h5 class="mb-1">{{ $nhanvien->ten_nv }}</h5>
                <p class="text-muted small mb-3">{{ $nhanvien->email ?? 'Chưa có email' }}</p>

                <div class="bg-light p-3 rounded">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">ID:</span>
                        <span class="badge badge-light">#{{ $nhanvien->ma_nv }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Vai trò hiện tại:</span>
                        @if($nhanvien->chuc_vu == 'tài xế')
                        <span class="badge badge-primary">Tài xế</span>
                        @elseif($nhanvien->chuc_vu == 'phụ xe')
                        <span class="badge badge-info">Phụ xe</span>
                        @elseif($nhanvien->chuc_vu == 'nhân viên văn phòng')
                        <span class="badge badge-success">NV Văn phòng</span>
                        @else
                        <span class="badge badge-warning">Quản lý</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title mb-0">
                    <i class="fas fa-bolt mr-2 text-warning"></i>Thao tác nhanh
                </h3>
            </div>
            <div class="card-body p-2">
                <a href="{{ route('admin.nhanvien.show', $nhanvien) }}" class="btn btn-block btn-info mb-2">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </a>
                <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-block btn-secondary mb-2">
                    <i class="fas fa-list"></i> Danh sách nhân viên
                </a>
                <form action="{{ route('admin.nhanvien.destroy', $nhanvien) }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-block btn-danger">
                        <i class="fas fa-trash-alt"></i> Xóa nhân viên
                    </button>
                </form>
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

    .rounded-circle {
        border: 3px solid #f8f9fa;
    }
</style>
@endsection