    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Tracuu.css')); ?>">
<?php $__env->startSection('content'); ?>
<div class="tracuu-container">
    <h2 class="title">TRA CỨU THÔNG TIN ĐẶT VÉ</h2>

    <!-- Form -->
    <form method="POST" class="form-box">
        <?php echo csrf_field(); ?>
        <div class="input-group">
            <i class="fa fa-phone"></i>
            <input type="text" name="phone" placeholder="Vui lòng nhập số điện thoại" required>
        </div>
        <div class="input-group">
            <i class="fa fa-ticket"></i>
            <input type="text" name="code" placeholder="Vui lòng nhập mã vé" required>
        </div>
        <button type="submit" class="search-btn">
            <i class="fa fa-search"></i> Tra cứu
        </button>
    </form>

    <!-- Kết quả -->
    <div class="result-box" style="display:block;">
        <?php if($error): ?>
            <p style="color:red; text-align:center;"><?php echo e($error); ?></p>
        <?php elseif($result): ?>
            <div class="result-card">
                <h3>Thông tin vé</h3>
                <p><strong>Khách hàng:</strong> <?php echo e($result->fullname); ?></p>
                <p><strong>SĐT:</strong> <?php echo e($result->phone); ?></p>
                <p><strong>Mã vé:</strong> <?php echo e($result->ma_ve); ?></p>
                <p><strong>Ghế:</strong> <?php echo e($result->so_ghe); ?></p>
                <p><strong>Xe:</strong> <?php echo e($result->ten_xe); ?> (<?php echo e($result->nha_xe_ten); ?>)</p>
                <p><strong>Tuyến:</strong> <?php echo e($result->diem_di_ten); ?> → <?php echo e($result->diem_den_ten); ?></p>
                <p><strong>Thời gian:</strong> <?php echo e($result->gio_di); ?> <?php echo e(\Carbon\Carbon::parse($result->ngay_di)->format('d/m/Y')); ?></p>
                <p><strong>Giá vé:</strong> <?php echo e(number_format($result->gia_ve, 0, ",", ".")); ?>đ</p>
                <p><strong>Trạng thái:</strong> <?php echo e($result->trang_thai); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
    <script src="<?php echo e(asset('assets/js/Tracuu.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/tracking/tracking.blade.php ENDPATH**/ ?>