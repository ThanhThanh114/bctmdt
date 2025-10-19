// =======================
// NEWS DETAIL PAGE
// =======================

document.addEventListener('DOMContentLoaded', function () {
    // Smooth scroll to top when clicking related news
    const relatedCards = document.querySelectorAll('.related-news-card');
    relatedCards.forEach(card => {
        card.addEventListener('click', function (e) {
            // Let the link navigate naturally, but prepare for smooth scroll
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        });
    });

    // Add reading time estimate
    const articleContent = document.querySelector('.article-content');
    if (articleContent) {
        const text = articleContent.textContent || articleContent.innerText;
        const wordCount = text.trim().split(/\s+/).length;
        const readingTime = Math.ceil(wordCount / 200); // Average reading speed: 200 words/min

        // Create reading time badge
        const readingTimeBadge = document.createElement('div');
        readingTimeBadge.className = 'reading-time-badge';
        readingTimeBadge.innerHTML = `
            <i class="fas fa-clock"></i>
            <span>${readingTime} phút đọc</span>
        `;
        readingTimeBadge.style.cssText = `
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F0F0F0;
            color: #666;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            margin-left: 20px;
            margin-bottom: 15px;
        `;

        const articleDate = document.querySelector('.article-date');
        if (articleDate) {
            articleDate.parentNode.insertBefore(readingTimeBadge, articleDate.nextSibling);
        }
    }

    // Image lightbox effect
    const contentImages = document.querySelectorAll('.article-content img');
    contentImages.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function () {
            openImageLightbox(this.src, this.alt);
        });
    });

    // Add copy link functionality
    const articleUrl = window.location.href;
    const shareBtn = createShareButton(articleUrl);
    const articleTitle = document.querySelector('.article-title');
    if (articleTitle && shareBtn) {
        articleTitle.appendChild(shareBtn);
    }

    // Reading progress bar
    const progressBar = document.createElement('div');
    progressBar.id = 'reading-progress';
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, #00613D, #FF6600);
        z-index: 9999;
        transition: width 0.2s ease;
    `;
    document.body.appendChild(progressBar);

    // Update progress on scroll
    window.addEventListener('scroll', () => {
        const article = document.querySelector('.article-wrapper');
        if (!article) return;

        const articleTop = article.offsetTop;
        const articleHeight = article.offsetHeight;
        const scrollTop = window.pageYOffset;
        const windowHeight = window.innerHeight;

        const scrollPercent = ((scrollTop - articleTop + windowHeight) / articleHeight) * 100;
        progressBar.style.width = Math.min(Math.max(scrollPercent, 0), 100) + '%';
    });

    // Add back to top button
    const backToTopBtn = document.createElement('button');
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: #00613D;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    `;
    document.body.appendChild(backToTopBtn);

    // Show/hide back to top button
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.opacity = '1';
            backToTopBtn.style.visibility = 'visible';
        } else {
            backToTopBtn.style.opacity = '0';
            backToTopBtn.style.visibility = 'hidden';
        }
    });

    // Back to top functionality
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Print button (optional)
    const printBtn = document.createElement('button');
    printBtn.className = 'print-article-btn';
    printBtn.innerHTML = '<i class="fas fa-print"></i> In bài viết';
    printBtn.style.cssText = `
        position: fixed;
        bottom: 90px;
        right: 30px;
        padding: 12px 20px;
        background: white;
        color: #00613D;
        border: 2px solid #00613D;
        border-radius: 25px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: none;
    `;
    document.body.appendChild(printBtn);

    printBtn.addEventListener('click', () => {
        window.print();
    });

    // Show print button on scroll
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            printBtn.style.display = 'block';
        } else {
            printBtn.style.display = 'none';
        }
    });
});

// Image lightbox function
function openImageLightbox(src, alt) {
    const lightbox = document.createElement('div');
    lightbox.className = 'image-lightbox';
    lightbox.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    `;

    const img = document.createElement('img');
    img.src = src;
    img.alt = alt;
    img.style.cssText = `
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 8px;
    `;

    lightbox.appendChild(img);
    document.body.appendChild(lightbox);

    lightbox.addEventListener('click', () => {
        lightbox.remove();
    });
}

// Create share button
function createShareButton(url) {
    const shareBtn = document.createElement('button');
    shareBtn.className = 'share-article-btn';
    shareBtn.innerHTML = '<i class="fas fa-share-alt"></i>';
    shareBtn.title = 'Chia sẻ bài viết';
    shareBtn.style.cssText = `
        position: absolute;
        right: 20px;
        top: 20px;
        width: 40px;
        height: 40px;
        background: #FF6600;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    `;

    shareBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        shareArticle(url);
    });

    shareBtn.addEventListener('mouseenter', function () {
        this.style.transform = 'scale(1.1)';
        this.style.background = '#FF4300';
    });

    shareBtn.addEventListener('mouseleave', function () {
        this.style.transform = 'scale(1)';
        this.style.background = '#FF6600';
    });

    return shareBtn;
}

// Share article function
function shareArticle(url) {
    const title = document.querySelector('.article-title')?.textContent || 'FUTA Bus Lines';

    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(err => {
            console.log('Error sharing:', err);
            copyToClipboard(url);
        });
    } else {
        copyToClipboard(url);
    }
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Đã sao chép liên kết bài viết!');
    }).catch(err => {
        console.error('Failed to copy:', err);
        showNotification('Không thể sao chép liên kết', 'error');
    });
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: ${type === 'success' ? '#00613D' : '#DC3545'};
        color: white;
        padding: 15px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10001;
        animation: slideUp 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideDown 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideUp {
        from {
            transform: translateX(-50%) translateY(100px);
            opacity: 0;
        }
        to {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes slideDown {
        from {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        to {
            transform: translateX(-50%) translateY(100px);
            opacity: 0;
        }
    }
    
    .back-to-top:hover {
        background: #004d2f !important;
        transform: translateY(-3px);
    }
    
    .print-article-btn:hover {
        background: #00613D !important;
        color: white !important;
    }
`;
document.head.appendChild(style);
