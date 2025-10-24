@extends('layouts.admin')

@section('title', 'Chi tiết Bình luận')

@section('page-title', 'Chi tiết Bình luận')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('staff.binh-luan.index') }}">Bình luận</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
@endsection

@push('styles')
<style>
    .comment-card {
        border-left: 4px solid #007bff;
    }
    .reply-card {
        border-left: 4px solid #28a745;
        background-color: #f8f9fa;
    }
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
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

<div class="row">
    <div class="col-md-8">
        <!-- Thông tin chuyến xe -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="fas fa-bus mr-2"></i>Thông tin chuyến xe
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tuyến:</strong> {{ $binhLuan->chuyenXe->tramDi->ten_tram }} → {{ $binhLuan->chuyenXe->tramDen->ten_tram }}</p>
                        <p><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($binhLuan->chuyenXe->ngay_di)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Giờ đi:</strong> {{ date('H:i', strtotime($binhLuan->chuyenXe->gio_di)) }}</p>
                        <p><strong>Loại xe:</strong> {{ $binhLuan->chuyenXe->loai_xe }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bình luận chính -->
        <div class="card comment-card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title">
                    <i class="fas fa-comment mr-2"></i>Bình luận từ khách hàng
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="user-avatar mr-3">
                        {{ strtoupper(substr($binhLuan->user->fullname, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">{{ $binhLuan->user->fullname }}</h5>
                        <small class="text-muted">{{ $binhLuan->user->email }}</small>
                        <div class="mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $binhLuan->so_sao)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            <span class="ml-2">({{ $binhLuan->so_sao }}/5 sao)</span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-light">
                    <p class="mb-0">{{ $binhLuan->noi_dung }}</p>
                </div>

                <small class="text-muted">
                    <i class="fas fa-clock mr-1"></i>
                    {{ \Carbon\Carbon::parse($binhLuan->ngay_bl)->format('d/m/Y H:i') }}
                    ({{ \Carbon\Carbon::parse($binhLuan->ngay_bl)->diffForHumans() }})
                </small>
            </div>
        </div>

        <!-- Các câu trả lời -->
        @forelse($binhLuan->replies as $reply)
        <div class="card reply-card ml-4">
            <div class="card-body">
                <div class="d-flex">
                    @if($reply->user->role === 'staff' || $reply->user->role === 'nhanvien')
                        {{-- Staff/NhanVien reply --}}
                        <div class="user-avatar mr-3" style="width: 50px; height: 50px; font-size: 18px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <strong>{{ $binhLuan->chuyenXe->nhaXe->ten_nha_xe }}</strong>
                                <span class="badge badge-success">NHÀ XE</span>
                            </h6>
                            <p class="mb-1">{{ $reply->noi_dung }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($reply->ngay_tl ?? $reply->ngay_bl)->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    @else
                        {{-- User reply --}}
                        <div class="user-avatar mr-3" style="width: 50px; height: 50px; font-size: 18px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            {{ strtoupper(substr($reply->user->fullname, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <strong>{{ $reply->user->fullname }}</strong>
                                <span class="badge badge-info">KHÁCH HÀNG</span>
                            </h6>
                            <p class="mb-1">{{ $reply->noi_dung }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($reply->ngay_tl ?? $reply->ngay_bl)->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="card ml-4">
            <div class="card-body text-center text-muted">
                <i class="fas fa-comment-slash fa-2x mb-2"></i>
                <p class="mb-0">Chưa có phản hồi nào</p>
            </div>
        </div>
        @endforelse

        <!-- Form trả lời -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">
                    <i class="fas fa-reply mr-2"></i>Trả lời khách hàng
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.binh-luan.reply', $binhLuan->ma_bl) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="noi_dung">Nội dung trả lời:</label>
                        <textarea 
                            class="form-control @error('noi_dung') is-invalid @enderror" 
                            name="noi_dung" 
                            id="noi_dung" 
                            rows="5" 
                            placeholder="Nhập nội dung trả lời..."
                            required
                        ></textarea>
                        @error('noi_dung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane mr-2"></i>Gửi trả lời
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Trạng thái -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Trạng thái</h3>
            </div>
            <div class="card-body">
                <p>
                    @if($binhLuan->trang_thai == 'cho_duyet')
                        <span class="badge badge-warning badge-lg">Chờ duyệt</span>
                    @elseif($binhLuan->trang_thai == 'da_duyet')
                        <span class="badge badge-success badge-lg">Đã duyệt</span>
                    @else
                        <span class="badge badge-danger badge-lg">Từ chối</span>
                    @endif
                </p>

                @if($binhLuan->ngay_duyet)
                <p class="mb-0">
                    <small class="text-muted">
                        Duyệt lúc: {{ \Carbon\Carbon::parse($binhLuan->ngay_duyet)->format('d/m/Y H:i') }}
                    </small>
                </p>
                @endif

                @if($binhLuan->ly_do_tu_choi)
                <div class="alert alert-danger mt-2">
                    <strong>Lý do từ chối:</strong><br>
                    {{ $binhLuan->ly_do_tu_choi }}
                </div>
                @endif
            </div>
        </div>

        <!-- Thao tác -->
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h3 class="card-title">Thao tác</h3>
            </div>
            <div class="card-body p-2">
                @if($binhLuan->trang_thai == 'cho_duyet')
                <form action="{{ route('staff.binh-luan.approve', $binhLuan->ma_bl) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-check mr-2"></i>Duyệt bình luận
                    </button>
                </form>

                <button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#rejectModal">
                    <i class="fas fa-times mr-2"></i>Từ chối
                </button>
                @endif

                <form action="{{ route('staff.binh-luan.destroy', $binhLuan->ma_bl) }}" method="POST" 
                      onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-block mb-2">
                        <i class="fas fa-trash mr-2"></i>Xóa bình luận
                    </button>
                </form>

                <a href="{{ route('staff.binh-luan.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal từ chối -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staff.binh-luan.reject', $binhLuan->ma_bl) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Từ chối bình luận</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ly_do_tu_choi">Lý do từ chối:</label>
                        <textarea 
                            class="form-control" 
                            name="ly_do_tu_choi" 
                            id="ly_do_tu_choi" 
                            rows="4" 
                            required
                            placeholder="Nhập lý do từ chối bình luận này..."
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-2"></i>Từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
