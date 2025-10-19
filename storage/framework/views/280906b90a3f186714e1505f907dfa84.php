<?php $__env->startSection('title', 'Quản lý Khuyến mãi'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Khuyến mãi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Khuyến mãi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo e($stats['total']); ?></h3>
                        <p>Tổng khuyến mãi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo e($stats['active']); ?></h3>
                        <p>Đang áp dụng</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo e($stats['upcoming']); ?></h3>
                        <p>Sắp diễn ra</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo e($stats['expired']); ?></h3>
                        <p>Đã hết hạn</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách khuyến mãi</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.khuyenmai.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" action="<?php echo e(route('admin.khuyenmai.index')); ?>" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tên hoặc mã khuyến mãi"
                                value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">-- Trạng thái --</option>
                                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Đang áp
                                    dụng</option>
                                <option value="upcoming" <?php echo e(request('status') == 'upcoming' ? 'selected' : ''); ?>>Sắp
                                    diễn ra</option>
                                <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Đã hết
                                    hạn</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="giam_gia" class="form-control" placeholder="% giảm giá"
                                value="<?php echo e(request('giam_gia')); ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Mã KM</th>
                                <th>Tên KM</th>
                                <th>Mã Code</th>
                                <th>Giảm giá</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $khuyenMai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                            $now = now();
                            $start = \Carbon\Carbon::parse($item->ngay_bat_dau);
                            $end = \Carbon\Carbon::parse($item->ngay_ket_thuc);
                            ?>
                            <tr>
                                <td><strong><?php echo e($item->ma_km); ?></strong></td>
                                <td><?php echo e($item->ten_km); ?></td>
                                <td><code><?php echo e($item->ma_code); ?></code></td>
                                <td><span class="badge badge-success"><?php echo e($item->giam_gia); ?>%</span></td>
                                <td><?php echo e($start->format('d/m/Y')); ?></td>
                                <td><?php echo e($end->format('d/m/Y')); ?></td>
                                <td>
                                    <?php if($now->between($start, $end)): ?>
                                    <span class="badge badge-success">Đang áp dụng</span>
                                    <?php elseif($now->lt($start)): ?>
                                    <span class="badge badge-warning">Sắp diễn ra</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger">Đã hết hạn</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.khuyenmai.show', $item->ma_km)); ?>"
                                        class="btn btn-sm btn-info" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.khuyenmai.edit', $item->ma_km)); ?>"
                                        class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.khuyenmai.destroy', $item->ma_km)); ?>" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    <?php echo e($khuyenMai->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/AdminLTE/admin/khuyen_mai/index.blade.php ENDPATH**/ ?>