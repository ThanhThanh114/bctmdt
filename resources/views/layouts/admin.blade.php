<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - FUTA Bus</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bus-owner-custom.css') }}">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light navbar-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Trang chủ</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Account Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user"></i> {{ Auth::user()->fullname ?? Auth::user()->username }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Hồ sơ cá nhân
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('password.edit') }}" class="dropdown-item">
                            <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item"
                                style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            {{-- <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="FUTA Logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light">FUTA Bus</span>
            </a> --}}

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('assets/images/logo.jpg') }}" class="img-circle elevation-2"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->fullname ?? Auth::user()->username }}</a>
                        <small class="text-muted">{{ Auth::user()->role }}</small>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        @if(strtolower(Auth::user()->role) === 'admin')
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <!-- User Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Quản lý người dùng</p>
                            </a>
                        </li>

                        <!-- Employee Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.nhanvien.index') }}"
                                class="nav-link {{ request()->routeIs('admin.nhanvien.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>Quản lý nhân viên</p>
                            </a>
                        </li>

                        <!-- Booking Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.datve.index') }}"
                                class="nav-link {{ request()->routeIs('admin.datve.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-ticket-alt"></i>
                                <p>Quản lý đặt vé</p>
                            </a>
                        </li>

                        <!-- Comment Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.comments.index') }}"
                                class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Quản lý bình luận</p>
                            </a>
                        </li>

                        <!-- Bus Company Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.nha-xe.index') }}"
                                class="nav-link {{ request()->routeIs('admin.nha-xe.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Quản lý nhà xe</p>
                            </a>
                        </li>

                        <!-- Bus Company Account Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.tai-khoan-nha-xe.index') }}"
                                class="nav-link {{ request()->routeIs('admin.tai-khoan-nha-xe.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>TK Nhà xe</p>
                            </a>
                        </li>

                        <!-- Revenue Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.doanhthu.index') }}"
                                class="nav-link {{ request()->routeIs('admin.doanhthu.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-dollar-sign"></i>
                                <p>Quản lý doanh thu</p>
                            </a>
                        </li>

                        <!-- Promotion Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.khuyenmai.index') }}"
                                class="nav-link {{ request()->routeIs('admin.khuyenmai.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Quản lý khuyến mãi</p>
                            </a>
                        </li>

                        <!-- News Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.tintuc.index') }}"
                                class="nav-link {{ request()->routeIs('admin.tintuc.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>Quản lý tin tức</p>
                            </a>
                        </li>

                        <!-- Contact Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.contact.index') }}"
                                class="nav-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Quản lý liên hệ</p>
                            </a>
                        </li>

                        <!-- Reports -->
                        <li class="nav-item">
                            <a href="{{ route('admin.report.index') }}"
                                class="nav-link {{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Quản lý báo cáo</p>
                            </a>
                        </li>

                        <!-- Profile -->
                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}"
                                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Hồ sơ cá nhân</p>
                            </a>
                        </li>
                        @endif

                        @if(strtolower(Auth::user()->role) === 'staff')
                        <!-- Staff Menu -->
                        <li class="nav-item">
                            <a href="{{ route('staff.dashboard') }}"
                                class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('staff.comments.index') }}"
                                class="nav-link {{ request()->routeIs('staff.comments.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Quản lý bình luận</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('staff.news.index') }}"
                                class="nav-link {{ request()->routeIs('staff.news.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>Quản lý tin tức</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('staff.bookings.index') }}"
                                class="nav-link {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-ticket-alt"></i>
                                <p>Quản lý đặt vé</p>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('staff.ticket-scanner.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('staff.ticket-scanner.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-qrcode"></i>
                                <p>
                                    Soát vé QR
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('staff.ticket-scanner.index') }}"
                                       class="nav-link {{ request()->routeIs('staff.ticket-scanner.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Quét mã QR</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('staff.ticket-scanner.today-trips') }}"
                                       class="nav-link {{ request()->routeIs('staff.ticket-scanner.today-trips') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Chuyến xe hôm nay</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('staff.promotions.index') }}"
                                class="nav-link {{ request()->routeIs('staff.promotions.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-gift"></i>
                                <p>Quản lý vé khuyến mãi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('staff.contact.index') }}"
                                class="nav-link {{ request()->routeIs('staff.contact.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Quản lý liên hệ</p>
                            </a>
                        </li>

                        <!-- Profile -->
                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}"
                                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Hồ sơ cá nhân</p>
                            </a>
                        </li>
                        @endif

                        @if(strtolower(Auth::user()->role) === 'user')
                        <!-- User Menu -->
                        <li class="nav-item">
                            <a href="{{ route('user.dashboard') }}"
                                class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('user.bookings.index') }}"
                                class="nav-link {{ request()->routeIs('user.bookings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-ticket-alt"></i>
                                <p>Vé của tôi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('user.upgrade.index') }}"
                                class="nav-link {{ request()->routeIs('user.upgrade.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-star"></i>
                                <p>Nâng cấp lên Nhà xe</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}"
                                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Hồ sơ cá nhân</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('password.edit') }}"
                                class="nav-link {{ request()->routeIs('password.edit') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Đổi mật khẩu</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-left w-100" style="border: none;">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p style="display: inline;">Đăng xuất</p>
                                </button>
                            </form>
                        </li>
                        @endif

                        @if(strtolower(Auth::user()->role) === 'bus_owner')
                        <!-- Bus Owner Menu -->
                        <li class="nav-header">QUẢN LÝ</li>

                        <li class="nav-item">
                            <a href="{{ route('bus-owner.dashboard') }}"
                                class="nav-link {{ request()->routeIs('bus-owner.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bus-owner.nha-xe.index') }}"
                                class="nav-link {{ request()->routeIs('bus-owner.nha-xe.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Quản lý nhà xe</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bus-owner.tram-xe.index') }}"
                                class="nav-link {{ request()->routeIs('bus-owner.tram-xe.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>Quản lý trạm xe</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bus-owner.trips.index') }}"
                                class="nav-link {{ request()->routeIs('bus-owner.trips.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bus"></i>
                                <p>Quản lý chuyến xe</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bus-owner.nhan-vien.index') }}"
                                class="nav-link {{ request()->routeIs('bus-owner.nhan-vien.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Quản lý nhân viên</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bus-owner.dat-ve.index') }}"
                                class="nav-link {{ request()->routeIs('bus-owner.dat-ve.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-ticket-alt"></i>
                                <p>Quản lý đặt vé</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bus-owner.doanh-thu.index') }}"
                                class="nav-link {{ request()->routeIs('bus-owner.doanh-thu.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Quản lý doanh thu</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}"
                                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Hồ sơ cá nhân</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">@yield('breadcrumb', 'Tổng quan')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 <a href="#">FUTA Bus</a>.</strong>
            Hệ thống quản lý đặt vé xe khách.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/admin-custom.js') }}"></script>
    <script src="{{ asset('js/bus-owner-custom.js') }}"></script>

    @stack('scripts')
    @yield('scripts')
</body>

</html>