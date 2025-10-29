<?php
// Kiểm tra session trước khi start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Trình - FUTA Bus Lines</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Search-form.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Lichtrinh.css') }}">
</head>

<body>
    @include('layouts.header')

    <main class="main-content">
        @include('home.search-form')

        <div class="trips-container">
            <!-- Sidebar Bộ lọc bên trái -->
            <aside class="filters-sidebar">
                <div class="sidebar-header">
                    <h3><i class="fas fa-filter"></i> Bộ lọc tìm kiếm</h3>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-sort"></i> Sắp xếp theo</h4>
                    <select id="sortSelect" onchange="changeSort(this.value)" class="filter-select">
                        <option value="date_asc" {{ ($params['sort'] ?? 'date_asc') == 'date_asc' ? 'selected' : '' }}>
                            Ngày gần nhất</option>
                        <option value="date_desc" {{ ($params['sort'] ?? 'date_asc') == 'date_desc' ? 'selected' : '' }}>
                            Ngày xa nhất</option>
                        <option value="time_asc" {{ ($params['sort'] ?? 'date_asc') == 'time_asc' ? 'selected' : '' }}>Giờ
                            sớm nhất</option>
                        <option value="time_desc" {{ ($params['sort'] ?? 'date_asc') == 'time_desc' ? 'selected' : '' }}>
                            Giờ muộn nhất</option>
                        <option value="price_asc" {{ ($params['sort'] ?? 'date_asc') == 'price_asc' ? 'selected' : '' }}>
                            Giá thấp đến cao</option>
                        <option value="price_desc" {{ ($params['sort'] ?? 'date_asc') == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                    </select>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-bus"></i> Loại xe</h4>
                    <select id="busTypeSelect" onchange="changeBusType(this.value)" class="filter-select">
                        <option value="all" {{ ($params['bus_type'] ?? 'all') == 'all' ? 'selected' : '' }}>Tất cả
                        </option>
                        <option value="Giường nằm" {{ ($params['bus_type'] ?? 'all') == 'Giường nằm' ? 'selected' : '' }}>
                            Giường nằm</option>
                        <option value="Limousine" {{ ($params['bus_type'] ?? 'all') == 'Limousine' ? 'selected' : '' }}>
                            Limousine</option>
                        <option value="Ghế ngồi" {{ ($params['bus_type'] ?? 'all') == 'Ghế ngồi' ? 'selected' : '' }}>Ghế
                            ngồi</option>
                    </select>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-building"></i> Nhà xe</h4>
                    <select id="busCompanySelect" onchange="changeBusCompany(this.value)" class="filter-select">
                        <option value="all" {{ ($params['bus_company'] ?? 'all') == 'all' ? 'selected' : '' }}>Tất cả nhà xe</option>
                        @foreach(\App\Models\NhaXe::all() as $nhaXe)
                        <option value="{{ $nhaXe->ma_nha_xe }}" {{ ($params['bus_company'] ?? 'all') == $nhaXe->ma_nha_xe ? 'selected' : '' }}>
                            {{ $nhaXe->ten_nha_xe }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-calendar-day"></i> Ngày đi</h4>
                    <input type="date" id="departureDateSelect" onchange="changeDepartureDate(this.value)" class="filter-select"
                           value="{{ $params['departure_date'] ?? '' }}" min="{{ date('Y-m-d') }}">
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-calendar-check"></i> Ngày đến</h4>
                    <input type="date" id="arrivalDateSelect" onchange="changeArrivalDate(this.value)" class="filter-select"
                           value="{{ $params['arrival_date'] ?? '' }}">
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-user-tie"></i> Tài xế</h4>
                    <select id="driverSelect" onchange="changeDriver(this.value)" class="filter-select">
                        <option value="all" {{ ($params['driver'] ?? 'all') == 'all' ? 'selected' : '' }}>Tất cả tài xế</option>
                        @foreach(\App\Models\NhanVien::where('chuc_vu', 'Tài xế')->get() as $driver)
                        {{-- Pass driver's name here because the controller filters by ten_tai_xe (name) --}}
                        <option value="{{ $driver->ten_nv }}" {{ ($params['driver'] ?? 'all') == $driver->ten_nv ? 'selected' : '' }}>
                            {{ $driver->ten_nv }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-money-bill-wave"></i> Khoảng giá</h4>
                    <select id="priceRangeSelect" onchange="changePriceRange(this.value)" class="filter-select">
                        <option value="all" {{ ($params['price_range'] ?? 'all') == 'all' ? 'selected' : '' }}>Tất cả
                        </option>
                        <option value="0-200000" {{ ($params['price_range'] ?? 'all') == '0-200000' ? 'selected' : '' }}>
                            Dưới 200k</option>
                        <option value="200000-400000" {{ ($params['price_range'] ?? 'all') == '200000-400000' ? 'selected' : '' }}>200k - 400k</option>
                        <option value="400000-600000" {{ ($params['price_range'] ?? 'all') == '400000-600000' ? 'selected' : '' }}>400k - 600k</option>
                        <option value="600000-1000000" {{ ($params['price_range'] ?? 'all') == '600000-1000000' ? 'selected' : '' }}>Trên 600k</option>
                    </select>
                </div>

                <button class="reset-filters-btn" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Đặt lại bộ lọc
                </button>
            </aside>

            <!-- Nội dung chính bên phải -->
            <div class="trips-content">
                <!-- Kết quả tìm kiếm -->
                @include('trips.results')

                <!-- Phân trang -->
                @if ($totalPages > 1)
                    @include('trips.pagination')
                @endif
            </div>
        </div>
    </main>

    @include('layouts.footer')

    <script src="{{ asset('assets/js/Search-form.js') }}"></script>
    <script src="{{ asset('assets/js/Lichtrinh.js') }}"></script>
    <script>
        // Intercept pagination link clicks and use AJAX fragment loading so URL stays /lichtrinh
        document.addEventListener('click', function (e) {
            var a = e.target.closest('#paginationWrapper a, .pagination a, a.page-btn');
            if (a && a.tagName === 'A') {
                // Only intercept links that point to the trips route
                try {
                    var href = a.getAttribute('href');
                    if (href && href.indexOf('/lichtrinh') !== -1) {
                        e.preventDefault();
                        if (typeof fetchAndReplace === 'function') {
                            fetchAndReplace(href);
                        } else {
                            // fallback
                            window.location.href = href;
                        }
                    }
                } catch (err) {
                    // ignore and allow default
                }
            }
        });

        // Remove / hide default query parameters (like show_all=1) from the address bar
        // without reloading the page. This keeps the page content (showing all trips)
        // but cleans the URL to /lichtrinh for a nicer canonical link.
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var url = new URL(window.location.href);
                var params = new URLSearchParams(url.search);

                // If show_all present, remove entire query string to hide it
                if (params.has('show_all')) {
                    history.replaceState({}, document.title, url.pathname);
                    return;
                }

                // Otherwise, if only default params are present (driver=all&page=1 etc.),
                // hide them as well to keep the URL clean.
                var defaults = {
                    driver: 'all',
                    page: '1',
                    bus_type: 'all',
                    sort: 'date_asc',
                    price_range: 'all',
                    bus_company: 'all',
                    departure_date: '',
                    arrival_date: ''
                };

                var onlyDefaults = true;
                params.forEach(function (value, key) {
                    if (!defaults.hasOwnProperty(key) || String(value) !== String(defaults[key])) {
                        onlyDefaults = false;
                    }
                });

                if (onlyDefaults && params.toString().length > 0) {
                    history.replaceState({}, document.title, url.pathname);
                }
            } catch (e) {
                // don't block the page if URL API fails
                console && console.warn && console.warn('Failed to normalize URL', e);
            }
        });
    </script>
</body>

</html>
