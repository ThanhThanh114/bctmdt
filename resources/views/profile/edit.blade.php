<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - FUTA Bus Lines</title>
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
                <i class="fas fa-user-circle"></i> Quản lý tài khoản
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
                    <div class="profile-name">{{ $user->fullname }}</div>
                    <div class="profile-role">{{ ucfirst($user->role) }}</div>
                </div>

                <ul class="profile-menu">
                    <li class="profile-menu-item">
                        <a href="{{ route('profile.edit') }}" class="profile-menu-link active">
                            <i class="fas fa-user-edit"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                    </li>
                    <li class="profile-menu-item">
                        <a href="{{ route('password.edit') }}" class="profile-menu-link">
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
                        <i class="fas fa-user-edit"></i>
                        Thông tin cá nhân
                    </h2>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input type="text" name="fullname" class="form-input"
                                value="{{ old('fullname', $user->fullname) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" class="form-input" value="{{ $user->username }}" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}"
                                required>
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại *</label>
                            <input type="tel" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}"
                                required>
                            @error('phone')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" name="address" class="form-input"
                            value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ của bạn">
                        @error('address')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <input type="date" name="date_of_birth" class="form-input"
                                value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                            @error('date_of_birth')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Giới tính</label>
                            <select name="gender" class="form-input">
                                <option value="">Chọn giới tính</option>
                                <option value="Nam" {{ old('gender', $user->gender) == 'Nam' ? 'selected' : '' }}>Nam
                                </option>
                                <option value="Nữ" {{ old('gender', $user->gender) == 'Nữ' ? 'selected' : '' }}>Nữ
                                </option>
                                <option value="Khác" {{ old('gender', $user->gender) == 'Khác' ? 'selected' : '' }}>Khác
                                </option>
                            </select>
                            @error('gender')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
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

</html>