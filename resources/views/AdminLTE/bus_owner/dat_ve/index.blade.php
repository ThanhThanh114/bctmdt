@extends('layouts.admin')

@section('title', 'Quản lý Đặt vé')
@section('page-title', 'Danh sách Đặt vé')
@section('breadcrumb', 'Đặt vé')

@section('content')
<!-- Stats -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Tổng vé</p>
            </div>
            <div class="icon"><i class="fas fa-ticket-alt"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending'] }}</h3>
                <p>Chờ thanh toán</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['confirmed'] }}</h3>
                <p>Đã thanh toán</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['cancelled'] }}</h3>
                <p>Đã hủy</p>
            </div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
</div>

<!-- Filter & Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách vé</h3>
            </div>

            <!-- Filters -->
            <div class="card-header border-0">
                <form method="GET" class="row">
                    <div class="col-md-3">
                        <select name="trang_thai" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Đã đặt" {{ request('trang_thai') == 'Đã đặt' ? 'selected' : '' }}>Đã đặt</option>
                            <option value="Đã xác nhận" {{ request('trang_thai') == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="Đã thanh toán" {{ request('trang_thai') == 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="Đã hủy" {{ request('trang_thai') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="ngay_dat" class="form-control" value="{{ request('ngay_dat') }}" placeholder="Ngày đặt">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-1"></i> Lọc
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('bus-owner.dat-ve.index') }}" class="btn btn-secondary btn-block">
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
                            <th>Khách hàng</th>
                            <th>Chuyến xe</th>
                            <th>Số lượng vé</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>
                                @if($booking->user)
                                <strong>{{ $booking->user->fullname ?? $booking->user->username ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                                @else
                                <span class="text-muted">Khách hàng không tồn tại</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $booking->chuyenXe->ten_xe ?? 'N/A' }}</strong><br>
                                <small class="text-muted">
                                    {{ $booking->chuyenXe->ngay_di ? \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') : '' }}
                                    {{ $booking->chuyenXe->gio_di ? \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') : '' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $booking->so_luong_ve ?? 1 }}</span>
                            </td>
                            <td>
                                <strong>{{ number_format($booking->chuyenXe->gia_ve * ($booking->so_luong_ve ?? 1)) }}đ</strong>
                            </td>
                            <td>{{ $booking->ngay_dat ? \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                @if($booking->trang_thai == 'Đã thanh toán')
                                <span class="badge badge-success">{{ $booking->trang_thai }}</span>
                                @elseif($booking->trang_thai == 'Đã xác nhận')
                                <span class="badge badge-primary">{{ $booking->trang_thai }}</span>
                                @elseif($booking->trang_thai == 'Đã đặt')
                                <span class="badge badge-warning">{{ $booking->trang_thai }}</span>
                                @else
                                <span class="badge badge-danger">{{ $booking->trang_thai }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('bus-owner.dat-ve.show', $booking->id) }}"
                                        class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($booking->trang_thai == 'Đã đặt')
                                    <form action="{{ route('bus-owner.dat-ve.confirm', $booking->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            title="Xác nhận" onclick="return confirm('Xác nhận đặt vé này?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($booking->trang_thai != 'Đã hủy')
                                    <form action="{{ route('bus-owner.dat-ve.cancel', $booking->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            title="Hủy vé" onclick="return confirm('Hủy đặt vé này?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-ticket-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Chưa có vé nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection