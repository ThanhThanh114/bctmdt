@extends('layouts.admin')

@section('title', 'Quản lý nhân viên')

@section('page-title', 'Quản lý nhân viên')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Quản lý nhân viên</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $statistics['total'] }}</h3>
                <p>Tổng nhân viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $statistics['tai_xe'] }}</h3>
                <p>Tài xế</p>
            </div>
            <div class="icon">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $statistics['pho_xe'] }}</h3>
                <p>Phụ xe</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $statistics['quan_ly'] }}</h3>
                <p>Quản lý</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-shield"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users mr-2"></i>Danh sách nhân viên của {{ $nhaXe->name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('bus-owner.nhan-vien.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Thêm nhân viên
                    </a>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="card-header border-0 bg-light">
                <form method="GET" id="searchForm">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="small mb-1"><i class="fas fa-search mr-1"></i>Tìm kiếm</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Tên, email, số điện thoại, chức vụ..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="small mb-1"><i class="fas fa-briefcase mr-1"></i>Chức vụ</label>
                            <select name="chuc_vu" class="form-control">
                                <option value="">Tất cả</option>
                                @foreach($positions as $position)
                                <option value="{{ $position }}" {{ request('chuc_vu') == $position ? 'selected' : '' }}>
                                    {{ $position }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1"><i class="fas fa-sort mr-1"></i>Hiển thị</label>
                            <select name="per_page" class="form-control">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1 d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary mr-1" title="Tìm kiếm">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                            <a href="{{ route('bus-owner.nhan-vien.index') }}" class="btn btn-secondary" title="Làm mới">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th>
                                <a href="?sort_by=ma_nv&sort_order={{ request('sort_order') == 'asc' ? 'desc' : 'asc' }}">
                                    Mã NV
                                    @if(request('sort_by') == 'ma_nv')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=ten_nv&sort_order={{ request('sort_order') == 'asc' ? 'desc' : 'asc' }}">
                                    Tên nhân viên
                                    @if(request('sort_by') == 'ten_nv')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Chức vụ</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nhanViens as $nhanVien)
                        <tr>
                            <td><strong class="text-primary">#{{ $nhanVien->ma_nv }}</strong></td>
                            <td>
                                <strong>{{ $nhanVien->ten_nv }}</strong>
                            </td>
                            <td>
                                @if($nhanVien->chuc_vu == 'Tài xế')
                                <span class="badge badge-success">{{ $nhanVien->chuc_vu }}</span>
                                @elseif($nhanVien->chuc_vu == 'Phụ xe')
                                <span class="badge badge-info">{{ $nhanVien->chuc_vu }}</span>
                                @elseif($nhanVien->chuc_vu == 'Quản lý')
                                <span class="badge badge-danger">{{ $nhanVien->chuc_vu }}</span>
                                @else
                                <span class="badge badge-secondary">{{ $nhanVien->chuc_vu }}</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-phone mr-1"></i>{{ $nhanVien->so_dien_thoai }}
                            </td>
                            <td>
                                <i class="fas fa-envelope mr-1"></i>{{ $nhanVien->email }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('bus-owner.nhan-vien.show', $nhanVien->ma_nv) }}"
                                        class="btn btn-sm btn-info"
                                        title="Xem chi tiết"
                                        data-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bus-owner.nhan-vien.edit', $nhanVien->ma_nv) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Chỉnh sửa"
                                        data-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $nhanVien->ma_nv }}"
                                        data-name="{{ $nhanVien->ten_nv }}"
                                        title="Xóa"
                                        data-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $nhanVien->ma_nv }}"
                                    method="POST"
                                    action="{{ route('bus-owner.nhan-vien.destroy', $nhanVien->ma_nv) }}"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Chưa có nhân viên nào</p>
                                <a href="{{ route('bus-owner.nhan-vien.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i>Thêm nhân viên đầu tiên
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($nhanViens->hasPages())
            <div class="card-footer clearfix">
                <div class="float-left">
                    <p class="text-sm text-muted">
                        Hiển thị {{ $nhanViens->firstItem() }} - {{ $nhanViens->lastItem() }}
                        trong tổng số {{ $nhanViens->total() }} nhân viên
                    </p>
                </div>
                <div class="float-right">
                    {{ $nhanViens->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/bus-owner-nhan-vien-index.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/bus-owner-nhan-vien-index.css') }}">
@endpush
