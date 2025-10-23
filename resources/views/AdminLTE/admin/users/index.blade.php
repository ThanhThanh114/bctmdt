@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('page-title', 'Quản lý người dùng')
@section('breadcrumb', 'Người dùng')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách người dùng</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.upgrade-requests') }}" class="btn btn-sm btn-primary mr-2">
                        <i class="fas fa-star mr-1"></i>Yêu cầu nâng cấp
                        @php $pendingCount = \App\Models\UpgradeRequest::whereIn('status', ['pending', 'payment_pending', 'paid'])->count(); @endphp
                        @if($pendingCount > 0)
                        <span class="badge badge-warning">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <form method="GET" class="d-inline-flex">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Tìm kiếm..."
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <select name="role" class="form-control form-control-sm ml-2" style="width: auto;"
                            onchange="this.form.submit()">
                            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Tất cả vai trò</option>
                            <option value="User" {{ request('role') == 'User' ? 'selected' : '' }}>Người dùng</option>
                            <option value="Staff" {{ request('role') == 'Staff' ? 'selected' : '' }}>Nhân viên</option>
                            <option value="Bus_owner" {{ request('role') == 'Bus_owner' ? 'selected' : '' }}>Nhà xe
                            </option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Nhà xe</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->username }}</strong>
                                {{-- Email verification feature not yet implemented --}}
                                {{-- @if($user->is_verified)
                                    <i class="fas fa-check-circle text-success" title="Đã xác thực"></i>
                                @endif --}}
                            </td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if(strtolower($user->role) === 'admin')
                                <span class="badge badge-danger">Quản trị</span>
                                @elseif($user->role === 'Staff')
                                <span class="badge badge-warning">Nhân viên</span>
                                @elseif($user->role === 'Bus_owner')
                                <span class="badge badge-info">Nhà xe</span>
                                @else
                                <span class="badge badge-success">Người dùng</span>
                                @endif
                            </td>
                            <td>
                                @if($user->role === 'Bus_owner')
                                    @if($user->ma_nha_xe && $user->nhaXe)
                                        {{ $user->nhaXe->ten_nha_xe }}
                                    @else
                                        <span class="text-muted">Chưa gán</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'Chưa cập nhật' }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->role !== 'Admin')
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        style="display: inline;"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Không tìm thấy người dùng nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            {{-- <div class="card-footer">
                {{ $users->appends(request()->query())->links() }}
        </div> --}}
        @endif
    </div>
</div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ App\Models\User::where('role', 'User')->count() }}</h3>
                <p>Người dùng</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users.index', ['role' => 'User']) }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ App\Models\User::where('role', 'Staff')->count() }}</h3>
                <p>Nhân viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="{{ route('admin.users.index', ['role' => 'Staff']) }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ App\Models\User::where('role', 'Bus_owner')->count() }}</h3>
                <p>Nhà xe</p>
            </div>
            <div class="icon">
                <i class="fas fa-bus"></i>
            </div>
            <a href="{{ route('admin.users.index', ['role' => 'Bus_owner']) }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ App\Models\User::count() }}</h3>
                <p>Tổng người dùng</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-box {
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
        position: relative;
        display: block;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, .125);
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
    }

    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 3rem;
        color: rgba(255, 255, 255, .15);
    }

    .small-box .inner {
        padding: 10px;
    }

    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }

    .small-box p {
        font-size: 1rem;
        margin: 0;
    }

    .small-box-footer {
        background-color: rgba(0, 0, 0, .1);
        color: rgba(255, 255, 255, .8);
        display: block;
        padding: 3px 10px;
        position: relative;
        text-decoration: none;
        transition: all .15s linear;
    }

    .small-box-footer:hover {
        text-decoration: none;
        color: #fff;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }
</style>
@endpush