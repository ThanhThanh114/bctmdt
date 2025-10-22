<?php $__env->startSection('title', 'Tra cứu hóa đơn - FUTA Bus Lines'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/HoaDon.css')); ?>">

<div class="container">
    <h2 class="page-title">Tra cứu hóa đơn</h2>

    <?php if(session('error_message')): ?>
    <div class="error-message">
        <p><?php echo e(session('error_message')); ?></p>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('invoice.check')); ?>" method="POST" class="invoice-form">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label><i class="fa fa-user-secret"></i> Mã số bí mật</label>
            <input type="text" name="ma_bimat" placeholder="Nhập mã bí mật (ví dụ: VE001)" required>
        </div>
        
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/invoice/index.blade.php ENDPATH**/ ?>