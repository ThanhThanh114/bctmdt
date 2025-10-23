<!-- Recent Bookings -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lịch sử đặt vé</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Mã vé</th>
                    <th>Ngày giờ</th>
                    <th>Tuyến đường</th>
                    <th>Số ghế</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') }}</td>
                    <td>{{ $booking->chuyenXe->route_name }}</td>
                    <td>{{ $booking->seat_number }}</td>
                    <td>{{ number_format($booking->chuyenXe->gia_ve ?? 0) }}đ</td>
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
                    <td colspan="6" class="text-center">Chưa có lịch sử đặt vé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
