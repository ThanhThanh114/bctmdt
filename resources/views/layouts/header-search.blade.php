@php
// Lấy danh sách thành phố từ database
$cities = \App\Models\TramXe::orderBy('ten_tram')->get();

// Get current page name for active navigation
$current_page = basename(request()->path());
@endphp

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @stack('styles')

    <style>
    /* Reset và Base Styles */
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    /* ================= HEADER MỚI ================= */
    .header-home-page {
        position: relative;
        width: 100%;
        background: linear-gradient(rgba(255, 123, 57, 0.9), rgba(255, 87, 34, 0.9)),
            url('https://cdn.futabus.vn/futa-busline-cms-dev/header_bg.jpg') center/cover;
        color: white;
        transition: all 0.3s ease;
        height: 220px;
        overflow: hidden;
    }

    /* Desktop Header */
    .header-top-desktop {
        display: none;
        height: 80px;
        align-items: center;
        justify-content: space-between;
        padding: 0 40px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .header-top-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .language-dropdown,
    .app-download {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background-color 0.2s ease;
    }

    .language-dropdown:hover,
    .app-download:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .language-text,
    .app-text {
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .app-download {
        border-left: 1px solid rgba(255, 255, 255, 0.3);
        padding-left: 16px;
        margin-left: 8px;
    }

    .header-logo {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }

    .header-logo a {
        color: white;
        text-decoration: none;
        font-size: 28px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-logo a:hover {
        color: #FFD54F;
    }

    .header-top-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .user-dropdown {
        position: relative;
    }

    .user-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        border: none;
        color: white;
        font-size: 14px;
    }

    .user-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .user-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        min-width: 200px;
        z-index: 1000;
        display: none;
    }

    .user-menu.active {
        display: block;
    }

    .user-menu-item {
        display: block;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.2s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .user-menu-item:hover {
        background: #f8f9fa;
    }

    .user-menu-item i {
        margin-right: 8px;
        color: #FF5722;
    }

    .login-btn {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }

    .login-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Navigation */
    .header-nav-desktop {
        display: none;
        background: rgba(0, 0, 0, 0.3);
        padding: 0 40px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .header-nav-desktop ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    .header-nav-desktop li {
        margin: 0;
    }

    .header-nav-desktop a {
        display: block;
        padding: 16px 20px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }

    .header-nav-desktop a:hover,
    .header-nav-desktop a.active {
        background: rgba(255, 255, 255, 0.1);
    }

    /* Mobile Header */
    .header-mobile {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mobile-menu-toggle {
        display: none;
    }

    .hamburger {
        width: 24px;
        height: 20px;
        cursor: pointer;
        position: relative;
    }

    .hamburger span {
        display: block;
        width: 100%;
        height: 2px;
        background: white;
        margin-bottom: 4px;
        transition: all 0.3s ease;
    }

    .mobile-language {
        color: #FFD700;
        font-size: 18px;
        cursor: pointer;
    }

    .mobile-logo a {
        color: white;
        text-decoration: none;
        font-size: 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mobile-logo a:hover {
        color: #FFD54F;
    }

    .mobile-avatar {
        cursor: pointer;
    }

    /* Mobile Menu Overlay */
    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 999;
        display: none;
    }

    .mobile-menu-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 280px;
        height: 100%;
        background: white;
        padding: 20px;
        overflow-y: auto;
    }

    .mobile-menu-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .mobile-nav ul {
        list-style: none;
        padding: 0;
    }

    .mobile-nav li {
        margin-bottom: 8px;
    }

    .mobile-nav a {
        display: block;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        border-radius: 8px;
        transition: background-color 0.2s ease;
    }

    .mobile-nav a:hover,
    .mobile-nav a.active {
        background: #f8f9fa;
        color: #FF5722;
    }

    /* Search Form Styles */
    .search-form-container {
        margin: 20px auto 60px;
        max-width: 1128px;
        padding: 0 16px;
        position: relative;
        z-index: 20;
    }

    .search-form-wrapper {
        background: white;
        border-radius: 16px;
        padding: 24px 24px 40px 24px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 2px solid #ff5722;
        font-weight: 500;
        position: relative;
    }

    .trip-type-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        font-size: 15px;
    }

    .trip-type-radio {
        display: flex;
        gap: 20px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        cursor: pointer;
        gap: 8px;
    }

    .radio-option.active {
        color: #ff5722;
    }

    .radio-option input {
        margin: 0;
    }

    .guide-link {
        color: #ff5722;
        font-weight: 500;
    }

    .guide-link a {
        color: #ff5722;
        text-decoration: none;
    }

    .search-row {
        margin-bottom: 16px;
    }

    .search-locations {
        display: flex;
        align-items: center;
        gap: 16px;
        position: relative;
    }

    .location-field {
        flex: 1;
    }

    .location-field label {
        display: block;
        margin-bottom: 4px;
        font-weight: 500;
        color: #333;
    }

    .location-input select,
    .date-input input,
    .ticket-input select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        background: white;
        color: #333;
        outline: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .location-input select:focus,
    .date-input input:focus,
    .ticket-input select:focus {
        border-color: #ff5722;
        box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1), 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .switch-location {
        width: 48px;
        height: 48px;
        cursor: pointer;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
    }

    .switch-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #2196F3, #1976D2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .switch-location:hover .switch-icon {
        transform: rotate(180deg) scale(1.05);
        box-shadow: 0 6px 16px rgba(33, 150, 243, 0.5);
    }

    .switch-icon i {
        color: white;
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .search-row-bottom {
        margin-bottom: 20px;
    }

    .date-ticket-wrapper {
        display: flex;
        gap: 16px;
    }

    .date-field,
    .ticket-field {
        flex: 1;
    }

    .date-field label,
    .ticket-field label {
        display: block;
        margin-bottom: 4px;
        font-weight: 500;
        color: #333;
    }

    .search-button-wrapper {
        display: flex;
        justify-content: center;
        position: absolute;
        bottom: -24px;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
    }

    .search-button {
        background: linear-gradient(135deg, #ff7b39 0%, #ff5722 100%);
        color: white;
        border: none;
        padding: 12px 80px;
        border-radius: 24px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
    }

    .search-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 87, 34, 0.3);
    }

    /* Responsive */
    @media (min-width: 1024px) {

        .header-top-desktop,
        .header-nav-desktop {
            display: flex;
        }

        .header-mobile {
            display: none;
        }

        .guide-link {
            display: block;
        }

        .search-form-container {
            margin-top: -80px;
        }
    }

    @media (min-width: 768px) {
        .search-locations {
            justify-content: center;
        }

        .search-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    @media (max-width: 1023px) {
        .search-row-bottom {
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
            margin-top: 16px;
        }
    }

    /* Mobile User Menu */
    .mobile-user-menu {
        position: fixed;
        top: 70px;
        right: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        min-width: 220px;
        overflow: hidden;
    }

    .mobile-user-info {
        padding: 16px;
        background: linear-gradient(135deg, #FF7B39, #FF5722);
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
    }

    .mobile-user-actions a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .mobile-user-actions a:hover {
        background-color: #f8f9fa;
    }

    .mobile-user-actions a i {
        color: #FF5722;
        width: 16px;
    }
    </style>
</head>

<body>
    <!-- ================= HEADER ================= -->
    <header class="header-home-page">
        <!-- Top bar desktop -->
        <div class="header-top-desktop">
            <div class="header-top-left">
                <div class="language-dropdown">
                    <i class="fas fa-flag" style="color: #FFD700; font-size: 20px;"></i>
                    <span class="language-text">VI</span>
                    <i class="fas fa-chevron-down" style="color: white; font-size: 12px;"></i>
                </div>
                <div class="app-download">
                    <i class="fas fa-mobile-alt" style="color: white; font-size: 20px;"></i>
                    <span class="app-text">Tải ứng dụng</span>
                    <i class="fas fa-chevron-down" style="color: white; font-size: 12px;"></i>
                </div>
            </div>

            <div class="header-logo">
                <a href="{{ url('/') }}">
                    <div
                        style="color: white; font-size: 28px; font-weight: bold; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-bus" style="color: #FFD54F;"></i>
                        FUTA Bus Lines
                    </div>
                </a>
            </div>

            <div class="header-top-right">
                @auth
                <div class="user-dropdown">
                    <button class="user-btn" onclick="toggleUserMenu()">
                        <i class="fas fa-user" style="color: #FF5722; font-size: 16px;"></i>
                        {{ Auth::user()->fullname ?? Auth::user()->name ?? 'User' }}
                        <i class="fas fa-chevron-down" style="color: #333; font-size: 12px;"></i>
                    </button>
                    <div class="user-menu" id="user-menu">
                        <a href="{{ route('profile.edit') }}" class="user-menu-item">
                            <i class="fas fa-user-edit"></i> Thông tin tài khoản
                        </a>
                        <a href="{{ route('booking.history') }}" class="user-menu-item">
                            <i class="fas fa-history"></i> Lịch sử đặt vé
                        </a>
                        <hr style="margin: 8px 0; border: none; border-top: 1px solid #e5e7eb;">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="user-menu-item logout">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="login-btn">
                    <i class="fas fa-user" style="color: #FF5722; font-size: 16px;"></i>
                    Đăng nhập/Đăng ký
                </a>
                @endauth
            </div>
        </div>

        <!-- Navigation desktop -->
        <div class="header-nav-desktop">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ $current_page == 'home' ? 'active' : '' }}">Trang chủ</a>
                </li>
                <li><a href="{{ route('trips.trips') }}" class="{{ $current_page == 'trips' ? 'active' : '' }}">Lịch
                        trình</a></li>
                <li><a href="{{ route('tracking.tracking') }}"
                        class="{{ $current_page == 'tracking' ? 'active' : '' }}">Tra cứu vé</a></li>
                <li><a href="{{ route('news.news') }}" class="{{ $current_page == 'news' ? 'active' : '' }}">Tin tức</a>
                </li>
                <li><a href="{{ route('invoice.index') }}" class="{{ $current_page == 'invoice' ? 'active' : '' }}">Hóa
                        đơn</a></li>
                <li><a href="{{ route('contact.contact') }}"
                        class="{{ $current_page == 'contact' ? 'active' : '' }}">Liên hệ</a></li>
                <li><a href="{{ route('about.about') }}" class="{{ $current_page == 'about' ? 'active' : '' }}">Về chúng
                        tôi</a></li>
            </ul>
        </div>

        <!-- Mobile header -->
        <div class="header-mobile">
            <div class="mobile-top">
                <div class="mobile-left">
                    <input type="checkbox" id="mobile-menu" class="mobile-menu-toggle">
                    <label for="mobile-menu" class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </label>
                    <div class="mobile-language">
                        <i class="fas fa-flag" style="color: #FFD700; font-size: 20px;"></i>
                    </div>
                </div>

                <a href="{{ url('/') }}" class="mobile-logo">
                    <div
                        style="color: white; font-size: 20px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-bus" style="color: #FFD54F;"></i>
                        FUTA
                    </div>
                </a>

                <div class="mobile-avatar">
                    @auth
                    <div class="mobile-user" onclick="toggleMobileUserMenu()">
                        <i class="fas fa-user" style="color: #FF5722; font-size: 20px;"></i>
                    </div>
                    @else
                    <i class="fas fa-user" style="color: #FF5722; font-size: 20px;" onclick="showLoginModal()"></i>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile User Menu (ẩn/hiện khi click avatar) -->
    @auth
    <div id="mobile-user-menu" class="mobile-user-menu" style="display: none;">
        <div class="mobile-user-info">
            <i class="fas fa-user"></i>
            <span>{{ Auth::user()->fullname ?? Auth::user()->name ?? 'User' }}</span>
        </div>
        <div class="mobile-user-actions">
            <a href="{{ route('profile.edit') }}">
                <i class="fas fa-user-cog"></i> Thông tin tài khoản
            </a>
            <a href="{{ route('booking.history') }}">
                <i class="fas fa-history"></i> Lịch sử mua vé
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    style="border: none; background: none; color: inherit; cursor: pointer; width: 100%; text-align: left;">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </button>
            </form>
        </div>
    </div>
    @endauth

    <!-- ================= SEARCH FORM ================= -->
    <div class="search-form-container">
        <div class="search-form-wrapper">
            <div class="trip-type-wrapper">
                <div class="trip-type-radio">
                    <label class="radio-option active">
                        <input type="radio" name="trip-type" value="oneway" checked>
                        <span class="radio-text">Một chiều</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="trip-type" value="roundtrip">
                        <span class="radio-text">Khứ hồi</span>
                    </label>
                </div>
                <span class="guide-link">
                    <a href="#" target="_blank">Hướng dẫn mua vé</a>
                </span>
            </div>

            <form class="search-form" method="GET" action="">
                <div class="search-row">
                    <div class="search-locations">
                        <div class="location-field">
                            <label>Điểm đi</label>
                            <div class="location-input">
                                <select name="start" required>
                                    <option value="">Chọn điểm đi</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->ten_tram }}"
                                        {{ request('start') == $city->ten_tram ? 'selected' : '' }}>
                                        {{ $city->ten_tram }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="switch-location" onclick="switchLocations()">
                            <div class="switch-icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                        </div>

                        <div class="location-field">
                            <label>Điểm đến</label>
                            <div class="location-input">
                                <select name="end" required>
                                    <option value="">Chọn điểm đến</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->ten_tram }}"
                                        {{ request('end') == $city->ten_tram ? 'selected' : '' }}>
                                        {{ $city->ten_tram }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="search-row-bottom">
                    <div class="date-ticket-wrapper">
                        <div class="date-field">
                            <label>Ngày đi</label>
                            <div class="date-input">
                                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="ticket-field">
                            <label>Số vé</label>
                            <div class="ticket-input">
                                <select name="ticket">
                                    <option value="1" {{ request('ticket', 1) == 1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ request('ticket') == 2 ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ request('ticket') == 3 ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ request('ticket') == 4 ? 'selected' : '' }}>4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="search-button-wrapper">
                    <button type="submit" class="search-button">Tìm chuyến xe</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Switch location functionality
    document.addEventListener('DOMContentLoaded', function() {
        const switchBtn = document.querySelector('.switch-location');
        if (switchBtn) {
            switchBtn.addEventListener('click', function() {
                const startSelect = document.querySelector('select[name="start"]');
                const endSelect = document.querySelector('select[name="end"]');

                if (startSelect && endSelect) {
                    const startValue = startSelect.value;
                    const endValue = endSelect.value;

                    startSelect.value = endValue;
                    endSelect.value = startValue;
                }
            });
        }
    });

    // User authentication functions
    function showLoginModal() {
        window.location.href = '{{ route("login") }}';
    }

    function toggleUserMenu() {
        const menu = document.getElementById('user-menu');
        if (menu) {
            menu.classList.toggle('active');
        }
    }

    function toggleMobileUserMenu() {
        const menu = document.getElementById('mobile-user-menu');
        if (menu) {
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }
    }

    // Close mobile user menu when clicking outside
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('mobile-user-menu');
        const avatar = document.querySelector('.mobile-user');

        if (menu && !menu.contains(e.target) && !avatar?.contains(e.target)) {
            menu.style.display = 'none';
        }
    });

    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobile-menu');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');

    if (mobileMenuToggle && mobileMenuOverlay) {
        mobileMenuToggle.addEventListener('change', function() {
            if (this.checked) {
                mobileMenuOverlay.style.display = 'block';
            } else {
                mobileMenuOverlay.style.display = 'none';
            }
        });
    }
    </script>