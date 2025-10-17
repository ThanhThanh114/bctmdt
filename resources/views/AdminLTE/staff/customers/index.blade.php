@extends('layouts.staff')

@section('title', 'Staff Customers Management')

@section('page-title', 'Danh sách khách hàng')
@section('breadcrumb', 'Nhân viên')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Khách hàng</h3>
                <div class="card-tools">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Tìm kiếm khách hàng..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-primary">Tìm</button>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên khách hàng</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Số vé đã đặt</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->fullname ?? 'N/A' }}</td>
                            <td>{{ $customer->username }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $customer->datVe->count() }}</span>
                            </td>
                            <td>{{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có khách hàng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())
            <div class="card-footer">
                {{ $customers->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
