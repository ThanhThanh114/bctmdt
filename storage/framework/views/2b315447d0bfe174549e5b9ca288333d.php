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
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Index.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/footer.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Search-form.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Lichtrinh.css')); ?>">
</head>

<body>
    <?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="main-content">
        <?php echo $__env->make('home.search-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="trips-container">
            <!-- Sidebar Bộ lọc bên trái -->
            <aside class="filters-sidebar">
                <div class="sidebar-header">
                    <h3><i class="fas fa-filter"></i> Bộ lọc tìm kiếm</h3>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-sort"></i> Sắp xếp theo</h4>
                    <select id="sortSelect" onchange="changeSort(this.value)" class="filter-select">
                        <option value="date_asc" <?php echo e(($params['sort'] ?? 'date_asc') == 'date_asc' ? 'selected' : ''); ?>>
                            Ngày gần nhất</option>
                        <option value="date_desc" <?php echo e(($params['sort'] ?? 'date_asc') == 'date_desc' ? 'selected' : ''); ?>>
                            Ngày xa nhất</option>
                        <option value="time_asc" <?php echo e(($params['sort'] ?? 'date_asc') == 'time_asc' ? 'selected' : ''); ?>>Giờ
                            sớm nhất</option>
                        <option value="time_desc" <?php echo e(($params['sort'] ?? 'date_asc') == 'time_desc' ? 'selected' : ''); ?>>
                            Giờ muộn nhất</option>
                        <option value="price_asc" <?php echo e(($params['sort'] ?? 'date_asc') == 'price_asc' ? 'selected' : ''); ?>>
                            Giá thấp đến cao</option>
                        <option value="price_desc" <?php echo e(($params['sort'] ?? 'date_asc') == 'price_desc' ? 'selected' : ''); ?>>Giá cao đến thấp</option>
                    </select>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-bus"></i> Loại xe</h4>
                    <select id="busTypeSelect" onchange="changeBusType(this.value)" class="filter-select">
                        <option value="all" <?php echo e(($params['bus_type'] ?? 'all') == 'all' ? 'selected' : ''); ?>>Tất cả
                        </option>
                        <option value="Giường nằm" <?php echo e(($params['bus_type'] ?? 'all') == 'Giường nằm' ? 'selected' : ''); ?>>
                            Giường nằm</option>
                        <option value="Limousine" <?php echo e(($params['bus_type'] ?? 'all') == 'Limousine' ? 'selected' : ''); ?>>
                            Limousine</option>
                        <option value="Ghế ngồi" <?php echo e(($params['bus_type'] ?? 'all') == 'Ghế ngồi' ? 'selected' : ''); ?>>Ghế
                            ngồi</option>
                    </select>
                </div>

                <div class="filter-section">
                    <h4><i class="fas fa-money-bill-wave"></i> Khoảng giá</h4>
                    <select id="priceRangeSelect" onchange="changePriceRange(this.value)" class="filter-select">
                        <option value="all" <?php echo e(($params['price_range'] ?? 'all') == 'all' ? 'selected' : ''); ?>>Tất cả
                        </option>
                        <option value="0-200000" <?php echo e(($params['price_range'] ?? 'all') == '0-200000' ? 'selected' : ''); ?>>
                            Dưới 200k</option>
                        <option value="200000-400000" <?php echo e(($params['price_range'] ?? 'all') == '200000-400000' ? 'selected' : ''); ?>>200k - 400k</option>
                        <option value="400000-600000" <?php echo e(($params['price_range'] ?? 'all') == '400000-600000' ? 'selected' : ''); ?>>400k - 600k</option>
                        <option value="600000-1000000" <?php echo e(($params['price_range'] ?? 'all') == '600000-1000000' ? 'selected' : ''); ?>>Trên 600k</option>
                    </select>
                </div>

                <button class="reset-filters-btn" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Đặt lại bộ lọc
                </button>
            </aside>

            <!-- Nội dung chính bên phải -->
            <div class="trips-content">
                <!-- Kết quả tìm kiếm -->
                <?php echo $__env->make('trips.results', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <!-- Phân trang -->
                <?php if($totalPages > 1): ?>
                    <?php echo $__env->make('trips.pagination', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="<?php echo e(asset('assets/js/Search-form.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/Lichtrinh.js')); ?>"></script>
</body>

</html><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/trips/trips.blade.php ENDPATH**/ ?>