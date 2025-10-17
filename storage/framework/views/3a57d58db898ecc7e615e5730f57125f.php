<?php $__env->startSection('title', 'Chi tiết Chuyến xe'); ?>
<?php $__env->startSection('page-title', 'Chi tiết Chuyến xe #' . $trip->id); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.trips.index')); ?>">Quản lý Chuyến xe</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <!-- Trip Info Card -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bus mr-2"></i>Thông tin Chuyến xe</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('bus-owner.trips.edit', $trip->id)); ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th style="width: 150px"><i class="fas fa-hashtag text-muted mr-2"></i>Mã chuyến:</th>
                                <td><strong class="text-primary">#<?php echo e($trip->id); ?></strong></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-tag text-muted mr-2"></i>Tên chuyến:</th>
                                <td><strong><?php echo e($trip->ten_xe); ?></strong></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-route text-muted mr-2"></i>Tuyến đường:</th>
                                <td>
                                    <?php if($trip->tramDi && $trip->tramDen): ?>
                                    <span class="badge badge-success"><?php echo e($trip->tramDi->ten_tram); ?></span>
                                    
                                    <?php if($trip->tram_trung_gian): ?>
                                        <?php
                                            $tramTrungGianIds = explode(',', $trip->tram_trung_gian);
                                            $tramTrungGian = \App\Models\TramXe::whereIn('ma_tram_xe', $tramTrungGianIds)->get();
                                        ?>
                                        <?php $__currentLoopData = $tramTrungGian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <i class="fas fa-arrow-right mx-1"></i>
                                            <span class="badge badge-info"><?php echo e($tram->ten_tram); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    <span class="badge badge-danger"><?php echo e($trip->tramDen->ten_tram); ?></span>
                                    <?php else: ?>
                                    <span class="text-muted">Chưa có thông tin tuyến</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-calendar text-muted mr-2"></i>Ngày đi:</th>
                                <td><?php echo e(\Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y')); ?></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-clock text-muted mr-2"></i>Giờ khởi hành:</th>
                                <td><strong
                                        class="text-info"><?php echo e(\Carbon\Carbon::parse($trip->gio_di)->format('H:i')); ?></strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th style="width: 150px"><i class="fas fa-car text-muted mr-2"></i>Loại xe:</th>
                                <td><?php echo e($trip->loai_xe ?? 'Chưa cập nhật'); ?></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-exchange-alt text-muted mr-2"></i>Loại chuyến:</th>
                                <td>
                                    <?php if($trip->loai_chuyen == 'Một chiều'): ?>
                                    <span class="badge badge-info"><?php echo e($trip->loai_chuyen); ?></span>
                                    <?php else: ?>
                                    <span class="badge badge-primary"><?php echo e($trip->loai_chuyen); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-dollar-sign text-muted mr-2"></i>Giá vé:</th>
                                <td><strong class="text-success"><?php echo e(number_format($trip->gia_ve)); ?>đ</strong></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-chair text-muted mr-2"></i>Số chỗ:</th>
                                <td><?php echo e($trip->so_cho); ?> chỗ</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-ticket-alt text-muted mr-2"></i>Đã bán:</th>
                                <td>
                                    <span class="badge badge-warning"><?php echo e($trip->so_ve); ?> vé</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if($trip->ten_tai_xe || $trip->sdt_tai_xe): ?>
                <hr>
                <h5 class="text-secondary"><i class="fas fa-user-tie mr-2"></i>Thông tin Tài xế</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tên tài xế:</strong> <?php echo e($trip->ten_tai_xe ?? 'Chưa cập nhật'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Số điện thoại:</strong> <?php echo e($trip->sdt_tai_xe ?? 'Chưa cập nhật'); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Bookings Card -->
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-ticket-alt mr-2"></i>Vé đã đặt gần đây</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Khách hàng</th>
                            <th>Email</th>
                            <th>Số vé</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recent_bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($booking->user->name ?? 'N/A'); ?></td>
                            <td><?php echo e($booking->user->email ?? 'N/A'); ?></td>
                            <td><span class="badge badge-info"><?php echo e($booking->so_luong_ve ?? 1); ?></span></td>
                            <td><?php echo e($booking->ngay_dat ? \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') : 'N/A'); ?>

                            </td>
                            <td>
                                <?php if($booking->trang_thai == 'Đã thanh toán'): ?>
                                <span class="badge badge-success"><?php echo e($booking->trang_thai); ?></span>
                                <?php elseif($booking->trang_thai == 'Đã xác nhận'): ?>
                                <span class="badge badge-primary"><?php echo e($booking->trang_thai); ?></span>
                                <?php elseif($booking->trang_thai == 'Đã đặt'): ?>
                                <span class="badge badge-warning"><?php echo e($booking->trang_thai); ?></span>
                                <?php else: ?>
                                <span class="badge badge-danger"><?php echo e($booking->trang_thai); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có vé nào được đặt</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Statistics Card -->
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Thống kê</h3>
            </div>
            <div class="card-body">
                <?php
                $totalSeats = $trip->so_cho;
                $bookedSeats = $trip->so_ve;
                $availableSeats = $totalSeats - $bookedSeats;
                $bookingRate = $totalSeats > 0 ? ($bookedSeats / $totalSeats) * 100 : 0;
                ?>

                <div class="mb-4">
                    <h6 class="text-center">Tỷ lệ lấp đầy</h6>
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar <?php echo e($bookingRate > 80 ? 'bg-success' : ($bookingRate > 50 ? 'bg-info' : 'bg-warning')); ?>"
                            role="progressbar" style="width: <?php echo e($bookingRate); ?>%">
                            <strong><?php echo e(number_format($bookingRate, 1)); ?>%</strong>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted"><?php echo e($bookedSeats); ?> / <?php echo e($totalSeats); ?> ghế</small>
                    </div>
                </div>

                <div class="info-box bg-light mb-3">
                    <span class="info-box-icon bg-warning"><i class="fas fa-chair"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Đã đặt</span>
                        <span class="info-box-number"><?php echo e($bookedSeats); ?> ghế</span>
                    </div>
                </div>

                <div class="info-box bg-light mb-3">
                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Còn trống</span>
                        <span class="info-box-number"><?php echo e($availableSeats); ?> ghế</span>
                    </div>
                </div>

                <div class="info-box bg-light">
                    <span class="info-box-icon bg-primary"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Doanh thu dự kiến</span>
                        <span class="info-box-number"><?php echo e(number_format($bookedSeats * $trip->gia_ve)); ?>đ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cogs mr-2"></i>Thao tác nhanh</h3>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('bus-owner.trips.edit', $trip->id)); ?>" class="btn btn-warning btn-block mb-2">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa chuyến xe
                </a>
                <a href="<?php echo e(route('bus-owner.dat-ve.index')); ?>" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-ticket-alt mr-2"></i>Xem tất cả vé đã đặt
                </a>
                <a href="<?php echo e(route('bus-owner.trips.index')); ?>" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
                </a>

                <form action="<?php echo e(route('bus-owner.trips.destroy', $trip)); ?>" method="POST" class="mt-3"
                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa chuyến xe này? Hành động này không thể hoàn tác!')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-trash mr-2"></i>Xóa chuyến xe
                    </button>
                </form>
            </div>
        </div>

        <!-- Status Info Card -->
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Trạng thái chuyến</h3>
            </div>
            <div class="card-body">
                <?php
                // Parse ngày đi và giờ đi
                $ngayDi = \Carbon\Carbon::parse($trip->ngay_di);
                $gioDi = \Carbon\Carbon::parse($trip->gio_di);
                // Tạo datetime bằng cách set time cho ngày đi
                $tripDateTime = $ngayDi->copy()->setTime($gioDi->hour, $gioDi->minute, $gioDi->second);
                $now = \Carbon\Carbon::now();
                $isPast = $tripDateTime->isPast();
                $isToday = $tripDateTime->isToday();
                $isTomorrow = $tripDateTime->isTomorrow();
                ?>

                <?php if($isPast): ?>
                <div class="alert alert-secondary mb-0">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Chuyến đã qua</strong><br>
                    <small>Chuyến xe này đã khởi hành</small>
                </div>
                <?php elseif($isToday): ?>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Chuyến hôm nay</strong><br>
                    <small>Khởi hành lúc <?php echo e(\Carbon\Carbon::parse($trip->gio_di)->format('H:i')); ?></small>
                </div>
                <?php elseif($isTomorrow): ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-calendar-day mr-2"></i>
                    <strong>Chuyến ngày mai</strong><br>
                    <small>Khởi hành lúc <?php echo e(\Carbon\Carbon::parse($trip->gio_di)->format('H:i')); ?></small>
                </div>
                <?php else: ?>
                <div class="alert alert-success mb-0">
                    <i class="fas fa-clock mr-2"></i>
                    <strong>Sắp khởi hành</strong><br>
                    <small><?php echo e($tripDateTime->diffForHumans()); ?></small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .card-outline {
        border-top: 3px solid;
    }

    .info-box {
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        border-radius: .25rem;
        background-color: #fff;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: .5rem;
        position: relative;
    }

    .info-box-icon {
        border-radius: .25rem;
        align-items: center;
        display: flex;
        font-size: 1.875rem;
        justify-content: center;
        text-align: center;
        width: 70px;
    }

    .info-box-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.8;
        flex: 1;
        padding: 0 10px;
    }

    .progress {
        border-radius: 15px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
    }

    .progress-bar {
        border-radius: 15px;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/trips/show.blade.php ENDPATH**/ ?>