@extends('layouts.admin')

@section('title', 'Chi tiết bình luận')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chi tiết bình luận</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.comments.index') }}">Bình luận</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
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
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Mã bình luận:</dt>
                            <dd class="col-sm-9">{{ $comment->ma_bl }}</dd>

                            <dt class="col-sm-3">Người dùng:</dt>
                            <dd class="col-sm-9">
                                <strong>{{ $comment->user->fullname ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $comment->user->email ?? '' }}</small>
                            </dd>

                            <dt class="col-sm-3">Chuyến xe:</dt>
                            <dd class="col-sm-9">
                                @if($comment->chuyenXe)
                                <i class="fas fa-map-marker-alt text-success"></i>
                                {{ $comment->chuyenXe->tramDi->ten_tram ?? 'N/A' }}
                                <i class="fas fa-arrow-right text-primary"></i>
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                {{ $comment->chuyenXe->tramDen->ten_tram ?? 'N/A' }}<br>
                                <small class="text-muted">
                                    <i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($comment->chuyenXe->ngay_di)->format('d/m/Y') }}
                                    <i class="far fa-clock ml-2"></i> {{ $comment->chuyenXe->gio_di ?? '' }}
                                </small>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </dd>

                            <dt class="col-sm-3">Nội dung:</dt>
                            <dd class="col-sm-9">{{ $comment->noi_dung }}</dd>

                            @if($comment->noi_dung_tl)
                            <dt class="col-sm-3">Phản hồi của Admin:</dt>
                            <dd class="col-sm-9">
                                <div class="alert alert-info">
                                    <i class="fas fa-reply"></i> {{ $comment->noi_dung_tl }}
                                    <br><small class="text-muted">
                                        <i class="far fa-clock"></i> {{ $comment->ngay_tl ? \Carbon\Carbon::parse($comment->ngay_tl)->format('d/m/Y H:i') : 'N/A' }}
                                    </small>
                                </div>
                            </dd>
                            @endif

                            <dt class="col-sm-3">Đánh giá:</dt>
                            <dd class="col-sm-9">
                                @if($comment->so_sao)
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$comment->so_sao)
                                    <i class="fas fa-star text-warning"></i>
                                    @else
                                    <i class="far fa-star text-muted"></i>
                                    @endif
                                    @endfor
                                    <br><small>({{ $comment->so_sao }}/5)</small>
                                    @else
                                    <small class="text-muted">Chưa đánh giá</small>
                                    @endif
                            </dd>

                            <dt class="col-sm-3">Ngày bình luận:</dt>
                            <dd class="col-sm-9">{{ \Carbon\Carbon::parse($comment->ngay_bl)->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-3">Trạng thái:</dt>
                            <dd class="col-sm-9">
                                @if($comment->trang_thai == 'cho_duyet')
                                <span class="badge badge-warning">Chờ duyệt</span>
                                @elseif($comment->trang_thai == 'da_duyet')
                                <span class="badge badge-success">Đã duyệt</span>
                                @else
                                <span class="badge badge-danger">Từ chối</span>
                                @endif
                            </dd>

                            @if($comment->ly_do_tu_choi)
                            <dt class="col-sm-3">Lý do từ chối:</dt>
                            <dd class="col-sm-9">{{ $comment->ly_do_tu_choi }}</dd>
                            @endif
                        </dl>

                        @if($comment->replies && $comment->replies->count() > 0)
                        <hr>
                        <h5>Phản hồi ({{ $comment->replies->count() }})</h5>
                        @foreach($comment->replies as $reply)
                        <div class="card bg-light mb-2">
                            <div class="card-body">
                                <p><strong>{{ $reply->user->fullname ?? 'N/A' }}</strong></p>
                                <p>{{ $reply->noi_dung_tl ?? $reply->noi_dung }}</p>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($reply->ngay_tl ?? $reply->ngay_bl)->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endforeach
                        @endif

                        <!-- Reply Form -->
                        <hr>
                        <h5>Trả lời bình luận</h5>
                        <form action="{{ route('staff.comments.reply', $comment->ma_bl) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Nội dung trả lời:</label>
                                <textarea class="form-control" name="noi_dung_tl" rows="3" required placeholder="Nhập nội dung trả lời..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-reply"></i> Gửi trả lời
                            </button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('staff.comments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>

                        <!-- Update Status Form -->
                        <form action="{{ route('staff.comments.update', $comment->ma_bl) }}" method="POST" style="display:inline-block; margin-left: 10px;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="trang_thai" value="{{ $comment->trang_thai == 'da_duyet' ? 'cho_duyet' : 'da_duyet' }}">
                            <button type="submit" class="btn {{ $comment->trang_thai == 'da_duyet' ? 'btn-warning' : 'btn-success' }}">
                                <i class="fas {{ $comment->trang_thai == 'da_duyet' ? 'fa-lock' : 'fa-unlock' }}"></i>
                                {{ $comment->trang_thai == 'da_duyet' ? 'Khóa' : 'Mở khóa' }}
                            </button>
                        </form>

                        <form action="{{ route('staff.comments.destroy', $comment->ma_bl) }}" method="POST" style="display:inline-block; margin-left: 10px;" onsubmit="return confirm('Xóa bình luận?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection