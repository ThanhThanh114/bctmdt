@extends('layouts.admin')

@section('title', 'Báo cáo Doanh thu')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/doanh_thu.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Báo cáo Doanh thu & Vé Bán</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Doanh thu</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Bộ lọc thống kê</h3>
                    </div>
                    <div class="card-body">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Loại báo cáo</label>
                                        <select class="form-control" id="reportType" name="report_type">
                                            <option value="day">Theo Ngày (30 ngày gần nhất)</option>
                                            <option value="month" selected>Theo Tháng (12 tháng)</option>
                                            <option value="year">Theo Năm (5 năm gần nhất)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="yearFilter">
                                    <div class="form-group">
                                        <label>Năm</label>
                                        <select class="form-control" id="year" name="year">
                                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="monthFilter">
                                    <div class="form-group">
                                        <label>Tháng</label>
                                        <select class="form-control" id="month" name="month">
                                            @for($m = 1; $m <= 12; $m++) <option value="{{ $m }}"
                                                {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-success btn-block" onclick="applyFilter()">
                                            <i class="fas fa-search"></i> Lọc dữ liệu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($stats['today_revenue']) }}</h3>
                        <p>Doanh thu hôm nay</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($stats['month_revenue']) }}</h3>
                        <p>Doanh thu tháng này</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($stats['year_revenue']) }}</h3>
                        <p>Doanh thu năm nay</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['total_bookings'] }}</h3>
                        <p>Tổng vé đã bán</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Charts Section - Revenue -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line"></i> Biểu đồ Doanh thu</h3>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="revenueTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $reportType === 'day' ? 'active' : '' }}" id="revenue-day-tab"
                                    data-toggle="tab" href="#revenue-day" role="tab">
                                    <i class="fas fa-calendar-day"></i> Theo Ngày
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $reportType === 'month' ? 'active' : '' }}" id="revenue-month-tab"
                                    data-toggle="tab" href="#revenue-month" role="tab">
                                    <i class="fas fa-calendar-alt"></i> Theo Tháng
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $reportType === 'year' ? 'active' : '' }}" id="revenue-year-tab"
                                    data-toggle="tab" href="#revenue-year" role="tab">
                                    <i class="fas fa-calendar"></i> Theo Năm
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="revenueTabContent">
                            <div class="tab-pane fade {{ $reportType === 'day' ? 'show active' : '' }}" id="revenue-day"
                                role="tabpanel">
                                <div class="chart-container"
                                    style="position: relative; height:400px; margin-top: 20px;">
                                    <canvas id="dailyRevenueChart"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $reportType === 'month' ? 'show active' : '' }}"
                                id="revenue-month" role="tabpanel">
                                <div class="chart-container"
                                    style="position: relative; height:400px; margin-top: 20px;">
                                    <canvas id="monthlyRevenueChart"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $reportType === 'year' ? 'show active' : '' }}"
                                id="revenue-year" role="tabpanel">
                                <div class="chart-container"
                                    style="position: relative; height:400px; margin-top: 20px;">
                                    <canvas id="yearlyRevenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section - Tickets Sold -->
        <div class="row">
            <div class="col-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-ticket-alt"></i> Biểu đồ Số vé đã bán</h3>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="ticketsTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $reportType === 'day' ? 'active' : '' }}" id="tickets-day-tab"
                                    data-toggle="tab" href="#tickets-day" role="tab">
                                    <i class="fas fa-calendar-day"></i> Theo Ngày
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $reportType === 'month' ? 'active' : '' }}" id="tickets-month-tab"
                                    data-toggle="tab" href="#tickets-month" role="tab">
                                    <i class="fas fa-calendar-alt"></i> Theo Tháng
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $reportType === 'year' ? 'active' : '' }}" id="tickets-year-tab"
                                    data-toggle="tab" href="#tickets-year" role="tab">
                                    <i class="fas fa-calendar"></i> Theo Năm
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="ticketsTabContent">
                            <div class="tab-pane fade {{ $reportType === 'day' ? 'show active' : '' }}" id="tickets-day"
                                role="tabpanel">
                                <div class="chart-container"
                                    style="position: relative; height:400px; margin-top: 20px;">
                                    <canvas id="dailyTicketsChart"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $reportType === 'month' ? 'show active' : '' }}"
                                id="tickets-month" role="tabpanel">
                                <div class="chart-container"
                                    style="position: relative; height:400px; margin-top: 20px;">
                                    <canvas id="monthlyTicketsChart"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $reportType === 'year' ? 'show active' : '' }}"
                                id="tickets-year" role="tabpanel">
                                <div class="chart-container"
                                    style="position: relative; height:400px; margin-top: 20px;">
                                    <canvas id="yearlyTicketsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Routes & Operators -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Top 10 chuyến xe có doanh thu cao nhất</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tuyến đường</th>
                                    <th>Ngày đi</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topTrips as $index => $tripData)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $tripData['trip']->tramDi->ten_tram ?? 'N/A' }} →
                                        {{ $tripData['trip']->tramDen->ten_tram ?? 'N/A' }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($tripData['trip']->ngay_di)->format('d/m/Y') }}</td>
                                    <td><strong class="text-success">{{ number_format($tripData['revenue']) }}
                                            VNĐ</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Doanh thu theo nhà xe</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nhà xe</th>
                                    <th>Số vé</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenueByCompany as $index => $companyData)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $companyData['company']->ten_nha_xe }}</td>
                                    <td><span
                                            class="badge badge-info">{{ $companyData['company']->xe_count ?? 0 }}</span>
                                    </td>
                                    <td><strong class="text-success">{{ number_format($companyData['revenue']) }}
                                            VNĐ</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data from backend
