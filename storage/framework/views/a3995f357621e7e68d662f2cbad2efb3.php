<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Profile.css')); ?>">
</head>

<body>
    <div class="profile-container">
        <a href="<?php echo e(route('home')); ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại trang chủ
        </a>

        <div class="profile-header">
            <h1 class="profile-title">
                <i class="fas fa-user-circle"></i> Quản lý tài khoản
            </h1>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="profile-wrapper">
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-name"><?php echo e($user->fullname); ?></div>
                    <div class="profile-role"><?php echo e(ucfirst($user->role)); ?></div>
                </div>

                <ul class="profile-menu">
                    <li class="profile-menu-item">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="profile-menu-link active">
                            <i class="fas fa-user-edit"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="<?php echo e(route('password.edit')); ?>" class="profile-menu-link">
                            <i class="fas fa-key"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="<?php echo e(route('booking.history')); ?>" class="profile-menu-link">
                            <i class="fas fa-history"></i>
                            <span>Lịch sử đặt vé</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="<?php echo e(route('home')); ?>" class="profile-menu-link">
                            <i class="fas fa-home"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="profile-content">
                <div class="content-header">
                    <h2 class="content-title">
                        <i class="fas fa-user-edit"></i>
                        Thông tin cá nhân
                    </h2>
                </div>

                <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input type="text" name="fullname" class="form-input"
                                value="<?php echo e(old('fullname', $user->fullname)); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" class="form-input" value="<?php echo e($user->username); ?>" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-input" value="<?php echo e(old('email', $user->email)); ?>"
                                required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error-text"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại *</label>
                            <input type="tel" name="phone" class="form-input" value="<?php echo e(old('phone', $user->phone)); ?>"
                                required>
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error-text"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" name="address" class="form-input"
                            value="<?php echo e(old('address', $user->address)); ?>" placeholder="Nhập địa chỉ của bạn">
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-text"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <input type="date" name="date_of_birth" class="form-input"
                                value="<?php echo e(old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '')); ?>">
                            <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error-text"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label>Giới tính</label>
                            <select name="gender" class="form-input">
                                <option value="">Chọn giới tính</option>
                                <option value="Nam" <?php echo e(old('gender', $user->gender) == 'Nam' ? 'selected' : ''); ?>>Nam
                                </option>
                                <option value="Nữ" <?php echo e(old('gender', $user->gender) == 'Nữ' ? 'selected' : ''); ?>>Nữ
                                </option>
                                <option value="Khác" <?php echo e(old('gender', $user->gender) == 'Khác' ? 'selected' : ''); ?>>Khác
                                </option>
                            </select>
                            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error-text"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Cập nhật thông tin
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/profile/edit.blade.php ENDPATH**/ ?>