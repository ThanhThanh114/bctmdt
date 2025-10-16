// Click handler for news cards
document.querySelectorAll('.news-card-modern').forEach(card => {
    card.addEventListener('click', function() {
        const link = this.querySelector('.read-more-btn');
        if (link) {
            link.click();
        }
    });
});

// Smooth scroll for pagination
document.querySelectorAll('.pagination-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!this.classList.contains('active')) {
            // Add loading animation
            this.style.opacity = '0.7';
            setTimeout(() => {
                this.style.opacity = '1';
            }, 300);
        }
    });
});

// Add smooth scrolling for better UX
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: '{{ htmlspecialchars($news->tieu_de) }}',
            text: '{{ htmlspecialchars(mb_substr($news->noi_dung, 0, 100, "UTF-8")) }}...',
            url: window.location.href
        });
    } else {
        // Fallback: copy URL to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Đã sao chép liên kết bài viết!');
        });
    }
}

// Add smooth scroll for better UX
window.addEventListener('load', function() {
    // Add reading progress indicator
    const article = document.querySelector('article');
    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 4px;
        background: linear-gradient(90deg, #ff6600, #ff8533);
        z-index: 1000;
        transition: width 0.3s ease;
    `;
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset;
        const docHeight = document.body.scrollHeight - window.innerHeight;
        const scrollPercent = (scrollTop / docHeight) * 100;
        progressBar.style.width = scrollPercent + '%';
    });
});
