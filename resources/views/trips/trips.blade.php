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
</body>

</html>