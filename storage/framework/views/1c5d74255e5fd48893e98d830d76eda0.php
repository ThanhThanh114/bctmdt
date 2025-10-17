

<?php $__env->startSection('title', 'Thêm nhân viên mới'); ?>

<?php $__env->startSection('page-title', 'Thêm nhân viên mới'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.nhan-vien.index')); ?>">Quản lý nhân viên</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Thông tin nhân viên</h3>
            </div>

            <form action="<?php echo e(route('bus-owner.nhan-vien.store')); ?>" method="POST" id="createForm">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="ten_nv"><i class="fas fa-user mr-1"></i>Tên nhân viên <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control <?php $__errorArgs = ['ten_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="ten_nv"
                            name="ten_nv"
                            placeholder="VD: Nguyễn Văn A"
                            value="<?php echo e(old('ten_nv')); ?>"
                            required
                            autofocus>
                        <?php $__errorArgs = ['ten_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">Nhập họ tên đầy đủ của nhân viên</small>
                    </div>

                    <div class="form-group">
                        <label for="chuc_vu"><i class="fas fa-briefcase mr-1"></i>Chức vụ <span class="text-danger">*</span></label>
                        <select class="form-control <?php $__errorArgs = ['chuc_vu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="chuc_vu"
                            name="chuc_vu"
                            required>
                            <option value="">-- Chọn chức vụ --</option>
                            <option value="Tài xế" <?php echo e(old('chuc_vu') == 'Tài xế' ? 'selected' : ''); ?>>Tài xế</option>
                            <option value="Phụ xe" <?php echo e(old('chuc_vu') == 'Phụ xe' ? 'selected' : ''); ?>>Phụ xe</option>
                            <option value="Quản lý" <?php echo e(old('chuc_vu') == 'Quản lý' ? 'selected' : ''); ?>>Quản lý</option>
                            <option value="Nhân viên kỹ thuật" <?php echo e(old('chuc_vu') == 'Nhân viên kỹ thuật' ? 'selected' : ''); ?>>Nhân viên kỹ thuật</option>
                            <option value="Nhân viên bán vé" <?php echo e(old('chuc_vu') == 'Nhân viên bán vé' ? 'selected' : ''); ?>>Nhân viên bán vé</option>
                            <option value="Khác" <?php echo e(old('chuc_vu') == 'Khác' ? 'selected' : ''); ?>>Khác</option>
                        </select>
                        <?php $__errorArgs = ['chuc_vu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="so_dien_thoai"><i class="fas fa-phone mr-1"></i>Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control <?php $__errorArgs = ['so_dien_thoai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="so_dien_thoai"
                            name="so_dien_thoai"
                            placeholder="VD: 0912345678"
                            value="<?php echo e(old('so_dien_thoai')); ?>"
                            required
                            pattern="[0-9]{10,11}"
                            maxlength="11">
                        <?php $__errorArgs = ['so_dien_thoai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">Nhập 10-11 chữ số</small>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope mr-1"></i>Email <span class="text-danger">*</span></label>
                        <input type="email"
                            class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="email"
                            name="email"
                            placeholder="VD: nhanvien@example.com"
                            value="<?php echo e(old('email')); ?>"
                            required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">Email dùng để liên hệ và đăng nhập (nếu có)</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="icon fas fa-info-circle"></i>
                        <strong>Lưu ý:</strong> Nhân viên sẽ được gán cho nhà xe <strong><?php echo e($nhaXe->name); ?></strong>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Lưu nhân viên
                    </button>
                    <a href="<?php echo e(route('bus-owner.nhan-vien.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Phone number validation
        $('#so_dien_thoai').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Form validation before submit
        $('#createForm').on('submit', function(e) {
            let isValid = true;
            let errors = [];

            // Validate name
            const tenNv = $('#ten_nv').val().trim();
            if (tenNv.length < 3) {
                errors.push('Tên nhân viên phải có ít nhất 3 ký tự');
                isValid = false;
            }

            // Validate position
            const chucVu = $('#chuc_vu').val();
            if (!chucVu) {
                errors.push('Vui lòng chọn chức vụ');
                isValid = false;
            }

            // Validate phone
            const phone = $('#so_dien_thoai').val();
            if (phone.length < 10 || phone.length > 11) {
                errors.push('Số điện thoại phải có 10-11 chữ số');
                isValid = false;
            }

            // Validate email
            const email = $('#email').val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errors.push('Email không hợp lệ');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi nhập liệu',
                    html: '<ul class="text-left">' + errors.map(e => '<li>' + e + '</li>').join('') + '</ul>',
                });
            }
        });

        // Show success message if redirected with success
        <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '<?php echo e(session('
            success ')); ?>',
            timer: 3000
        });
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/nhan_vien/create.blade.php ENDPATH**/ ?>