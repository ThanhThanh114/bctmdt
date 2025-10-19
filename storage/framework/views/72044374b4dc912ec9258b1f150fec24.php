<?php $__env->startSection('title', 'Tin Tức - FUTA Bus Lines'); ?>

<?php $__env->startSection('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/News.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php
        function formatTimestamp($timestamp)
        {
            return date("H:i d/m/Y", strtotime($timestamp));
        }
    ?>

    <div class="news-page-wrapper">
        <!-- Category Tabs & Search -->
        <div class="news-header-section">
            <div class="container-custom">
                <div class="news-tabs-container">
                    <!-- Category Tabs -->
                    <div class="news-tabs">
                        <div class="news-tab active" data-category="all">
                            <img src="/images/shape.png" alt="" class="tab-icon">
                            Tin Tức Tổng Hợp
                        </div>
                        <div class="news-tab" data-category="futa-bus-lines">
                            FUTA Bus Lines
                        </div>
                        <div class="news-tab" data-category="futa-city-bus">
                            FUTA City Bus
                        </div>
                        <div class="news-tab" data-category="promotion">
                            Khuyến Mãi
                        </div>
                        <div class="news-tab" data-category="awards">
                            Giải Thưởng
                        </div>
                        <div class="news-tab" data-category="rest-stop">
                            Trạm Dừng
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="news-search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="inputSearchNews" class="search-input" placeholder="Tìm kiếm tin tức...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured News Section -->
        <div class="container-custom">
            <div class="section-title-wrapper">
                <h2 class="section-title">Tin Tức Nổi Bật</h2>
                <div class="title-line"></div>
            </div>

            <?php if($highlight_news->isNotEmpty()): ?>
                <div class="featured-news-grid">
                    <!-- Main Featured News (Large) - 1 ô to bên trái -->
                    <?php $hero_news = $highlight_news->first(); ?>
                    <div class="featured-main">
                        <a href="<?php echo e(route('news.show', $hero_news->ma_tin)); ?>" class="featured-card">
                            <div class="featured-image">
                                <?php if($hero_news->hinh_anh): ?>
                                    <?php if(filter_var($hero_news->hinh_anh, FILTER_VALIDATE_URL)): ?>
                                        <img src="<?php echo e($hero_news->hinh_anh); ?>" alt="<?php echo e(htmlspecialchars($hero_news->tieu_de)); ?>"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/800x600/FF5722/ffffff?text=Tin+Tức'">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset($hero_news->hinh_anh)); ?>" alt="<?php echo e(htmlspecialchars($hero_news->tieu_de)); ?>"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/800x600/FF5722/ffffff?text=Tin+Tức'">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets/images/header.jpg')); ?>"
                                        alt="<?php echo e(htmlspecialchars($hero_news->tieu_de)); ?>" loading="lazy"
                                        onerror="this.src='https://via.placeholder.com/800x600/FF5722/ffffff?text=Tin+Tức'">
                                <?php endif; ?>
                            </div>
                            <div class="featured-content">
                                <h3 class="featured-title"><?php echo e(htmlspecialchars($hero_news->tieu_de)); ?></h3>
                                <p class="featured-excerpt">
                                    <?php echo e(htmlspecialchars(mb_substr(strip_tags($hero_news->noi_dung), 0, 200, 'UTF-8')) . '...'); ?>

                                </p>
                                <span class="featured-time"><?php echo e(date("H:i d/m/Y", strtotime($hero_news->ngay_dang))); ?></span>
                            </div>
                        </a>
                    </div>

                    <!-- Side Featured News (Small) - 4 ô nhỏ bên phải -->
                    <div class="featured-side">
                        <?php $__currentLoopData = $highlight_news->slice(1, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('news.show', $news->ma_tin)); ?>" class="featured-card-small">
                                <div class="featured-image-small">
                                    <?php if($news->hinh_anh): ?>
                                        <?php if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL)): ?>
                                            <img src="<?php echo e($news->hinh_anh); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>" loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset($news->hinh_anh)); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('assets/images/header.jpg')); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                    <?php endif; ?>
                                </div>
                                <div class="featured-content-small">
                                    <h4 class="featured-title-small"><?php echo e(htmlspecialchars($news->tieu_de)); ?></h4>
                                    <span class="featured-time"><?php echo e(date("H:i d/m/Y", strtotime($news->ngay_dang))); ?></span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- FUTA City Bus Spotlight -->
            <?php if($highlight_news->count() > 5): ?>
                <div class="spotlight-section">
                    <div class="spotlight-badge">
                        <h3>Tiêu Điểm</h3>
                        <p>FUTA City Bus</p>
                    </div>
                    <div class="spotlight-carousel">
                        <?php $__currentLoopData = $highlight_news->slice(5, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('news.show', $news->ma_tin)); ?>" class="spotlight-card">
                                <div class="spotlight-image">
                                    <?php if($news->hinh_anh): ?>
                                        <?php if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL)): ?>
                                            <img src="<?php echo e($news->hinh_anh); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>" loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset($news->hinh_anh)); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('assets/images/header.jpg')); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                    <?php endif; ?>
                                </div>
                                <div class="spotlight-content">
                                    <h4><?php echo e(htmlspecialchars($news->tieu_de)); ?></h4>
                                    <span class="spotlight-time"><?php echo e(date("H:i d/m/Y", strtotime($news->ngay_dang))); ?></span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- All News Section -->
            <div class="section-title-wrapper">
                <h2 class="section-title">Tất Cả Tin Tức</h2>
                <div class="title-line"></div>
            </div>

            <?php if($all_news->isNotEmpty()): ?>
                <div class="all-news-grid">
                    <?php $__currentLoopData = $all_news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="news-card-horizontal">
                            <div class="news-card-image">
                                <?php if($news->hinh_anh): ?>
                                    <?php if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL)): ?>
                                        <img src="<?php echo e($news->hinh_anh); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>" loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset($news->hinh_anh)); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>" loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets/images/header.jpg')); ?>" alt="<?php echo e(htmlspecialchars($news->tieu_de)); ?>"
                                        loading="lazy"
                                        onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                <?php endif; ?>
                            </div>
                            <div class="news-card-content">
                                <h3 class="news-card-title">
                                    <a href="<?php echo e(route('news.show', $news->ma_tin)); ?>">
                                        <?php echo e(htmlspecialchars($news->tieu_de)); ?>

                                    </a>
                                </h3>
                                <p class="news-card-excerpt">
                                    <?php echo e(htmlspecialchars(mb_substr(strip_tags($news->noi_dung), 0, 200, 'UTF-8')) . '...'); ?>

                                </p>
                                <span class="news-card-time"><?php echo e(date("H:i d/m/Y", strtotime($news->ngay_dang))); ?></span>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="no-news-message">
                    <i class="fas fa-newspaper"></i>
                    <h3>Chưa có tin tức nào</h3>
                    <p>Hãy quay lại sau để xem những thông tin mới nhất từ FUTA Bus Lines!</p>
                </div>
            <?php endif; ?>

            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
                <div class="pagination-wrapper">
                    <nav class="pagination-nav">
                        <!-- Previous Button -->
                        <?php if($current_page > 1): ?>
                            <a href="<?php echo e(route('news.news', ['page' => $current_page - 1])); ?>"
                                class="pagination-btn pagination-prev">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php else: ?>
                            <span class="pagination-btn pagination-prev disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php for($i = 1; $i <= min(5, $total_pages); $i++): ?>
                            <a href="<?php echo e(route('news.news', ['page' => $i])); ?>"
                                class="pagination-btn <?php echo e($i == $current_page ? 'active' : ''); ?>">
                                <?php echo e($i); ?>

                            </a>
                        <?php endfor; ?>

                        <?php if($total_pages > 5): ?>
                            <?php if($current_page < $total_pages - 2): ?>
                                <span class="pagination-dots">...</span>
                            <?php endif; ?>

                            <?php if($total_pages > 6): ?>
                                <a href="<?php echo e(route('news.news', ['page' => $total_pages - 1])); ?>"
                                    class="pagination-btn <?php echo e(($total_pages - 1) == $current_page ? 'active' : ''); ?>">
                                    <?php echo e($total_pages - 1); ?>

                                </a>
                            <?php endif; ?>

                            <a href="<?php echo e(route('news.news', ['page' => $total_pages])); ?>"
                                class="pagination-btn <?php echo e($total_pages == $current_page ? 'active' : ''); ?>">
                                <?php echo e($total_pages); ?>

                            </a>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if($current_page < $total_pages): ?>
                            <a href="<?php echo e(route('news.news', ['page' => $current_page + 1])); ?>"
                                class="pagination-btn pagination-next">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="pagination-btn pagination-next disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/News.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thanh\Documents\GitHub\bctmdt\resources\views/news/news.blade.php ENDPATH**/ ?>