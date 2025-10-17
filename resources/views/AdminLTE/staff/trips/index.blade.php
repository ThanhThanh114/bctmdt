@extends('layouts.staff')

@section('title', 'Staff Trips Management')

@section('page-title', 'Quản lý chuyến xe')
@section('breadcrumb', 'Nhân viên')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách chuyến xe</h3>
                <div class="card-tools">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Tìm kiếm tuyến đường..." value="{{ request('search') }}">
                        <select name="status" class="form-control form-control-sm mr-2">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Tuyến đường</th>
                            <th>Ngày đi</th>
                            <th>Giờ đi</th>
                            <th>Nhà xe</th>
                            <th>Giá vé</th>
                            <th>Số chỗ</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trips as $trip)
                        <tr>
                            <td>{{ $trip->route_name }}</td>
                            <td>{{ $trip->ngay_di ? \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $trip->gio_di ?? 'N/A' }}</td>
                            <td>{{ $trip->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                            <td>{{ number_format($trip->gia_ve ?? 0, 0, ',', '.') }}đ</td>
                            <td>{{ $trip->available_seats }}/{{ $trip->so_cho }}</td>
                            <td>
                                @if($trip->status == 'active')
                                    <span class="badge badge-success">Hoạt động</span>
                                @else
                                    <span class="badge badge-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('staff.trips.show', $trip) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có chuyến xe nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($trips->hasPages())
            <div class="card-footer">
                {{ $trips->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
