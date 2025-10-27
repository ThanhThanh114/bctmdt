@extends('layouts.admin')

@section('title', 'Quản lý Tài Khoản Nhà Xe')

@section('page-title', 'Quản lý Tài Khoản Nhà Xe')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Tài khoản nhà xe</li>
@endsection

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 16px;
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

<!-- Thống kê -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info stat-card">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Tổng tài khoản</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success stat-card">
            <div class="inner">
                <h3>{{ $stats['active'] }}</h3>
                <p>Đang hoạt động</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger stat-card">
            <div class="inner">
                <h3>{{ $stats['locked'] }}</h3>
                <p>Bị khóa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-lock"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning stat-card">
            <div class="inner">
                <h3>{{ $stats['staff'] }}</h3>
                <p>Nhân viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>
</div>

<!-- Bộ lọc -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter mr-2"></i>Bộ lọc
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.tai-khoan-nha-xe.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Username, họ tên, email, SĐT..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nhà xe</label>
                        <select name="ma_nha_xe" class="form-control">
                            <option value="all">Tất cả</option>
                            @foreach($nhaXe as $nx)
                            <option value="{{ $nx->ma_nha_xe }}" {{ request('ma_nha_xe') == $nx->ma_nha_xe ? 'selected' : '' }}>
                                {{ $nx->ten_nha_xe }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Vai trò</label>
                        <select name="role" class="form-control">
                            <option value="all">Tất cả</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                            <option value="bus_owner" {{ request('role') == 'bus_owner' ? 'selected' : '' }}>Chủ xe</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="is_active" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search mr-1"></i>Lọc
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Danh sách tài khoản -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-2"></i>Danh sách tài khoản ({{ $taiKhoan->total() }})
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th>Tài khoản</th>
                        <th>Nhà xe</th>
                        <th>Vai trò</th>
                        <th>Liên hệ</th>
                        <th width="120" class="text-center">Trạng thái</th>
                        <th width="180" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($taiKhoan as $tk)
                    <tr>
                        <td><strong>#{{ $tk->id }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar mr-2">
                                    {{ strtoupper(substr($tk->username, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $tk->username }}</strong><br>
                                    <small class="text-muted">{{ $tk->fullname }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($tk->nhaXe)
                                <span class="badge badge-info">{{ $tk->nhaXe->ten_nha_xe }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($tk->role === 'staff')
                                <span class="badge badge-primary">Nhân viên</span>
                            @elseif($tk->role === 'bus_owner')
                                <span class="badge badge-warning">Chủ xe</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                <i class="fas fa-envelope text-info"></i> {{ $tk->email }}<br>
                                <i class="fas fa-phone text-info"></i> {{ $tk->phone ?? 'N/A' }}
                            </small>
                        </td>
                        <td class="text-center">
                            @if($tk->is_active)
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Hoạt động
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-lock"></i> Bị khóa
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('admin.tai-khoan-nha-xe.show', $tk->id) }}" 
                                   class="btn btn-sm btn-info" title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($tk->is_active)
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            data-toggle="modal" 
                                            data-target="#lockModal{{ $tk->id }}"
                                            title="Khóa">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @else
                                    <form action="{{ route('admin.tai-khoan-nha-xe.unlock', $tk->id) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" 
                                                title="Mở khóa"
                                                onclick="return confirm('Bạn có chắc muốn mở khóa tài khoản này?')">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.tai-khoan-nha-xe.reset-password', $tk->id) }}" 
                                      method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary" 
                                            title="Reset mật khẩu"
                                            onclick="return confirm('Bạn có chắc muốn reset mật khẩu?')">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal khóa -->
                    <div class="modal fade" id="lockModal{{ $tk->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('admin.tai-khoan-nha-xe.lock', $tk->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">
                                            <i class="fas fa-lock mr-2"></i>Khóa tài khoản: {{ $tk->username }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <strong>Cảnh báo:</strong> Khi khóa tài khoản:
                                            <ul class="mb-0 mt-2">
                                                <li>Tài khoản sẽ không thể đăng nhập</li>
                                                @if(in_array($tk->role, ['bus_owner', 'staff']))
                                                <li><strong>Tài khoản sẽ bị HẠ CẤP xuống quyền USER</strong></li>
                                                <li>Liên kết với nhà xe sẽ bị xóa</li>
                                                @endif
                                            </ul>
                                        </div>
                                        @if(in_array($tk->role, ['bus_owner', 'staff']))
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <strong>Lưu ý:</strong> Khi mở khóa, quyền sẽ được <strong>TỰ ĐỘNG KHÔI PHỤC</strong>.
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="ly_do_khoa{{ $tk->id }}">Lý do khóa: <span class="text-danger">*</span></label>
                                            <textarea 
                                                class="form-control" 
                                                name="ly_do_khoa" 
                                                id="ly_do_khoa{{ $tk->id }}" 
                                                rows="4" 
                                                required
                                                placeholder="Nhập lý do khóa tài khoản..."
                                            ></textarea>
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
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không tìm thấy tài khoản nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($taiKhoan->hasPages())
    <div class="card-footer">
        {{ $taiKhoan->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection
