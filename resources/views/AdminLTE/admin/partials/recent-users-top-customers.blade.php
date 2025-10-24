<!-- Recent users and Top customers -->
<div class="row mt-3">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white"><i class="fas fa-user-plus mr-2"></i>Khách hàng mới đăng ký
            </div>
            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>TÊN</th>
                            <th>EMAIL</th>
                            <th>SỐ ĐIỆN THOẠI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recent_users ?? [])->take(10) as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->fullname ?? ($u->username ?? 'N/A') }}</td>
                            <td>{{ $u->email ?? 'N/A' }}</td>
                            <td>{{ $u->phone ?? 'N/A' }}</td>
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
            <div class="card-header bg-warning text-dark"><i class="fas fa-crown mr-2"></i>Top 10 khách hàng đặt vé
                nhiều nhất</div>
            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>TÊN KHÁCH HÀNG</th>
                            <th>EMAIL</th>
                            <th>SỐ ĐIỆN THOẠI</th>
                            <th class="text-right">SỐ LƯỢNG VÉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($top_customers ?? [])->take(10) as $tc)
                        @php $usr = $tc['user'] ?? null; @endphp
                        <tr>
                            <td><span class="badge badge-pill"
                                    style="background: linear-gradient(135deg, #667eea, #764ba2); color: #fff;">{{ $loop->iteration }}</span>
                            </td>
                            <td>{{ optional($usr)->fullname ?? optional($usr)->username ?? 'N/A' }}</td>
                            <td>{{ optional($usr)->email ?? 'Chưa cập nhật' }}</td>
                            <td>{{ optional($usr)->phone ?? 'Chưa cập nhật' }}</td>
                            <td class="text-right"><span class="badge badge-success"><i
                                        class="fas fa-ticket-alt mr-1"></i>{{ $tc['tickets'] ?? 0 }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
