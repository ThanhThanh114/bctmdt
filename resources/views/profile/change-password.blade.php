<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Profile.css') }}">
</head>

<body>
    <div class="profile-container">
        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại trang chủ
        </a>

        <div class="profile-header">
            <h1 class="profile-title">
                <i class="fas fa-key"></i> Đổi mật khẩu
            </h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="profile-wrapper">
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-name">{{ Auth::user()->fullname }}</div>
                    <div class="profile-role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>

                <ul class="profile-menu">
                    <li class="profile-menu-item">
                        <a href="{{ route('profile.edit') }}" class="profile-menu-link">
                            <i class="fas fa-user-edit"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="{{ route('password.edit') }}" class="profile-menu-link active">
                            <i class="fas fa-key"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="{{ route('booking.history') }}" class="profile-menu-link">
                            <i class="fas fa-history"></i>
                            <span>Lịch sử đặt vé</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="{{ route('home') }}" class="profile-menu-link">
                            <i class="fas fa-home"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="profile-content">
                <div class="content-header">
                    <h2 class="content-title">
                        <i class="fas fa-key"></i>
                        Thay đổi mật khẩu
                    </h2>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Mật khẩu hiện tại *</label>
                        <input type="password" name="current_password" class="form-input" required>
                        @error('current_password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Mật khẩu mới *</label>
                            <input type="password" name="password" class="form-input" required>
                            @error('password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                            <small style="font-size: 12px; color: #666; display: block; margin-top: 5px;">
                                Mật khẩu phải có ít nhất 8 ký tự
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới *</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                            @error('password_confirmation')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Cập nhật mật khẩu
                    </button>
                </form>

                <!-- Security Tips -->
                <div
                    style="margin-top: 30px; padding: 20px; background: rgba(255, 255, 255, 0.3); border-radius: 12px; backdrop-filter: blur(5px);">
                    <h3 style="color: #FF6F3C; margin-bottom: 15px;">
                        <i class="fas fa-shield-alt"></i> Mẹo bảo mật
                    </h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 10px; padding-left: 25px; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 3px; color: #4CAF50;"></i>
                            <strong>Chọn mật khẩu mạnh:</strong> Sử dụng ít nhất 8 ký tự, kết hợp chữ hoa, chữ thường,
                            số và ký tự đặc biệt
                        </li>
                        <li style="margin-bottom: 10px; padding-left: 25px; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 3px; color: #4CAF50;"></i>
                            <strong>Không dùng mật khẩu cũ:</strong> Tránh sử dụng lại mật khẩu đã dùng trước đây
                        </li>
                        <li style="margin-bottom: 10px; padding-left: 25px; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 3px; color: #4CAF50;"></i>
                            <strong>Bảo mật thông tin:</strong> Không chia sẻ mật khẩu với người khác
                        </li>
                        <li style="padding-left: 25px; position: relative;">
                            <i class="fas fa-check" style="position: absolute; left: 0; top: 3px; color: #4CAF50;"></i>
                            <strong>Đổi mật khẩu định kỳ:</strong> Thay đổi mật khẩu mỗi 3-6 tháng để tăng cường bảo mật
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>