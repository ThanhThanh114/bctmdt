@extends('layouts.admin')

@section('title', 'Chi tiết Bình luận')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chi tiết Bình luận #{{ $binhluan->ma_bl }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.binhluan.index') }}">Bình luận</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <!-- Chat Box -->
                <div class="card direct-chat direct-chat-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-comments"></i> Cuộc hội thoại với khách hàng
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-primary">{{ $binhluan->replies->count() + 1 }} tin nhắn</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Conversations -->
                        <div class="direct-chat-messages" style="height: 500px; overflow-y: auto;" id="chatMessages">
                            <!-- Bình luận gốc từ khách hàng -->
                            <div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">
                                        <i class="fas fa-user text-primary"></i>
                                        {{ $binhluan->user->fullname ?? 'N/A' }}
                                    </span>
                                    <span class="direct-chat-timestamp float-right">
                                        {{ \Carbon\Carbon::parse($binhluan->ngay_bl)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <img class="direct-chat-img"
                                    src="{{ $binhluan->user->avatar ?? asset('assets/img/default-avatar.png') }}"
                                    alt="User Image">
                                <div class="direct-chat-text">
                                    <div class="mb-2">
                                        <strong>Đánh giá:</strong>
                                        @for($i = 1; $i <= 5; $i++) @if($i <=$binhluan->so_sao)
                                            <i class="fas fa-star text-warning"></i>
                                            @else
                                            <i class="far fa-star text-muted"></i>
                                            @endif
                                            @endfor
                                            <span class="badge badge-info ml-2">{{ $binhluan->so_sao }}/5</span>
                                    </div>
                                    <p class="mb-0">{{ $binhluan->noi_dung }}</p>

                                    @if($binhluan->trang_thai == 'cho_duyet')
                                    <span class="badge badge-warning mt-2">
                                        <i class="fas fa-clock"></i> Chờ duyệt
                                    </span>
                                    @elseif($binhluan->trang_thai == 'da_duyet')
                                    <span class="badge badge-success mt-2">
                                        <i class="fas fa-check"></i> Đã duyệt
                                    </span>
                                    @else
                                    <span class="badge badge-danger mt-2">
                                        <i class="fas fa-times"></i> Từ chối
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Các phản hồi -->
                            @foreach($binhluan->replies as $reply)
                            @if($reply->user_id == auth()->id() || $reply->nv_id)
                            <!-- Tin nhắn từ Admin (bên phải) -->
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-right">
                                        <i class="fas fa-user-shield text-success"></i>
                                        {{ $reply->user->fullname ?? 'Admin' }}
                                    </span>
                                    <span class="direct-chat-timestamp float-left">
                                        {{ \Carbon\Carbon::parse($reply->ngay_bl)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <img class="direct-chat-img" src="{{ asset('assets/img/admin-avatar.png') }}"
                                    alt="Admin Image">
                                <div class="direct-chat-text bg-success">
                                    {{ $reply->noi_dung }}
                                </div>
                            </div>
                            @else
                            <!-- Tin nhắn từ khách hàng (bên trái) -->
                            <div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">
                                        <i class="fas fa-user text-primary"></i>
                                        {{ $reply->user->fullname ?? 'N/A' }}
                                    </span>
                                    <span class="direct-chat-timestamp float-right">
                                        {{ \Carbon\Carbon::parse($reply->ngay_bl)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <img class="direct-chat-img"
                                    src="{{ $reply->user->avatar ?? asset('assets/img/default-avatar.png') }}"
                                    alt="User Image">
                                <div class="direct-chat-text">
                                    {{ $reply->noi_dung }}
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Form trả lời -->
                    <div class="card-footer">
                        <form action="{{ route('admin.binhluan.reply', $binhluan->ma_bl) }}" method="POST" id="replyForm">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="noi_dung" class="font-weight-bold">
                                    <i class="fas fa-comment-dots"></i> Nội dung trả lời <span class="text-danger">*</span>
                                </label>
                                <textarea name="noi_dung"
                                    id="noi_dung"
                                    class="form-control @error('noi_dung') is-invalid @enderror"
                                    placeholder="Nhập câu trả lời cho khách hàng..."
                                    rows="4"
                                    required
                                    maxlength="1000"></textarea>
                                <small class="form-text text-muted">
                                    <span id="charCount">0</span>/1000 ký tự
                                </small>
                                @error('noi_dung')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane"></i> Gửi trả lời
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="clearForm()">
                                        <i class="fas fa-eraser"></i> Xóa
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-user-shield"></i> Trả lời với tư cách Admin
                                </small>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Nút thao tác với bình luận gốc -->
                @if($binhluan->trang_thai == 'cho_duyet')
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">
                            <i class="fas fa-tasks"></i> Duyệt bình luận
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('admin.binhluan.approve', $binhluan->ma_bl) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block"
                                        onclick="return confirm('Duyệt bình luận này?')">
                                        <i class="fas fa-check"></i> Duyệt bình luận
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                    data-target="#rejectModal">
                                    <i class="fas fa-times"></i> Từ chối bình luận
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar thông tin -->
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Thông tin chuyến xe
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($binhluan->chuyenXe)
                        <div class="mb-3">
                            <h5 class="text-center">
                                <i class="fas fa-map-marker-alt text-success"></i>
                                {{ $binhluan->chuyenXe->tramDi->ten_tram ?? 'N/A' }}
                            </h5>
                            <div class="text-center my-2">
                                <i class="fas fa-arrow-down fa-2x text-primary"></i>
                            </div>
                            <h5 class="text-center">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                {{ $binhluan->chuyenXe->tramDen->ten_tram ?? 'N/A' }}
                            </h5>
                        </div>
                        <hr>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th><i class="fas fa-calendar"></i> Ngày đi:</th>
                                <td>{{ \Carbon\Carbon::parse($binhluan->chuyenXe->ngay_di)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-clock"></i> Giờ đi:</th>
                                <td><strong>{{ $binhluan->chuyenXe->gio_di }}</strong></td>
                            </tr>
                        </table>
                        @else
                        <p class="text-muted">Không tìm thấy thông tin chuyến xe</p>
                        @endif
                    </div>
                </div>

                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i> Thông tin khách hàng
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($binhluan->user)
                        <div class="text-center mb-3">
                            <img src="{{ $binhluan->user->avatar ?? asset('assets/img/default-avatar.png') }}"
                                alt="User Avatar" class="img-circle elevation-2" style="width: 80px; height: 80px;">
                        </div>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th><i class="fas fa-user"></i> Họ tên:</th>
                                <td>{{ $binhluan->user->fullname }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-envelope"></i> Email:</th>
                                <td>{{ $binhluan->user->email }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-phone"></i> SĐT:</th>
                                <td>{{ $binhluan->user->so_dien_thoai ?? 'Chưa cập nhật' }}</td>
                            </tr>
                        </table>
                        @else
                        <p class="text-muted">Không tìm thấy thông tin người dùng</p>
                        @endif
                    </div>
                </div>

                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog"></i> Thao tác
                        </h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.binhluan.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>

                        <!-- Nút Khóa/Mở khóa -->
                        <form action="{{ route('admin.binhluan.toggle-lock', $binhluan->ma_bl) }}" method="POST" class="mt-2">
                            @csrf
                            @if($binhluan->trang_thai === 'tu_choi')
                            <button type="submit" class="btn btn-success btn-block"
                                onclick="return confirm('Bạn có chắc muốn mở khóa bình luận này?')">
                                <i class="fas fa-lock-open"></i> Mở khóa bình luận
                            </button>
                            @else
                            <button type="submit" class="btn btn-warning btn-block"
                                onclick="return confirm('Bạn có chắc muốn khóa bình luận này?')">
                                <i class="fas fa-lock"></i> Khóa bình luận
                            </button>
                            @endif
                        </form>

                        <!-- Nút Xóa -->
                        <form action="{{ route('admin.binhluan.destroy', $binhluan->ma_bl) }}" method="POST"
                            class="mt-2" onsubmit="return confirm('⚠️ Cảnh báo: Xóa bình luận sẽ xóa cả các phản hồi! Bạn có chắc muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Xóa vĩnh viễn
                            </button>
                        </form>

                        <!-- Thông tin trạng thái -->
                        <div class="mt-3 p-3 bg-light rounded">
                            <h6 class="font-weight-bold">
                                <i class="fas fa-info-circle"></i> Trạng thái hiện tại:
                            </h6>
                            @if($binhluan->trang_thai === 'tu_choi')
                            <span class="badge badge-danger badge-lg">
                                <i class="fas fa-lock"></i> Đã khóa
                            </span>
                            <small class="d-block mt-2 text-muted">
                                Bình luận này đã bị khóa và không hiển thị công khai
                            </small>
                            @elseif($binhluan->trang_thai === 'da_duyet')
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-check"></i> Đã duyệt
                            </span>
                            <small class="d-block mt-2 text-muted">
                                Bình luận đang hiển thị công khai
                            </small>
                            @else
                            <span class="badge badge-warning badge-lg">
                                <i class="fas fa-clock"></i> Chờ duyệt
                            </span>
                            <small class="d-block mt-2 text-muted">
                                Bình luận cần được duyệt trước khi hiển thị
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal từ chối -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.binhluan.reject', $binhluan->ma_bl) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">Từ chối bình luận</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea name="ly_do_tu_choi" class="form-control" rows="4" required
                            placeholder="Nhập lý do từ chối bình luận này..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto scroll to bottom of chat on page load
    document.addEventListener('DOMContentLoaded', function() {
        var chatMessages = document.getElementById('chatMessages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Character counter for reply textarea
        var textarea = document.getElementById('noi_dung');
        var charCount = document.getElementById('charCount');

        if (textarea && charCount) {
            // Update on input
            textarea.addEventListener('input', function() {
                var count = this.value.length;
                charCount.textContent = count;

                // Change color based on character count
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

        // Auto-focus on textarea
        if (textarea) {
            textarea.focus();
        }
    });

    function clearForm() {
        var textarea = document.querySelector('textarea[name="noi_dung"]');
        var charCount = document.getElementById('charCount');

        if (textarea) {
            textarea.value = '';
            textarea.focus();
        }

        if (charCount) {
            charCount.textContent = '0';
            charCount.parentElement.className = 'form-text text-muted';
        }
    }

    // Confirm before leaving if there's unsaved content
    var form = document.getElementById('replyForm');
    var textarea = document.getElementById('noi_dung');

    if (form && textarea) {
        window.addEventListener('beforeunload', function(e) {
            if (textarea.value.trim().length > 0) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Don't show warning after form submission
        form.addEventListener('submit', function() {
            window.removeEventListener('beforeunload', null);
        });
    }
</script>

<style>
    /* Chat Messages Styling */
    .direct-chat-text {
        margin: 5px 0 10px 50px;
        padding: 10px 15px;
        background: #d2d6de;
        border-radius: 10px;
        position: relative;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Speech bubble arrow for left messages */
    .direct-chat-text:before {
        content: '';
        position: absolute;
        left: -8px;
        top: 10px;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 8px 8px 8px 0;
        border-color: transparent #d2d6de transparent transparent;
    }

    .direct-chat-msg.right .direct-chat-text {
        margin-left: 0;
        margin-right: 50px;
        background: #28a745;
        color: white;
    }

    /* Speech bubble arrow for right messages */
    .direct-chat-msg.right .direct-chat-text:before {
        left: auto;
        right: -8px;
        border-width: 8px 0 8px 8px;
        border-color: transparent transparent transparent #28a745;
    }

    .direct-chat-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        float: left;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .direct-chat-msg.right .direct-chat-img {
        float: right;
    }

    .direct-chat-infos {
        display: block;
        margin-bottom: 5px;
        font-size: 0.9em;
        color: #6c757d;
    }

    .direct-chat-messages {
        padding: 15px;
        background: #f9f9f9;
    }

    .direct-chat-msg {
        margin-bottom: 20px;
        clear: both;
    }

    /* Form styling improvements */
    #noi_dung {
        resize: vertical;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    #noi_dung:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .card-footer {
        background-color: #fff;
        border-top: 2px solid #e9ecef;
    }

    /* Button hover effects */
    .btn {
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    }

    /* Smooth scroll */
    .direct-chat-messages {
        scroll-behavior: smooth;
    }

    /* Badge styling */
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.6em;
    }

    /* Alert animations */
    .alert {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection