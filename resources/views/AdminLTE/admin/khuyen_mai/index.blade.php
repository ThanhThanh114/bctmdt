@extends('layouts.admin')

@section('title', 'Quản lý Khuyến mãi')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Khuyến mãi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Khuyến mãi</li>
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
                        <p>Tổng khuyến mãi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['active'] }}</h3>
                        <p>Đang áp dụng</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['upcoming'] }}</h3>
                        <p>Sắp diễn ra</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['expired'] }}</h3>
                        <p>Đã hết hạn</p>
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
                <h3 class="card-title">Danh sách khuyến mãi</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.khuyenmai.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.khuyenmai.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tên hoặc mã khuyến mãi"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="ma_nha_xe" class="form-control">
                                <option value="all">-- Nhà xe --</option>
                                <option value="null" {{ request('ma_nha_xe') == 'null' ? 'selected' : '' }}>Tất cả nhà xe</option>
                                @foreach($nhaXes as $nhaXe)
                                    <option value="{{ $nhaXe->ma_nha_xe }}" {{ request('ma_nha_xe') == $nhaXe->ma_nha_xe ? 'selected' : '' }}>
                                        {{ $nhaXe->ten_nha_xe }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">-- Trạng thái --</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang áp
                                    dụng</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Sắp
                                    diễn ra</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Đã hết
                                    hạn</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="giam_gia" class="form-control" placeholder="% giảm giá"
                                value="{{ request('giam_gia') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                            <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Mã KM</th>
                                <th>Tên KM</th>
                                <th>Mã Code</th>
                                <th>Nhà xe</th>
                                <th>Giảm giá</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($khuyenMai as $item)
                            @php
                            $now = now();
                            $start = \Carbon\Carbon::parse($item->ngay_bat_dau);
                            $end = \Carbon\Carbon::parse($item->ngay_ket_thuc);
                            @endphp
                            <tr>
                                <td><strong>{{ $item->ma_km }}</strong></td>
                                <td>{{ $item->ten_km }}</td>
                                <td><code>{{ $item->ma_code }}</code></td>
                                <td>
                                    @if($item->ma_nha_xe)
                                        {{ $item->nhaXe->ten_nha_xe ?? 'N/A' }}
                                    @else
                                        <span class="badge badge-info">Tất cả nhà xe</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-success">{{ $item->giam_gia }}%</span></td>
                                <td>{{ $start->format('d/m/Y') }}</td>
                                <td>{{ $end->format('d/m/Y') }}</td>
                                <td>
                                    @if($now->between($start, $end))
                                    <span class="badge badge-success">Đang áp dụng</span>
                                    @elseif($now->lt($start))
                                    <span class="badge badge-warning">Sắp diễn ra</span>
                                    @else
                                    <span class="badge badge-danger">Đã hết hạn</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.khuyenmai.show', $item->ma_km) }}"
                                        class="btn btn-sm btn-info" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.khuyenmai.edit', $item->ma_km) }}"
                                        class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.khuyenmai.destroy', $item->ma_km) }}" method="POST"
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
                    {{ $khuyenMai->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection