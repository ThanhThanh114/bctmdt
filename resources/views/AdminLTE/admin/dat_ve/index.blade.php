@extends('layouts.admin')

@section('title', 'Quản lý Đặt vé')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Đặt vé</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Đặt vé</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Tổng đặt vé</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['da_dat'] }}</h3>
                        <p>Đã đặt</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['da_thanh_toan'] }}</h3>
                        <p>Đã thanh toán</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['da_huy'] }}</h3>
                        <p>Đã hủy</p>
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
                <h3 class="card-title">Danh sách đặt vé</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.datve.create') }}" class="btn btn-primary btn-sm mr-1">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                    <button type="button" class="btn btn-success btn-sm" onclick="exportData()">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.datve.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="ma_ve" class="form-control" placeholder="Mã vé"
                                value="{{ request('ma_ve') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="trang_thai" class="form-control">
                                <option value="">-- Trạng thái --</option>
                                <option value="Đã đặt" {{ request('trang_thai') == 'Đã đặt' ? 'selected' : '' }}>Đã đặt
                                </option>
                                <option value="Đã thanh toán"
                                    {{ request('trang_thai') == 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán
                                </option>
                                <option value="Đã hủy" {{ request('trang_thai') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="ngay_dat" class="form-control" value="{{ request('ngay_dat') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="user" class="form-control" placeholder="Tên người đặt"
                                value="{{ request('user') }}">
                        </div>
                        <div class="col-md-2">
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
                                <th>Mã vé</th>
                                <th>Khách hàng</th>
                                <th>Chuyến xe</th>
                                <th>Số ghế</th>
                                <th>Giá vé</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datVe as $item)
                            <tr>
                                <td><strong>{{ $item->ma_ve }}</strong></td>
                                <td>
                                    {{ $item->user->fullname ?? 'N/A' }}<br>
                                    <small class="text-muted">{{ $item->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    @if($item->chuyenXe)
                                    <strong>
                                        {{ $item->chuyenXe->tramDi->ten_tram ?? 'N/A' }}
                                        →
                                        {{ $item->chuyenXe->tramDen->ten_tram ?? 'N/A' }}
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $item->chuyenXe->ngay_di ? \Carbon\Carbon::parse($item->chuyenXe->ngay_di)->format('d/m/Y') : 'N/A' }}
                                        -
                                        {{ $item->chuyenXe->gio_di ? \Carbon\Carbon::parse($item->chuyenXe->gio_di)->format('H:i') : 'N/A' }}
                                    </small>
                                    @else
                                    N/A
                                    @endif
                                </td>
                                <td><span class="badge badge-info">{{ $item->so_ghe }}</span></td>
                                <td><strong>{{ $item->chuyenXe ? number_format($item->chuyenXe->gia_ve, 0, ',', '.') : '0' }}
                                        VNĐ</strong></td>
                                <td>{{ $item->ngay_dat ? $item->ngay_dat->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    @if($item->trang_thai == 'Đã đặt')
                                    <span class="badge badge-warning">{{ $item->trang_thai }}</span>
                                    @elseif($item->trang_thai == 'Đã thanh toán')
                                    <span class="badge badge-success">{{ $item->trang_thai }}</span>
                                    @else
                                    <span class="badge badge-danger">{{ $item->trang_thai }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.datve.show', $item->id) }}" class="btn btn-sm btn-info"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($item->trang_thai !== 'Đã hủy' && $item->trang_thai !== 'Đã thanh toán')
                                    <form action="{{ route('admin.datve.destroy', $item->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Bạn có chắc muốn hủy vé này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hủy vé">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="text-muted mb-0">
                            Hiển thị {{ $datVe->firstItem() ?? 0 }} đến {{ $datVe->lastItem() ?? 0 }}
                            trong tổng số {{ $datVe->total() }} kết quả
                        </p>
                    </div>
                    <div>
                        {{ $datVe->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = "{{ route('admin.datve.export') }}?" + params.toString();
}
</script>
@endsection