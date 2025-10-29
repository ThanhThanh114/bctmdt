@extends('layouts.admin')

@section('title', 'Chỉnh sửa Bình luận')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chỉnh sửa Bình luận</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.comments.index') }}">Bình luận</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin bình luận</h3>
                    </div>
                    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label>Người dùng</label>
                                <input type="text" class="form-control" value="{{ $comment->user->ho_ten ?? 'N/A' }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label>Chuyến xe</label>
                                <input type="text" class="form-control"
                                    value="{{ $comment->chuyenXe ? ($comment->chuyenXe->tramXeDi->ten_tram ?? '') . ' → ' . ($comment->chuyenXe->tramXeDen->ten_tram ?? '') : 'N/A' }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label>Nội dung bình luận</label>
                                <textarea class="form-control" rows="5" readonly>{{ $comment->noi_dung }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Đánh giá</label>
                                <div>
                                    @for($i = 1; $i <= 5; $i++) @if($i <=$comment->danh_gia)
                                        <i class="fas fa-star text-warning fa-2x"></i>
                                        @else
                                        <i class="far fa-star text-muted fa-2x"></i>
                                        @endif
                                        @endfor
                                        <span class="ml-2">({{ $comment->danh_gia }}/5)</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror"
                                    required>
                                    <option value="cho_duyet"
                                        {{ $comment->trang_thai == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="da_duyet"
                                        {{ $comment->trang_thai == 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
                                    <option value="tu_choi" {{ $comment->trang_thai == 'tu_choi' ? 'selected' : '' }}>
                                        Từ chối</option>
                                </select>
                                @error('trang_thai')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Ghi chú từ Admin</label>
                                <textarea name="admin_note" class="form-control" rows="3"
                                    placeholder="Nhập ghi chú (nếu có)">{{ old('admin_note', $comment->admin_note ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin thêm</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Ngày bình
                                luận:</strong><br>{{ \Carbon\Carbon::parse($comment->ngay_bl)->format('d/m/Y H:i:s') }}
                        </p>
                        <p><strong>Trạng thái hiện tại:</strong><br>
                            @if($comment->trang_thai == 'cho_duyet')
                            <span class="badge badge-warning">Chờ duyệt</span>
                            @elseif($comment->trang_thai == 'da_duyet')
                            <span class="badge badge-success">Đã duyệt</span>
                            @else
                            <span class="badge badge-danger">Từ chối</span>
                            @endif
                        </p>
                        @if($comment->parent_id)
                        <p><strong>Phản hồi cho:</strong><br>Bình luận #{{ $comment->parent_id }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection