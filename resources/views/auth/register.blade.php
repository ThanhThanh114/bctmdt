<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Login.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Login_Enhanced.css') }}?v={{ time() }}">
    <style>
    /* Two Column Layout */
    .form-columns {
        display: flex;
        gap: 16px;
    }

    .form-column-left,
    .form-column-right {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-columns {
            flex-direction: column;
        }
    }
    </style>
</head>

<body>
    <div class="login-container">
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

        <div class="right-section">
            <div class="form-header">
                <h1 class="form-title">Tạo tài khoản</h1>
            </div>

            @if(session('success'))
            <div class="message success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="message error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif

            <div class="form-content">
                <form id="registerForm" method="POST" action="{{ route('register.post') }}" class="form-group">
                    @csrf

                    <div class="form-columns">
                        <div class="form-column-left">
                            <div class="input-wrapper input-row">
                                <label class="input-label">Tên đăng nhập</label>
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    class="input-field @error('username') error @enderror"
                                    placeholder="Nhập tên đăng nhập" pattern="[a-zA-Z0-9_]+" required>
                            </div>
                            @error('username')<div class="field-error"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</div>@enderror

                            <div class="input-wrapper input-row">
                                <label class="input-label">Số điện thoại</label>
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                    class="input-field @error('phone') error @enderror" placeholder="Nhập số điện thoại"
                                    pattern="[0-9]{10,11}" required>
                            </div>
                            @error('phone')<div class="field-error"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</div>@enderror

                            <div class="input-wrapper input-row">
                                <label class="input-label">Mật khẩu</label>
                                <i class="fas fa-lock input-icon"></i>
                                <input id="regPassword" type="password" name="password"
                                    class="input-field has-toggle @error('password') error @enderror"
                                    placeholder="Nhập mật khẩu" minlength="6" required>
                                <button type="button" class="toggle-password"
                                    onclick="togglePassword('regPassword', this)"><i class="fas fa-eye"></i></button>
                            </div>
                            @error('password')<div class="field-error"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</div>@enderror
                        </div>

                        <div class="form-column-right">
                            <div class="input-wrapper input-row">
                                <label class="input-label">Họ và tên</label>
                                <i class="fas fa-id-card input-icon"></i>
                                <input type="text" name="fullname" value="{{ old('fullname') }}"
                                    class="input-field @error('fullname') error @enderror"
                                    placeholder="Nhập họ và tên đầy đủ" minlength="3" required>
                            </div>
                            @error('fullname')<div class="field-error"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</div>@enderror

                            <div class="input-wrapper input-row">
                                <label class="input-label">Email</label>
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="input-field @error('email') error @enderror" placeholder="Nhập địa chỉ email"
                                    required>
                            </div>
                            @error('email')<div class="field-error"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</div>@enderror

                            <div class="input-wrapper input-row">
                                <label class="input-label">Xác nhận mật khẩu</label>
                                <i class="fas fa-lock input-icon"></i>
                                <input id="regPasswordConfirm" type="password" name="password_confirmation"
                                    class="input-field has-toggle" placeholder="Nhập lại mật khẩu" required>
                                <button type="button" class="toggle-password"
                                    onclick="togglePassword('regPasswordConfirm', this)"><i
                                        class="fas fa-eye"></i></button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn"><i class="fas fa-user-plus"></i> Đăng ký</button>
                </form>

                <div class="forgot-password"><a href="{{ route('login') }}">Đã có tài khoản? Đăng nhập</a></div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/login_enhanced.js') }}?v={{ time() }}"></script>
</body>

</html>