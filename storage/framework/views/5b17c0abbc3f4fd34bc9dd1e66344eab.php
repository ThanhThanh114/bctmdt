<?php $__env->startSection('title', 'Quản lý Đặt vé'); ?>
<?php $__env->startSection('page-title', 'Danh sách Đặt vé'); ?>
<?php $__env->startSection('breadcrumb', 'Đặt vé'); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo e($stats['total']); ?></h3>
                <p>Tổng vé</p>
            </div>
            <div class="icon"><i class="fas fa-ticket-alt"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo e($stats['pending']); ?></h3>
                <p>Chờ thanh toán</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo e($stats['confirmed']); ?></h3>
                <p>Đã thanh toán</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo e($stats['cancelled']); ?></h3>
                <p>Đã hủy</p>
            </div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
</div>

<!-- Filter & Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách vé</h3>
            </div>

            <!-- Filters -->
            <div class="card-header border-0">
                <form method="GET" class="row">
                    <div class="col-md-3">
                        <select name="trang_thai" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Đã đặt" <?php echo e(request('trang_thai') == 'Đã đặt' ? 'selected' : ''); ?>>Đã đặt</option>
                            <option value="Đã xác nhận" <?php echo e(request('trang_thai') == 'Đã xác nhận' ? 'selected' : ''); ?>>Đã xác nhận</option>
                            <option value="Đã thanh toán" <?php echo e(request('trang_thai') == 'Đã thanh toán' ? 'selected' : ''); ?>>Đã thanh toán</option>
                            <option value="Đã hủy" <?php echo e(request('trang_thai') == 'Đã hủy' ? 'selected' : ''); ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="ngay_dat" class="form-control" value="<?php echo e(request('ngay_dat')); ?>" placeholder="Ngày đặt">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-1"></i> Lọc
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('bus-owner.dat-ve.index')); ?>" class="btn btn-secondary btn-block">
                            <i class="fas fa-redo mr-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Chuyến xe</th>
                            <th>Số lượng vé</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($booking->id); ?></td>
                            <td>
                                <?php if($booking->user): ?>
                                <strong><?php echo e($booking->user->fullname ?? $booking->user->username ?? 'N/A'); ?></strong><br>
                                <small class="text-muted"><?php echo e($booking->user->email ?? ''); ?></small>
                                <?php else: ?>
                                <span class="text-muted">Khách hàng không tồn tại</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($booking->chuyenXe->ten_xe ?? 'N/A'); ?></strong><br>
                                <small class="text-muted">
                                    <?php echo e($booking->chuyenXe->ngay_di ? \Carbon\Carbon::parse($booking->chuyenXe->ngay_di)->format('d/m/Y') : ''); ?>

                                    <?php echo e($booking->chuyenXe->gio_di ? \Carbon\Carbon::parse($booking->chuyenXe->gio_di)->format('H:i') : ''); ?>

                                </small>
                            </td>
                            <td>
                                <span class="badge badge-info"><?php echo e($booking->so_luong_ve ?? 1); ?></span>
                            </td>
                            <td>
                                <strong><?php echo e(number_format($booking->chuyenXe->gia_ve * ($booking->so_luong_ve ?? 1))); ?>đ</strong>
                            </td>
                            <td><?php echo e($booking->ngay_dat ? \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y H:i') : 'N/A'); ?></td>
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
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('bus-owner.dat-ve.show', $booking->id)); ?>"
                                        class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($booking->trang_thai == 'Đã đặt'): ?>
                                    <form action="<?php echo e(route('bus-owner.dat-ve.confirm', $booking->id)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn btn-sm btn-success"
                                            title="Xác nhận" onclick="return confirm('Xác nhận đặt vé này?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if($booking->trang_thai != 'Đã hủy'): ?>
                                    <form action="<?php echo e(route('bus-owner.dat-ve.cancel', $booking->id)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            title="Hủy vé" onclick="return confirm('Hủy đặt vé này?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-ticket-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Chưa có vé nào</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <?php echo e($bookings->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/dat_ve/index.blade.php ENDPATH**/ ?>