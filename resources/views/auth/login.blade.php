<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Login.css') }}">
</head>

<body>
    <div class="login-container">
        <!-- Left Section -->
        <div class="left-section">
            <button class="back-btn" onclick="window.location.href='{{ route('home') }}'">
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
            <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>

            <div class="form-content">
                <!-- Login Form -->
                <form id="loginForm" method="POST" class="form-group">
                    @csrf
                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" name="identifier" class="input-field" placeholder="Nhập số điện thoại"
                            required>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="input-field" placeholder="Nhập mật khẩu" required>
                    </div>

                    <button type="submit" name="login" class="submit-btn">
                        Đăng nhập
                    </button>
                </form>

                <!-- Register Form -->
                <form id="registerForm" method="POST" action="{{ route('register.post') }}" class="form-group hidden">
                    @csrf
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="username" class="input-field" placeholder="Tên đăng nhập" required>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" name="fullname" class="input-field" placeholder="Họ và tên" required>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel" name="phone" class="input-field" placeholder="Số điện thoại" required>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="input-field" placeholder="Email" required>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="input-field" placeholder="Mật khẩu" required>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" class="input-field"
                            placeholder="Xác nhận mật khẩu" required>
                    </div>

                    <button type="submit" name="register" class="submit-btn">
                        Đăng ký
                    </button>
                </form>

                <!-- Forgot Password Form -->
                <form id="forgotForm" method="POST" action="{{ route('password.email') }}" class="form-group hidden">
                    @csrf
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="input-field" placeholder="Nhập email của bạn" required>
                    </div>

                    <button type="submit" name="forgot" class="submit-btn">
                        Gửi mã xác nhận
                    </button>
                </form>

                <div class="forgot-password hidden">
                    <a href="{{ route('password.request') }}">Quên mật khẩu</a>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/login.js') }}"></script>
</body>

</html>