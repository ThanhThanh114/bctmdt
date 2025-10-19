<?php if($totalPages > 1): ?>
    <div class="pagination">
        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?php echo e(route('trips.trips', array_merge($params, ['page' => $i]))); ?>" class="page-btn <?php echo e($i === $params['page'] ? 'active' : ''); ?>"><?php echo e($i); ?></a>
        <?php endfor; ?>
    </div>
<?php else: ?>
    <div class="no-results">
        <h3>Không tìm thấy chuyến xe phù hợp</h3>
        <p>Vui lòng thử lại với các tiêu chí tìm kiếm khác hoặc chọn ngày khác.</p>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/trips/pagination.blade.php ENDPATH**/ ?>