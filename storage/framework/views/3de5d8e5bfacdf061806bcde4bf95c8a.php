

<?php $__env->startSection('title', 'Chi tiết nhân viên'); ?>

<?php $__env->startSection('page-title', 'Chi tiết nhân viên'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.nhan-vien.index')); ?>">Quản lý nhân viên</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Main info -->
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user mr-2"></i>Thông tin nhân viên</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('bus-owner.nhan-vien.edit', $nhanVien->ma_nv)); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="200"><i class="fas fa-hashtag mr-2"></i>Mã nhân viên</th>
                            <td><strong class="text-primary">#<?php echo e($nhanVien->ma_nv); ?></strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user mr-2"></i>Tên nhân viên</th>
                            <td><strong><?php echo e($nhanVien->ten_nv); ?></strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-briefcase mr-2"></i>Chức vụ</th>
                            <td>
                                <?php if($nhanVien->chuc_vu == 'Tài xế'): ?>
                                <span class="badge badge-success badge-lg"><?php echo e($nhanVien->chuc_vu); ?></span>
                                <?php elseif($nhanVien->chuc_vu == 'Phụ xe'): ?>
                                <span class="badge badge-info badge-lg"><?php echo e($nhanVien->chuc_vu); ?></span>
                                <?php elseif($nhanVien->chuc_vu == 'Quản lý'): ?>
                                <span class="badge badge-danger badge-lg"><?php echo e($nhanVien->chuc_vu); ?></span>
                                <?php else: ?>
                                <span class="badge badge-secondary badge-lg"><?php echo e($nhanVien->chuc_vu); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone mr-2"></i>Số điện thoại</th>
                            <td>
                                <a href="tel:<?php echo e($nhanVien->so_dien_thoai); ?>" class="text-decoration-none">
                                    <?php echo e($nhanVien->so_dien_thoai); ?>

                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope mr-2"></i>Email</th>
                            <td>
                                <a href="mailto:<?php echo e($nhanVien->email); ?>" class="text-decoration-none">
                                    <?php echo e($nhanVien->email); ?>

                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-building mr-2"></i>Nhà xe</th>
                            <td>
                                <?php if($nhanVien->nhaXe): ?>
                                <strong><?php echo e($nhanVien->nhaXe->name); ?></strong>
                                <?php else: ?>
                                <span class="text-muted">Chưa có thông tin</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Thông tin bổ sung</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-route"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Chuyến xe phụ trách</span>
                                <span class="info-box-number"><?php echo e($statistics['trips_count'] ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Năm kinh nghiệm</span>
                                <span class="info-box-number"><?php echo e($statistics['years_of_service'] ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <h5><i class="icon fas fa-info"></i> Ghi chú</h5>
                    Đây là thông tin cơ bản của nhân viên. Bạn có thể cập nhật thông tin này bất kỳ lúc nào.
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>Thao tác nhanh</h3>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('bus-owner.nhan-vien.edit', $nhanVien->ma_nv)); ?>" class="btn btn-warning btn-block">
                    <i class="fas fa-edit mr-2"></i> Chỉnh sửa thông tin
                </a>
                <a href="tel:<?php echo e($nhanVien->so_dien_thoai); ?>" class="btn btn-info btn-block">
                    <i class="fas fa-phone mr-2"></i> Gọi điện thoại
                </a>
                <a href="mailto:<?php echo e($nhanVien->email); ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-envelope mr-2"></i> Gửi email
                </a>
                <button type="button" class="btn btn-danger btn-block" id="deleteBtn">
                    <i class="fas fa-trash mr-2"></i> Xóa nhân viên
                </button>
                <a href="<?php echo e(route('bus-owner.nhan-vien.index')); ?>" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Status Card -->
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Trạng thái</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-info"></i>
                    </div>
                    <h4 class="text-bold"><?php echo e($nhanVien->ten_nv); ?></h4>
                    <p class="text-muted"><?php echo e($nhanVien->chuc_vu); ?></p>

                    <div class="progress-group">
                        <span class="progress-text">Hiệu suất làm việc</span>
                        <span class="float-right"><b>85</b>/100</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" action="<?php echo e(route('bus-owner.nhan-vien.destroy', $nhanVien->ma_nv)); ?>" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Delete button
        $('#deleteBtn').on('click', function() {
            Swal.fire({
                title: 'Xác nhận xóa?',
                html: `Bạn có chắc chắn muốn xóa nhân viên:<br><strong><?php echo e($nhanVien->ten_nv); ?></strong>?<br><br><span class="text-danger">Hành động này không thể hoàn tác!</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Xóa',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-form').submit();
                }
            });
        });

        // Show success message if any
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

        <?php if(session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: '<?php echo e(session('
            error ')); ?>',
        });
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }

    .info-box {
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        border-radius: .25rem;
        margin-bottom: 1rem;
    }

    .info-box-icon {
        border-radius: .25rem 0 0 .25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 90px;
    }

    .info-box-content {
        padding: .5rem;
    }

    .progress-group {
        margin-bottom: 1rem;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/nhan_vien/show.blade.php ENDPATH**/ ?>