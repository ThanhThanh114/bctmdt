

<?php $__env->startSection('title', 'Thêm nhà xe mới'); ?>
<?php $__env->startSection('page-title', 'Thêm nhà xe mới'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.nha-xe.index')); ?>">Quản lý Nhà xe</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-success text-white">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Thêm nhà xe mới
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('bus-owner.nha-xe.index')); ?>" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('bus-owner.nha-xe.store')); ?>" method="POST" id="createForm">
                    <?php echo csrf_field(); ?>

                    <div class="card-body">
                        <div class="row">
                            <!-- Tên nhà xe -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ten_nha_xe" class="required">
                                        <i class="fas fa-building text-primary mr-1"></i>
                                        Tên nhà xe
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg <?php $__errorArgs = ['ten_nha_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="ten_nha_xe" name="ten_nha_xe" value="<?php echo e(old('ten_nha_xe')); ?>"
                                        placeholder="Nhập tên nhà xe" required maxlength="100">
                                    <?php $__errorArgs = ['ten_nha_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Tối đa 100 ký tự</small>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">
                                        <i class="fas fa-envelope text-info mr-1"></i>
                                        Email
                                    </label>
                                    <input type="email"
                                        class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="email" name="email" value="<?php echo e(old('email')); ?>"
                                        placeholder="email@example.com" maxlength="100">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Email liên hệ của nhà xe</small>
                                </div>
                            </div>

                            <!-- Địa chỉ -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="dia_chi" class="required">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                        Địa chỉ
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg <?php $__errorArgs = ['dia_chi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="dia_chi" name="dia_chi" value="<?php echo e(old('dia_chi')); ?>"
                                        placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố" required
                                        maxlength="255">
                                    <?php $__errorArgs = ['dia_chi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Địa chỉ trụ sở chính</small>
                                </div>
                            </div>

                            <!-- Số điện thoại -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="so_dien_thoai" class="required">
                                        <i class="fas fa-phone text-success mr-1"></i>
                                        Số điện thoại
                                    </label>
                                    <input type="tel"
                                        class="form-control form-control-lg <?php $__errorArgs = ['so_dien_thoai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="so_dien_thoai" name="so_dien_thoai" value="<?php echo e(old('so_dien_thoai')); ?>"
                                        placeholder="0xxxxxxxxx" required maxlength="15" pattern="[0-9]{10,11}">
                                    <?php $__errorArgs = ['so_dien_thoai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">10-11 chữ số</small>
                                </div>
                            </div>
                        </div>

                        <!-- Alert -->
                        <div class="alert alert-info mt-3">
                            <i class="icon fas fa-info-circle"></i>
                            <strong>Lưu ý:</strong> Các trường có dấu <span class="text-danger">*</span> là bắt buộc
                            phải nhập.
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Lưu thông tin
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg ml-2">
                                    <i class="fas fa-redo mr-2"></i>
                                    Làm mới
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="<?php echo e(route('bus-owner.nha-xe.index')); ?>"
                                    class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times mr-2"></i>
                                    Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Form validation
    $('#createForm').on('submit', function(e) {
        var isValid = true;

        // Validate phone number
        var phone = $('#so_dien_thoai').val();
        if (phone && !/^[0-9]{10,11}$/.test(phone)) {
            $('#so_dien_thoai').addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Vui lòng kiểm tra lại thông tin nhập vào.',
            });
        }
    });

    // Show validation errors if any
    <?php if($errors->any()): ?>
    Swal.fire({
        icon: 'error',
        title: 'Có lỗi xảy ra!',
        html: '<ul class="text-left"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>',
    });
    <?php endif; ?>
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.required:after {
    content: " *";
    color: red;
}

.form-control-lg {
    border-radius: 8px;
}

.card {
    border-radius: 15px;
    border: none;
}

.card-header {
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.invalid-feedback {
    display: block;
    font-weight: 500;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/nha_xe/create.blade.php ENDPATH**/ ?>