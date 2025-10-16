<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Profile.css') }}">
</head>
<body>
    <div class="profile-container">
        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại trang chủ
        </a>

        <div class="profile-header">
            <h1 class="profile-title">
                <i class="fas fa-user-edit"></i>
                Thông tin tài khoản
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

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-card">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <i class="fas fa-user"></i>
                </div>
                <h3>{{ $user->fullname }}</h3>
                <p style="color: #666; margin: 0;">{{ $user->email }}</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="fullname" class="form-label">
                            <i class="fas fa-user"></i> Họ và tên *
                        </label>
                        <input type="text" id="fullname" name="fullname" class="form-input"
                               value="{{ old('fullname', $user->fullname) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-id-card"></i> Tên đăng nhập
                        </label>
                        <input type="text" id="username" name="username" class="form-input"
                               value="{{ $user->username }}" disabled>
                        <small style="color: #666; font-size: 12px;">Không thể thay đổi tên đăng nhập</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email *
                        </label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i> Số điện thoại *
                        </label>
                        <input type="tel" id="phone" name="phone" class="form-input"
                               value="{{ old('phone', $user->phone) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Địa chỉ
                        </label>
                        <input type="text" id="address" name="address" class="form-input"
                               value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ của bạn">
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth" class="form-label">
                            <i class="fas fa-calendar"></i> Ngày sinh
                        </label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-input"
                               value="{{ old('date_of_birth', $user->date_of_birth) }}" max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-venus-mars"></i> Giới tính
                        </label>
                        <div class="gender-options">
                            <label class="gender-option">
                                <input type="radio" name="gender" value="Nam"
                                       {{ old('gender', $user->gender) == 'Nam' ? 'checked' : '' }}>
                                <span>Nam</span>
                            </label>
                            <label class="gender-option">
                                <input type="radio" name="gender" value="Nữ"
                                       {{ old('gender', $user->gender) == 'Nữ' ? 'checked' : '' }}>
                                <span>Nữ</span>
                            </label>
                            <label class="gender-option">
                                <input type="radio" name="gender" value="Khác"
                                       {{ old('gender', $user->gender) == 'Khác' ? 'checked' : '' }}>
                                <span>Khác</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
