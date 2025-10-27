@extends('layouts.admin')

@section('title', 'Chi tiết Nhà Xe')

@section('page-title')
    Chi tiết: {{ $nhaxe->ten_nha_xe }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.nha-xe.index') }}">Nhà xe</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
@endsection

@push('styles')
<style>
    .info-box-custom {
        min-height: 80px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }
    .info-box-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .nha-xe-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .nha-xe-logo {
        width: 100px;
        height: 100px;
        border-radius: 15px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: bold;
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

<!-- Header -->
<div class="nha-xe-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="nha-xe-logo">
                {{ strtoupper(substr($nhaxe->ten_nha_xe, 0, 2)) }}
            </div>
        </div>
        <div class="col">
            <h2 class="mb-2">{{ $nhaxe->ten_nha_xe }}</h2>
            <p class="mb-1"><i class="fas fa-map-marker-alt mr-2"></i>{{ $nhaxe->dia_chi }}</p>
            <p class="mb-1">
                <i class="fas fa-phone mr-2"></i>{{ $nhaxe->so_dien_thoai }}
                <span class="mx-3">|</span>
                <i class="fas fa-envelope mr-2"></i>{{ $nhaxe->email }}
            </p>
            <div class="mt-3">
                @if($nhaxe->trang_thai == 'hoat_dong')
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

<!-- Thống kê -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="info-box info-box-custom">
            <span class="info-box-icon bg-info"><i class="fas fa-bus"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tổng chuyến xe</span>
                <span class="info-box-number">{{ $stats['tong_chuyen_xe'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box info-box-custom">
            <span class="info-box-icon bg-success"><i class="fas fa-calendar-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Chuyến hôm nay</span>
                <span class="info-box-number">{{ $stats['chuyen_xe_hom_nay'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box info-box-custom">
            <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Nhân viên</span>
                <span class="info-box-number">{{ $stats['tong_nhan_vien'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box info-box-custom">
            <span class="info-box-icon bg-secondary"><i class="fas fa-user-shield"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tài khoản</span>
                <span class="info-box-number">{{ $stats['tong_tai_khoan'] }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Cột trái -->
    <div class="col-md-8">
        <!-- Chuyến xe gần đây -->
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">
                    <i class="fas fa-bus mr-2"></i>Chuyến xe gần đây
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tuyến</th>
                                <th>Ngày đi</th>
                                <th>Giờ</th>
                                <th>Loại xe</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nhaxe->chuyenXe as $cx)
                            <tr>
                                <td><strong>#{{ $cx->ma_chuyen_xe }}</strong></td>
                                <td>{{ $cx->tramDi->ten_tram }} → {{ $cx->tramDen->ten_tram }}</td>
                                <td>{{ \Carbon\Carbon::parse($cx->ngay_di)->format('d/m/Y') }}</td>
                                <td>{{ date('H:i', strtotime($cx->gio_di)) }}</td>
                                <td>{{ $cx->loai_xe }}</td>
                                <td>{{ number_format($cx->gia_ve) }}đ</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">
                                    Chưa có chuyến xe nào
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Nhân viên -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i>Nhân viên
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã NV</th>
                                <th>Họ tên</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nhaxe->nhanVien as $nv)
                            <tr>
                                <td><strong>#{{ $nv->ma_nv }}</strong></td>
                                <td>{{ $nv->ten_nv }}</td>
                                <td>{{ $nv->so_dien_thoai ?? 'N/A' }}</td>
                                <td>{{ $nv->email ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">
                                    Chưa có nhân viên nào
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột phải -->
    <div class="col-md-4">
        <!-- Trạng thái -->
        <div class="card">
            <div class="card-header {{ $nhaxe->trang_thai == 'hoat_dong' ? 'bg-success' : 'bg-danger' }}">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Trạng thái
                </h3>
            </div>
            <div class="card-body">
                @if($nhaxe->trang_thai == 'hoat_dong')
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Đang hoạt động</h5>
                        <p class="text-muted">Nhà xe đang hoạt động bình thường</p>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-lock mr-2"></i>Nhà xe bị khóa</h6>
                        <hr>
                        <p><strong>Lý do:</strong></p>
                        <p>{{ $nhaxe->ly_do_khoa }}</p>
                        <p class="mb-0">
                            <small class="text-muted">
                                <i class="fas fa-calendar mr-1"></i>
                                Khóa lúc: {{ \Carbon\Carbon::parse($nhaxe->ngay_khoa)->format('d/m/Y H:i') }}
                            </small>
                        </p>
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
                @if($nhaxe->trang_thai == 'hoat_dong')
                    <button type="button" class="btn btn-warning btn-block mb-2" 
                            data-toggle="modal" 
                            data-target="#lockModal">
                        <i class="fas fa-lock mr-2"></i>Khóa nhà xe
                    </button>
                @else
                    <form action="{{ route('admin.nha-xe.unlock', $nhaxe->ma_nha_xe) }}" 
                          method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block mb-2"
                                onclick="return confirm('Bạn có chắc muốn mở khóa nhà xe này?')">
                            <i class="fas fa-unlock mr-2"></i>Mở khóa nhà xe
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.nha-xe.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Tài khoản staff -->
        <div class="card">
            <div class="card-header bg-secondary">
                <h3 class="card-title">
                    <i class="fas fa-user-shield mr-2"></i>Tài khoản Staff
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($nhaxe->users as $user)
                    <div class="list-group-item">
                        <strong>{{ $user->fullname }}</strong><br>
                        <small class="text-muted">
                            <i class="fas fa-envelope mr-1"></i>{{ $user->email }}
                        </small><br>
                        @if($user->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-danger">Bị khóa</span>
                        @endif
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted">
                        Chưa có tài khoản nào
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal khóa nhà xe -->
@if($nhaxe->trang_thai == 'hoat_dong')
<div class="modal fade" id="lockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.nha-xe.lock', $nhaxe->ma_nha_xe) }}" method="POST" id="lockFormDetail">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-lock mr-2"></i>Khóa nhà xe
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Cảnh báo:</strong> Khi khóa nhà xe:
                        <ul class="mb-0 mt-2">
                            <li>Tất cả {{ $stats['tong_tai_khoan'] }} tài khoản nhân viên sẽ bị khóa</li>
                            <li><strong>Tài khoản bus_owner và staff sẽ bị HẠ CẤP xuống quyền USER</strong></li>
                            <li>Các tài khoản sẽ bị xóa liên kết với nhà xe</li>
                        </ul>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Lưu ý:</strong> Hệ thống sẽ tự động lưu thông tin gốc. Khi mở khóa, quyền và liên kết nhà xe sẽ được <strong>TỰ ĐỘNG KHÔI PHỤC</strong>.
                    </div>
                    <div class="form-group">
                        <label for="ly_do_khoa">Lý do khóa: <span class="text-danger">*</span></label>
                        <textarea 
                            class="form-control @error('ly_do_khoa') is-invalid @enderror" 
                            name="ly_do_khoa" 
                            id="ly_do_khoa" 
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
@endif

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Xử lý form khóa trong trang chi tiết
    $('#lockFormDetail').on('submit', function(e) {
        var textarea = $(this).find('textarea[name="ly_do_khoa"]');
        
        console.log('Submitting lock form from detail page');
        console.log('Lý do khóa:', textarea.val());
        
        if (!textarea.val() || textarea.val().trim() === '') {
            e.preventDefault();
            alert('Vui lòng nhập lý do khóa nhà xe!');
            textarea.focus();
            return false;
        }
        
        if (!confirm('Bạn có chắc chắn muốn khóa nhà xe này và tất cả tài khoản nhân viên?')) {
            e.preventDefault();
            return false;
        }
        
        console.log('Form submitted successfully');
        return true;
    });
});
</script>
@endpush
