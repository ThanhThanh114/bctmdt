@extends('layouts.admin')

@section('title', 'Chi tiết nhân viên')
@section('page-title', 'Chi tiết nhân viên')
@section('breadcrumb', 'Chi tiết nhân viên')

@section('content')
<div class="row">
    <!-- Main Content -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title mb-0">
                    <i class="fas fa-user-circle mr-2 text-primary"></i>Thông tin nhân viên
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.nhanvien.edit', $nhanvien) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Avatar Section -->
                    <div class="col-md-4 text-center border-right">
                        <div class="mb-3 mt-3">
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                                style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-4x text-white"></i>
                            </div>
                        </div>
                        <h4 class="font-weight-bold mb-2">{{ $nhanvien->ten_nv }}</h4>
                        <p class="text-muted mb-2">
                            @if($nhanvien->email)
                            {{ $nhanvien->email }}
                            @else
                            <span class="text-muted">Chưa có email</span>
                            @endif
                        </p>
                    </div>

                    <!-- Info Section -->
                    <div class="col-md-8">
                        <div class="info-box mb-3 bg-light">
                            <span class="info-box-icon bg-primary elevation-1">
                                <i class="fas fa-id-badge"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Mã nhân viên:</span>
                                <span class="info-box-number">#{{ $nhanvien->ma_nv }}</span>
                            </div>
                        </div>

                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td width="40%" class="font-weight-bold">Tên nhân viên:</td>
                                    <td>{{ $nhanvien->ten_nv }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Chức vụ:</td>
                                    <td>
                                        @if($nhanvien->chuc_vu == 'tài xế')
                                        <span class="badge badge-primary px-3 py-1">
                                            <i class="fas fa-steering-wheel mr-1"></i> Tài xế
                                        </span>
                                        @elseif($nhanvien->chuc_vu == 'phụ xe')
                                        <span class="badge badge-info px-3 py-1">
                                            <i class="fas fa-user-friends mr-1"></i> Phụ xe
                                        </span>
                                        @elseif($nhanvien->chuc_vu == 'nhân viên văn phòng')
                                        <span class="badge badge-success px-3 py-1">
                                            <i class="fas fa-briefcase mr-1"></i> Nhân viên văn phòng
                                        </span>
                                        @else
                                        <span class="badge badge-warning px-3 py-1">
                                            <i class="fas fa-user-tie mr-1"></i> Quản lý
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Số điện thoại:</td>
                                    <td>
                                        @if($nhanvien->so_dien_thoai)
                                        <a href="tel:{{ $nhanvien->so_dien_thoai }}" class="text-success">
                                            <i class="fas fa-phone-alt"></i> {{ $nhanvien->so_dien_thoai }}
                                        </a>
                                        @else
                                        <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Email:</td>
                                    <td>
                                        @if($nhanvien->email)
                                        <a href="mailto:{{ $nhanvien->email }}" class="text-primary">
                                            <i class="fas fa-envelope"></i> {{ $nhanvien->email }}
                                        </a>
                                        @else
                                        <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Nhà xe:</td>
                                    <td>
                                        @if($nhanvien->nhaXe)
                                        <div>
                                            <strong class="text-dark">{{ $nhanvien->nhaXe->ten_nha_xe }}</strong>
                                        </div>
                                        @if($nhanvien->nhaXe->dia_chi)
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                            {{ $nhanvien->nhaXe->dia_chi }}
                                        </small>
                                        @endif
                                        @else
                                        <span class="text-muted">Chưa xác định</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                <form action="{{ route('admin.nhanvien.destroy', $nhanvien) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Xóa nhân viên
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-md-4">
        <!-- ID Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2 text-info"></i>Thông tin nhân viên
                </h3>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="rounded-circle bg-gradient-primary d-inline-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                </div>
                <h4 class="mb-1">{{ $nhanvien->ten_nv }}</h4>
                <p class="text-muted small">{{ $nhanvien->email ?? 'Chưa có email' }}</p>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">ID:</span>
                    <span class="badge badge-light">#{{ $nhanvien->ma_nv }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
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

        <!-- Quick Actions -->
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title mb-0">
                    <i class="fas fa-bolt mr-2 text-warning"></i>Thao tác nhanh
                </h3>
            </div>
            <div class="card-body p-2">
                <a href="{{ route('admin.nhanvien.edit', $nhanvien) }}" class="btn btn-block btn-warning mb-2">
                    <i class="fas fa-edit"></i> Chỉnh sửa thông tin
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
.info-box {
    border-radius: 0.5rem;
}

.card {
    border-radius: 0.5rem;
}

.rounded-circle {
    border: 3px solid #f8f9fa;
}
</style>
@endsection