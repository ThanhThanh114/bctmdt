<?php $__env->startSection('title', 'Chi tiết Trạm xe'); ?>
<?php $__env->startSection('page-title', 'Chi tiết Trạm xe'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.tram-xe.index')); ?>">Trạm xe</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt mr-2"></i>Thông tin trạm xe
                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('bus-owner.tram-xe.edit', $tram->ma_tram_xe)); ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="<?php echo e(route('bus-owner.tram-xe.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 200px" class="bg-light">
                                <i class="fas fa-hashtag text-primary mr-2"></i>Mã trạm
                            </th>
                            <td>
                                <span class="badge badge-primary badge-lg"><?php echo e($tram->ma_tram_xe); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">
                                <i class="fas fa-home text-success mr-2"></i>Tên trạm
                            </th>
                            <td><strong class="text-lg"><?php echo e($tram->ten_tram); ?></strong></td>
                        </tr>
                        <tr>
                            <th class="bg-light">
                                <i class="fas fa-map-marker-alt text-danger mr-2"></i>Tỉnh/Thành phố
                            </th>
                            <td><?php echo e($tram->tinh_thanh ?? 'Chưa cập nhật'); ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">
                                <i class="fas fa-map-pin text-info mr-2"></i>Địa chỉ chi tiết
                            </th>
                            <td><?php echo e($tram->dia_chi ?? $tram->dia_chi_tram ?? 'Chưa cập nhật'); ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">
                                <i class="fas fa-building text-warning mr-2"></i>Nhà xe quản lý
                            </th>
                            <td>
                                <?php if($tram->nhaXe): ?>
                                <span class="badge badge-success badge-lg">
                                    <?php echo e($tram->nhaXe->ten_nha_xe); ?>

                                </span>
                                <?php else: ?>
                                <span class="badge badge-secondary">Chưa gán nhà xe</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if($tram->nhaXe): ?>
                        <tr>
                            <th class="bg-light">
                                <i class="fas fa-phone text-success mr-2"></i>Số điện thoại liên hệ
                            </th>
                            <td>
                                <?php if($tram->nhaXe->so_dien_thoai): ?>
                                <a href="tel:<?php echo e($tram->nhaXe->so_dien_thoai); ?>" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-phone-alt mr-1"></i><?php echo e($tram->nhaXe->so_dien_thoai); ?>

                                </a>
                                <?php else: ?>
                                <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">
                                <i class="fas fa-envelope text-info mr-2"></i>Email liên hệ
                            </th>
                            <td>
                                <?php if($tram->nhaXe->email): ?>
                                <a href="mailto:<?php echo e($tram->nhaXe->email); ?>" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-envelope mr-1"></i><?php echo e($tram->nhaXe->email); ?>

                                </a>
                                <?php else: ?>
                                <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Thống kê chuyến xe -->
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>Thống kê chuyến xe
                </h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-gradient-info mb-3">
                    <span class="info-box-icon"><i class="fas fa-bus-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng số chuyến</span>
                        <span class="info-box-number"><?php echo e(number_format($tongChuyen)); ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fas fa-arrow-right"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Trạm đi</span>
                                <span class="info-box-number"><?php echo e(number_format($tongChuyenDi)); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon"><i class="fas fa-arrow-left"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Trạm đến</span>
                                <span class="info-box-number"><?php echo e(number_format($tongChuyenDen)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin nhà xe -->
        <?php if($tram->nhaXe): ?>
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building mr-2"></i>Thông tin nhà xe
                </h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Tên nhà xe:</dt>
                    <dd class="col-sm-7"><?php echo e($tram->nhaXe->ten_nha_xe); ?></dd>

                    <dt class="col-sm-5">Địa chỉ:</dt>
                    <dd class="col-sm-7"><?php echo e($tram->nhaXe->dia_chi ?? 'Chưa cập nhật'); ?></dd>

                    <?php if($tram->nhaXe->website): ?>
                    <dt class="col-sm-5">Website:</dt>
                    <dd class="col-sm-7">
                        <a href="<?php echo e($tram->nhaXe->website); ?>" target="_blank">
                            <?php echo e($tram->nhaXe->website); ?>

                        </a>
                    </dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
        <?php endif; ?>

        <!-- Thao tác nhanh -->
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools mr-2"></i>Thao tác nhanh
                </h3>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('bus-owner.tram-xe.edit', $tram->ma_tram_xe)); ?>" class="btn btn-warning btn-block">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa thông tin
                </a>
                <button type="button" class="btn btn-danger btn-block" id="deleteBtn">
                    <i class="fas fa-trash mr-2"></i>Xóa trạm xe
                </button>
                <hr>
                <a href="<?php echo e(route('bus-owner.tram-xe.index')); ?>" class="btn btn-secondary btn-block">
                    <i class="fas fa-list mr-2"></i>Danh sách trạm xe
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa trạm xe này?</p>
                <p class="text-danger font-weight-bold"><?php echo e($tram->ten_tram); ?></p>
                <p class="text-muted small">
                    <i class="fas fa-info-circle mr-1"></i>
                    Lưu ý: Không thể xóa trạm xe đang được sử dụng trong chuyến xe.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash mr-1"></i> Xóa
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Show delete modal
        $('#deleteBtn').click(function() {
            $('#deleteModal').modal('show');
        });

        // Confirm delete
        $('#confirmDelete').click(function() {
            const btn = $(this);
            const originalHtml = btn.html();

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Đang xóa...');

            $.ajax({
                url: '<?php echo e(route("bus-owner.tram-xe.destroy", $tram->ma_tram_xe)); ?>',
                type: 'DELETE',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '<?php echo e(route("bus-owner.tram-xe.index")); ?>';
                    } else {
                        alert(response.message);
                        btn.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function(xhr) {
                    let message = 'Có lỗi xảy ra khi xóa trạm xe!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/tram_xe/show.blade.php ENDPATH**/ ?>