<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Staff Dashboard') - FUTA Bus</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('staff.dashboard') }}" class="nav-link">Trang chủ</a>
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
                            <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            {{-- <a href="{{ route('staff.dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="FUTA Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">FUTA Bus</span>
            </a> --}}

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('assets/images/logo.jpg') }}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->fullname ?? Auth::user()->username }}</a>
                        <small class="text-muted">
                            @if(strtolower(Auth::user()->role) === 'admin')
                                Quản trị
                            @elseif(Auth::user()->role === 'Staff')
                                Nhân viên
                            @elseif(Auth::user()->role === 'Bus_owner')
                                Nhà xe
                            @else
                                Người dùng
                            @endif
                        </small>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @if(strtolower(Auth::user()->role) === 'staff')
                        <!-- Staff Bookings -->
                        <li class="nav-item">
                            <a href="{{ route('staff.bookings.index') }}" class="nav-link {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-ticket-alt"></i>
                                <p>Quản lý đặt vé</p>
                            </a>
                        </li>

                        <!-- Staff Trips -->
                        <li class="nav-item">
                            <a href="{{ route('staff.trips.index') }}" class="nav-link {{ request()->routeIs('staff.trips.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-route"></i>
                                <p>Quản lý chuyến xe</p>
                            </a>
                        </li>

                        <!-- Staff Customers -->
                        <li class="nav-item">
                            <a href="{{ route('staff.customers.index') }}" class="nav-link {{ request()->routeIs('staff.customers.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Khách hàng</p>
                            </a>
                        </li>
                        @endif

                        @if(strtolower(Auth::user()->role) === 'bus_owner')
                        <!-- Bus Owner Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('bus-owner.dashboard') }}" class="nav-link {{ request()->routeIs('bus-owner.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bus"></i>
                                <p>Quản lý nhà xe</p>
                            </a>
                        </li>

                        <!-- Bus Owner Trips -->
                        <li class="nav-item">
                            <a href="{{ route('bus-owner.trips.index') }}" class="nav-link {{ request()->routeIs('bus-owner.trips.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-route"></i>
                                <p>Chuyến xe của tôi</p>
                            </a>
                        </li>
                        @endif

                        <!-- Profile -->
                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Hồ sơ cá nhân</p>
                            </a>
                        </li>
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

    <!-- Custom JS -->
    <script src="{{ asset('js/admin-custom.js') }}"></script>

    @stack('scripts')
</body>

</html>
