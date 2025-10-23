@extends('layouts.admin')

@section('title', 'Quản lý yêu cầu nâng cấp')

@section('page-title', 'Quản lý yêu cầu nâng cấp')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Yêu cầu nâng cấp</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách yêu cầu nâng cấp</h3>
        <div class="card-tools">
            <form action="{{ route('admin.users.upgrade-requests') }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <select name="status" class="form-control form-control-sm ml-2" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người dùng</th>
                    <th>Tên công ty</th>
                    <th>Số tiền</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($upgradeRequests as $request)
                <tr>
                    <td>#{{ $request->id }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $request->user) }}">
                            {{ $request->user->fullname ?? $request->user->username }}
                        </a><br>
                        <small class="text-muted">{{ $request->user->email }}</small>
                    </td>
                    <td>{{ $request->business_info['company_name'] ?? 'N/A' }}</td>
                    <td><span class="text-danger">{{ number_format($request->amount) }}đ</span></td>
                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $request->getStatusBadgeClass() }}">
                            {{ $request->getStatusLabel() }}
                        </span>
                    </td>
                    <td>
                        @if($request->payment)
                        <span class="badge {{ $request->payment->getStatusBadgeClass() }}">
                            {{ $request->payment->getStatusLabel() }}
                        </span>
                        @else
                        <span class="badge badge-secondary">N/A</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.upgrade-request-detail', $request->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        @if($request->status === 'paid')
                        <a href="{{ route('admin.users.upgrade-request-detail', $request->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i> Duyệt
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Không có yêu cầu nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $upgradeRequests->links() }}
    </div>
</div>

<!-- Statistics -->
<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Chờ xử lý</span>
                <span class="info-box-number">{{ \App\Models\UpgradeRequest::pending()->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-credit-card"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đã thanh toán</span>
                <span class="info-box-number">{{ \App\Models\UpgradeRequest::paid()->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đã duyệt</span>
                <span class="info-box-number">{{ \App\Models\UpgradeRequest::approved()->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Từ chối</span>
                <span class="info-box-number">{{ \App\Models\UpgradeRequest::rejected()->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
