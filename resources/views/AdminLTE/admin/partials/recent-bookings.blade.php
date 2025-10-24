<!-- Recent bookings table - moved to the end -->
<div class="row mt-3">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white"><i class="fas fa-ticket-alt mr-2"></i>Vé đặt gần đây</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>KHÁCH HÀNG</th>
                            <th>CHUYẾN XE</th>
                            <th>SỐ GHẾ</th>
                            <th>NGÀY ĐẶT</th>
                            <th>TỔNG TIỀN</th>
                            <th>TRẠNG THÁI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_bookings ?? [] as $booking)
                        @php
                        // Get price
                        $rawPrice = optional($booking->chuyenXe)->gia_ve ?? 0;
                        $cleanPrice = preg_replace('/[^0-9\.]/', '', (string)$rawPrice);
                        $price = $cleanPrice === '' ? 0.0 : (float)$cleanPrice;

                        // Count seats from string like "A01, A02,A03"
                        $rawSeats = $booking->so_ghe ?? '';
                        if(empty($rawSeats)) {
                        $seatCount = 0;
                        $seatDisplay = 'N/A';
                        } else {
                        $seatArray = array_filter(array_map('trim', explode(',', (string)$rawSeats)));
                        $seatCount = count($seatArray);
                        $seatDisplay = $rawSeats; // Show original string
                        }

                        // Calculate total
                        $total = $price * $seatCount;
                        @endphp
                        <tr>
                            <td>{{ $booking->ma_ve ?? $booking->id }}</td>
                            <td>
                                <strong>{{ optional($booking->user)->full_name ?? optional($booking->user)->username ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ optional($booking->user)->email ?? '' }}</small>
                            </td>
                            <td>
                                <strong>{{ optional($booking->chuyenXe)->ten_xe ?? 'N/A' }}</strong><br>
                                <small class="text-muted">
                                    {{ optional(optional($booking->chuyenXe)->tramDi)->ten_tram ?? 'N/A' }}
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    {{ optional(optional($booking->chuyenXe)->tramDen)->ten_tram ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                @if($seatCount > 0)
                                <span class="badge badge-primary badge-pill"
                                    title="{{ $seatDisplay }}">{{ $seatCount }}</span>
                                @else
                                <span class="badge badge-secondary badge-pill">0</span>
                                @endif
                            </td>
                            <td>{{ $booking->ngay_dat ? \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                @if($total > 0)
                                <strong class="text-success">{{ number_format($total, 0, ',', '.') }} đ</strong>
                                @else
                                <strong class="text-muted">0 đ</strong>
                                @endif
                            </td>
                            <td>
                                @if($booking->trang_thai == 'Đã thanh toán')
                                <span class="badge badge-success">Đã thanh toán</span>
                                @elseif($booking->trang_thai == 'Đã đặt')
                                <span class="badge badge-warning">Đã đặt</span>
                                @else
                                <span class="badge badge-danger">{{ $booking->trang_thai ?? 'Đã hủy' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                <p class="text-muted mb-0">Chưa có vé đặt nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
