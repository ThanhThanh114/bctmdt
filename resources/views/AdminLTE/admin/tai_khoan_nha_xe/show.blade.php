@extends('layouts.admin')

@section('title', 'Chi tiết Tài Khoản')

@section('page-title')
    Chi tiết tài khoản: {{ $taikhoan->username }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.tai-khoan-nha-xe.index') }}">Tài khoản nhà xe</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
@endsection

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        border: 5px solid rgba(255,255,255,0.3);
    }
    .info-card {
        transition: all 0.3s;
    }
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
</div>
@endif

<!-- Header -->
<div class="profile-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="profile-avatar">
                {{ strtoupper(substr($taikhoan->username, 0, 2)) }}
            </div>
        </div>
        <div class="col">
            <h2 class="mb-2">{{ $taikhoan->fullname }}</h2>
            <p class="mb-1">
                <i class="fas fa-user mr-2"></i>@{{ $taikhoan->username }}
                @if($taikhoan->role === 'staff')
                    <span class="badge badge-light ml-2">Nhân viên</span>
                @elseif($taikhoan->role === 'bus_owner')
                    <span class="badge badge-warning ml-2">Chủ xe</span>
                @endif
            </p>
            <p class="mb-1">
                <i class="fas fa-envelope mr-2"></i>{{ $taikhoan->email }}
                @if($taikhoan->phone)
                    <span class="mx-2">|</span>
                    <i class="fas fa-phone mr-2"></i>{{ $taikhoan->phone }}
                @endif
            </p>
            <div class="mt-3">
                @if($taikhoan->is_active)
                    <span class="badge badge-success badge-lg">
                        <i class="fas fa-check-circle"></i> HOẠT ĐỘNG
                    </span>
                @else
                    <span class="badge badge-danger badge-lg">
                        <i class="fas fa-lock"></i> BỊ KHÓA
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Cột trái -->
    <div class="col-md-8">
        <!-- Thông tin cơ bản -->
        <div class="card info-card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Thông tin cơ bản
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID:</th>
                        <td><strong>#{{ $taikhoan->id }}</strong></td>
                    </tr>
                    <tr>
                        <th>Username:</th>
                        <td>{{ $taikhoan->username }}</td>
                    </tr>
                    <tr>
                        <th>Họ tên:</th>
                        <td>{{ $taikhoan->fullname }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $taikhoan->email }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại:</th>
                        <td>{{ $taikhoan->phone ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ:</th>
                        <td>{{ $taikhoan->address ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày sinh:</th>
                        <td>{{ $taikhoan->date_of_birth ? \Carbon\Carbon::parse($taikhoan->date_of_birth)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Giới tính:</th>
                        <td>{{ $taikhoan->gender ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Vai trò:</th>
                        <td>
                            @if($taikhoan->role === 'staff')
                                <span class="badge badge-primary">Nhân viên</span>
                            @elseif($taikhoan->role === 'bus_owner')
                                <span class="badge badge-warning">Chủ xe</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Nhà xe:</th>
                        <td>
                            @if($taikhoan->nhaXe)
                                <strong>{{ $taikhoan->nhaXe->ten_nha_xe }}</strong><br>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $taikhoan->nhaXe->dia_chi }}<br>
                                    <i class="fas fa-phone mr-1"></i>{{ $taikhoan->nhaXe->so_dien_thoai }}
                                </small>
                            @else
                                <span class="text-muted">Không thuộc nhà xe nào</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>
                            {{ \Carbon\Carbon::parse($taikhoan->created_at)->format('d/m/Y H:i') }}
                            <small class="text-muted">({{ \Carbon\Carbon::parse($taikhoan->created_at)->diffForHumans() }})</small>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Thông tin khóa (nếu có) -->
        @if(!$taikhoan->is_active && $taikhoan->locked_reason)
        <div class="card info-card border-danger">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Thông tin khóa
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-danger mb-0">
                    <h6><i class="fas fa-lock mr-2"></i>Tài khoản bị khóa</h6>
                    <hr>
                    <p><strong>Lý do:</strong></p>
                    <p>{{ $taikhoan->locked_reason }}</p>
                    @if($taikhoan->locked_at)
                    <p class="mb-0">
                        <small class="text-muted">
                            <i class="fas fa-calendar mr-1"></i>
                            Khóa lúc: {{ \Carbon\Carbon::parse($taikhoan->locked_at)->format('d/m/Y H:i') }}
                            ({{ \Carbon\Carbon::parse($taikhoan->locked_at)->diffForHumans() }})
                        </small>
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Cột phải -->
    <div class="col-md-4">
        <!-- Trạng thái -->
        <div class="card">
            <div class="card-header {{ $taikhoan->is_active ? 'bg-success' : 'bg-danger' }} text-white">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Trạng thái
                </h3>
            </div>
            <div class="card-body">
                @if($taikhoan->is_active)
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Đang hoạt động</h5>
                        <p class="text-muted">Tài khoản có thể đăng nhập bình thường</p>
                    </div>
                @else
                    <div class="text-center">
                        <i class="fas fa-lock fa-3x text-danger mb-3"></i>
                        <h5>Bị khóa</h5>
                        <p class="text-muted">Tài khoản không thể đăng nhập</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Thao tác -->
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">
                    <i class="fas fa-cog mr-2"></i>Thao tác
                </h3>
            </div>
            <div class="card-body p-2">
                @if($taikhoan->is_active)
                    <button type="button" class="btn btn-warning btn-block mb-2" 
                            data-toggle="modal" 
                            data-target="#lockModal">
                        <i class="fas fa-lock mr-2"></i>Khóa tài khoản
                    </button>
                @else
                    <form action="{{ route('admin.tai-khoan-nha-xe.unlock', $taikhoan->id) }}" 
                          method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block mb-2"
                                onclick="return confirm('Bạn có chắc muốn mở khóa tài khoản này?')">
                            <i class="fas fa-unlock mr-2"></i>Mở khóa tài khoản
                        </button>
                    </form>
                @endif

                <form action="{{ route('admin.tai-khoan-nha-xe.reset-password', $taikhoan->id) }}" 
                      method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-block mb-2"
                            onclick="return confirm('Bạn có chắc muốn reset mật khẩu?')">
                        <i class="fas fa-key mr-2"></i>Reset mật khẩu
                    </button>
                </form>

                <form action="{{ route('admin.tai-khoan-nha-xe.destroy', $taikhoan->id) }}" 
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block mb-2"
                            onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')">
                        <i class="fas fa-trash mr-2"></i>Xóa tài khoản
                    </button>
                </form>

                <a href="{{ route('admin.tai-khoan-nha-xe.index') }}" class="btn btn-outline-secondary btn-block">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal khóa tài khoản -->
@if($taikhoan->is_active)
<div class="modal fade" id="lockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.tai-khoan-nha-xe.lock', $taikhoan->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-lock mr-2"></i>Khóa tài khoản
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Cảnh báo:</strong> Khi khóa tài khoản <strong>{{ $taikhoan->username }}</strong>:
                        <ul class="mb-0 mt-2">
                            <li>Tài khoản sẽ không thể đăng nhập</li>
                            @if(in_array($taikhoan->role, ['bus_owner', 'staff']))
                            <li><strong>Tài khoản sẽ bị HẠ CẤP xuống quyền USER</strong></li>
                            <li>Liên kết với nhà xe sẽ bị xóa</li>
                            @endif
                        </ul>
                    </div>
                    @if(in_array($taikhoan->role, ['bus_owner', 'staff']))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Lưu ý:</strong> Hệ thống sẽ tự động lưu thông tin gốc (role: {{ $taikhoan->role }}, nhà xe: {{ $taikhoan->nhaXe->ten_nha_xe ?? 'N/A' }}). Khi mở khóa, quyền sẽ được <strong>TỰ ĐỘNG KHÔI PHỤC</strong>.
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="ly_do_khoa">Lý do khóa: <span class="text-danger">*</span></label>
                        <textarea 
                            class="form-control @error('ly_do_khoa') is-invalid @enderror" 
                            name="ly_do_khoa" 
                            id="ly_do_khoa" 
                            rows="4" 
                            required
                            placeholder="Nhập lý do khóa tài khoản..."
                        ></textarea>
                        @error('ly_do_khoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-lock mr-2"></i>Xác nhận khóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Account show page loaded');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Bootstrap loaded:', typeof $().modal);
    
    // Debug: Check if modal element exists
    console.log('Lock modal exists:', $('#lockModal').length);
    console.log('Unlock modal exists:', $('#unlockModal').length);
    
    // Debug: Check if lock button exists
    console.log('Lock button exists:', $('[data-target="#lockModal"]').length);
    
    // Lock button click handler
    $('[data-target="#lockModal"]').on('click', function(e) {
        console.log('Lock button clicked');
        e.preventDefault();
        $('#lockModal').modal('show');
    });
    
    // Lock form validation
    $('#lockForm').on('submit', function(e) {
        console.log('Lock form submitting');
        var reason = $('#ly_do_khoa').val().trim();
        
        if (!reason) {
            e.preventDefault();
            alert('Vui lòng nhập lý do khóa tài khoản');
            $('#ly_do_khoa').focus();
            return false;
        }
        
        if (reason.length > 500) {
            e.preventDefault();
            alert('Lý do khóa không được vượt quá 500 ký tự');
            $('#ly_do_khoa').focus();
            return false;
        }
        
        console.log('Form validation passed, submitting...');
    });
    
    // Unlock form validation
    $('#unlockForm').on('submit', function(e) {
        console.log('Unlock form submitting');
        if (!confirm('Bạn có chắc chắn muốn mở khóa tài khoản này?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Reset password confirmation
    $('#resetPasswordForm').on('submit', function(e) {
        console.log('Reset password form submitting');
        if (!confirm('Bạn có chắc chắn muốn đặt lại mật khẩu thành "Password@123"?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Delete account confirmation
    $('#deleteForm').on('submit', function(e) {
        console.log('Delete form submitting');
        if (!confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa tài khoản này? Hành động này không thể hoàn tác!')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Modal events for debugging
    $('#lockModal').on('show.bs.modal', function() {
        console.log('Lock modal is showing');
    });
    
    $('#lockModal').on('shown.bs.modal', function() {
        console.log('Lock modal shown');
        $('#ly_do_khoa').focus();
    });
    
    $('#lockModal').on('hide.bs.modal', function() {
        console.log('Lock modal is hiding');
    });
    
    $('#unlockModal').on('show.bs.modal', function() {
        console.log('Unlock modal is showing');
    });
});
</script>
@endpush