const dailyRevenueData = @json($dailyRevenue);
const monthlyRevenueData = @json($monthlyRevenue);
const yearlyRevenueData = @json($yearlyRevenue);
const dailyTicketsData = @json($dailyTickets);
const monthlyTicketsData = @json($monthlyTickets);
const yearlyTicketsData = @json($yearlyTickets);
const reportType = "{{ $reportType }}";
const selectedYear = "{{ $year }}";
const selectedMonth = "{{ $month }}";

// Chart configurations
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top'
        },
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    if (context.parsed.y !== null) {
                        if (context.dataset.label.includes('Doanh thu')) {
                            label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                        } else {
                            label += context.parsed.y + ' vé';
                        }
                    }
                    return label;
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                callback: function(value) {
                    return new Intl.NumberFormat('vi-VN').format(value);
                }
            }
        }
    }
};

// Generate labels based on report type
let dailyLabels = Object.keys(dailyRevenueData);
if (reportType === 'day' && dailyLabels.length > 0) {
    dailyLabels = Object.keys(dailyRevenueData).map(d => d + '/' + selectedYear);
}

// 1. Daily Revenue Chart
const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
const dailyRevenueChart = new Chart(dailyRevenueCtx, {
    type: 'line',
    data: {
        labels: dailyLabels,
        datasets: [{
            label: reportType === 'day' ? 'Doanh thu ngày ' + selectedMonth + '/' + selectedYear +
                ' (VNĐ)' : 'Doanh thu theo ngày (VNĐ)',
            data: Object.values(dailyRevenueData),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: chartOptions
});

// 2. Monthly Revenue Chart
const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
const monthlyRevenueChart = new Chart(monthlyRevenueCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(monthlyRevenueData).map(m => 'Tháng ' + m),
        datasets: [{
            label: 'Doanh thu năm ' + selectedYear + ' (VNĐ)',
            data: Object.values(monthlyRevenueData),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 2
        }]
    },
    options: chartOptions
});

// 3. Yearly Revenue Chart
const yearlyRevenueCtx = document.getElementById('yearlyRevenueChart').getContext('2d');
const yearlyRevenueChart = new Chart(yearlyRevenueCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(yearlyRevenueData).map(y => 'Năm ' + y),
        datasets: [{
            label: 'Doanh thu theo năm (VNĐ)',
            data: Object.values(yearlyRevenueData),
            backgroundColor: 'rgba(255, 159, 64, 0.6)',
            borderColor: 'rgb(255, 159, 64)',
            borderWidth: 2
        }]
    },
    options: chartOptions
});

// 4. Daily Tickets Chart
const dailyTicketsCtx = document.getElementById('dailyTicketsChart').getContext('2d');
const dailyTicketsChart = new Chart(dailyTicketsCtx, {
    type: 'line',
    data: {
        labels: dailyLabels,
        datasets: [{
            label: reportType === 'day' ? 'Số vé bán ngày ' + selectedMonth + '/' + selectedYear :
                'Số vé bán theo ngày',
            data: Object.values(dailyTicketsData),
            borderColor: 'rgb(153, 102, 255)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: chartOptions
});

// 5. Monthly Tickets Chart
const monthlyTicketsCtx = document.getElementById('monthlyTicketsChart').getContext('2d');
const monthlyTicketsChart = new Chart(monthlyTicketsCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(monthlyTicketsData).map(m => 'Tháng ' + m),
        datasets: [{
            label: 'Số vé bán năm ' + selectedYear,
            data: Object.values(monthlyTicketsData),
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 2
        }]
    },
    options: chartOptions
});

// 6. Yearly Tickets Chart
const yearlyTicketsCtx = document.getElementById('yearlyTicketsChart').getContext('2d');
const yearlyTicketsChart = new Chart(yearlyTicketsCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(yearlyTicketsData).map(y => 'Năm ' + y),
        datasets: [{
            label: 'Số vé bán theo năm',
            data: Object.values(yearlyTicketsData),
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgb(255, 99, 132)',
            borderWidth: 2
        }]
    },
    options: chartOptions
});

// Filter functionality
function applyFilter() {
    const reportType = document.getElementById('reportType').value;
    const year = document.getElementById('year').value;
    const month = document.getElementById('month').value;

    let url = new URL(window.location.href);
    url.searchParams.set('report_type', reportType);
    url.searchParams.set('year', year);
    url.searchParams.set('month', month);

    window.location.href = url.toString();
}

// Toggle filter visibility based on report type
document.getElementById('reportType').addEventListener('change', function() {
    const reportType = this.value;
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');

    if (reportType === 'day') {
        monthFilter.style.display = 'block';
        yearFilter.style.display = 'block';
    } else if (reportType === 'month') {
        monthFilter.style.display = 'none';
        yearFilter.style.display = 'block';
    } else if (reportType === 'year') {
        monthFilter.style.display = 'none';
        yearFilter.style.display = 'none';
    }
});

// Initial filter state
document.addEventListener('DOMContentLoaded', function() {
    const reportType = document.getElementById('reportType').value;
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');

    if (reportType === 'month') {
        monthFilter.style.display = 'none';
    } else if (reportType === 'year') {
        monthFilter.style.display = 'none';
        yearFilter.style.display = 'none';
    }
});

function exportChart(type) {
    window.location.href = "{{ route('admin.doanhthu.export') }}?type=" + type;
}
</script>
@endsection