@extends('layouts.admin')

@section('title', 'Quản lý Bình luận')

@section('page-title', 'Quản lý Bình luận & Đánh giá')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Bình luận</li>
@endsection

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

<!-- Statistics Cards -->
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Tổng bình luận</p>
            </div>
            <div class="icon">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['cho_duyet'] }}</h3>
                <p>Chờ duyệt</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['da_duyet'] }}</h3>
                <p>Đã duyệt</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['tu_choi'] }}</h3>
                <p>Từ chối</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách bình luận</h3>
        <div class="card-tools">
            <a href="{{ route('admin.comments.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm bình luận
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-body border-bottom">
        <form method="GET" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </div>

            <div class="form-group mr-2">
                <select name="trang_thai" class="form-control">
                    <option value="all" {{ request('trang_thai') == 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                    <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
                </select>
            </div>

            <div class="form-group mr-2">
                <select name="so_sao" class="form-control">
                    <option value="all" {{ request('so_sao') == 'all' ? 'selected' : '' }}>Tất cả đánh giá</option>
                    <option value="5" {{ request('so_sao') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 sao)</option>
                    <option value="4" {{ request('so_sao') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 sao)</option>
                    <option value="3" {{ request('so_sao') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 sao)</option>
                    <option value="2" {{ request('so_sao') == '2' ? 'selected' : '' }}>⭐⭐ (2 sao)</option>
                    <option value="1" {{ request('so_sao') == '1' ? 'selected' : '' }}>⭐ (1 sao)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mr-2">
                <i class="fas fa-search"></i> Tìm
            </button>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Chuyến xe</th>
                    <th>Nhà xe</th>
                    <th>Đánh giá</th>
                    <th>Trạng thái</th>
                    <th>Ngày</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                <tr>
                    <td>
                        <strong>{{ $comment->user->fullname }}</strong><br>
                        <small class="text-muted">{{ $comment->user->email }}</small>
                    </td>
                    <td>
                        <i class="fas fa-map-marker-alt text-success"></i>
                        {{ $comment->chuyenXe->tramDi->ten_tram }}
                        <i class="fas fa-arrow-right mx-1"></i>
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        {{ $comment->chuyenXe->tramDen->ten_tram }}<br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($comment->chuyenXe->ngay_di)->format('d/m/Y') }}
                            - {{ date('H:i', strtotime($comment->chuyenXe->gio_di)) }}
                        </small>
                    </td>
                    <td>
                        <strong>{{ $comment->chuyenXe->nhaXe->ten_nha_xe }}</strong>
                    </td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $comment->so_sao)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </td>
                    <td>
                        @if($comment->trang_thai == 'cho_duyet')
                            <span class="badge badge-warning">Chờ duyệt</span>
                        @elseif($comment->trang_thai == 'da_duyet')
                            <span class="badge badge-success">Đã duyệt</span>
                        @else
                            <span class="badge badge-danger">Từ chối</span>
                        @endif
                    </td>
                    <td>
                        <small>{{ \Carbon\Carbon::parse($comment->ngay_bl)->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.comments.show', $comment->ma_bl) }}"
                               class="btn btn-sm btn-info" title="Xem & Trả lời">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($comment->trang_thai == 'cho_duyet')
                            <form action="{{ route('admin.comments.approve', $comment->ma_bl) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Duyệt">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-warning" title="Từ chối"
                                    onclick="rejectComment({{ $comment->ma_bl }}, '{{ $comment->user->fullname }}')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif

                            <form action="{{ route('admin.comments.destroy', $comment->ma_bl) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa"
                                        onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-comment-slash fa-3x mb-3"></i>
                        <p>Không có bình luận nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($comments->hasPages())
    <div class="card-footer">
        {{ $comments->links() }}
    </div>
    @endif
</div>

<!-- Modal duyệt bình luận -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" id="approveForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Duyệt bình luận</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn duyệt bình luận của <strong id="approveUserName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i>Duyệt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal từ chối bình luận -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" id="rejectForm">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Từ chối bình luận</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn từ chối bình luận của <strong id="rejectUserName"></strong>?</p>
                    <div class="form-group">
                        <label for="ly_do_tu_choi">Lý do từ chối:</label>
                        <textarea class="form-control" name="ly_do_tu_choi" id="ly_do_tu_choi" rows="3" required
                                  placeholder="Nhập lý do từ chối bình luận này..."></textarea>
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

<script>
function approveComment(commentId, userName) {
    document.getElementById('approveForm').action = `/admin/comments/${commentId}/approve`;
    document.getElementById('approveUserName').textContent = userName;
    $('#approveModal').modal('show');
}

function rejectComment(commentId, userName) {
    document.getElementById('rejectForm').action = `/admin/comments/${commentId}/reject`;
    document.getElementById('rejectUserName').textContent = userName;
    $('#rejectModal').modal('show');
}

// Handle form submissions with AJAX
$(document).ready(function() {
    $('#approveForm, #rejectForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var formData = new FormData(form[0]);

        // Add CSRF token
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Close modal
                $('#approveModal, #rejectModal').modal('hide');
                // Reload page after short delay
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                alert('Có lỗi xảy ra khi xử lý yêu cầu. Vui lòng thử lại.');
            }
        });
    });

    // Handle inline form submissions (approve and delete)
    $('form.d-inline').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var formData = new FormData(form[0]);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method') || 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Reload page after short delay
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                alert('Có lỗi xảy ra khi xử lý yêu cầu. Vui lòng thử lại.');
            }
        });
    });
});
</script>

@endsection