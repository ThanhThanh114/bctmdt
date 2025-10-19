<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Đăng nhập - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Login.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Login_Enhanced.css')); ?>">
</head>

<body>
    <div class="login-container">
        <!-- Left Section -->
        <div class="left-section">
            <button class="back-btn" onclick="window.location.href='<?php echo e(route('home')); ?>'">
                <i class="fas fa-arrow-left"></i>
            </button>

            <div class="left-content">
                <div class="brand-title">PHƯƠNG TRANG</div>
                <div class="brand-subtitle">Cùng bạn trên mọi nẻo đường</div>

                <div class="bus-illustration"></div>

                <div class="service-title">XE TRUNG CHUYỂN</div>
                <div class="service-subtitle">ĐÓN - TRẢ TẬN NƠI</div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="form-header">
                <h1 class="form-title">Tạo tài khoản</h1>

                <div class="tab-container">
                    <button class="tab-btn active" onclick="switchTab('login')">
                        <i class="fas fa-phone"></i>
                        Đăng nhập
                    </button>
                    <button class="tab-btn" onclick="switchTab('register')">
                        Đăng ký
                    </button>
                    <button class="tab-btn" onclick="switchTab('forgot')">
                        <i class="fas fa-key"></i>
                        Quên mật khẩu
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <?php if(session('success')): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Có lỗi xảy ra:</strong>
                    <ul style="margin: 8px 0 0 20px; padding: 0; list-style: disc;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-content">
                <!-- Login Form -->
                <form id="loginForm" method="POST" action="<?php echo e(route('login.post')); ?>" class="form-group">
                    <?php echo csrf_field(); ?>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="identifier" class="input-field <?php $__errorArgs = ['identifier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Tên đăng nhập / Email / Số điện thoại" value="<?php echo e(old('identifier')); ?>"
                            required>
                    </div>
                    <?php $__errorArgs = ['identifier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: #dc3545; font-size: 12px; margin-top: -8px; margin-bottom: 8px;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="loginPassword" name="password"
                            class="input-field <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Nhập mật khẩu" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('loginPassword', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: #dc3545; font-size: 12px; margin-top: -8px; margin-bottom: 8px;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <button type="submit" name="login" class="submit-btn">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </button>
                </form>

                <!-- Register Form -->
                <form id="registerForm" method="POST" action="<?php echo e(route('register.post')); ?>" class="form-group hidden">
                    <?php echo csrf_field(); ?>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="username" id="regUsername" class="input-field"
                            placeholder="Tên đăng nhập (chỉ chữ, số, gạch dưới)" pattern="[a-zA-Z0-9_]+" required>
                        <small class="form-hint">Chỉ gồm chữ cái, số và dấu gạch dưới (_)</small>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" name="fullname" id="regFullname" class="input-field"
                            placeholder="Họ và tên đầy đủ" minlength="3" required>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel" name="phone" id="regPhone" class="input-field"
                            placeholder="Số điện thoại (10-11 số)" pattern="[0-9]{10,11}" required>
                        <small class="form-hint">VD: 0901234567</small>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" id="regEmail" class="input-field" placeholder="Email" required>
                        <small class="form-hint">VD: example@gmail.com</small>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="regPassword" class="input-field"
                            placeholder="Mật khẩu (tối thiểu 6 ký tự)" minlength="6"
                            oninput="checkPasswordStrength(this.value)" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('regPassword', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <!-- Password Strength Meter -->
                    <div class="password-strength-container">
                        <div class="password-strength-bar">
                            <div class="password-strength-fill" id="passwordStrengthFill"></div>
                        </div>
                        <small class="password-strength-text" id="passwordStrengthText">Nhập mật khẩu để kiểm tra độ
                            mạnh</small>
                    </div>

                    <div class="password-requirements">
                        <small>Mật khẩu mạnh nên có:</small>
                        <ul>
                            <li id="req-length">✗ Ít nhất 8 ký tự</li>
                            <li id="req-uppercase">✗ Chữ hoa (A-Z)</li>
                            <li id="req-lowercase">✗ Chữ thường (a-z)</li>
                            <li id="req-number">✗ Chữ số (0-9)</li>
                            <li id="req-special">✗ Ký tự đặc biệt (!@#$...)</li>
                        </ul>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" id="regPasswordConfirm" class="input-field"
                            placeholder="Xác nhận mật khẩu" required>
                        <button type="button" class="toggle-password"
                            onclick="togglePassword('regPasswordConfirm', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <button type="submit" name="register" class="submit-btn">
                        <i class="fas fa-user-plus"></i> Đăng ký
                    </button>
                </form>

                <!-- Forgot Password Form -->
                <form id="forgotForm" method="POST" action="<?php echo e(route('password.email')); ?>" class="form-group hidden">
                    <?php echo csrf_field(); ?>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="input-field" placeholder="Nhập email của bạn" required>
                    </div>

                    <button type="submit" name="forgot" class="submit-btn">
                        Gửi mã xác nhận
                    </button>
                </form>

                <div class="forgot-password hidden">
                    <a href="<?php echo e(route('password.request')); ?>">Quên mật khẩu</a>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo e(asset('assets/js/login.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/login_enhanced.js')); ?>"></script>
</body>

</html><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/auth/login.blade.php ENDPATH**/ ?>