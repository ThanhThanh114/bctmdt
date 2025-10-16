<!-- resources/views/components/header.blade.php -->
<style>
    /* Dropdown menu styling for user menu */
    .user-dropdown {
        position: relative;
        display: inline-block;
        z-index: 10000;
    }

    .user-menu {
        position: fixed;
        background: white;
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        padding: 8px 0;
        min-width: 220px;
        max-height: 400px;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 99999;
    }

    .user-menu.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .user-menu-item {
        display: block;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.2s ease;
        white-space: nowrap;
    }

    .user-menu-item:hover {
        background-color: #f3f4f6;
    }

    .user-menu-item.logout:hover {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .user-menu hr {
        margin: 8px 0;
        border: none;
        border-top: 1px solid #e5e7eb;
    }

    /* Custom scrollbar for dropdown */
    .user-menu::-webkit-scrollbar {
        width: 6px;
    }

    .user-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .user-menu::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .user-menu::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .user-btn {
        cursor: pointer;
        user-select: none;
    }

    .user-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>
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
                    <button class="user-btn" type="button">
                        <i class="fas fa-user" style="color: #FF5722; font-size: 16px;"></i>
                        {{ Auth::user()->fullname ?? Auth::user()->name ?? 'User' }}
                        <i class="fas fa-chevron-down" style="color: #333; font-size: 12px;"></i>
                    </button>
                    <div class="user-menu" id="user-menu">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="user-menu-item">
                                <i class="fas fa-cog"></i> Trang quản trị
                            </a>
                            <hr style="margin: 8px 0; border: none; border-top: 1px solid #e5e7eb;">
                        @elseif(Auth::user()->role === 'staff')
                            <a href="{{ route('staff.dashboard') }}" class="user-menu-item">
                                <i class="fas fa-user-tie"></i> Trang nhân viên
                            </a>
                            <hr style="margin: 8px 0; border: none; border-top: 1px solid #e5e7eb;">
                        @elseif(Auth::user()->role === 'bus_owner')
                            <a href="{{ route('bus-owner.dashboard') }}" class="user-menu-item">
                                <i class="fas fa-bus"></i> Trang nhà xe
                            </a>
                            <hr style="margin: 8px 0; border: none; border-top: 1px solid #e5e7eb;">
                        @endif
                        <a href="{{ route('profile.edit') }}" class="user-menu-item">
                            <i class="fas fa-user-edit"></i> Thông tin tài khoản
                        </a>
                        <a href="{{ route('booking.history') }}" class="user-menu-item">
                            <i class="fas fa-history"></i> Lịch sử đặt vé
                        </a>
                        <hr style="margin: 8px 0; border: none; border-top: 1px solid #e5e7eb;">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="user-menu-item logout"
                                style="border: none; background: none; width: 100%; text-align: left; cursor: pointer;">
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
        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Trang
            chủ</a>
        <a href="{{ route('trips.trips') }}" class="nav-link {{ request()->routeIs('trips.*') ? 'active' : '' }}">Lịch
            trình</a>
        <a href="{{ route('tracking.tracking') }}"
            class="nav-link {{ request()->routeIs('tracking.*') ? 'active' : '' }}">Tra cứu vé</a>
        <a href="{{ route('news.news') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">Tin
            tức</a>
        <a href="{{ route('invoice.index') }}"
            class="nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}">Hóa đơn</a>
        <a href="{{ route('contact.contact') }}"
            class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}">Liên hệ</a>
        <a href="{{ route('about.about') }}" class="nav-link {{ request()->routeIs('about.*') ? 'active' : '' }}">Về
            chúng tôi</a>
    </div> <!-- Mobile header -->
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

            <a href="{{ route('home') }}" class="mobile-logo">
                <div
                    style="color: white; font-size: 20px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-bus" style="color: #FFD54F;"></i>
                    FUTA
                </div>
            </a>

            <div class="mobile-avatar">
                @auth
                    <i class="fas fa-user-circle" style="color: #FFD54F; font-size: 24px;"></i>
                @else
                    <i class="fas fa-user" style="color: #FF5722; font-size: 20px;"></i>
                @endauth
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="mobile-menu-overlay">
            <div class="mobile-menu-content">
                <div class="mobile-menu-header">
                    <a href="{{ url('/') }}" class="mobile-menu-logo"></a>
                </div>

                @auth
                    <div class="mobile-user-menu">
                        <div class="mobile-user-info">
                            <h4>Xin chào, {{ Auth::user()->fullname ?? Auth::user()->name ?? 'User' }}!</h4>
                        </div>
                        <ul class="mobile-user-links">
                            <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i> Thông tin tài
                                    khoản</a></li>
                            <li><a href="{{ route('booking.history') }}"><i class="fas fa-history"></i> Lịch sử đặt
                                    vé</a></li>
                        </ul>
                        <div class="mobile-logout">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    style="border: none; background: none; color: inherit; cursor: pointer; width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="mobile-login">
                        <a href="{{ route('login') }}"><button>Đăng nhập/Đăng ký</button></a>
                    </div>
                @endauth

                <nav class="mobile-nav">
                    <ul>
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang
                                chủ</a></li>
                        <li><a href="{{ route('trips.trips') }}"
                                class="{{ request()->routeIs('trips.*') ? 'active' : '' }}">Lịch trình</a></li>
                        <li><a href="{{ route('tracking.tracking') }}"
                                class="{{ request()->routeIs('tracking.*') ? 'active' : '' }}">Tra cứu vé</a></li>
                        <li><a href="{{ route('news.news') }}"
                                class="{{ request()->routeIs('news.*') ? 'active' : '' }}">Tin tức</a></li>
                        <li><a href="{{ route('invoice.index') }}"
                                class="{{ request()->routeIs('invoice.*') ? 'active' : '' }}">Hóa đơn</a></li>
                        <li><a href="{{ route('contact.contact') }}"
                                class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">Liên hệ</a></li>
                        <li><a href="{{ route('about.about') }}"
                                class="{{ request()->routeIs('about.*') ? 'active' : '' }}">Về chúng tôi</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<!-- User dropdown functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userMenu = document.getElementById('user-menu');
        const userBtn = document.querySelector('.user-btn');

        console.log('User menu elements:', {
            userMenu,
            userBtn
        }); // Debug log

        if (userBtn && userMenu) {
            // Function to position dropdown
            function positionDropdown() {
                const btnRect = userBtn.getBoundingClientRect();
                const menuHeight = userMenu.offsetHeight || 300; // Estimated height
                const spaceAbove = btnRect.top;
                const spaceBelow = window.innerHeight - btnRect.bottom;

                // Position dropdown
                if (spaceBelow < menuHeight && spaceAbove > spaceBelow) {
                    // Show above if not enough space below
                    userMenu.style.bottom = (window.innerHeight - btnRect.top + 8) + 'px';
                    userMenu.style.top = 'auto';
                } else {
                    // Show below by default
                    userMenu.style.top = (btnRect.bottom + 8) + 'px';
                    userMenu.style.bottom = 'auto';
                }
                userMenu.style.right = (window.innerWidth - btnRect.right) + 'px';
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function (event) {
                if (!userBtn.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.remove('active');
                }
            });

            // Toggle dropdown when clicking button
            userBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Button clicked, toggling menu'); // Debug log

                if (!userMenu.classList.contains('active')) {
                    positionDropdown();
                }

                userMenu.classList.toggle('active');
            });

            // Reposition on window resize
            window.addEventListener('resize', function () {
                if (userMenu.classList.contains('active')) {
                    positionDropdown();
                }
            });

            console.log('Dropdown functionality initialized'); // Debug log
        } else {
            console.log('Dropdown elements not found'); // Debug log
        }
    });
</script>