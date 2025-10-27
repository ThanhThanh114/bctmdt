@extends('layouts.admin')

@section('title', 'Quản lý Nhà Xe')

@section('page-title', 'Quản lý Nhà Xe')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Nhà xe</li>
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
    .nha-xe-avatar {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 24px;
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
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info stat-card">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Tổng số nhà xe</p>
            </div>
            <div class="icon">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success stat-card">
            <div class="inner">
                <h3>{{ $stats['hoat_dong'] }}</h3>
                <p>Đang hoạt động</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger stat-card">
            <div class="inner">
                <h3>{{ $stats['bi_khoa'] }}</h3>
                <p>Bị khóa</p>
            </div>
            <div class="icon">
                <i class="fas fa-lock"></i>
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
        <form method="GET" action="{{ route('admin.nha-xe.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tên nhà xe, email, số điện thoại..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="all" {{ request('trang_thai') == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="hoat_dong" {{ request('trang_thai') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="bi_khoa" {{ request('trang_thai') == 'bi_khoa' ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-2"></i>Tìm kiếm
                            </button>
                            <a href="{{ route('admin.nha-xe.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo mr-2"></i>Đặt lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Danh sách nhà xe -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-2"></i>Danh sách nhà xe ({{ $nhaXe->total() }})
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Mã</th>
                        <th>Nhà xe</th>
                        <th>Liên hệ</th>
                        <th width="120" class="text-center">Chuyến xe</th>
                        <th width="120" class="text-center">Nhân viên</th>
                        <th width="120" class="text-center">Tài khoản</th>
                        <th width="120" class="text-center">Trạng thái</th>
                        <th width="150" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nhaXe as $nx)
                    <tr>
                        <td>
                            <strong>#{{ $nx->ma_nha_xe }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="nha-xe-avatar mr-3">
                                    {{ strtoupper(substr($nx->ten_nha_xe, 0, 2)) }}
                                </div>
                                <div>
                                    <strong>{{ $nx->ten_nha_xe }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ Str::limit($nx->dia_chi, 50) }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-phone text-info mr-1"></i>{{ $nx->so_dien_thoai }}<br>
                            <i class="fas fa-envelope text-info mr-1"></i>{{ $nx->email }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-info badge-lg">
                                {{ $nx->chuyen_xe_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-primary badge-lg">
                                {{ $nx->nhan_vien_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-secondary badge-lg">
                                {{ $nx->users_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($nx->trang_thai == 'hoat_dong')
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
                                <a href="{{ route('admin.nha-xe.show', $nx->ma_nha_xe) }}" 
                                   class="btn btn-sm btn-info" title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($nx->trang_thai == 'hoat_dong')
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            data-toggle="modal" 
                                            data-target="#lockModal{{ $nx->ma_nha_xe }}"
                                            title="Khóa">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @else
                                    <form action="{{ route('admin.nha-xe.unlock', $nx->ma_nha_xe) }}" 
                                          method="POST" 
                                          style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" 
                                                title="Mở khóa"
                                                onclick="return confirm('Bạn có chắc muốn mở khóa nhà xe này?')">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal khóa nhà xe -->
                    <div class="modal fade" id="lockModal{{ $nx->ma_nha_xe }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('admin.nha-xe.lock', $nx->ma_nha_xe) }}" method="POST" id="lockForm{{ $nx->ma_nha_xe }}">
                                    @csrf
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">
                                            <i class="fas fa-lock mr-2"></i>Khóa nhà xe: {{ $nx->ten_nha_xe }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <strong>Cảnh báo:</strong> Khi khóa nhà xe, tất cả tài khoản nhân viên của nhà xe này sẽ bị khóa!
                                        </div>
                                        <div class="form-group">
                                            <label for="ly_do_khoa{{ $nx->ma_nha_xe }}">Lý do khóa: <span class="text-danger">*</span></label>
                                            <textarea 
                                                class="form-control @error('ly_do_khoa') is-invalid @enderror" 
                                                name="ly_do_khoa" 
                                                id="ly_do_khoa{{ $nx->ma_nha_xe }}" 
                                                rows="4" 
                                                required
                                                placeholder="Nhập lý do khóa nhà xe này..."
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
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không tìm thấy nhà xe nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($nhaXe->hasPages())
    <div class="card-footer">
        {{ $nhaXe->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Debug modal
    $('[data-toggle="modal"]').on('click', function(e) {
        var target = $(this).data('target');
        console.log('Opening modal:', target);
    });
    
    // Xử lý submit form khóa
    $('form[id^="lockForm"]').on('submit', function(e) {
        var formId = $(this).attr('id');
        var textarea = $(this).find('textarea[name="ly_do_khoa"]');
        
        console.log('Submitting form:', formId);
        console.log('Lý do khóa:', textarea.val());
        
        if (!textarea.val() || textarea.val().trim() === '') {
            e.preventDefault();
            alert('Vui lòng nhập lý do khóa nhà xe!');
            textarea.focus();
            return false;
        }
        
        // Confirm trước khi submit
        if (!confirm('Bạn có chắc chắn muốn khóa nhà xe này?')) {
            e.preventDefault();
            return false;
        }
        
        console.log('Form đã được submit');
        return true;
    });
});
</script>
@endpush
