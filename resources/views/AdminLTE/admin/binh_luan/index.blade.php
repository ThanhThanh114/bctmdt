@extends('layouts.admin')

@section('title', 'Quản lý Bình luận')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Bình luận</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bình luận</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
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

        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-comments"></i> Danh sách bình luận
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.binhluan.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Thêm bình luận
                    </a>
                </div>
            </div>
            <div class="card-body">
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

                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.binhluan.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="trang_thai" class="form-control">
                                <option value="">-- Trạng thái --</option>
                                <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>
                                    Chờ duyệt</option>
                                <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã
                                    duyệt</option>
                                <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Từ
                                    chối</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="user" class="form-control" placeholder="Tên người dùng"
                                value="{{ request('user') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="ngay_bl" class="form-control" value="{{ request('ngay_bl') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Người dùng</th>
                                <th width="25%">Chuyến xe</th>
                                <th width="30%">Nội dung</th>
                                <th>Đánh giá</th>
                                <th>Ngày BL</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($binhLuan as $item)
                            <tr>
                                <td>{{ $item->ma_bl }}</td>
                                <td>
                                    <strong>{{ $item->user->fullname ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $item->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    @if($item->chuyenXe)
                                    <i class="fas fa-map-marker-alt text-success"></i>
                                    {{ $item->chuyenXe->tramDi->ten_tram ?? 'N/A' }}
                                    <i class="fas fa-arrow-right text-primary"></i>
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    {{ $item->chuyenXe->tramDen->ten_tram ?? 'N/A' }}<br>
                                    <small class="text-muted">
                                        <i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($item->chuyenXe->ngay_di)->format('d/m/Y') }}
                                        <i class="far fa-clock ml-2"></i> {{ $item->chuyenXe->gio_di ?? '' }}
                                    </small>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="mb-1">{{ Str::limit($item->noi_dung, 100) }}</p>
                                    @if($item->replies && $item->replies->count() > 0)
                                    <small class="text-info">
                                        <i class="fas fa-reply"></i> {{ $item->replies->count() }} phản hồi
                                    </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->so_sao)
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <=$item->so_sao)
                                        <i class="fas fa-star text-warning"></i>
                                        @else
                                        <i class="far fa-star text-muted"></i>
                                        @endif
                                        @endfor
                                        <br><small>({{ $item->so_sao }}/5)</small>
                                        @else
                                        <small class="text-muted">Chưa đánh giá</small>
                                        @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->ngay_bl)->format('d/m/Y') }}</td>
                                <td>
                                    @if($item->trang_thai == 'cho_duyet')
                                    <span class="badge badge-warning">Chờ duyệt</span>
                                    @elseif($item->trang_thai == 'da_duyet')
                                    <span class="badge badge-success">Đã duyệt</span>
                                    @else
                                    <span class="badge badge-danger">Từ chối</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.binhluan.show', $item->ma_bl) }}"
                                        class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($item->trang_thai === 'cho_duyet')
                                    <form action="{{ route('admin.binhluan.approve', $item->ma_bl) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Duyệt">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.binhluan.destroy', $item->ma_bl) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $binhLuan->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection