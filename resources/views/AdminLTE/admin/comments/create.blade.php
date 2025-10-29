@extends('layouts.admin')

@section('title', 'Thêm Bình luận')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Thêm Bình luận mới</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.comments.index') }}">Bình luận</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-comment-medical"></i> Form thêm bình luận
                        </h3>
                    </div>

                    <form action="{{ route('admin.comments.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <!-- Success/Error Messages -->
                            @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Người dùng -->
                            <div class="form-group">
                                <label for="user_id">
                                    <i class="fas fa-user text-primary"></i> Người dùng <span class="text-danger">*</span>
                                </label>
                                <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn người dùng --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->fullname }} ({{ $user->email }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Chọn người dùng để thêm bình luận thay mặt họ</small>
                            </div>

                            <!-- Chuyến xe -->
                            <div class="form-group">
                                <label for="chuyen_xe_id">
                                    <i class="fas fa-bus text-success"></i> Chuyến xe <span class="text-danger">*</span>
                                </label>
                                <select name="chuyen_xe_id" id="chuyen_xe_id" class="form-control select2 @error('chuyen_xe_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn chuyến xe --</option>
                                    @forelse($chuyenXe as $cx)
                                    <option value="{{ $cx->id }}" {{ old('chuyen_xe_id') == $cx->id ? 'selected' : '' }}>
                                        @if($cx->tramDi && $cx->tramDen)
                                        {{ $cx->tramDi->ten_tram }} → {{ $cx->tramDen->ten_tram }}
                                        @else
                                        Chuyến xe #{{ $cx->id }}
                                        @endif
                                        ({{ \Carbon\Carbon::parse($cx->ngay_di)->format('d/m/Y') }} - {{ $cx->gio_di }})
                                    </option>
                                    @empty
                                    <option value="" disabled>Không có chuyến xe nào</option>
                                    @endforelse
                                </select>
                                @error('chuyen_xe_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Chọn chuyến xe mà người dùng muốn đánh giá
                                    @if($chuyenXe->isEmpty())
                                    <span class="text-danger">(Chưa có chuyến xe nào trong hệ thống)</span>
                                    @else
                                    <span class="text-success">(Có {{ $chuyenXe->count() }} chuyến xe)</span>
                                    @endif
                                </small>
                            </div>

                            <!-- Số sao -->
                            <div class="form-group">
                                <label for="so_sao">
                                    <i class="fas fa-star text-warning"></i> Đánh giá <span class="text-danger">*</span>
                                </label>
                                <div class="rating-container">
                                    <div class="star-rating" id="starRating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="far fa-star star-icon" data-rating="{{ $i }}" style="font-size: 2rem; cursor: pointer; color: #ddd;"></i>
                                            @endfor
                                    </div>
                                    <input type="hidden" name="so_sao" id="so_sao" value="{{ old('so_sao', '') }}">
                                    <div class="mt-2">
                                        <span id="ratingText" class="badge badge-secondary">Chưa chọn</span>
                                    </div>
                                </div>
                                @error('so_sao')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Click vào sao để chọn đánh giá (1-5 sao)</small>
                            </div>

                            <!-- Nội dung -->
                            <div class="form-group">
                                <label for="noi_dung">
                                    <i class="fas fa-comment-dots text-info"></i> Nội dung bình luận <span class="text-danger">*</span>
                                </label>
                                <textarea name="noi_dung" id="noi_dung" rows="6"
                                    class="form-control @error('noi_dung') is-invalid @enderror"
                                    placeholder="Nhập nội dung bình luận..."
                                    required
                                    maxlength="1000">{{ old('noi_dung') }}</textarea>
                                <small class="form-text text-muted">
                                    <span id="charCount">0</span>/1000 ký tự
                                </small>
                                @error('noi_dung')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Thông báo tự động duyệt -->
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Đánh giá <strong>1-2 sao</strong>: Bình luận sẽ ở trạng thái <span class="badge badge-warning">Chờ duyệt</span> (cần review)</li>
                                    <li>Đánh giá <strong>3-5 sao</strong>: Bình luận sẽ tự động <span class="badge badge-success">Đã duyệt</span> và hiển thị</li>
                                </ul>
                            </div>

                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-save"></i> Lưu bình luận
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-block">
                                        <i class="fas fa-times"></i> Hủy bỏ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Star rating functionality
        const stars = document.querySelectorAll('.star-icon');
        const ratingInput = document.getElementById('so_sao');
        const ratingText = document.getElementById('ratingText');

        // Load old value if exists
        const oldRating = ratingInput.value;
        if (oldRating) {
            updateStars(parseInt(oldRating));
        }

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = rating;
                updateStars(rating);
            });

            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                highlightStars(rating);
            });
        });

        document.getElementById('starRating').addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingInput.value) || 0;
            updateStars(currentRating);
        });

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                    star.style.color = '#ffc107';
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                    star.style.color = '#ddd';
                }
            });

            // Update text
            const texts = ['', 'Rất tệ', 'Tệ', 'Trung bình', 'Tốt', 'Xuất sắc'];
            const colors = ['secondary', 'danger', 'warning', 'info', 'primary', 'success'];
            ratingText.textContent = rating > 0 ? texts[rating] + ' (' + rating + '/5)' : 'Chưa chọn';
            ratingText.className = 'badge badge-' + (rating > 0 ? colors[rating] : 'secondary');
        }

        function highlightStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#ddd';
                }
            });
        }

        // Character counter
        const textarea = document.getElementById('noi_dung');
        const charCount = document.getElementById('charCount');

        if (textarea && charCount) {
            textarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;

                if (count > 900) {
                    charCount.parentElement.className = 'form-text text-danger font-weight-bold';
                } else if (count > 700) {
                    charCount.parentElement.className = 'form-text text-warning';
                } else {
                    charCount.parentElement.className = 'form-text text-muted';
                }
            });

            // Initial count
            charCount.textContent = textarea.value.length;
        }

        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }
    });
</script>

<style>
    .star-icon {
        transition: all 0.2s ease;
    }

    .star-icon:hover {
        transform: scale(1.2);
    }

    .rating-container {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        text-align: center;
    }

    .card-primary:not(.card-outline)>.card-header {
        background-color: #007bff;
    }

    .form-group label {
        font-weight: 600;
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
@endsection