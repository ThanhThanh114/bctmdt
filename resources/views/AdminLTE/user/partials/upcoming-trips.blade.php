<!-- Upcoming Trips -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chuyến đi sắp tới</h3>
        <div class="card-tools">
            <a href="{{ route('user.bookings.index') }}" class="btn btn-sm btn-primary">
                Xem tất cả vé
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Ngày giờ</th>
                    <th>Tuyến đường</th>
                    <th>Nhà xe</th>
                    <th>Số ghế</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($upcoming_trips as $booking)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</td>
                    <td>{{ $booking->chuyenXe->route_name }}</td>
                    <td>{{ $booking->chuyenXe->nhaXe->name ?? 'N/A' }}</td>
                    <td>{{ $booking->seat_number }}</td>
                    <td>{{ number_format($booking->total_price) }}đ</td>
                    <td>
                        @if($booking->status == 'confirmed')
                            <span class="badge badge-success">Đã xác nhận</span>
                        @elseif($booking->status == 'pending')
                            <span class="badge badge-warning">Chờ xử lý</span>
                        @else
                            <span class="badge badge-danger">Đã hủy</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Chưa có chuyến đi nào sắp tới</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
