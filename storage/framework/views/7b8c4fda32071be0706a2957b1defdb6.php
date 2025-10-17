<?php $__env->startSection('title', 'Quản lý nhân viên'); ?>

<?php $__env->startSection('page-title', 'Quản lý nhân viên'); ?>
<?php $__env->startSection('breadcrumb', 'Nhân viên'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách nhân viên</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.nhanvien.create')); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Thêm nhân viên
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..."
                                    value="<?php echo e(request('search')); ?>">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="chuc_vu" class="form-control" onchange="this.form.submit()">
                                <option value="all" <?php echo e(request('chuc_vu') == 'all' ? 'selected' : ''); ?>>Tất cả chức vụ
                                </option>
                                <option value="tài xế" <?php echo e(request('chuc_vu') == 'tài xế' ? 'selected' : ''); ?>>Tài xế
                                </option>
                                <option value="phụ xe" <?php echo e(request('chuc_vu') == 'phụ xe' ? 'selected' : ''); ?>>Phụ xe
                                </option>
                                <option value="nhân viên văn phòng"
                                    <?php echo e(request('chuc_vu') == 'nhân viên văn phòng' ? 'selected' : ''); ?>>Nhân viên văn
                                    phòng</option>
                                <option value="quản lý" <?php echo e(request('chuc_vu') == 'quản lý' ? 'selected' : ''); ?>>Quản lý
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="ma_nha_xe" class="form-control" onchange="this.form.submit()">
                                <option value="all" <?php echo e(request('ma_nha_xe') == 'all' ? 'selected' : ''); ?>>Tất cả nhà xe
                                </option>
                                <?php $__currentLoopData = $nhaXes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nhaXe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($nhaXe->ma_nha_xe); ?>"
                                    <?php echo e(request('ma_nha_xe') == $nhaXe->ma_nha_xe ? 'selected' : ''); ?>>
                                    <?php echo e($nhaXe->ten_nha_xe); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo e(route('admin.nhanvien.index')); ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên nhân viên</th>
                                <th>Chức vụ</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Nhà xe</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $nhanViens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nhanVien): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($nhanVien->ma_nv); ?></td>
                                <td><strong><?php echo e($nhanVien->ten_nv); ?></strong></td>
                                <td>
                                    <?php if($nhanVien->chuc_vu === 'tài xế'): ?>
                                    <span class="badge badge-primary"><?php echo e($nhanVien->chuc_vu); ?></span>
                                    <?php elseif($nhanVien->chuc_vu === 'phụ xe'): ?>
                                    <span class="badge badge-info"><?php echo e($nhanVien->chuc_vu); ?></span>
                                    <?php elseif($nhanVien->chuc_vu === 'quản lý'): ?>
                                    <span class="badge badge-danger"><?php echo e($nhanVien->chuc_vu); ?></span>
                                    <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e($nhanVien->chuc_vu); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($nhanVien->so_dien_thoai); ?></td>
                                <td><?php echo e($nhanVien->email); ?></td>
                                <td><?php echo e($nhanVien->nhaXe->ten_nha_xe ?? 'N/A'); ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('admin.nhanvien.show', $nhanVien)); ?>"
                                            class="btn btn-sm btn-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.nhanvien.edit', $nhanVien)); ?>"
                                            class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.nhanvien.destroy', $nhanVien)); ?>"
                                            style="display: inline;"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer clearfix">
                <?php echo e($nhanViens->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/admin/nhan_vien/index.blade.php ENDPATH**/ ?>