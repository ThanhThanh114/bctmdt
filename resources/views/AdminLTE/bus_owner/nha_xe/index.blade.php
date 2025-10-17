@extends('layouts.admin')

@section('title', 'Quản lý Nhà xe')
@section('page-title', 'Quản lý Nhà xe')
@section('breadcrumb', 'Danh sách Nhà xe')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $statistics['total'] }}</h3>
                    <p>Tổng số nhà xe</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $statistics['total_trips'] }}</h3>
                    <p>Tổng chuyến xe</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bus"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $statistics['total_bookings'] }}</h3>
                    <p>Vé đã bán</p>
                </div>
                <div class="icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">
                <i class="fas fa-list mr-2"></i>
                Danh sách Nhà xe
            </h3>
            <div class="card-tools">
                <a href="{{ route('bus-owner.nha-xe.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus-circle"></i> Thêm nhà xe mới
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <form action="{{ route('bus-owner.nha-xe.index') }}" method="GET" class="form-inline">
                        <div class="input-group input-group-lg" style="width: 100%;">
                            <input type="text" name="search" class="form-control"
                                placeholder="Tìm kiếm theo tên, địa chỉ, SĐT, email..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                @if(request('search'))
                                <a href="{{ route('bus-owner.nha-xe.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Xóa lọc
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <form action="{{ route('bus-owner.nha-xe.index') }}" method="GET" class="form-inline float-right">
                        <label class="mr-2">Hiển thị:</label>
                        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="ml-2">kết quả</span>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th width="80" class="text-center">
                                <a href="{{ route('bus-owner.nha-xe.index', array_merge(request()->all(), ['sort_by' => 'ma_nha_xe', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="text-white">
                                    Mã
                                    @if(request('sort_by') == 'ma_nha_xe')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('bus-owner.nha-xe.index', array_merge(request()->all(), ['sort_by' => 'ten_nha_xe', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="text-white">
                                    Tên nhà xe
                                    @if(request('sort_by') == 'ten_nha_xe')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Địa chỉ</th>
                            <th width="130">Số điện thoại</th>
                            <th width="200">Email</th>
                            <th width="100" class="text-center">Chuyến xe</th>
                            <th width="180" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nhaXes as $nhaXe)
                        <tr>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $nhaXe->ma_nha_xe }}</span>
                            </td>
                            <td>
                                <strong class="text-primary">{{ $nhaXe->ten_nha_xe }}</strong>
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                {{ $nhaXe->dia_chi }}
                            </td>
                            <td>
                                <i class="fas fa-phone text-success mr-1"></i>
                                {{ $nhaXe->so_dien_thoai }}
                            </td>
                            <td>
                                @if($nhaXe->email)
                                <i class="fas fa-envelope text-info mr-1"></i>
                                {{ $nhaXe->email }}
                                @else
                                <span class="text-muted">Chưa cập nhật</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success badge-lg">
                                    {{ $nhaXe->chuyenXe()->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('bus-owner.nha-xe.show', $nhaXe->ma_nha_xe) }}"
                                        class="btn btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bus-owner.nha-xe.edit', $nhaXe->ma_nha_xe) }}"
                                        class="btn btn-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $nhaXe->ma_nha_xe }})" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Hidden Delete Form -->
                                <form id="delete-form-{{ $nhaXe->ma_nha_xe }}"
                                    action="{{ route('bus-owner.nha-xe.destroy', $nhaXe->ma_nha_xe) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p class="h5">Không có dữ liệu</p>
                                    @if(request('search'))
                                    <p>Không tìm thấy kết quả cho "{{ request('search') }}"</p>
                                    <a href="{{ route('bus-owner.nha-xe.index') }}" class="btn btn-primary">
                                        <i class="fas fa-redo"></i> Xem tất cả
                                    </a>
                                    @else
                                    <p>Chưa có nhà xe nào trong hệ thống</p>
                                    <a href="{{ route('bus-owner.nha-xe.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Thêm nhà xe mới
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Hiển thị {{ $nhaXes->firstItem() ?? 0 }} - {{ $nhaXes->lastItem() ?? 0 }}
                    trong tổng số {{ $nhaXes->total() }} kết quả
                </div>
                <div>
                    {{ $nhaXes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa nhà xe này? Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Đồng ý, xóa!',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Show success/error messages
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: '{{ session('
        success ') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: '{{ session('
        error ') }}',
    });
    @endif

    @if(session('warning'))
    Swal.fire({
        icon: 'warning',
        title: 'Cảnh báo!',
        text: '{{ session('
        warning ') }}',
    });
    @endif
</script>
@endpush

@push('styles')
<style>
    .table th {
        white-space: nowrap;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }

    .small-box {
        border-radius: 10px;
    }

    .card {
        border-radius: 10px;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }

    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
@endsection