<!-- Recent trips table -->
<div class="row mt-3">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white"><i class="fas fa-bus mr-2"></i>Chuyến xe gần đây</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>TÊN XE</th>
                            <th>NHÀ XE</th>
                            <th>TUYẾN</th>
                            <th>NGÀY ĐI</th>
                            <th>GIỜ ĐI</th>
                            <th>GIÁ VÉ</th>
                            <th>CÒN LẠI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recent_trips ?? [])->take(10) as $trip)
                        @php
                        $from = optional($trip->tramDi)->ten_tram ?? optional($trip->tramDi)->dia_chi ?? 'N/A';
                        $to = optional($trip->tramDen)->ten_tram ?? optional($trip->tramDen)->dia_chi ?? 'N/A';
                        @endphp
                        <tr>
                            <td>{{ $trip->id }}</td>
                            <td><strong>{{ $trip->ten_xe }}</strong></td>
                            <td>{{ optional($trip->nhaXe)->ten_nha_xe ?? 'N/A' }}</td>
                            <td>{{ $from }} <i class="fas fa-arrow-right mx-1"></i> {{ $to }}</td>
                            <td>{{ $trip->ngay_di ? \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>{{ $trip->gio_di ? \Carbon\Carbon::parse($trip->gio_di)->format('H:i') : 'N/A' }}</td>
                            <td><strong>{{ number_format($trip->gia_ve ?? 0) }} đ</strong></td>
                            <td><span
                                    class="badge badge-success">{{ ($trip->so_cho ?? 0) - ($trip->so_ve ?? 0) }}/{{ $trip->so_cho ?? 0 }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Chưa có chuyến xe</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
