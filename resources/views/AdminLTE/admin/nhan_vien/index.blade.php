@extends('layouts.admin')

@section('title', 'Quản lý nhân viên')

@section('page-title', 'Quản lý nhân viên')
@section('breadcrumb', 'Nhân viên')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách nhân viên</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.nhanvien.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Thêm nhân viên
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..."
                                    value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="chuc_vu" class="form-control" onchange="this.form.submit()">
                                <option value="all" {{ request('chuc_vu') == 'all' ? 'selected' : '' }}>Tất cả chức vụ
                                </option>
                                <option value="tài xế" {{ request('chuc_vu') == 'tài xế' ? 'selected' : '' }}>Tài xế
                                </option>
                                <option value="phụ xe" {{ request('chuc_vu') == 'phụ xe' ? 'selected' : '' }}>Phụ xe
                                </option>
                                <option value="nhân viên văn phòng"
                                    {{ request('chuc_vu') == 'nhân viên văn phòng' ? 'selected' : '' }}>Nhân viên văn
                                    phòng</option>
                                <option value="quản lý" {{ request('chuc_vu') == 'quản lý' ? 'selected' : '' }}>Quản lý
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="ma_nha_xe" class="form-control" onchange="this.form.submit()">
                                <option value="all" {{ request('ma_nha_xe') == 'all' ? 'selected' : '' }}>Tất cả nhà xe
                                </option>
                                @foreach($nhaXes as $nhaXe)
                                <option value="{{ $nhaXe->ma_nha_xe }}"
                                    {{ request('ma_nha_xe') == $nhaXe->ma_nha_xe ? 'selected' : '' }}>
                                    {{ $nhaXe->ten_nha_xe }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên nhân viên</th>
                                <th>Chức vụ</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Nhà xe</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nhanViens as $nhanVien)
                            <tr>
                                <td>{{ $nhanVien->ma_nv }}</td>
                                <td><strong>{{ $nhanVien->ten_nv }}</strong></td>
                                <td>
                                    @if($nhanVien->chuc_vu === 'tài xế')
                                    <span class="badge badge-primary">{{ $nhanVien->chuc_vu }}</span>
                                    @elseif($nhanVien->chuc_vu === 'phụ xe')
                                    <span class="badge badge-info">{{ $nhanVien->chuc_vu }}</span>
                                    @elseif($nhanVien->chuc_vu === 'quản lý')
                                    <span class="badge badge-danger">{{ $nhanVien->chuc_vu }}</span>
                                    @else
                                    <span class="badge badge-secondary">{{ $nhanVien->chuc_vu }}</span>
                                    @endif
                                </td>
                                <td>{{ $nhanVien->so_dien_thoai }}</td>
                                <td>{{ $nhanVien->email }}</td>
                                <td>{{ $nhanVien->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.nhanvien.show', $nhanVien) }}"
                                            class="btn btn-sm btn-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.nhanvien.edit', $nhanVien) }}"
                                            class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.nhanvien.destroy', $nhanVien) }}"
                                            style="display: inline;"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer clearfix">
                {{ $nhanViens->links() }}
            </div>
        </div>
    </div>
</div>
@endsection