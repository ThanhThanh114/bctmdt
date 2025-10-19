// =======================
// NEWS PAGE FUNCTIONALITY
// =======================

document.addEventListener('DOMContentLoaded', function () {
    // Category tabs functionality
    const tabs = document.querySelectorAll('.news-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            // Get category
            const category = this.dataset.category;

            // Here you can add AJAX call to filter news by category
            // For now, we'll just log it
            console.log('Selected category:', category);

            // Optional: Add loading animation
            const newsGrid = document.querySelector('.all-news-grid');
            if (newsGrid) {
                newsGrid.style.opacity = '0.5';
                setTimeout(() => {
                    newsGrid.style.opacity = '1';
                }, 300);
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('inputSearchNews');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.toLowerCase().trim();

            // Debounce search
            searchTimeout = setTimeout(() => {
                if (searchTerm.length > 0) {
                    filterNews(searchTerm);
                } else {
                    showAllNews();
                }
            }, 500);
        });
    }

    // Filter news function
    function filterNews(searchTerm) {
        const newsCards = document.querySelectorAll('.news-card-horizontal');
        let visibleCount = 0;

        newsCards.forEach(card => {
            const title = card.querySelector('.news-card-title a').textContent.toLowerCase();
            const excerpt = card.querySelector('.news-card-excerpt').textContent.toLowerCase();

            if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show message if no results
        updateNoResultsMessage(visibleCount);
    }

    // Show all news
    function showAllNews() {
        const newsCards = document.querySelectorAll('.news-card-horizontal');
        newsCards.forEach(card => {
            card.style.display = 'flex';
        });
        updateNoResultsMessage(newsCards.length);
    }

    // Update no results message
    function updateNoResultsMessage(count) {
        const existingMessage = document.querySelector('.search-no-results');
        const newsGrid = document.querySelector('.all-news-grid');

        if (count === 0) {
            if (!existingMessage && newsGrid) {
                const noResults = document.createElement('div');
                noResults.className = 'search-no-results';
                noResults.innerHTML = `
                    <div class="no-news-message">
                        <i class="fas fa-search"></i>
                        <h3>Không tìm thấy kết quả</h3>
                        <p>Vui lòng thử lại với từ khóa khác</p>
                    </div>
                `;
                newsGrid.parentNode.insertBefore(noResults, newsGrid.nextSibling);
            }
        } else {
            if (existingMessage) {
                existingMessage.remove();
            }
        }
    }

    // Smooth scroll for pagination
    const paginationBtns = document.querySelectorAll('.pagination-btn');
    paginationBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!this.classList.contains('disabled') && !this.classList.contains('active')) {
                // Scroll to top smoothly
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                // Add loading animation
                this.style.opacity = '0.7';
                setTimeout(() => {
                    this.style.opacity = '1';
                }, 300);
            }
        });
    });

    // Add hover effects for featured cards
    const featuredCards = document.querySelectorAll('.featured-card, .featured-card-small, .spotlight-card, .news-card-horizontal');
    featuredCards.forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.style.transition = 'all 0.3s ease';
        });
    });

    // Reading progress indicator
    const progressBar = document.createElement('div');
    progressBar.id = 'reading-progress';
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, #FF6600, #FF4300);
        z-index: 9999;
        transition: width 0.2s ease;
    `;
    document.body.appendChild(progressBar);

    // Update progress on scroll
    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset;
        const docHeight = document.body.scrollHeight - window.innerHeight;
        const scrollPercent = (scrollTop / docHeight) * 100;
        progressBar.style.width = Math.min(scrollPercent, 100) + '%';
    });

    // Lazy loading for images
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    });

    // Observe all images
    const lazyImages = document.querySelectorAll('img[data-src]');
    lazyImages.forEach(img => imageObserver.observe(img));

    // Add animation on scroll for cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Initially hide cards for animation
    const animatedCards = document.querySelectorAll('.news-card-horizontal, .featured-card-small');
    animatedCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        cardObserver.observe(card);
    });

    // Keyboard navigation for accessibility
    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') {
            const prevBtn = document.querySelector('.pagination-prev:not(.disabled)');
            if (prevBtn) prevBtn.click();
        } else if (e.key === 'ArrowRight') {
            const nextBtn = document.querySelector('.pagination-next:not(.disabled)');
            if (nextBtn) nextBtn.click();
        }
    });
});

// Share functionality (for future use)
function shareNews(title, url) {
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Đã sao chép liên kết!');
        });
    }
}

// Show notification helper
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #00613D;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .search-no-results {
        grid-column: 1 / -1;
    }
`;
document.head.appendChild(style);
