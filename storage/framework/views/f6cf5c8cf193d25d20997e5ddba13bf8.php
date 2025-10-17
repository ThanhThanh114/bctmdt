<?php $__env->startSection('title', 'Doanh thu'); ?>
<?php $__env->startSection('page-title', 'Báo cáo Doanh thu'); ?>
<?php $__env->startSection('breadcrumb', 'Doanh thu'); ?>

<?php $__env->startSection('content'); ?>
<!-- Filter Form -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('bus-owner.doanh-thu.index')); ?>" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="year" class="mr-2">Năm:</label>
                        <select name="year" id="year" class="form-control">
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label for="month" class="mr-2">Tháng:</label>
                        <select name="month" id="month" class="form-control">
                            <?php for($m = 1; $m <= 12; $m++): ?> <option value="<?php echo e(str_pad($m, 2, '0', STR_PAD_LEFT)); ?>"
                                <?php echo e($month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''); ?>>
                                Tháng <?php echo e($m); ?>

                                </option>
                                <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stats Boxes -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo e(number_format($stats['monthly_revenue'])); ?>đ</h3>
                <p>Doanh thu tháng <?php echo e($month); ?></p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo e(number_format($stats['yearly_revenue'])); ?>đ</h3>
                <p>Doanh thu năm <?php echo e($year); ?></p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo e(number_format($stats['monthly_bookings'])); ?></h3>
                <p>Vé đã bán (tháng <?php echo e($month); ?>)</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo e(number_format($stats['average_booking'])); ?>đ</h3>
                <p>Giá trung bình/vé</p>
            </div>
            <div class="icon">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Doanh thu theo tháng năm <?php echo e($year); ?></h3>
            </div>
            <div class="card-body">
                <canvas id="monthlyRevenueChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Doanh thu hôm nay</h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Doanh thu</span>
                        <span class="info-box-number"><?php echo e(number_format($stats['today_revenue'])); ?>đ</span>
                    </div>
                </div>
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Số vé</span>
                        <span class="info-box-number"><?php echo e($stats['today_bookings']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Trips -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top 10 chuyến xe có doanh thu cao nhất (tháng <?php echo e($month); ?>/<?php echo e($year); ?>)</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên chuyến xe</th>
                            <th>Ngày đi</th>
                            <th>Số vé đã bán</th>
                            <th>Giá vé</th>
                            <th>Tổng doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $topTrips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($trip->ten_xe); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y')); ?></td>
                            <td><?php echo e($trip->bookings_count); ?></td>
                            <td><?php echo e(number_format($trip->gia_ve)); ?>đ</td>
                            <td><strong><?php echo e(number_format($trip->total_revenue)); ?>đ</strong></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Monthly Revenue Chart
        const ctx = document.getElementById('monthlyRevenueChart');
        const monthlyData = <?php echo json_encode($monthlyRevenue, 15, 512) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => 'Tháng ' + d.month),
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: monthlyData.map(d => d.revenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + 'đ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/doanh_thu/index.blade.php ENDPATH**/ ?>