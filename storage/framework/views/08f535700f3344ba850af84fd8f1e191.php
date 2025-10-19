<?php $__env->startSection('title', 'Quản lý Bình luận'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Bình luận</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bình luận</li>
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
                        <p>Tổng bình luận</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo e($stats['cho_duyet']); ?></h3>
                        <p>Chờ duyệt</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo e($stats['da_duyet']); ?></h3>
                        <p>Đã duyệt</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo e($stats['tu_choi']); ?></h3>
                        <p>Từ chối</p>
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
                <h3 class="card-title">
                    <i class="fas fa-comments"></i> Danh sách bình luận
                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.binhluan.create')); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Thêm bình luận
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Success/Error Messages -->
                <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

                </div>
                <?php endif; ?>

                <!-- Search Form -->
                <form method="GET" action="<?php echo e(route('admin.binhluan.index')); ?>" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="trang_thai" class="form-control">
                                <option value="">-- Trạng thái --</option>
                                <option value="cho_duyet" <?php echo e(request('trang_thai') == 'cho_duyet' ? 'selected' : ''); ?>>
                                    Chờ duyệt</option>
                                <option value="da_duyet" <?php echo e(request('trang_thai') == 'da_duyet' ? 'selected' : ''); ?>>Đã
                                    duyệt</option>
                                <option value="tu_choi" <?php echo e(request('trang_thai') == 'tu_choi' ? 'selected' : ''); ?>>Từ
                                    chối</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="user" class="form-control" placeholder="Tên người dùng"
                                value="<?php echo e(request('user')); ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="ngay_bl" class="form-control" value="<?php echo e(request('ngay_bl')); ?>">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Người dùng</th>
                                <th width="25%">Chuyến xe</th>
                                <th width="30%">Nội dung</th>
                                <th>Đánh giá</th>
                                <th>Ngày BL</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $binhLuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->ma_bl); ?></td>
                                <td>
                                    <strong><?php echo e($item->user->fullname ?? 'N/A'); ?></strong><br>
                                    <small class="text-muted"><?php echo e($item->user->email ?? ''); ?></small>
                                </td>
                                <td>
                                    <?php if($item->chuyenXe): ?>
                                    <i class="fas fa-map-marker-alt text-success"></i>
                                    <?php echo e($item->chuyenXe->tramDi->ten_tram ?? 'N/A'); ?>

                                    <i class="fas fa-arrow-right text-primary"></i>
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    <?php echo e($item->chuyenXe->tramDen->ten_tram ?? 'N/A'); ?><br>
                                    <small class="text-muted">
                                        <i class="far fa-calendar"></i> <?php echo e(\Carbon\Carbon::parse($item->chuyenXe->ngay_di)->format('d/m/Y')); ?>

                                        <i class="far fa-clock ml-2"></i> <?php echo e($item->chuyenXe->gio_di ?? ''); ?>

                                    </small>
                                    <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <p class="mb-1"><?php echo e(Str::limit($item->noi_dung, 100)); ?></p>
                                    <?php if($item->replies && $item->replies->count() > 0): ?>
                                    <small class="text-info">
                                        <i class="fas fa-reply"></i> <?php echo e($item->replies->count()); ?> phản hồi
                                    </small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($item->so_sao): ?>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <=$item->so_sao): ?>
                                        <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                        <i class="far fa-star text-muted"></i>
                                        <?php endif; ?>
                                        <?php endfor; ?>
                                        <br><small>(<?php echo e($item->so_sao); ?>/5)</small>
                                        <?php else: ?>
                                        <small class="text-muted">Chưa đánh giá</small>
                                        <?php endif; ?>
                                </td>
                                <td><?php echo e(\Carbon\Carbon::parse($item->ngay_bl)->format('d/m/Y')); ?></td>
                                <td>
                                    <?php if($item->trang_thai == 'cho_duyet'): ?>
                                    <span class="badge badge-warning">Chờ duyệt</span>
                                    <?php elseif($item->trang_thai == 'da_duyet'): ?>
                                    <span class="badge badge-success">Đã duyệt</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger">Từ chối</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.binhluan.show', $item->ma_bl)); ?>"
                                        class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($item->trang_thai === 'cho_duyet'): ?>
                                    <form action="<?php echo e(route('admin.binhluan.approve', $item->ma_bl)); ?>" method="POST"
                                        style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success" title="Duyệt">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('admin.binhluan.destroy', $item->ma_bl)); ?>" method="POST"
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
                    <?php echo e($binhLuan->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/AdminLTE/admin/binh_luan/index.blade.php ENDPATH**/ ?>