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
            @if(session('success'))
            <div class="message success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="message error">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Có lỗi xảy ra:</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0; list-style: disc;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <?php if (isset($message) && $message): ?>
            <div class="message <?= $messageType ?? 'info' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>

            <div class="form-content">
                <!-- Login Form -->
                <form id="loginForm" method="POST" action="{{ route('login.post') }}" class="form-group">
                    @csrf
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text"
                               name="identifier"
                               class="input-field @error('identifier') error @enderror"
                               placeholder="Tên đăng nhập / Email / Số điện thoại"
                               value="{{ old('identifier') }}"
                               required>
                    </div>
                    @error('identifier')
                    <div style="color: #dc3545; font-size: 12px; margin-top: -8px; margin-bottom: 8px;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password"
                               name="password"
                               id="loginPassword"
                               class="input-field @error('password') error @enderror"
                               placeholder="Nhập mật khẩu"
                               required>
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('loginPassword')">
                            <i class="fas fa-eye" id="loginPasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <div style="color: #dc3545; font-size: 12px; margin-top: -8px; margin-bottom: 8px;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror

                    <button type="submit" name="login" class="submit-btn">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
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
                        <input type="password" name="password" id="registerPassword" class="input-field" placeholder="Mật khẩu" required>
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('registerPassword')">
                            <i class="fas fa-eye" id="registerPasswordIcon"></i>
                        </button>
                    </div>

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" id="registerPasswordConfirm" class="input-field"
                            placeholder="Xác nhận mật khẩu" required>
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('registerPasswordConfirm')">
                            <i class="fas fa-eye" id="registerPasswordConfirmIcon"></i>
                        </button>
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

    <script>
    function togglePasswordVisibility(passwordId) {
        const passwordInput = document.getElementById(passwordId);
        const icon = document.getElementById(passwordId + 'Icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    </script>

    <style>
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 5px;
        z-index: 10;
    }

    .password-toggle:hover {
        color: #495057;
    }

    .input-wrapper {
        position: relative;
    }
    </style>
</body>

</html>
