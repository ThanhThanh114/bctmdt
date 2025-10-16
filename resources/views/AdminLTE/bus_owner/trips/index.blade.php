@extends('layouts.admin')

@section('title', 'Quản lý chuyến xe')

@section('page-title', 'Quản lý chuyến xe')
@section('breadcrumb', 'Chuyến xe')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách chuyến xe của {{ $bus_company->name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('bus-owner.trips.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Thêm chuyến xe
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card-header border-0">
                <form method="GET" class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tuyến đường..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-1"></i> Lọc
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('bus-owner.trips.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-redo mr-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tuyến đường</th>
                            <th>Giờ khởi hành</th>
                            <th>Giờ đến</th>
                            <th>Giá vé</th>
                            <th>Số ghế</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trips as $trip)
                        <tr>
                            <td>{{ $trip->id }}</td>
                            <td>
                                <strong>{{ $trip->route_name }}</strong>
                            </td>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $trip->ngay_di . ' ' . $trip->gio_di)->format('d/m/Y H:i') }}</td>
                            <td>{{ $trip->ngay_den ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $trip->ngay_den . ' ' . $trip->gio_den)->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                <strong>{{ number_format($trip->price) }}đ</strong>
                            </td>
                            <td>
                                <span class="badge {{ $trip->available_seats > 10 ? 'badge-success' : ($trip->available_seats > 0 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $trip->available_seats }}/{{ $trip->total_seats }}
                                </span>
                            </td>
                            <td>
                                @if($trip->status == 'active')
                                    <span class="badge badge-success">Hoạt động</span>
                                @else
                                    <span class="badge badge-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td>{{ $trip->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('bus-owner.trips.show', $trip) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bus-owner.trips.edit', $trip) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('bus-owner.trips.destroy', $trip) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chuyến xe này?')">
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
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-bus fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Chưa có chuyến xe nào</p>
                                <a href="{{ route('bus-owner.trips.create') }}" class="btn btn-primary">Thêm chuyến xe đầu tiên</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $trips->where('status', 'Đang hoạt động')->count() }}</h3>
                <p>Chuyến hoạt động</p>
            </div>
            <div class="icon">
                <i class="fas fa-bus"></i>
            </div>
            <a href="{{ route('bus-owner.trips.index', ['status' => 'active']) }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $trips->where('status', 'Không hoạt động')->count() }}</h3>
                <p>Chuyến không hoạt động</p>
            </div>
            <div class="icon">
                <i class="fas fa-pause-circle"></i>
            </div>
            <a href="{{ route('bus-owner.trips.index', ['status' => 'inactive']) }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $trips->sum('total_seats') }}</h3>
                <p>Tổng số ghế</p>
            </div>
            <div class="icon">
                <i class="fas fa-chair"></i>
            </div>
            <div class="small-box-footer">
                &nbsp;
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $trips->sum('available_seats') }}</h3>
                <p>Ghế còn trống</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="small-box-footer">
                &nbsp;
            </div>
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
    border: 1px solid rgba(0,0,0,.125);
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.small-box .icon {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 3rem;
    color: rgba(255,255,255,.15);
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
    background-color: rgba(0,0,0,.1);
    color: rgba(255,255,255,.8);
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
