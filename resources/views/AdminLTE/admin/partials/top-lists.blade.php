<!-- Top lists: Bus companies and routes -->
<div class="row mt-3">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white"><i class="fas fa-building mr-2"></i>Top 5 Nhà xe có nhiều chuyến
                nhất</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>STT</th>
                            <th>TÊN NHÀ XE</th>
                            <th>SỐ ĐIỆN THOẠI</th>
                            <th class="text-right">SỐ CHUYẾN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bus_company_stats ?? [] as $idx => $company)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td><strong>{{ $company->ten_nha_xe ?? 'N/A' }}</strong></td>
                            <td>{{ $company->so_dien_thoai ?? 'N/A' }}</td>
                            <td class="text-right"><span
                                    class="badge badge-primary">{{ $company->chuyen_xe_count ?? 0 }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white"><i class="fas fa-route mr-2"></i>Top 5 Tuyến phổ biến</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>STT</th>
                            <th>TUYẾN ĐƯỜNG</th>
                            <th class="text-right">SỐ CHUYẾN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $routeIdx = 0; @endphp
                        @forelse(($top_routes ?? collect())->take(5) as $route => $count)
                        @php $routeIdx++; @endphp
                        <tr>
                            <td>{{ $routeIdx }}</td>
                            <td><strong>{{ $route }}</strong></td>
                            <td class="text-right"><span class="badge badge-success">{{ $count }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
