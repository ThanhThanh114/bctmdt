@extends('layouts.admin')

@section('title', 'Đổi mật khẩu')

@section('page-title', 'Đổi mật khẩu')
@section('breadcrumb', 'Bảo mật')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-key mr-2"></i>Đổi mật khẩu
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">Mật khẩu phải có ít nhất 8 ký tự</small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                            </div>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation" name="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Cập nhật mật khẩu
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Security Tips -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shield-alt mr-2"></i>Mẹo bảo mật
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-check text-success mr-2"></i>Chọn mật khẩu mạnh</h6>
                    <p class="text-muted">Sử dụng ít nhất 8 ký tự, kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt</p>
                </div>

                <div class="mb-3">
                    <h6><i class="fas fa-check text-success mr-2"></i>Không dùng mật khẩu cũ</h6>
                    <p class="text-muted">Tránh sử dụng lại mật khẩu đã dùng trước đây</p>
                </div>

                <div class="mb-3">
                    <h6><i class="fas fa-check text-success mr-2"></i>Bảo mật thông tin</h6>
                    <p class="text-muted">Không chia sẻ mật khẩu với người khác</p>
                </div>

                <div class="mb-3">
                    <h6><i class="fas fa-check text-success mr-2"></i>Đăng xuất khi không dùng</h6>
                    <p class="text-muted">Luôn đăng xuất khi sử dụng máy tính công cộng</p>
                </div>
            </div>
        </div>

        <!-- Account Security -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-shield mr-2"></i>Bảo mật tài khoản
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Mật khẩu:</span>
                    <span class="badge badge-success">Đã đặt</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Email xác thực:</span>
                    {{-- Email verification feature not yet implemented --}}
                    {{-- @if($user->is_verified)
                        <span class="badge badge-success">Đã xác thực</span>
                    @else
                        <span class="badge badge-warning">Chưa xác thực</span>
                    @endif --}}
                    <span class="badge badge-success">Đang hoạt động</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Trạng thái:</span>
                    <span class="badge badge-success">Bảo mật</span>
                </div>
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

.btn {
    border-radius: 0.25rem;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border: none;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush
