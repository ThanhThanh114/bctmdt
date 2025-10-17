

<?php $__env->startSection('title', 'Chi tiết Nhà xe'); ?>
<?php $__env->startSection('page-title', 'Chi tiết Nhà xe'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.nha-xe.index')); ?>">Quản lý Nhà xe</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="text-primary">
                    <i class="fas fa-building mr-2"></i>
                    <?php echo e($nhaXe->ten_nha_xe); ?>

                </h2>
                <div>
                    <a href="<?php echo e(route('bus-owner.nha-xe.edit', $nhaXe->ma_nha_xe)); ?>" class="btn btn-warning btn-lg">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="<?php echo e(route('bus-owner.nha-xe.index')); ?>" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo e($statistics['total_trips']); ?></h3>
                    <p>Tổng chuyến xe</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bus"></i>
                </div>
                <a href="<?php echo e(route('bus-owner.trips.index')); ?>" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?php echo e($statistics['active_trips']); ?></h3>
                    <p>Chuyến đang hoạt động</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="<?php echo e(route('bus-owner.trips.index')); ?>" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?php echo e($statistics['total_stations']); ?></h3>
                    <p>Trạm xe</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <a href="<?php echo e(route('bus-owner.tram-xe.index')); ?>" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?php echo e($statistics['total_employees']); ?></h3>
                    <p>Nhân viên</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Information Card -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        Thông tin chi tiết
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th width="200" class="bg-light">
                                    <i class="fas fa-hashtag text-muted mr-2"></i>
                                    Mã nhà xe
                                </th>
                                <td>
                                    <span class="badge badge-info badge-lg"><?php echo e($nhaXe->ma_nha_xe); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="fas fa-building text-primary mr-2"></i>
                                    Tên nhà xe
                                </th>
                                <td>
                                    <strong class="text-primary" style="font-size: 1.1rem;">
                                        <?php echo e($nhaXe->ten_nha_xe); ?>

                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                    Địa chỉ
                                </th>
                                <td><?php echo e($nhaXe->dia_chi); ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="fas fa-phone text-success mr-2"></i>
                                    Số điện thoại
                                </th>
                                <td>
                                    <a href="tel:<?php echo e($nhaXe->so_dien_thoai); ?>" class="text-success">
                                        <strong><?php echo e($nhaXe->so_dien_thoai); ?></strong>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="fas fa-envelope text-info mr-2"></i>
                                    Email
                                </th>
                                <td>
                                    <?php if($nhaXe->email): ?>
                                    <a href="mailto:<?php echo e($nhaXe->email); ?>" class="text-info">
                                        <?php echo e($nhaXe->email); ?>

                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-chart-line mr-2"></i>
                            Doanh thu ước tính
                        </h5>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                role="progressbar"
                                style="width: 75%">
                                <strong><?php echo e(number_format($statistics['total_revenue'])); ?> VNĐ</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Card -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2"></i>
                        Hoạt động gần đây
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-primary">Hôm nay</span>
                        </div>
                        <div>
                            <i class="fas fa-bus bg-info"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> 2 giờ trước
                                </span>
                                <h3 class="timeline-header">
                                    <strong>Chuyến xe mới</strong>
                                </h3>
                                <div class="timeline-body">
                                    Đã thêm <?php echo e($statistics['active_trips']); ?> chuyến xe mới
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-ticket-alt bg-success"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> 5 giờ trước
                                </span>
                                <h3 class="timeline-header">
                                    <strong>Vé được đặt</strong>
                                </h3>
                                <div class="timeline-body">
                                    Có khách hàng mới đặt vé
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-chart-line bg-warning"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> Hôm qua
                                </span>
                                <h3 class="timeline-header">
                                    <strong>Báo cáo doanh thu</strong>
                                </h3>
                                <div class="timeline-body">
                                    Doanh thu tháng này tăng 15%
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-warning text-white">
                    <h3 class="card-title">
                        <i class="fas fa-bolt mr-2"></i>
                        Thao tác nhanh
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('bus-owner.trips.create')); ?>" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Thêm chuyến xe
                        </a>
                        <a href="<?php echo e(route('bus-owner.tram-xe.create')); ?>" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Thêm trạm xe
                        </a>
                        <a href="<?php echo e(route('bus-owner.doanh-thu.index')); ?>" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Xem doanh thu
                        </a>
                        <a href="<?php echo e(route('bus-owner.nha-xe.edit', $nhaXe->ma_nha_xe)); ?>" class="btn btn-warning btn-block">
                            <i class="fas fa-edit mr-2"></i>
                            Cập nhật thông tin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .small-box {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .card {
        border-radius: 10px;
        border: none;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .badge-lg {
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
    }

    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }

    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #dee2e6;
        left: 31px;
        margin: 0;
    }

    .timeline>div {
        margin-bottom: 15px;
        position: relative;
    }

    .timeline>div>.timeline-item {
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        border-radius: 3px;
        margin-top: 0;
        background: #fff;
        color: #444;
        margin-left: 60px;
        margin-right: 15px;
        padding: 10px;
        position: relative;
    }

    .timeline>div>.fa,
    .timeline>div>.fas,
    .timeline>div>.far,
    .timeline>div>.fab,
    .timeline>div>.fal,
    .timeline>div>.fad,
    .timeline>div>.svg-inline--fa,
    .timeline>div>.ion {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #666;
        background: #d2d6de;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }

    .timeline>.time-label>span {
        font-weight: 600;
        padding: 5px;
        display: inline-block;
        background-color: #fff;
        border-radius: 4px;
    }

    .timeline-item>.time {
        color: #999;
        float: right;
        padding: 10px;
        font-size: 12px;
    }

    .timeline-item>.timeline-header {
        margin: 0;
        color: #555;
        border-bottom: 1px solid #f4f4f4;
        padding: 10px;
        font-size: 16px;
        line-height: 1.1;
    }

    .timeline-item>.timeline-body {
        padding: 10px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    <?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: '<?php echo e(session('
        success ')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
    <?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/nha_xe/show.blade.php ENDPATH**/ ?>