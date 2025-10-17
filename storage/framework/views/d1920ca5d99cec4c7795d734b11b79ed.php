

<?php $__env->startSection('title', 'Quản lý nhân viên'); ?>

<?php $__env->startSection('page-title', 'Quản lý nhân viên'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item active">Quản lý nhân viên</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistics Cards -->
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo e($statistics['total']); ?></h3>
                <p>Tổng nhân viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo e($statistics['tai_xe']); ?></h3>
                <p>Tài xế</p>
            </div>
            <div class="icon">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo e($statistics['pho_xe']); ?></h3>
                <p>Phụ xe</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo e($statistics['quan_ly']); ?></h3>
                <p>Quản lý</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-shield"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users mr-2"></i>Danh sách nhân viên của <?php echo e($nhaXe->name); ?></h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('bus-owner.nhan-vien.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Thêm nhân viên
                    </a>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="card-header border-0 bg-light">
                <form method="GET" id="searchForm">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="small mb-1"><i class="fas fa-search mr-1"></i>Tìm kiếm</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Tên, email, số điện thoại, chức vụ..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="small mb-1"><i class="fas fa-briefcase mr-1"></i>Chức vụ</label>
                            <select name="chuc_vu" class="form-control">
                                <option value="">Tất cả</option>
                                <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($position); ?>" <?php echo e(request('chuc_vu') == $position ? 'selected' : ''); ?>>
                                    <?php echo e($position); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1"><i class="fas fa-sort mr-1"></i>Hiển thị</label>
                            <select name="per_page" class="form-control">
                                <option value="10" <?php echo e(request('per_page') == 10 ? 'selected' : ''); ?>>10</option>
                                <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>25</option>
                                <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50</option>
                                <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1 d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary mr-1" title="Tìm kiếm">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                            <a href="<?php echo e(route('bus-owner.nhan-vien.index')); ?>" class="btn btn-secondary" title="Làm mới">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th>
                                <a href="?sort_by=ma_nv&sort_order=<?php echo e(request('sort_order') == 'asc' ? 'desc' : 'asc'); ?>">
                                    Mã NV
                                    <?php if(request('sort_by') == 'ma_nv'): ?>
                                    <i class="fas fa-sort-<?php echo e(request('sort_order') == 'asc' ? 'up' : 'down'); ?>"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=ten_nv&sort_order=<?php echo e(request('sort_order') == 'asc' ? 'desc' : 'asc'); ?>">
                                    Tên nhân viên
                                    <?php if(request('sort_by') == 'ten_nv'): ?>
                                    <i class="fas fa-sort-<?php echo e(request('sort_order') == 'asc' ? 'up' : 'down'); ?>"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>Chức vụ</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $nhanViens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nhanVien): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong class="text-primary">#<?php echo e($nhanVien->ma_nv); ?></strong></td>
                            <td>
                                <strong><?php echo e($nhanVien->ten_nv); ?></strong>
                            </td>
                            <td>
                                <?php if($nhanVien->chuc_vu == 'Tài xế'): ?>
                                <span class="badge badge-success"><?php echo e($nhanVien->chuc_vu); ?></span>
                                <?php elseif($nhanVien->chuc_vu == 'Phụ xe'): ?>
                                <span class="badge badge-info"><?php echo e($nhanVien->chuc_vu); ?></span>
                                <?php elseif($nhanVien->chuc_vu == 'Quản lý'): ?>
                                <span class="badge badge-danger"><?php echo e($nhanVien->chuc_vu); ?></span>
                                <?php else: ?>
                                <span class="badge badge-secondary"><?php echo e($nhanVien->chuc_vu); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <i class="fas fa-phone mr-1"></i><?php echo e($nhanVien->so_dien_thoai); ?>

                            </td>
                            <td>
                                <i class="fas fa-envelope mr-1"></i><?php echo e($nhanVien->email); ?>

                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('bus-owner.nhan-vien.show', $nhanVien->ma_nv)); ?>"
                                        class="btn btn-sm btn-info"
                                        title="Xem chi tiết"
                                        data-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('bus-owner.nhan-vien.edit', $nhanVien->ma_nv)); ?>"
                                        class="btn btn-sm btn-warning"
                                        title="Chỉnh sửa"
                                        data-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-danger btn-delete"
                                        data-id="<?php echo e($nhanVien->ma_nv); ?>"
                                        data-name="<?php echo e($nhanVien->ten_nv); ?>"
                                        title="Xóa"
                                        data-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-<?php echo e($nhanVien->ma_nv); ?>"
                                    method="POST"
                                    action="<?php echo e(route('bus-owner.nhan-vien.destroy', $nhanVien->ma_nv)); ?>"
                                    style="display: none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Chưa có nhân viên nào</p>
                                <a href="<?php echo e(route('bus-owner.nhan-vien.create')); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i>Thêm nhân viên đầu tiên
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($nhanViens->hasPages()): ?>
            <div class="card-footer clearfix">
                <div class="float-left">
                    <p class="text-sm text-muted">
                        Hiển thị <?php echo e($nhanViens->firstItem()); ?> - <?php echo e($nhanViens->lastItem()); ?>

                        trong tổng số <?php echo e($nhanViens->total()); ?> nhân viên
                    </p>
                </div>
                <div class="float-right">
                    <?php echo e($nhanViens->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle delete button click with SweetAlert
        $('.btn-delete').on('click', function() {
            const nhanVienId = $(this).data('id');
            const nhanVienName = $(this).data('name');

            Swal.fire({
                title: 'Xác nhận xóa?',
                html: `Bạn có chắc chắn muốn xóa nhân viên:<br><strong>${nhanVienName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Xóa',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#delete-form-${nhanVienId}`).submit();
                }
            });
        });

        // Auto-submit form on select change
        $('select[name="chuc_vu"], select[name="per_page"]').on('change', function() {
            $('#searchForm').submit();
        });

        // Handle Enter key in search input
        $('input[name="search"]').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#searchForm').submit();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        display: block;
        margin-bottom: 20px;
        position: relative;
    }

    .small-box>.inner {
        padding: 10px;
    }

    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        padding: 0;
        white-space: nowrap;
    }

    .small-box p {
        font-size: 1rem;
        margin: 0;
    }

    .small-box .icon {
        color: rgba(0, 0, 0, .15);
        z-index: 0;
    }

    .small-box .icon>i {
        font-size: 70px;
        position: absolute;
        right: 15px;
        top: 15px;
    }

    .bg-info {
        background-color: #17a2b8 !important;
        color: #fff;
    }

    .bg-success {
        background-color: #28a745 !important;
        color: #fff;
    }

    .bg-warning {
        background-color: #ffc107 !important;
        color: #1f2d3d;
    }

    .bg-danger {
        background-color: #dc3545 !important;
        color: #fff;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/nhan_vien/index.blade.php ENDPATH**/ ?>