<div class="results-info">
    <?php if($trips->isNotEmpty()): ?>
        <span>Tìm thấy <?php echo e($totalCount); ?> chuyến xe</span>
    <?php endif; ?>
</div>

<?php if(!empty($trips) && $trips->isNotEmpty()): ?>
    <div class="trip-list">
        <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="trip-card">
            <div class="trip-info">
                <div class="route">
                    <i class="fas fa-route"></i>
                    <?php echo e($trip->diem_di); ?> → <?php echo e($trip->diem_den); ?>

                </div>
                <div class="datetime">
                    <div><i class="fas fa-calendar"></i> <?php echo e(\Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y')); ?></div>
                    <div><i class="fas fa-clock"></i> <?php echo e(\Carbon\Carbon::parse($trip->gio_di)->format('H:i')); ?></div>
                </div>
            </div>

            <div class="trip-details">
                <div class="bus-info">
                    <span class="bus-type"><?php echo e($trip->loai_xe); ?></span>
                    <span class="company"><?php echo e($trip->ten_nha_xe); ?></span>
                </div>
                <div class="seats-info">
                    <span class="available-seats"><?php echo e($trip->available_seats); ?> chỗ trống</span>
                </div>
            </div>

            <div class="trip-footer">
                <div class="price">
                    <?php echo e(number_format($trip->gia_ve, 0, ',', '.')); ?>đ
                </div>
                <a href="<?php echo e(route('booking.show', $trip->id)); ?>" class="book-btn">Đặt vé</a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <div class="no-results">
        <i class="fas fa-bus"></i>
        <h3>Không tìm thấy chuyến xe phù hợp</h3>
        <p>Vui lòng thử lại với các tiêu chí tìm kiếm khác hoặc chọn ngày khác.</p>
    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>
<?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/trips/results.blade.php ENDPATH**/ ?>