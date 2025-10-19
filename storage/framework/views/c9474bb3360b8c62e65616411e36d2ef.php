<?php $__env->startSection('title', $news->tieu_de); ?>

<?php $__env->startSection('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/NewsDetail.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php
        function formatTimestamp($timestamp)
        {
            return date("H:i d/m/Y", strtotime($timestamp));
        }
    ?>

    <main class="news-detail-page">
        <div class="news-detail-container">
            <!-- Article Content -->
            <div class="article-wrapper">
                <!-- Article Title -->
                <h3 class="article-title"><?php echo e($news->tieu_de); ?></h3>

                <!-- Created Date -->
                <div class="article-date">Created Date: <?php echo e(formatTimestamp($news->ngay_dang)); ?></div>

                <!-- Featured Image -->
                <?php if($news->hinh_anh): ?>
                    <div class="article-featured-image">
                        <?php if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL)): ?>
                            <img src="<?php echo e($news->hinh_anh); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>" loading="lazy"
                                onerror="this.src='https://via.placeholder.com/1200x600/FF5722/ffffff?text=Tin+Tức'">
                        <?php else: ?>
                            <img src="<?php echo e(asset($news->hinh_anh)); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>" loading="lazy"
                                onerror="this.src='https://via.placeholder.com/1200x600/FF5722/ffffff?text=Tin+Tức'">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Article Excerpt -->
                <div class="article-excerpt">
                    <?php echo e(htmlspecialchars(mb_substr(strip_tags($news->noi_dung), 0, 150, 'UTF-8'))); ?>...
                </div>

                <!-- Article Content -->
                <div class="article-content">
                    <?php
                        // Format nội dung: chuyển line break thành paragraph
                        $content = $news->noi_dung;
                        // Loại bỏ khoảng trắng thừa
                        $content = trim($content);
                        // Tách thành các đoạn bằng double line break
                        $paragraphs = preg_split('/\r?\n\r?\n/', $content);
                        // Lọc bỏ đoạn trống
                        $paragraphs = array_filter($paragraphs, function ($p) {
                            return trim($p) !== '';
                        });
                    ?>

                    <?php $__currentLoopData = $paragraphs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo nl2br(e(trim($paragraph))); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Related News Section -->
            <div class="related-news-section">
                <div class="related-header">
                    <div class="related-title">Related News</div>
                    <div class="title-divider"></div>
                    <a href="<?php echo e(route('news.news')); ?>" class="see-all-link">
                        <span>See All</span>
                        <img src="/images/icons/ic_arrow_right.svg" alt="arrow">
                    </a>
                </div>

                <!-- Related News Grid -->
                <div class="related-news-grid">
                    <?php if(isset($related_news) && $related_news->count() > 0): ?>
                        <?php $__currentLoopData = $related_news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('news.show', $related->ma_tin)); ?>" class="related-news-card">
                                <div class="related-image">
                                    <?php if($related->hinh_anh): ?>
                                        <?php if(filter_var($related->hinh_anh, FILTER_VALIDATE_URL)): ?>
                                            <img src="<?php echo e($related->hinh_anh); ?>" alt="<?php echo e(htmlspecialchars($related->tieu_de)); ?>"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset($related->hinh_anh)); ?>" alt="<?php echo e(htmlspecialchars($related->tieu_de)); ?>"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('assets/images/header.jpg')); ?>"
                                            alt="<?php echo e(htmlspecialchars($related->tieu_de)); ?>" loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                    <?php endif; ?>
                                </div>
                                <div class="related-content">
                                    <div class="related-news-title">
                                        <?php echo e($related->tieu_de); ?>

                                    </div>
                                    <div class="related-excerpt">
                                        <?php echo e(htmlspecialchars(mb_substr(strip_tags($related->noi_dung), 0, 150, 'UTF-8'))); ?>...
                                    </div>
                                    <span class="related-time"><?php echo e(formatTimestamp($related->ngay_dang)); ?></span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- Fallback: Show placeholder cards -->
                        <div class="no-related-news">
                            <p>Không có tin tức liên quan</p>
                            <a href="<?php echo e(route('news.news')); ?>" class="back-to-news-btn">
                                <i class="fas fa-arrow-left"></i> Quay lại trang tin tức
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/NewsDetail.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/news/show.blade.php ENDPATH**/ ?>