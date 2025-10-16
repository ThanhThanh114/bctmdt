@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('page-title', 'Chỉnh sửa người dùng')
@section('breadcrumb', 'Người dùng')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa thông tin người dùng
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fullname">Họ và tên <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                        id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}"
                                        required>
                                    @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Tên đăng nhập</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="username" value="{{ $user->username }}"
                                        disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-muted">Không thể thay đổi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" value="{{ old('address', $user->address) }}"
                                        placeholder="Nhập địa chỉ của người dùng">
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">Ngày sinh</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                        id="date_of_birth" name="date_of_birth"
                                        value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                        max="{{ date('Y-m-d') }}">
                                    @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Vai trò <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <select class="form-control @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="">Chọn vai trò</option>
                                        <option value="User" {{ old('role', $user->role) == 'User' ? 'selected' : '' }}>
                                            Người dùng</option>
                                        <option value="Staff"
                                            {{ old('role', $user->role) == 'Staff' ? 'selected' : '' }}>Nhân viên
                                        </option>
                                        <option value="Bus_owner"
                                            {{ old('role', $user->role) == 'Bus_owner' ? 'selected' : '' }}>Nhà xe
                                        </option>
                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Removed is_verified field as it doesn't exist in database --}}
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Trạng thái xác thực</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified"
                                        value="1" {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_verified">
                            <i class="fas fa-check-circle text-success mr-1"></i> Đã xác thực email
                        </label>
                    </div>
                    @error('is_verified')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
            </div>
        </div> --}}
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i>Lưu thay đổi
        </button>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
            <i class="fas fa-times mr-2"></i>Hủy
        </a>
    </div>
    </form>
</div>
</div>
</div>

<!-- User Info Sidebar -->
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle mr-2"></i>Thông tin người dùng
            </h3>
        </div>
        <div class="card-body">
            <div class="text-center mb-3">
                <i class="fas fa-user-circle fa-4x text-primary"></i>
            </div>
            <h5 class="text-center">{{ $user->fullname }}</h5>
            <p class="text-center text-muted">{{ $user->email }}</p>

            <hr>

            <div class="d-flex justify-content-between mb-2">
                <span>ID:</span>
                <span class="badge badge-secondary">{{ $user->id }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Vai trò hiện tại:</span>
                <span class="badge badge-info">{{ $user->role }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Ngày tạo:</span>
                <span>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Lần cập nhật:</span>
                <span>{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bolt mr-2"></i>Thao tác nhanh
            </h3>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-block">
                <i class="fas fa-eye mr-2"></i> Xem chi tiết
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                <i class="fas fa-list mr-2"></i> Danh sách người dùng
            </a>
            @if(strtolower($user->role) !== 'admin')
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-block">
                    <i class="fas fa-trash mr-2"></i> Xóa người dùng
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
</div>
@endsection

@push('styles')
<style>
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn {
        border-radius: 0.25rem;
    }

    .card {
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        border: none;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .badge {
        font-size: 0.75rem;
    }
</style>
@endpush