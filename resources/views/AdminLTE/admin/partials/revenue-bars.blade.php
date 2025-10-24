<!-- Revenue bars: Today and This Month -->
<div class="row mt-4">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center text-white">
                    <i class="fas fa-calendar-day fa-3x mr-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">Doanh thu hôm nay</h5>
                        @php
                        $todayRev = array_values($daily_revenue_7 ?? [])[count($daily_revenue_7 ?? [])-1] ?? 0;
                        @endphp
                        <h2 class="mb-0">{{ number_format($todayRev) }} đ</h2>
                        <div class="progress mt-2" style="height:8px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: 70%;"></div>
                        </div>
                        <small>{{ date('d/m/Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex align-items-center text-white">
                    <i class="fas fa-calendar-alt fa-3x mr-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">Doanh thu tháng này</h5>
                        <h2 class="mb-0">{{ number_format($stats['monthly_revenue'] ?? 0) }} đ</h2>
                        <div class="progress mt-2" style="height:8px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: 85%;"></div>
                        </div>
                        <small>Tháng {{ date('m/Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
