@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('page-title', 'Chi tiết người dùng')
@section('breadcrumb', 'Người dùng')

@section('content')
<div class="row">
    <!-- User Information Card -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-2"></i>Thông tin người dùng
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="profile-avatar-large">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h4 class="mt-3">{{ $user->fullname }}</h4>
                        <p class="text-muted">{{ $user->role }}</p>
                        @if(strtolower($user->role) === 'admin')
                        <span class="badge badge-danger">
                            <i class="fas fa-crown"></i> Quản trị viên
                        </span>
                        @elseif(strtolower($user->role) === 'staff')
                        <span class="badge badge-warning">
                            <i class="fas fa-user-tie"></i> Nhân viên
                        </span>
                        @elseif(strtolower($user->role) === 'bus_owner')
                        <span class="badge badge-info">
                            <i class="fas fa-bus"></i> Nhà xe
                        </span>
                        @else
                        <span class="badge badge-success">
                            <i class="fas fa-user"></i> Người dùng
                        </span>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tên đăng nhập:</strong></td>
                                <td>
                                    <strong>{{ $user->username }}</strong>
                                    {{-- Email verification feature not yet implemented --}}
                                    {{-- @if($user->is_verified)
                                        <i class="fas fa-check-circle text-success ml-2" title="Đã xác thực"></i>
                                    @endif --}}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Số điện thoại:</strong></td>
                                <td>{{ $user->phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Địa chỉ:</strong></td>
                                <td>{{ $user->address ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày sinh:</strong></td>
                                <td>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : 'Chưa cập nhật' }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Giới tính:</strong></td>
                                <td>{{ $user->gender ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo tài khoản:</strong></td>
                                <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') : 'Chưa cập nhật' }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Lần cập nhật cuối:</strong></td>
                                <td>{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') : 'Chưa cập nhật' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-ticket-alt mr-2"></i>Lịch sử đặt vé gần đây
                </h3>
                <div class="card-tools">
                    <a href="#" class="btn btn-sm btn-primary" onclick="alert('Tính năng đang được phát triển')">
                        Xem tất cả vé
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Mã vé</th>
                            <th>Chuyến xe</th>
                            <th>Số ghế</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_bookings as $booking)
                        <tr>
                            <td>
                                <strong>#{{ $booking->id }}</strong>
                                @if($booking->ma_ve)
                                <br><small class="text-muted">Mã: {{ $booking->ma_ve }}</small>
                                @endif
                            </td>
                            <td>
                                @if($booking->chuyenXe)
                                <div>
                                    <strong>{{ $booking->chuyenXe->tramDi->ten_tram ?? 'N/A' }}</strong>
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    <strong>{{ $booking->chuyenXe->tramDen->ten_tram ?? 'N/A' }}</strong>
                                </div>
                                <small class="text-muted">{{ $booking->chuyenXe->nhaXe->ten_nha_xe ?? '' }}</small>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $booking->so_ghe ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $booking->ngay_dat ? $booking->ngay_dat->format('d/m/Y H:i') : 'Chưa cập nhật' }}
                            </td>
                            <td>
                                <strong>{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</strong>
                            </td>
                            <td>
                                @if($booking->trang_thai == 'Đã thanh toán')
                                <span class="badge badge-success">Đã thanh toán</span>
                                @elseif($booking->trang_thai == 'Đã đặt')
                                <span class="badge badge-warning">Đã đặt</span>
                                @elseif($booking->trang_thai == 'Đã hủy')
                                <span class="badge badge-danger">Đã hủy</span>
                                @else
                                <span class="badge badge-secondary">{{ $booking->trang_thai }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Chưa có lịch sử đặt vé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Statistics & Actions -->
    <div class="col-md-4">
        <!-- User Statistics -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>Thống kê tài khoản
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Tổng vé đã đặt:</span>
                    <span class="badge badge-primary">{{ $stats['total'] }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Vé đã xác nhận:</span>
                    <span class="badge badge-success">{{ $stats['confirmed'] }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Vé chờ xử lý:</span>
                    <span class="badge badge-warning">{{ $stats['pending'] }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Vé đã hủy:</span>
                    <span class="badge badge-danger">{{ $stats['cancelled'] }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Tổng chi tiêu:</span>
                    <span class="badge badge-info">{{ number_format($stats['total_spent']) }}đ</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2"></i>Thao tác nhanh
                </h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-block">
                    <i class="fas fa-edit mr-2"></i> Chỉnh sửa thông tin
                </a>

                @if(strtolower($user->role) === 'user')
                <a href="#" class="btn btn-success btn-block" onclick="alert('Tính năng đang được phát triển')">
                    <i class="fas fa-ticket-alt mr-2"></i> Xem vé của người dùng
                </a>
                @endif

                @if(strtolower($user->role) === 'bus_owner' && $user->nhaXe)
                <a href="{{ route('bus-owner.trips.index') }}" class="btn btn-info btn-block">
                    <i class="fas fa-bus mr-2"></i> Quản lý nhà xe
                </a>
                @endif

                @if(strtolower($user->role) !== 'admin')
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;"
                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-trash mr-2"></i> Xóa người dùng
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Account Status -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Trạng thái tài khoản
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Vai trò:</span>
                    <span
                        class="badge {{ strtolower($user->role) === 'admin' ? 'badge-danger' : (strtolower($user->role) === 'staff' ? 'badge-warning' : (strtolower($user->role) === 'bus_owner' ? 'badge-info' : 'badge-success')) }}">
                        {{ $user->role }}
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Trạng thái:</span>
                    <span class="badge badge-success">Hoạt động</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Email xác thực:</span>
                    {{-- Email verification feature not yet implemented --}}
                    {{-- @if($user->is_verified)
                        <span class="badge badge-success">Đã xác thực</span>
                    @else
                        <span class="badge badge-warning">Chưa xác thực</span>
                    @endif --}}
                    <span class="badge badge-success">Đang hoạt động</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Thành viên từ:</span>
                    <span>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                </div>
            </div>
        </div>

        @if(strtolower($user->role) === 'bus_owner' && $user->nhaXe)
        <!-- Bus Company Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bus mr-2"></i>Thông tin nhà xe
                </h3>
            </div>
            <div class="card-body">
                <h5 class="text-primary">{{ $user->nhaXe->name }}</h5>
                <p class="text-muted">{{ $user->nhaXe->description ?? 'Chưa có mô tả' }}</p>
                <div class="d-flex justify-content-between mb-2">
                    <span>Số chuyến:</span>
                    <span class="badge badge-primary">{{ $user->nhaXe->chuyenXe()->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Đánh giá:</span>
                    <span class="badge badge-success">
                        <i class="fas fa-star"></i> {{ number_format($user->nhaXe->rating ?? 5, 1) }}
                    </span>
                </div>
                <a href="{{ route('bus-owner.trips.index') }}" class="btn btn-sm btn-primary btn-block">
                    Quản lý chuyến xe
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-avatar-large {
        margin-bottom: 20px;
    }

    .info-box {
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        border-radius: 0.25rem;
        margin-bottom: 1rem;
        background-color: #fff;
        display: block;
    }

    .info-box .info-box-icon {
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0.25rem;
        display: block;
        float: left;
        height: 70px;
        width: 70px;
        text-align: center;
        font-size: 30px;
        line-height: 70px;
        background-color: rgba(0, 0, 0, .2);
    }

    .info-box .info-box-content {
        padding: 5px 10px;
        margin-left: 70px;
    }

    .info-box .info-box-text {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .info-box .info-box-number {
        display: block;
        font-weight: 700;
        font-size: 18px;
    }

    .badge {
        font-size: 0.75rem;
    }

    .card {
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        border: none;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }
</style>
@endpush