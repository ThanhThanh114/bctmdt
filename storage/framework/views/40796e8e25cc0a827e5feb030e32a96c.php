<div class="search-form-container">
    <div class="search-form-wrapper">
        <div class="trip-type-wrapper">
            <div class="trip-type-radio">
                <label class="radio-option <?php echo e(($trip ?? '') == 'oneway' ? 'active' : ''); ?>">
                    <input type="radio" name="trip" value="oneway" <?php echo e(($trip ?? '') != 'round' ? 'checked' : ''); ?>>
                    <span class="radio-text">Một chiều</span>
                </label>
                <label class="radio-option <?php echo e(($trip ?? '') == 'round' ? 'active' : ''); ?>">
                    <input type="radio" name="trip" value="round">
                    <span class="radio-text">Khứ hồi</span>
                </label>
            </div>
            <span class="guide-link">
                <a href="#" target="_blank">Hướng dẫn mua vé</a>
            </span>
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
                                    <option value="<?php echo e($city->ten_tram); ?>" <?php echo e(($start ?? '') == $city->ten_tram ? 'selected' : ''); ?>><?php echo e($city->ten_tram); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                <div class="switch-location" onclick="swapLocations()" title="Đổi vị trí">
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
                                <option value="<?php echo e($city->ten_tram); ?>" <?php echo e(($end ?? '') == $city->ten_tram ? 'selected' : ''); ?>><?php echo e($city->ten_tram); ?></option>
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
                            <input type="date" name="date" value="<?php echo e($date ?? ''); ?>">
                        </div>
                    </div>
                <div class="ticket-field">
                    <label>Số vé</label>
                    <div class="ticket-input">
                        <select name="ticket">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e(($ticket ?? 1) == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
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
<?php /**PATH E:\bctmdt\resources\views/home/search-form.blade.php ENDPATH**/ ?>