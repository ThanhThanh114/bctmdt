<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Search-form.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/DatVe.css')); ?>?v=<?php echo e(time()); ?>">
    <style>
        /* Custom styles for booking page */
        .booking-info-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 30px !important;
            max-width: 1400px !important;
            margin: 0 auto !important;
        }
        
        @media (max-width: 768px) {
            .booking-info-grid {
                grid-template-columns: 1fr !important;
            }
        }
        
        .trip-route-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .trip-details-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }
        
        /* Remove search form white border */
        .search-form-container {
            background: transparent !important;
            box-shadow: none !important;
            padding: 20px 0 !important;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

<!-- Search Form Section -->
<div class="search-form-container">
    <div class="search-form-wrapper" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="trip-type-wrapper">
            <div class="trip-type-radio">
                <label class="radio-option active">
                    <input type="radio" name="trip" value="oneway" checked>
                    <span class="radio-text">Một chiều</span>
                </label>
                <label class="radio-option">
                    <input type="radio" name="trip" value="round">
                    <span class="radio-text">Khứ hồi</span>
                </label>
            </div>
        </div>

        <form class="search-form" method="GET" action="<?php echo e(route('trips.trips')); ?>">
            <div class="search-row">
                <div class="search-locations">
                    <div class="location-field">
                        <label>Điểm đi</label>
                        <div class="location-input">
                            <select name="start" required>
                                <option value="">Chọn điểm đi</option>
                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($city->ten_tram); ?>" <?php echo e(($start ?? '') == $city->ten_tram ? 'selected' : ''); ?>>
                                        <?php echo e($city->ten_tram); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="switch-location" onclick="swapLocations(event)" title="Đổi vị trí">
                        <div class="switch-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>

                    <div class="location-field">
                        <label>Điểm đến</label>
                        <div class="location-input">
                            <select name="end" required>
                                <option value="">Chọn điểm đến</option>
                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($city->ten_tram); ?>" <?php echo e(($end ?? '') == $city->ten_tram ? 'selected' : ''); ?>>
                                        <?php echo e($city->ten_tram); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <input type="date" name="date" value="<?php echo e($date ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="ticket-field">
                        <label>Số vé</label>
                        <div class="ticket-input">
                            <select name="ticket">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                <?php endfor; ?>
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

<main class="new-background-color min-h-screen py-8">
    <div class="layout">

    <!-- BOOKING ALERT -->
    <?php if(session('error')): ?>
        <div class="alert alert-error" style="margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

        <?php if($selectedTrip): ?>
            <div class="mb-8" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
                <!-- Main Grid: 2 columns -->
                <div class="booking-info-grid" style="align-items: start;">
                    
                    <!-- Left Column: Thông tin chuyến xe -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-ticket-alt orange mr-2"></i>
                            Thông tin chuyến xe
                        </h2>
                        
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange">
                            <!-- Thông tin tuyến đường -->
                            <div class="trip-route-info" style="margin-bottom: 24px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 24px; font-weight: bold; color: var(--primary-color, #FF7B39);">
                                            <?php echo e(date('H:i', strtotime($selectedTrip->gio_di))); ?>

                                        </div>
                                        <div style="font-size: 14px; color: #666; margin-top: 4px;"><?php echo e($selectedTrip->tramDi->ten_tram); ?></div>
                                    </div>

                                    <div style="flex: 1; margin: 0 20px; position: relative;">
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 12px; height: 12px; background: var(--primary-color, #FF7B39); border-radius: 50%;"></div>
                                            <div style="flex: 1; height: 2px; background: linear-gradient(to right, #ddd 50%, transparent 50%); background-size: 10px 2px; margin: 0 8px; position: relative;">
                                                <i class="fas fa-bus" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: var(--primary-color, #FF7B39); background: white; padding: 0 4px; font-size: 20px;"></i>
                                            </div>
                                            <div style="width: 12px; height: 12px; background: var(--primary-color, #FF7B39); border-radius: 50%;"></div>
                                        </div>
                                        <div style="text-align: center; font-size: 12px; color: #666; margin-top: 4px;">
                                            <?php echo e(\Carbon\Carbon::parse($selectedTrip->ngay_di)->format('d/m/Y')); ?>

                                        </div>
                                    </div>

                                    <div style="text-align: center;">
                                        <div style="font-size: 24px; font-weight: bold; color: var(--primary-color, #FF7B39);">19:30</div>
                                        <div style="font-size: 14px; color: #666; margin-top: 4px;"><?php echo e($selectedTrip->tramDen->ten_tram); ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin xe -->
                            <div class="trip-details-info">
                                <div class="info-row">
                                    <span style="color: #666; font-weight: 500;">
                                        <i class="fas fa-building" style="color: var(--primary-color, #FF7B39); margin-right: 8px;"></i>Nhà xe:
                                    </span>
                                    <span style="font-weight: 600;"><?php echo e($selectedTrip->nhaXe->ten_nha_xe); ?></span>
                                </div>
                                <div class="info-row">
                                    <span style="color: #666; font-weight: 500;">
                                        <i class="fas fa-bus" style="color: var(--primary-color, #FF7B39); margin-right: 8px;"></i>Loại xe:
                                    </span>
                                    <span style="font-weight: 600;"><?php echo e($selectedTrip->loai_xe); ?></span>
                                </div>
                                <div class="info-row">
                                    <span style="color: #666; font-weight: 500;">
                                        <i class="fas fa-money-bill" style="color: var(--primary-color, #FF7B39); margin-right: 8px;"></i>Giá vé:
                                    </span>
                                    <span style="font-weight: 700; font-size: 20px; color: var(--primary-color, #FF7B39);">
                                        <?php echo e(number_format($selectedTrip->gia_ve, 0, ',', '.')); ?>đ
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span style="color: #666; font-weight: 500;">
                                        <i class="fas fa-chair" style="color: var(--primary-color, #FF7B39); margin-right: 8px;"></i>Chỗ trống:
                                    </span>
                                    <span style="font-weight: 600; color: #28a745;">
                                        <?php echo e($selectedTrip->available_seats); ?> chỗ
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Form đặt vé -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-user-plus orange mr-2"></i>
                            Thông tin đặt vé
                        </h3>
                        
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <form method="POST" action="<?php echo e(route('booking.store')); ?>" id="bookingForm">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="trip_id" value="<?php echo e($selectedTrip->id); ?>">

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Số lượng vé:
                                    </label>
                                    <select name="seat_count" id="seatCount"
                                        class="w-full md:w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?> vé</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="passenger-form-section">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                        Thông tin hành khách
                                    </h4>
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên:</label>
                                            <input type="text" name="passenger_name" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange"
                                                placeholder="Nhập họ tên đầy đủ">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại:</label>
                                            <input type="tel" name="passenger_phone" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange"
                                                placeholder="Số điện thoại">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email (không bắt buộc):</label>
                                            <input type="email" name="passenger_email"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange"
                                                placeholder="email@example.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row gap-4 mt-8">
                                    <button type="submit"
                                        class="flex-1 bg-orange text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Đặt vé ngay
                                    </button>
                                    <button type="button" onclick="window.history.back()"
                                        class="flex-1 bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition border-0">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Quay lại
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div><!-- End main grid -->
            </div>

        <?php else: ?>
            <!-- Hiển thị danh sách chuyến xe nếu chưa chọn -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-search orange mr-2"></i>
                    Tìm kiếm chuyến xe
                </h2>

                <div class="text-center py-20 bg-white rounded-xl">
                    <i class="fas fa-bus text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">
                        Vui lòng nhập thông tin tìm kiếm
                    </h3>
                    <p class="text-gray-500 mb-4">
                        Hãy chọn điểm đi, điểm đến và ngày đi để tìm chuyến xe phù hợp
                    </p>
                    <a href="<?php echo e(route('home')); ?>"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-orange text-white rounded-lg hover:bg-orange-600 transition">
                        <i class="fas fa-search"></i>
                        Tìm kiếm chuyến xe
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/Search-form.js')); ?>"></script>
    <script>
        // Animation cho các card
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.trip-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/booking/results.blade.php ENDPATH**/ ?>