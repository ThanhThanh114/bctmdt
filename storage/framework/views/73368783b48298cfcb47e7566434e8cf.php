<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'FUTA Bus Demo'); ?></title>
    <!-- Đường dẫn tới base.css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Index.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/Header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/footer.css')); ?>">
    <!-- Swiper carousel CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php echo $__env->yieldContent('styles'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('home.chat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- FUTA Chat Widget -->
    <script src="<?php echo e(asset('js/futa-chat.js')); ?>"></script>

    <!-- Swiper JS (available for pages that initialize Swiper) -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html><?php /**PATH E:\bctmdt\resources\views/app.blade.php ENDPATH**/ ?>