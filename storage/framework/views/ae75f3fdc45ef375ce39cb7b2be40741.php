<?php $__env->startSection('title', 'Quản lý Tin tức'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tin tức</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo e($stats['total']); ?></h3>
                        <p>Tổng tin tức</p>
                    </div>
                    <div class="icon"><i class="fas fa-newspaper"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo e($stats['today']); ?></h3>
                        <p>Đăng hôm nay</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-day"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo e($stats['this_month']); ?></h3>
                        <p>Đăng tháng này</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách tin tức</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.tintuc.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tiêu đề tin tức"
                                value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="nha_xe" class="form-control">
                                <option value="">-- Nhà xe --</option>
                                <?php $__currentLoopData = $nhaXe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($nx->ma_nha_xe); ?>"
                                    <?php echo e(request('nha_xe') == $nx->ma_nha_xe ? 'selected' : ''); ?>><?php echo e($nx->ten_nha_xe); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="tac_gia" class="form-control">
                                <option value="">-- Tác giả --</option>
                                <?php $__currentLoopData = $tacGia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tg->id); ?>" <?php echo e(request('tac_gia') == $tg->id ? 'selected' : ''); ?>>
                                    <?php echo e($tg->fullname); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i>
                                Tìm</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Nhà xe</th>
                                <th>Tác giả</th>
                                <th>Ngày đăng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $tinTuc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($item->tieu_de); ?></strong></td>
                                <td><?php echo e($item->nhaXe->ten_nha_xe ?? 'N/A'); ?></td>
                                <td><?php echo e($item->user->fullname ?? 'N/A'); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($item->ngay_dang)->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('admin.tintuc.show', $item->ma_tin)); ?>"
                                        class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo e(route('admin.tintuc.edit', $item->ma_tin)); ?>"
                                        class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="<?php echo e(route('admin.tintuc.destroy', $item->ma_tin)); ?>" method="POST"
                                        style="display:inline-block;" onsubmit="return confirm('Xóa?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3"><?php echo e($tinTuc->appends(request()->query())->links()); ?></div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/AdminLTE/admin/tin_tuc/index.blade.php ENDPATH**/ ?>