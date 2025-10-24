@extends('layouts.admin')

@section('title', 'Đánh giá & Bình luận')

@section('page-title')
    Đánh giá Chuyến Xe: {{ $chuyenXe->tramDi->ten_tram }} → {{ $chuyenXe->tramDen->ten_tram }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('user.bookings.index') }}">Vé của tôi</a></li>
<li class="breadcrumb-item active">Bình luận</li>
@endsection

@push('styles')
<style>
    .rating-stars {
        display: inline-flex;
        gap: 5px;
        font-size: 24px;
        cursor: pointer;
    }
    .rating-stars i {
        color: #ddd;
        transition: color 0.2s;
    }
    .rating-stars i.active,
    .rating-stars i:hover,
    .rating-stars i:hover ~ i {
        color: #ffc107;
    }
    .comment-card {
        border-left: 4px solid #007bff;
        transition: all 0.3s;
    }
    .comment-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .reply-card {
        border-left: 4px solid #28a745;
        background-color: #f8f9fa;
    }
    .reply-card.border-primary {
        border-left: 4px solid #007bff;
        background-color: #e7f3ff;
    }
    .conversation-thread {
        margin-left: 20px;
        border-left: 2px solid #e0e0e0;
        padding-left: 20px;
    }
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
    .admin-badge {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .trip-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .rating-summary {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }
    .rating-summary .big-rating {
        font-size: 48px;
        font-weight: bold;
        color: #ffc107;
    }
    .rating-summary .stars {
        color: #ffc107;
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

<!-- Thông tin chuyến xe -->
<div class="trip-info-card shadow-lg">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="mb-3">
                <i class="fas fa-bus mr-2"></i>
                {{ $chuyenXe->tramDi->ten_tram }} → {{ $chuyenXe->tramDen->ten_tram }}
            </h4>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><i class="fas fa-calendar-alt mr-2"></i><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($chuyenXe->ngay_di)->format('d/m/Y') }}</p>
                    <p class="mb-2"><i class="fas fa-clock mr-2"></i><strong>Giờ đi:</strong> {{ date('H:i', strtotime($chuyenXe->gio_di)) }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><i class="fas fa-building mr-2"></i><strong>Nhà xe:</strong> {{ $chuyenXe->nhaXe->ten_nha_xe }}</p>
                    <p class="mb-2"><i class="fas fa-car mr-2"></i><strong>Loại xe:</strong> {{ $chuyenXe->loai_xe }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="rating-summary">
                @if($totalReviews > 0)
                    <div class="big-rating">{{ number_format($avgRating, 1) }}</div>
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avgRating))
                                <i class="fas fa-star"></i>
                            @elseif($i - $avgRating < 1)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="mb-0 mt-2 text-muted">{{ $totalReviews }} đánh giá</p>
                @else
                    <p class="mb-0"><i class="fas fa-comment-slash"></i><br>Chưa có đánh giá</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- Form viết bình luận mới -->
        @if($hasBooking)
            @if(!$userComment)
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit mr-2"></i>Viết đánh giá của bạn
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.binh-luan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="chuyen_xe_id" value="{{ $chuyenXe->id }}">
                        
                        <div class="form-group">
                            <label><strong>Đánh giá của bạn:</strong></label>
                            <div class="rating-stars" id="rating-input">
                                <i class="far fa-star" data-rating="1"></i>
                                <i class="far fa-star" data-rating="2"></i>
                                <i class="far fa-star" data-rating="3"></i>
                                <i class="far fa-star" data-rating="4"></i>
                                <i class="far fa-star" data-rating="5"></i>
                            </div>
                            <input type="hidden" name="so_sao" id="so_sao" value="" required>
                            @error('so_sao')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="noi_dung"><strong>Nội dung:</strong></label>
                            <textarea 
                                class="form-control @error('noi_dung') is-invalid @enderror" 
                                name="noi_dung" 
                                id="noi_dung" 
                                rows="5" 
                                placeholder="Chia sẻ trải nghiệm của bạn về chuyến xe này..."
                                required
                            >{{ old('noi_dung') }}</textarea>
                            @error('noi_dung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Đánh giá 1-2 sao sẽ được kiểm duyệt trước khi hiển thị.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane mr-2"></i>Gửi đánh giá
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                Bạn đã đánh giá chuyến xe này rồi. 
                @if($userComment->trang_thai == 'cho_duyet')
                    <span class="badge badge-warning">Đang chờ duyệt</span>
                @elseif($userComment->trang_thai == 'da_duyet')
                    <span class="badge badge-success">Đã đăng</span>
                @elseif($userComment->trang_thai == 'tu_choi')
                    <span class="badge badge-danger">Bị từ chối</span>
                @endif
            </div>
            @endif
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Bạn cần mua vé cho chuyến xe này mới có thể đánh giá!</strong>
            </div>
        @endif

        <!-- Danh sách bình luận -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-info text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-comments mr-2"></i>Đánh giá từ khách hàng
                    <span class="badge badge-light ml-2">{{ $binhLuan->total() }}</span>
                </h3>
            </div>
            <div class="card-body">
                @forelse($binhLuan as $comment)
                <div class="comment-card card mb-3">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="user-avatar mr-3">
                                {{ strtoupper(substr($comment->user->fullname, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">
                                            {{ $comment->user->fullname }}
                                            @if($comment->user->role === 'admin')
                                                <span class="admin-badge">ADMIN</span>
                                            @endif
                                        </h5>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $comment->so_sao)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <small class="text-muted ml-2">
                                                {{ \Carbon\Carbon::parse($comment->ngay_bl)->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    @if($comment->user_id === Auth::id() && $comment->replies->count() === 0)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <form action="{{ route('user.binh-luan.destroy', $comment->ma_bl) }}" method="POST" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash mr-2"></i>Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <p class="mb-0">{{ $comment->noi_dung }}</p>
                                
                                {{-- Debug: Số lượng replies --}}
                                @if(config('app.debug'))
                                <small class="text-info">
                                    <i class="fas fa-bug"></i> 
                                    Debug: {{ $comment->replies ? $comment->replies->count() : 0 }} phản hồi
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Hiển thị các trả lời từ Admin/Staff và User -->
                    @if($comment->replies && $comment->replies->count() > 0)
                        @foreach($comment->replies as $reply)
                        <div class="reply-card card mt-2 ml-4 {{ $reply->user_id === Auth::id() ? 'border-primary' : '' }}">
                            <div class="card-body py-2">
                                <div class="d-flex">
                                    @if($reply->user_id === Auth::id())
                                        {{-- Reply từ chính user --}}
                                        <div class="user-avatar mr-3" style="width: 40px; height: 40px; font-size: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            {{ strtoupper(substr($reply->user->fullname, 0, 1)) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <strong>{{ $reply->user->fullname }}</strong>
                                                <span class="badge badge-primary badge-sm">Bạn</span>
                                            </h6>
                                            <p class="mb-1">{{ $reply->noi_dung }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($reply->ngay_tl ?? $reply->ngay_bl)->diffForHumans() }}
                                            </small>
                                        </div>
                                    @else
                                        {{-- Reply từ nhà xe/admin --}}
                                        <div class="user-avatar mr-3" style="width: 40px; height: 40px; font-size: 16px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <strong>{{ $chuyenXe->nhaXe->ten_nha_xe }}</strong>
                                                <span class="admin-badge">NHÀ XE</span>
                                            </h6>
                                            <p class="mb-1">{{ $reply->noi_dung }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($reply->ngay_tl ?? $reply->ngay_bl)->diffForHumans() }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        {{-- Form trả lời của khách hàng --}}
                        @if($comment->user_id === Auth::id() && $hasBooking)
                        <div class="card mt-2 ml-4 border-info">
                            <div class="card-body py-2">
                                <button class="btn btn-sm btn-info btn-block reply-toggle-btn" type="button" 
                                        data-reply-form="reply-form-{{ $comment->ma_bl }}">
                                    <i class="fas fa-reply mr-1"></i> Trả lời
                                </button>
                                
                                <div class="reply-form-container mt-2" id="reply-form-{{ $comment->ma_bl }}" style="display: none;">
                                    <form action="{{ route('user.binh-luan.reply', $comment->ma_bl) }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <textarea 
                                                class="form-control form-control-sm" 
                                                name="noi_dung" 
                                                rows="2" 
                                                placeholder="Viết phản hồi của bạn..."
                                                required
                                            ></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-paper-plane mr-1"></i> Gửi
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary reply-cancel-btn" 
                                                data-reply-form="reply-form-{{ $comment->ma_bl }}">
                                            Hủy
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có đánh giá nào cho chuyến xe này.</p>
                </div>
                @endforelse

                <!-- Pagination -->
                @if($binhLuan->hasPages())
                <div class="mt-4">
                    {{ $binhLuan->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Rating stars interaction
    const stars = $('#rating-input i');
    const ratingInput = $('#so_sao');
    
    stars.on('mouseenter', function() {
        const rating = $(this).data('rating');
        stars.removeClass('fas').addClass('far');
        for(let i = 0; i < rating; i++) {
            $(stars[i]).removeClass('far').addClass('fas');
        }
    });
    
    $('#rating-input').on('mouseleave', function() {
        const currentRating = ratingInput.val();
        stars.removeClass('fas').addClass('far');
        if(currentRating) {
            for(let i = 0; i < currentRating; i++) {
                $(stars[i]).removeClass('far').addClass('fas');
            }
        }
    });
    
    stars.on('click', function() {
        const rating = $(this).data('rating');
        ratingInput.val(rating);
        stars.removeClass('fas active').addClass('far');
        for(let i = 0; i < rating; i++) {
            $(stars[i]).removeClass('far').addClass('fas active');
        }
    });
    
    // Reply button toggle - Simple jQuery version
    $('.reply-toggle-btn').on('click', function(e) {
        e.preventDefault();
        const formId = $(this).data('reply-form');
        const $form = $('#' + formId);
        
        console.log('Nút Trả lời được click, formId:', formId);
        
        if($form.length) {
            $form.slideToggle(300, function() {
                if($form.is(':visible')) {
                    $form.find('textarea').focus();
                    console.log('Form đã mở');
                }
            });
        } else {
            console.error('Không tìm thấy form với ID:', formId);
        }
    });
    
    // Cancel button
    $('.reply-cancel-btn').on('click', function(e) {
        e.preventDefault();
        const formId = $(this).data('reply-form');
        $('#' + formId).slideUp(300);
    });
});
</script>
@endpush
